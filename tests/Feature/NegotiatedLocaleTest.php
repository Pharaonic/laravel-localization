<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\User;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\TenantLocaleResolver;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\ThrowingResolver;

beforeEach(function () {
    Route::middleware('localization')->get('/api/products', PageController::class);
    Route::middleware(['web', 'localization:web'])->get('/web/products', PageController::class);
});

test('it prefers the user over the header in the api profile', function () {
    $this->actingAs(User::prefers('ar'))
        ->withHeader('Accept-Language', 'fr')
        ->get('/api/products')
        ->assertOk()
        ->assertHeader('Content-Language', 'ar')
        ->assertJson(['locale' => 'ar', 'source' => 'user', 'mode' => 'negotiated']);
});

test('it uses the header when there is no user preference', function () {
    $this->withHeader('Accept-Language', 'fr-CA,fr;q=0.9,en;q=0.8')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
    $this->actingAs(User::prefers(null))->withHeader('Accept-Language', 'ar-EG,ar;q=0.9,en;q=0.8')->get('/api/products')->assertJson(['locale' => 'ar', 'source' => 'header']);
});

test('it falls back to app locale never app fallback locale', function (?string $header) {
    $this->localization(['app.fallback_locale' => 'fr']);
    // An empty header stands for no header: Symfony adds `en-us,en;q=0.5` to test requests.
    $request = $this->withHeader('Accept-Language', $header ?? '');

    $request->get('/api/products')->assertJson(['locale' => 'en', 'source' => 'default'])->assertHeader('Content-Language', 'en');
})->with([[null], ['de-DE,ja;q=0.5'], ['@@invalid@@'], ['fr;q=0']]);

test('it lets laravel translate missing keys with the fallback locale while the request locale stays', function () {
    $this->localization(['app.fallback_locale' => 'en']);
    Route::middleware('localization')->get('/api/fallback', function () {
        return ['locale' => app()->getLocale(), 'text' => trans('messages.only_english')];
    });

    $this->withHeader('Accept-Language', 'ar')->get('/api/fallback')->assertExactJson(['locale' => 'ar', 'text' => 'English only']);
});

test('it never redirects and keeps the url', function () {
    $this->withHeader('Accept-Language', 'ar')->get('/api/products?x=1')->assertOk()->assertJson(['locale' => 'ar']);
});

test('it skips stale or unsupported preferences', function () {
    $this->actingAs(User::prefers('de'))->withHeader('Accept-Language', 'fr')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
    $this->actingAs(User::prefers('   '))->withHeader('Accept-Language', '')->get('/api/products')->assertJson(['locale' => 'en', 'source' => 'default']);
    $this->actingAs(User::prefers('ar_EG'))->get('/api/products')->assertJson(['locale' => 'ar', 'source' => 'user']);
});

test('it runs the web profile in order user session cookie header', function () {
    $this->withSession(['locale' => 'fr'])->withCookie('locale', 'ar')->withHeader('Accept-Language', 'ar')
        ->get('/web/products')->assertJson(['locale' => 'fr', 'source' => 'session']);

    $this->flushSession();
    $this->withCookie('locale', 'ar')->withHeader('Accept-Language', 'fr')
        ->get('/web/products')->assertJson(['locale' => 'ar', 'source' => 'cookie']);

    $this->actingAs(User::prefers('fr'))->withSession(['locale' => 'ar'])
        ->get('/web/products')->assertJson(['locale' => 'fr', 'source' => 'user']);
});

test('it reads encrypted cookies only after encrypt cookies', function () {
    // The web group decrypts; a forged plain value does not decrypt and is ignored.
    $this->withHeader('Accept-Language', '');
    $this->withUnencryptedCookie('locale', 'ar')->get('/web/products')->assertJson(['source' => 'default']);
    $this->withCookie('locale', 'invalid-locale')->get('/web/products')->assertJson(['source' => 'default']);
});

test('it ignores the session when it is not started', function () {
    $this->localization(['resolvers' => ['api' => ['session', 'header']]]);

    $this->withSession(['locale' => 'ar'])->withHeader('Accept-Language', 'fr')->get('/api/products')->assertJson(['locale' => 'fr', 'source' => 'header']);
});

test('it runs custom resolvers from the container in their configured position', function () {
    $this->localization(['resolvers' => ['api' => [TenantLocaleResolver::class, 'user', 'header']]]);
    $this->app->bind(TenantLocaleResolver::class, fn () => new TenantLocaleResolver(['acme.test' => 'ar']));

    $this->withHeader('X-Tenant-Locale', 'xx')->withHeader('Accept-Language', 'ar')->get('http://other.test/api/products')->assertJson(['locale' => 'ar', 'source' => 'header']);
    $this->actingAs(User::prefers('fr'))->get('http://acme.test/api/products')->assertJson(['locale' => 'ar', 'source' => TenantLocaleResolver::class]);
    $this->actingAs(User::prefers('fr'))->get('http://other.test/api/products')->assertJson(['locale' => 'fr', 'source' => 'user']);
});

