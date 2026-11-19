<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\User;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\TenantLocaleResolver;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\ThrowingResolver;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use RuntimeException;

final class NegotiatedLocaleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('localization')->get('/api/products', PageController::class);
        Route::middleware(['web', 'localization:web'])->get('/web/products', PageController::class);
    }

    public function test_it_prefers_the_user_over_the_header_in_the_api_profile(): void
    {
        $this->actingAs(User::prefers('ar'))
            ->withHeader('Accept-Language', 'fr')
            ->get('/api/products')
            ->assertOk()
            ->assertHeader('Content-Language', 'ar')
            ->assertJson(['locale' => 'ar', 'source' => 'user', 'mode' => 'negotiated']);
    }

    public function test_it_uses_the_header_when_there_is_no_user_preference(): void
    {
        $this->withHeader('Accept-Language', 'fr-CA,fr;q=0.9,en;q=0.8')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
        $this->actingAs(User::prefers(null))->withHeader('Accept-Language', 'ar-EG,ar;q=0.9,en;q=0.8')->get('/api/products')->assertJson(['locale' => 'ar', 'source' => 'header']);
    }

    /**
     * @dataProvider unusableHeaders
     */
    public function test_it_falls_back_to_app_locale_never_app_fallback_locale(?string $header): void
    {
        $this->localization(['app.fallback_locale' => 'fr']);
        // An empty header stands for no header: Symfony adds `en-us,en;q=0.5` to test requests.
        $request = $this->withHeader('Accept-Language', $header ?? '');

        $request->get('/api/products')->assertJson(['locale' => 'en', 'source' => 'default'])->assertHeader('Content-Language', 'en');
    }

    /**
     * @return list<array{?string}>
     */
    public static function unusableHeaders(): array
    {
        return [[null], ['de-DE,ja;q=0.5'], ['@@invalid@@'], ['fr;q=0']];
    }

    public function test_it_lets_laravel_translate_missing_keys_with_the_fallback_locale_while_the_request_locale_stays(): void
    {
        $this->localization(['app.fallback_locale' => 'en']);
        Route::middleware('localization')->get('/api/fallback', function () {
            return ['locale' => app()->getLocale(), 'text' => trans('messages.only_english')];
        });

        $this->withHeader('Accept-Language', 'ar')->get('/api/fallback')->assertExactJson(['locale' => 'ar', 'text' => 'English only']);
    }

    public function test_it_never_redirects_and_keeps_the_url(): void
    {
        $this->withHeader('Accept-Language', 'ar')->get('/api/products?x=1')->assertOk()->assertJson(['locale' => 'ar']);
    }

    public function test_it_skips_stale_or_unsupported_preferences(): void
    {
        $this->actingAs(User::prefers('de'))->withHeader('Accept-Language', 'fr')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
        $this->actingAs(User::prefers('   '))->withHeader('Accept-Language', '')->get('/api/products')->assertJson(['locale' => 'en', 'source' => 'default']);
        $this->actingAs(User::prefers('ar_EG'))->get('/api/products')->assertJson(['locale' => 'ar', 'source' => 'user']);
    }

    public function test_it_runs_the_web_profile_in_order_user_session_cookie_header(): void
    {
        $this->withSession(['locale' => 'fr'])->withCookie('locale', 'ar')->withHeader('Accept-Language', 'ar')
            ->get('/web/products')->assertJson(['locale' => 'fr', 'source' => 'session']);

        $this->flushSession();
        $this->withCookie('locale', 'ar')->withHeader('Accept-Language', 'fr')
            ->get('/web/products')->assertJson(['locale' => 'ar', 'source' => 'cookie']);

        $this->actingAs(User::prefers('fr'))->withSession(['locale' => 'ar'])
            ->get('/web/products')->assertJson(['locale' => 'fr', 'source' => 'user']);
    }

    public function test_it_reads_encrypted_cookies_only_after_encrypt_cookies(): void
    {
        // The web group decrypts; a forged plain value does not decrypt and is ignored.
        $this->withHeader('Accept-Language', '');
        $this->withUnencryptedCookie('locale', 'ar')->get('/web/products')->assertJson(['source' => 'default']);
        $this->withCookie('locale', 'invalid-locale')->get('/web/products')->assertJson(['source' => 'default']);
    }

    public function test_it_ignores_the_session_when_it_is_not_started(): void
    {
        $this->localization(['resolvers' => ['api' => ['session', 'header']]]);

        $this->withSession(['locale' => 'ar'])->withHeader('Accept-Language', 'fr')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
    }

    public function test_it_runs_custom_resolvers_from_the_container_in_their_configured_position(): void
    {
        $this->localization(['resolvers' => ['api' => [TenantLocaleResolver::class, 'user', 'header']]]);
        $this->app->bind(TenantLocaleResolver::class, fn () => new TenantLocaleResolver(['acme.test' => 'ar']));

        $this->withHeader('X-Tenant-Locale', 'xx')->withHeader('Accept-Language', 'ar')->get('http://other.test/api/products')->assertJson(['locale' => 'ar', 'source' => 'header']);
        $this->actingAs(User::prefers('fr'))->get('http://acme.test/api/products')->assertJson(['locale' => 'ar', 'source' => TenantLocaleResolver::class]);
        $this->actingAs(User::prefers('fr'))->get('http://other.test/api/products')->assertJson(['locale' => 'fr', 'source' => 'user']);
    }

    public function test_it_lets_a_failing_custom_resolver_fail_the_request_visibly(): void
    {
        $this->localization(['resolvers' => ['api' => [ThrowingResolver::class, 'header']]]);
        $this->withoutExceptionHandling();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tenant service is down.');

        $this->get('/api/products');
    }

    public function test_it_rejects_an_unknown_profile(): void
    {
        Route::middleware('localization:mobile')->get('/api/mobile', PageController::class);
        $this->withoutExceptionHandling();

        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage('Unknown resolver profile [mobile]');

        $this->get('/api/mobile');
    }

    public function test_it_reads_token_users_when_localization_is_listed_before_the_authenticator(): void
    {
        Auth::viaRequest('token', fn (Request $request) => $request->bearerToken() === 'secret' ? User::prefers('ar') : null);
        config(['auth.guards.token' => ['driver' => 'token']]);
        Route::middleware(['localization', 'auth:token'])->get('/api/me', PageController::class);

        $this->withHeader('Authorization', 'Bearer secret')->get('/api/me')->assertOk()->assertJson(['locale' => 'ar', 'source' => 'user']);
    }

    public function test_it_adds_vary_accept_language_without_dropping_existing_values(): void
    {
        Route::middleware('localization')->get('/api/vary', fn () => response('ok')->setVary('Origin'));

        $this->get('/api/vary')->assertHeader('Vary', 'Origin, Accept-Language');
        $this->actingAs(User::prefers('ar'))->get('/api/vary')->assertHeader('Vary', 'Origin');
    }

    public function test_it_uses_the_configured_header_name(): void
    {
        $this->localization(['header' => ['name' => 'X-Locale']]);

        $this->withHeader('X-Locale', 'fr')->withHeader('Accept-Language', 'ar')->get('/api/products')
            ->assertJson(['locale' => 'fr'])
            ->assertHeader('Vary', 'X-Locale');
    }

    public function test_it_keeps_personalized_responses_out_of_shared_caches(): void
    {
        Route::middleware('localization')->get('/api/public', fn () => response('ok')->setPublic()->setMaxAge(60)->setSharedMaxAge(600));
        Route::middleware('localization')->get('/api/no-store', fn () => response('ok')->header('Cache-Control', 'no-store'));
        Route::middleware('localization')->get('/api/default', fn () => response('ok'));

        $cacheControl = static function ($response): array {
            $values = array_map('trim', explode(',', (string) $response->headers->get('Cache-Control')));
            sort($values);

            return $values;
        };

        $this->assertSame(['max-age=60', 'private'], $cacheControl($this->actingAs(User::prefers('ar'))->get('/api/public')));
        $this->assertSame(['no-store', 'private'], $cacheControl($this->get('/api/no-store')));
        $this->assertSame(['no-cache', 'private'], $cacheControl($this->get('/api/default')));

        Auth::logout();
        $this->localization(['resolvers' => ['api' => ['header']]]);

        $this->assertSame(['max-age=60', 'public', 's-maxage=600'], $cacheControl($this->get('/api/public')));
    }

    public function test_it_keeps_an_application_content_language(): void
    {
        Route::middleware('localization')->get('/api/own', fn () => response('ok')->header('Content-Language', 'en-GB'));

        $this->withHeader('Accept-Language', 'ar')->get('/api/own')->assertHeader('Content-Language', 'en-GB');
    }

    public function test_it_leaves_path_routes_to_the_path_locale(): void
    {
        Route::middleware('localization')->group(function () {
            Route::localized(function () {
                Route::get('/mixed', PageController::class);
            });
        });

        $this->withHeader('Accept-Language', 'fr')->get('/mixed')->assertJson(['locale' => 'en', 'source' => 'path'])->assertHeaderMissing('Vary');
        $this->withHeader('Accept-Language', 'fr')->get('/ar/mixed')->assertJson(['locale' => 'ar', 'source' => 'path']);
    }

    public function test_it_offers_the_preferred_locale_on_path_pages_without_applying_it(): void
    {
        Route::middleware('web')->group(function () {
            Route::localized(function () {
                Route::get('/hello', fn () => ['locale' => app()->getLocale(), 'preferred' => locale()->preferred(), 'api' => locale()->preferred('api')]);
            });
        });

        $this->withHeader('Accept-Language', 'ar')->withCookie('locale', 'fr')->get('/hello')->assertExactJson(['locale' => 'en', 'preferred' => 'fr', 'api' => 'ar']);
    }
}
