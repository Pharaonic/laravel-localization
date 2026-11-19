<?php

declare(strict_types=1);

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

beforeEach(function () {
    Route::middleware('web')->group(function (): void {
        Route::localized(function (): void {
            Route::get('/', PageController::class)->name('home');
            Route::get('/products/{product}', PageController::class)->name('products.show');
            Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
            Route::get('/invites/{invite}', PageController::class)->name('invites.accept')->middleware('signed');
            // Laravel 7 has no signed:relative middleware; the application checks the relative signature.
            Route::get('/relative/{invite}', function (Request $request) {
                abort_unless($request->hasValidSignature(false), 403);

                return ['locale' => app()->getLocale()];
            })->name('invites.relative');
        });

        Route::get('/dashboard', PageController::class)->name('dashboard');
    });

    Route::getRoutes()->refreshNameLookups();
});

afterEach(function () {
    Carbon::setTestNow();
});

test('it generates localized urls through the concrete route names', function () {
    expect(locale()->route('products.show', ['product' => 5]))->toBe('http://localhost/products/5')
        ->and(locale()->route('products.show', ['product' => 5], 'ar'))->toBe('http://localhost/ar/'.rawurlencode('المنتجات').'/5')
        ->and(locale()->route('products.show', 5, 'fr', false))->toBe('/fr/produits/5')
        ->and(locale()->route('home', locale: 'ar'))->toBe('http://localhost/ar')
        ->and(locale()->route('localized.fr.products.show', ['product' => 1], 'ar'))->toBe('http://localhost/ar/'.rawurlencode('المنتجات').'/1')
        ->and(route('products.show', 5))->toBe('http://localhost/products/5');
});

test('it encodes unicode segments and parameters exactly once', function () {
    $url = locale()->route('products.show', ['product' => 'قميص أزرق', 'q' => 'a&b'], 'ar');

    expect($url)->toBe('http://localhost/ar/'.rawurlencode('المنتجات').'/'.rawurlencode('قميص أزرق').'?q=a%26b');

    $this->get($url)->assertOk()->assertJson(['locale' => 'ar', 'parameters' => ['product' => 'قميص أزرق']]);
});

test('it defaults to the current locale and keeps it unchanged', function () {
    app()->setLocale('fr');

    expect(locale()->route('home'))->toBe('http://localhost/fr')
        ->and(locale()->route('home', locale: 'ar'))->toBe('http://localhost/ar')
        ->and(app()->getLocale())->toBe('fr')
        ->and(locale()->current())->toBe('fr');
});

test('it throws typed exceptions for unknown routes plain routes and unsupported locales', function () {
    expect(fn () => locale()->route('missing'))->toThrow(LocalizedRouteNotFoundException::class, 'Route [missing] not defined.')
        ->and(fn () => locale()->route('dashboard'))->toThrow(LocalizedRouteNotFoundException::class, 'is not registered inside Route::localized()')
        ->and(fn () => locale()->route('home', locale: 'de'))->toThrow(UnsupportedLocaleException::class, 'Locale [de] is not supported')
        ->and(fn () => locale()->route('home', locale: 'ar-EG'))->toThrow(UnsupportedLocaleException::class);
});

test('it never mutates registered routes while generating urls', function () {
    $route = Route::getRoutes()->getByName('products.show');
    $before = serialize([$route->uri(), $route->getAction(), $route->getPrefix()]);

    foreach (['en', 'ar', 'fr', 'ar', 'en'] as $locale) {
        locale()->route('products.show', 1, $locale);
    }

    expect(serialize([$route->uri(), $route->getAction(), $route->getPrefix()]))->toBe($before)
        ->and(route('products.show', 1))->toBe('http://localhost/products/1');
});

test('it uses localized route keys and rejects a missing one', function () {
    expect(locale()->route('posts.show', new Post(1), 'ar'))->toBe('http://localhost/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات'))
        ->and(locale()->route('posts.show', ['post' => new Post(1)], 'en'))->toBe('http://localhost/posts/clean-architecture')
        ->and(fn () => locale()->route('posts.show', new Post(2), 'ar'))
        ->toThrow(MissingLocalizedRouteKeyException::class, 'Route parameter [0] ('.Post::class.') has no route key in [ar].');
});

test('it binds localized slugs with the url locale', function () {
    $this->get('/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات'))->assertOk()->assertJson(['id' => 1, 'locale' => 'ar']);
    $this->get('/posts/clean-architecture')->assertOk()->assertJson(['id' => 1, 'locale' => 'en']);
    $this->get('/ar/'.rawurlencode('مقالات').'/clean-architecture')->assertNotFound();
    $this->get('/fr/posts/clean-architecture')->assertNotFound();
});