test('it lets a failing custom resolver fail the request visibly', function () {
    $this->localization(['resolvers' => ['api' => [ThrowingResolver::class, 'header']]]);
    $this->withoutExceptionHandling();

    expect(fn () => $this->get('/api/products'))->toThrow(RuntimeException::class, 'Tenant service is down.');
});

test('it rejects an unknown profile', function () {
    Route::middleware('localization:mobile')->get('/api/mobile', PageController::class);
    $this->withoutExceptionHandling();

    expect(fn () => $this->get('/api/mobile'))->toThrow(InvalidLocalizationConfigurationException::class, 'Unknown resolver profile [mobile]');
});

test('it reads token users when localization is listed before the authenticator', function () {
    Auth::viaRequest('token', fn (Request $request) => $request->bearerToken() === 'secret' ? User::prefers('ar') : null);
    config(['auth.guards.token' => ['driver' => 'token']]);
    Route::middleware(['localization', 'auth:token'])->get('/api/me', PageController::class);

    $this->withHeader('Authorization', 'Bearer secret')->get('/api/me')->assertOk()->assertJson(['locale' => 'ar', 'source' => 'user']);
});

test('it adds vary accept language without dropping existing values', function () {
    Route::middleware('localization')->get('/api/vary', fn () => response('ok')->setVary('Origin'));

    $this->get('/api/vary')->assertHeader('Vary', 'Origin, Accept-Language');
    $this->actingAs(User::prefers('ar'))->get('/api/vary')->assertHeader('Vary', 'Origin');
});

test('it uses the configured header name', function () {
    $this->localization(['header' => ['name' => 'X-Locale']]);

    $this->withHeader('X-Locale', 'fr')->withHeader('Accept-Language', 'ar')->get('/api/products')
        ->assertJson(['locale' => 'fr'])
        ->assertHeader('Vary', 'X-Locale');
});

test('it keeps personalized responses out of shared caches', function () {
    Route::middleware('localization')->get('/api/public', fn () => response('ok')->setPublic()->setMaxAge(60)->setSharedMaxAge(600));
    Route::middleware('localization')->get('/api/no-store', fn () => response('ok')->header('Cache-Control', 'no-store'));
    Route::middleware('localization')->get('/api/default', fn () => response('ok'));

    $cacheControl = static function ($response): array {
        $values = array_map('trim', explode(',', (string) $response->headers->get('Cache-Control')));
        sort($values);

        return $values;
    };

    expect($cacheControl($this->actingAs(User::prefers('ar'))->get('/api/public')))->toBe(['max-age=60', 'private'])
        ->and($cacheControl($this->get('/api/no-store')))->toBe(['no-store', 'private'])
        ->and($cacheControl($this->get('/api/default')))->toBe(['no-cache', 'private']);

    Auth::logout();
    $this->localization(['resolvers' => ['api' => ['header']]]);

    expect($cacheControl($this->get('/api/public')))->toBe(['max-age=60', 'public', 's-maxage=600']);
});

test('it keeps an application content language', function () {
    Route::middleware('localization')->get('/api/own', fn () => response('ok')->header('Content-Language', 'en-GB'));

    $this->withHeader('Accept-Language', 'ar')->get('/api/own')->assertHeader('Content-Language', 'en-GB');
});

test('it leaves path routes to the path locale', function () {
    Route::middleware('localization')->group(function () {
        Route::localized(function () {
            Route::get('/mixed', PageController::class);
        });
    });

    $this->withHeader('Accept-Language', 'fr')->get('/mixed')->assertJson(['locale' => 'en', 'source' => 'path'])->assertHeaderMissing('Vary');
    $this->withHeader('Accept-Language', 'fr')->get('/ar/mixed')->assertJson(['locale' => 'ar', 'source' => 'path']);
});

test('it offers the preferred locale on path pages without applying it', function () {
    Route::middleware('web')->group(function () {
        Route::localized(function () {
            Route::get('/hello', fn () => ['locale' => app()->getLocale(), 'preferred' => locale()->preferred(), 'api' => locale()->preferred('api')]);
        });
    });

    $this->withHeader('Accept-Language', 'ar')->withCookie('locale', 'fr')->get('/hello')->assertExactJson(['locale' => 'en', 'preferred' => 'fr', 'api' => 'ar']);
});
