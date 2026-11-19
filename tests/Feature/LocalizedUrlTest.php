<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Pharaonic\Laravel\Localization\Exceptions\LocalizedRouteNotFoundException;
use Pharaonic\Laravel\Localization\Exceptions\MissingLocalizedRouteKeyException;
use Pharaonic\Laravel\Localization\Exceptions\UnsupportedLocaleException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PostController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\Post;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use Throwable;

final class LocalizedUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function (): void {
            Route::localized(function (): void {
                Route::get('/', PageController::class)->name('home');
                Route::get('/products/{product}', PageController::class)->name('products.show');
                Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
                Route::get('/invites/{invite}', PageController::class)->name('invites.accept')->middleware('signed');
                // Laravel 6 has no signed:relative middleware; the application checks the relative signature.
                Route::get('/relative/{invite}', function (Request $request) {
                    abort_unless($request->hasValidSignature(false), 403);

                    return ['locale' => app()->getLocale()];
                })->name('invites.relative');
            });

            Route::get('/dashboard', PageController::class)->name('dashboard');
        });

        Route::getRoutes()->refreshNameLookups();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /**
     * @param  class-string<Throwable>  $exception
     */
    private function assertThrows(Closure $callback, string $exception, ?string $message = null): void
    {
        try {
            $callback();
        } catch (Throwable $thrown) {
            $this->assertInstanceOf($exception, $thrown);

            if ($message !== null) {
                $this->assertStringContainsString($message, $thrown->getMessage());
            }

            return;
        }

        $this->fail(sprintf('%s was not thrown.', $exception));
    }

    public function test_it_generates_localized_urls_through_the_concrete_route_names(): void
    {
        $this->assertSame('http://localhost/products/5', locale()->route('products.show', ['product' => 5]));
        $this->assertSame('http://localhost/ar/'.rawurlencode('المنتجات').'/5', locale()->route('products.show', ['product' => 5], 'ar'));
        $this->assertSame('/fr/produits/5', locale()->route('products.show', 5, 'fr', false));
        $this->assertSame('http://localhost/ar', locale()->route('home', locale: 'ar'));
        $this->assertSame('http://localhost/ar/'.rawurlencode('المنتجات').'/1', locale()->route('localized.fr.products.show', ['product' => 1], 'ar'));
        $this->assertSame('http://localhost/products/5', route('products.show', 5));
    }

    public function test_it_encodes_unicode_segments_and_parameters_exactly_once(): void
    {
        $url = locale()->route('products.show', ['product' => 'قميص أزرق', 'q' => 'a&b'], 'ar');

        $this->assertSame('http://localhost/ar/'.rawurlencode('المنتجات').'/'.rawurlencode('قميص أزرق').'?q=a%26b', $url);

        $this->get($url)->assertOk()->assertJson(['locale' => 'ar', 'parameters' => ['product' => 'قميص أزرق']]);
    }

    public function test_it_defaults_to_the_current_locale_and_keeps_it_unchanged(): void
    {
        app()->setLocale('fr');

        $this->assertSame('http://localhost/fr', locale()->route('home'));
        $this->assertSame('http://localhost/ar', locale()->route('home', locale: 'ar'));
        $this->assertSame('fr', app()->getLocale());
        $this->assertSame('fr', locale()->current());
    }

    public function test_it_throws_typed_exceptions_for_unknown_routes_plain_routes_and_unsupported_locales(): void
    {
        $this->assertThrows(fn () => locale()->route('missing'), LocalizedRouteNotFoundException::class, 'Route [missing] not defined.');
        $this->assertThrows(fn () => locale()->route('dashboard'), LocalizedRouteNotFoundException::class, 'is not registered inside Route::localized()');
        $this->assertThrows(fn () => locale()->route('home', locale: 'de'), UnsupportedLocaleException::class, 'Locale [de] is not supported');
        $this->assertThrows(fn () => locale()->route('home', locale: 'ar-EG'), UnsupportedLocaleException::class);
    }

    public function test_it_never_mutates_registered_routes_while_generating_urls(): void
    {
        $route = Route::getRoutes()->getByName('products.show');
        $before = serialize([$route->uri(), $route->getAction(), $route->getPrefix()]);

        foreach (['en', 'ar', 'fr', 'ar', 'en'] as $locale) {
            locale()->route('products.show', 1, $locale);
        }

        $this->assertSame($before, serialize([$route->uri(), $route->getAction(), $route->getPrefix()]));
        $this->assertSame('http://localhost/products/1', route('products.show', 1));
    }

    public function test_it_uses_localized_route_keys_and_rejects_a_missing_one(): void
    {
        $this->assertSame('http://localhost/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات'), locale()->route('posts.show', new Post(1), 'ar'));
        $this->assertSame('http://localhost/posts/clean-architecture', locale()->route('posts.show', ['post' => new Post(1)], 'en'));
        $this->assertThrows(
            fn () => locale()->route('posts.show', new Post(2), 'ar'),
            MissingLocalizedRouteKeyException::class,
            'Route parameter [0] ('.Post::class.') has no route key in [ar].'
        );
    }

    public function test_it_binds_localized_slugs_with_the_url_locale(): void
    {
        $this->get('/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات'))->assertOk()->assertJson(['id' => 1, 'locale' => 'ar']);
        $this->get('/posts/clean-architecture')->assertOk()->assertJson(['id' => 1, 'locale' => 'en']);
        $this->get('/ar/'.rawurlencode('مقالات').'/clean-architecture')->assertNotFound();
        $this->get('/fr/posts/clean-architecture')->assertNotFound();
    }

    public function test_it_builds_alternates_and_switch_urls_from_the_current_route_skipping_missing_slugs(): void
    {
        $response = $this->get('/posts/clean-architecture?utm=x&page=2')->assertOk();
        $ar = 'http://localhost/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات');

        $response->assertJson([
            'alternates' => ['en' => 'http://localhost/posts/clean-architecture', 'ar' => $ar],
            'hreflangs' => ['en' => 'http://localhost/posts/clean-architecture', 'ar' => $ar, 'x-default' => 'http://localhost/posts/clean-architecture'],
            'switch' => ['ar' => $ar.'?utm=x&page=2', 'fr' => null, 'en' => 'http://localhost/posts/clean-architecture?utm=x&page=2'],
        ]);
        $this->assertArrayNotHasKey('fr', $response->json('alternates'));

        $this->get('/posts/english-only')->assertJson([
            'alternates' => ['en' => 'http://localhost/posts/english-only'],
            'switch' => ['ar' => null, 'fr' => null],
        ]);
    }

    public function test_it_switches_parameters_and_query_strings_without_decoding_them(): void
    {
        Route::localized(function () {
            Route::get('/probe/{product}', fn () => ['switch' => locale()->switchUrl('fr'), 'alternates' => locale()->alternates()])->name('probe');
        });

        $this->get('/ar/probe/a%20b?x=%20y&x=2')->assertJson([
            'switch' => 'http://localhost/fr/probe/a%20b?x=%20y&x=2',
            'alternates' => ['en' => 'http://localhost/probe/a%20b', 'ar' => 'http://localhost/ar/probe/a%20b', 'fr' => 'http://localhost/fr/probe/a%20b'],
        ]);
    }

    public function test_it_returns_no_alternates_outside_localized_routes(): void
    {
        Route::get('/plain', fn () => ['alternates' => locale()->alternates(), 'hreflangs' => locale()->hreflangs(), 'switch' => locale()->switchUrl('ar')]);

        $this->get('/plain')->assertExactJson(['alternates' => [], 'hreflangs' => [], 'switch' => null]);
        $this->assertThrows(fn () => locale()->switchUrl('de'), UnsupportedLocaleException::class);
    }

    public function test_it_signs_localized_routes_natively_and_verifies_them_over_http(): void
    {
        $ar = locale()->signedRoute('invites.accept', ['invite' => 7], locale: 'ar');
        $fr = locale()->temporarySignedRoute('invites.accept', now()->addMinutes(30), ['invite' => 7], locale: 'fr');

        $this->assertStringStartsWith('http://localhost/ar/invites/7?signature=', $ar);
        $this->assertStringStartsWith('http://localhost/fr/invites/7?expires=', $fr);

        $this->get($ar)->assertOk()->assertJson(['locale' => 'ar']);
        $this->get($fr)->assertOk()->assertJson(['locale' => 'fr']);

        $this->get(str_replace('/ar/', '/fr/', $ar))->assertForbidden();
        $this->get(str_replace('/invites/7', '/invites/8', $ar))->assertForbidden();
        $this->get(str_replace('/ar/invites', '/invites', $ar))->assertForbidden();
        $this->get($ar.'&extra=1')->assertForbidden();
    }

    public function test_it_rejects_expired_localized_signatures(): void
    {
        $url = locale()->temporarySignedRoute('invites.accept', now()->addMinute(), ['invite' => 1], locale: 'ar');

        Carbon::setTestNow(now()->addMinutes(2));

        $this->get($url)->assertForbidden();
    }

    public function test_it_supports_relative_signatures(): void
    {
        $url = locale()->signedRoute('invites.relative', ['invite' => 3], absolute: false, locale: 'fr');

        $this->assertStringStartsWith('/fr/relative/3?signature=', $url);

        $this->get('http://other.test'.$url)->assertOk();
        $this->get('http://other.test'.str_replace('/fr/', '/ar/', $url))->assertForbidden();
    }

    public function test_it_keeps_the_deprecated_url_signing_macros_on_the_native_signer(): void
    {
        $url = URL::LocalizedSignedRoute('ar', 'invites.accept', ['invite' => 2]);
        $temporary = URL::temporaryLocalizedSignedRoute('fr', 'invites.accept', now()->addMinute(), ['invite' => 2]);

        $this->get($url)->assertOk()->assertJson(['locale' => 'ar']);
        $this->get($temporary)->assertOk()->assertJson(['locale' => 'fr']);
    }

    public function test_it_does_not_offer_switch_urls_for_signed_pages(): void
    {
        Route::localized(function () {
            Route::get('/secret/{id}', fn () => ['switch' => locale()->switchUrl('fr'), 'alternates' => locale()->alternates()])->name('secret')->middleware('signed');
        });

        $this->get(locale()->signedRoute('secret', ['id' => 1], locale: 'ar'))->assertExactJson(['switch' => null, 'alternates' => []]);
    }
}