test('it builds alternates and switch urls from the current route skipping missing slugs', function () {
    $response = $this->get('/posts/clean-architecture?utm=x&page=2')->assertOk();
    $ar = 'http://localhost/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات');

    $response->assertJson([
        'alternates' => ['en' => 'http://localhost/posts/clean-architecture', 'ar' => $ar],
        'hreflangs' => ['en' => 'http://localhost/posts/clean-architecture', 'ar' => $ar, 'x-default' => 'http://localhost/posts/clean-architecture'],
        'switch' => ['ar' => $ar.'?utm=x&page=2', 'fr' => null, 'en' => 'http://localhost/posts/clean-architecture?utm=x&page=2'],
    ]);

    expect($response->json('alternates'))->not->toHaveKey('fr');

    $this->get('/posts/english-only')->assertJson([
        'alternates' => ['en' => 'http://localhost/posts/english-only'],
        'switch' => ['ar' => null, 'fr' => null],
    ]);
});

test('it switches parameters and query strings without decoding them', function () {
    Route::localized(function () {
        Route::get('/probe/{product}', fn () => ['switch' => locale()->switchUrl('fr'), 'alternates' => locale()->alternates()])->name('probe');
    });

    $this->get('/ar/probe/a%20b?x=%20y&x=2')->assertJson([
        'switch' => 'http://localhost/fr/probe/a%20b?x=%20y&x=2',
        'alternates' => ['en' => 'http://localhost/probe/a%20b', 'ar' => 'http://localhost/ar/probe/a%20b', 'fr' => 'http://localhost/fr/probe/a%20b'],
    ]);
});

test('it returns no alternates outside localized routes', function () {
    Route::get('/plain', fn () => ['alternates' => locale()->alternates(), 'hreflangs' => locale()->hreflangs(), 'switch' => locale()->switchUrl('ar')]);

    $this->get('/plain')->assertExactJson(['alternates' => [], 'hreflangs' => [], 'switch' => null]);

    expect(fn () => locale()->switchUrl('de'))->toThrow(UnsupportedLocaleException::class);
});

test('it signs localized routes natively and verifies them over http', function () {
    $ar = locale()->signedRoute('invites.accept', ['invite' => 7], locale: 'ar');
    $fr = locale()->temporarySignedRoute('invites.accept', now()->addMinutes(30), ['invite' => 7], locale: 'fr');

    expect($ar)->toStartWith('http://localhost/ar/invites/7?signature=')
        ->and($fr)->toStartWith('http://localhost/fr/invites/7?expires=');

    $this->get($ar)->assertOk()->assertJson(['locale' => 'ar']);
    $this->get($fr)->assertOk()->assertJson(['locale' => 'fr']);

    $this->get(str_replace('/ar/', '/fr/', $ar))->assertForbidden();
    $this->get(str_replace('/invites/7', '/invites/8', $ar))->assertForbidden();
    $this->get(str_replace('/ar/invites', '/invites', $ar))->assertForbidden();
    $this->get($ar.'&extra=1')->assertForbidden();
});

test('it rejects expired localized signatures', function () {
    $url = locale()->temporarySignedRoute('invites.accept', now()->addMinute(), ['invite' => 1], locale: 'ar');

    Carbon::setTestNow(now()->addMinutes(2));

    $this->get($url)->assertForbidden();
});

test('it supports relative signatures', function () {
    $url = locale()->signedRoute('invites.relative', ['invite' => 3], absolute: false, locale: 'fr');

    expect($url)->toStartWith('/fr/relative/3?signature=');

    $this->get('http://other.test'.$url)->assertOk();
    $this->get('http://other.test'.str_replace('/fr/', '/ar/', $url))->assertForbidden();
});

test('it keeps the deprecated url signing macros on the native signer', function () {
    $url = URL::LocalizedSignedRoute('ar', 'invites.accept', ['invite' => 2]);
    $temporary = URL::temporaryLocalizedSignedRoute('fr', 'invites.accept', now()->addMinute(), ['invite' => 2]);

    $this->get($url)->assertOk()->assertJson(['locale' => 'ar']);
    $this->get($temporary)->assertOk()->assertJson(['locale' => 'fr']);
});

test('it does not offer switch urls for signed pages', function () {
    Route::localized(function () {
        Route::get('/secret/{id}', fn () => ['switch' => locale()->switchUrl('fr'), 'alternates' => locale()->alternates()])->name('secret')->middleware('signed');
    });

    $this->get(locale()->signedRoute('secret', ['id' => 1], locale: 'ar'))->assertExactJson(['switch' => null, 'alternates' => []]);
});
