<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Middleware\SetNegotiatedLocale;
use Pharaonic\Laravel\Localization\Middleware\SetPathLocale;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\ResourceController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\User;

/**
 * The localized pages most path tests run against.
 */
function localizedPages(): void
{
    Route::localized(function (): void {
        Route::get('/', PageController::class)->name('home');
        Route::get('/products', PageController::class)->name('products.index');
        Route::get('/products/{product}', PageController::class)->name('products.show')->where('product', '[0-9]+');
        Route::get('/about', PageController::class);
        Route::post('/contact', PageController::class)->name('contact');
    });
}

/**
 * Every registered route as "METHODS uri".
 *
 * @return list<string>
 */
function routeUris(): array
{
    $uris = [];

    foreach (Route::getRoutes()->getRoutes() as $route) {
        $uris[] = implode('|', $route->methods()).' '.$route->uri();
    }

    return $uris;
}

test('it registers one static route per supported locale default unprefixed', function () {
    localizedPages();

    expect(routeUris())->toContain(
        'GET|HEAD /', 'GET|HEAD products', 'GET|HEAD products/{product}', 'POST contact',
        'GET|HEAD ar', 'GET|HEAD ar/المنتجات', 'GET|HEAD ar/المنتجات/{product}', 'GET|HEAD ar/من-نحن', 'POST ar/contact',
        'GET|HEAD fr', 'GET|HEAD fr/produits', 'GET|HEAD fr/a-propos',
        'GET|HEAD en', 'GET|HEAD en/products',
    )->not->toContain('POST en/contact')
        ->and(Route::has(['products.index', 'localized.ar.products.index', 'localized.fr.products.index']))->toBeTrue()
        ->and(Route::has('localized.en.products.index'))->toBeFalse();
});

test('it serves each variant in its locale with the path as source', function (string $uri, string $locale, string $route, string $translated) {
    localizedPages();

    $this->get($uri)
        ->assertOk()
        ->assertHeader('Content-Language', $locale)
        ->assertJson(['locale' => $locale, 'current' => $locale, 'source' => 'path', 'mode' => 'path', 'route' => $route, 'translated' => $translated]);
})->with([
    ['/', 'en', 'home', 'Hello'],
    ['/products', 'en', 'products.index', 'Hello'],
    ['/ar', 'ar', 'localized.ar.home', 'مرحبا'],
    ['/ar/'.rawurlencode('المنتجات'), 'ar', 'localized.ar.products.index', 'مرحبا'],
    ['/fr/produits/42', 'fr', 'localized.fr.products.show', 'Bonjour'],
]);

test('it lets an explicit url locale win over user session cookie and header', function () {
    localizedPages();

    $this->actingAs(User::prefers('fr'))
        ->withSession(['locale' => 'fr'])
        ->withUnencryptedCookie('locale', 'fr')
        ->withHeader('Accept-Language', 'fr')
        ->get('/ar')
        ->assertJson(['locale' => 'ar', 'source' => 'path']);
});

test('it serves the unprefixed url in the default locale whatever the preferences', function () {
    localizedPages();

    $this->actingAs(User::prefers('ar'))
        ->withSession(['locale' => 'ar'])
        ->withUnencryptedCookie('locale', 'ar')
        ->withHeader('Accept-Language', 'ar')
        ->get('/products')
        ->assertOk()
        ->assertJson(['locale' => 'en', 'source' => 'path']);
});

test('it keeps unsupported prefixes as plain 404s without redirects or shadowing', function () {
    localizedPages();
    Route::get('/xx/products', fn () => 'application route');

    $this->get('/de/products')->assertNotFound();
    $this->get('/xx/products')->assertOk()->assertSee('application route');
});

test('it redirects the default locale prefix to the canonical url for get and head only', function () {
    localizedPages();

    $this->get('/en/products/7?sort=price&page=2')->assertStatus(301)->assertRedirect('http://localhost/products/7?sort=price&page=2');
    $this->get('/en')->assertStatus(301)->assertRedirect('http://localhost');
    $this->call('HEAD', '/en/products')->assertStatus(301);
    $this->post('/en/contact')->assertNotFound();
    $this->post('/en/products')->assertStatus(405);
    $this->get('/en/products/abc')->assertNotFound();
});

test('it decides the canonical redirect from the route only', function () {
    localizedPages();

    $this->actingAs(User::prefers('ar'))
        ->withHeader('Accept-Language', 'fr')
        ->get('/en/products')
        ->assertRedirect('http://localhost/products');
});

test('it does not register redirects when canonical redirect is off', function () {
    $this->localization(['path' => ['hide_default' => true, 'canonical_redirect' => false]]);
    localizedPages();

    $this->get('/en/products')->assertNotFound();
});

test('it prefixes every locale when the default is not hidden', function () {
    $this->localization(['path' => ['hide_default' => false]]);
    localizedPages();

    $this->get('/products')->assertNotFound();
    $this->get('/')->assertNotFound();
    $this->get('/en/products')->assertOk()->assertJson(['locale' => 'en', 'route' => 'products.index']);
    $this->get('/ar/'.rawurlencode('المنتجات'))->assertOk()->assertJson(['locale' => 'ar']);
});

test('it uses configured prefixes', function () {
    $this->localization(['supported' => ['en', 'ar', 'pt_BR'], 'path' => ['prefixes' => ['pt_BR' => 'br']]]);
    localizedPages();

    $this->get('/br/products')->assertOk()->assertHeader('Content-Language', 'pt-BR')->assertJson(['locale' => 'pt_BR']);
    $this->get('/pt-br/products')->assertNotFound();
});

test('it keeps group prefixes names middleware domains and constraints', function () {
    Route::middleware('throttle:60,1')->prefix('shop')->name('shop.')->group(function (): void {
        Route::localized(function (): void {
            Route::prefix('catalog')->group(function (): void {
                Route::get('/products/{product?}', PageController::class)->name('products')->where('product', '[0-9]+');
            });
        });
    });
    Route::domain('{account}.example.com')->group(function (): void {
        Route::localized(function (): void {
            Route::get('/dashboard', PageController::class)->name('dashboard');
        });
    });

    $this->get('/shop/catalog/products')->assertOk()->assertJson(['locale' => 'en', 'route' => 'shop.products']);
    $this->get('/shop/ar/catalog/'.rawurlencode('المنتجات').'/5')->assertOk()->assertJson(['locale' => 'ar', 'route' => 'localized.ar.shop.products', 'parameters' => ['product' => '5']]);
    $this->get('/shop/ar/catalog/'.rawurlencode('المنتجات').'/abc')->assertNotFound();
    $this->get('/shop/en/catalog/products/5')->assertRedirect('http://localhost/shop/catalog/products/5');
    $this->get('http://acme.example.com/fr/dashboard')->assertOk()->assertJson(['locale' => 'fr', 'parameters' => ['account' => 'acme']]);
    $this->get('http://localhost/fr/dashboard')->assertNotFound();

    expect(Route::getRoutes()->getByName('localized.ar.shop.products')->gatherMiddleware())->toContain('throttle:60,1');
});

test('it localizes resource routes', function () {
    Route::localized(function (): void {
        Route::resource('products', ResourceController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });

    $this->get('/fr/produits')->assertOk()->assertJson(['action' => 'index', 'locale' => 'fr']);
    $this->get('/fr/produits/9')->assertOk()->assertJson(['action' => 'show', 'product' => '9', 'locale' => 'fr']);
    $this->post('/ar/'.rawurlencode('المنتجات'))->assertStatus(201)->assertJson(['action' => 'store', 'locale' => 'ar']);
    $this->put('/products/3')->assertOk()->assertJson(['action' => 'update', 'locale' => 'en']);
    $this->delete('/fr/produits/3')->assertOk()->assertJson(['action' => 'destroy', 'locale' => 'fr']);
    $this->put('/en/products/3')->assertStatus(405);

    expect(Route::has(['products.index', 'localized.ar.products.store', 'localized.fr.products.destroy']))->toBeTrue();
});

test('it names unnamed routes deterministically', function () {
    localizedPages();

    $names = [];

    foreach (Route::getRoutes()->getRoutes() as $route) {
        if (str_ends_with($route->uri(), 'about') || str_ends_with($route->uri(), 'من-نحن') || str_ends_with($route->uri(), 'a-propos')) {
            $names[] = (string) $route->getName();
        }
    }

    expect($names)->toHaveCount(4)
        ->each->toMatch('/^localized\.(en|ar|fr|redirect)\.uri\.[0-9a-f]{16}$/')
        ->and(array_unique(array_map(fn (string $name): string => substr($name, -16), $names)))->toHaveCount(1);
});

test('it registers prefixed variants before the default so a root slug cannot shadow them', function () {
    Route::localized(function (): void {
        Route::get('/', PageController::class)->name('home');
        Route::get('/{page}', PageController::class)->name('page');
    });

    $this->get('/ar')->assertJson(['locale' => 'ar', 'route' => 'localized.ar.home']);
    $this->get('/contact')->assertJson(['locale' => 'en', 'route' => 'page', 'parameters' => ['page' => 'contact']]);
});

/**
 * Definitions Route::localized() must reject, with the expected message.
 *
 * @return array<string, array{Closure(): void, string}>
 */
function invalidLocalizedDefinitions(): array
{
    return [
        'URI taken by the application' => [function () {
            Route::get('/fr/produits', fn () => 'taken');
            localizedPages();
        }, 'collides with the already registered route'],
        'translations colliding' => [function () {
            Route::localized(function (): void {
                Route::get('/produits', PageController::class)->name('a');
                Route::get('/products', PageController::class)->name('b');
            });
        }, 'collides with the already registered route'],
        'name taken' => [function () {
            Route::get('/elsewhere', fn () => 'x')->name('localized.fr.home');
            Route::getRoutes()->refreshNameLookups();
            Route::localized(function () {
                Route::get('/', PageController::class)->name('home');
            });
        }, 'Localized route name [localized.fr.home] is already registered.'],
        'reserved name' => [function () {
            Route::localized(function () {
                Route::get('/', PageController::class)->name('localized.home');
            });
        }, 'uses the reserved "localized." prefix'],
        'nested' => [function () {
            Route::localized(function () {
                Route::localized(function () {
                    Route::get('/', PageController::class);
                });
            });
        }, 'cannot be nested'],
    ];
}

test('it rejects colliding reserved and nested definitions', function (string $case) {
    [$routes, $message] = invalidLocalizedDefinitions()[$case];

    expect($routes)->toThrow(InvalidLocalizationConfigurationException::class, $message);
})->with(array_keys(invalidLocalizedDefinitions()));

test('it detects an existing name that a variant needs', function () {
    Route::get('/elsewhere', fn () => 'x')->name('home');
    Route::getRoutes()->refreshNameLookups();

    expect(fn () => Route::localized(function () {
        Route::get('/', PageController::class)->name('home');
    }))->toThrow(InvalidLocalizationConfigurationException::class, 'Localized route name [home] is already registered.');
});

test('it rejects route translations that would change the uri structure', function () {
    $this->app->instance('translation.loader', $this->translations('broken-lang'));

    expect(fn () => Route::localized(function () {
        Route::get('/products', PageController::class);
    }))->toThrow(InvalidLocalizationConfigurationException::class, 'Route translations for [ar] (routes.php) must map URI segments');
});

test('it keeps untranslated segments instead of falling back to another locale', function () {
    $this->localization(['app.fallback_locale' => 'ar']);
    Route::localized(function () {
        Route::get('/services', PageController::class)->name('services');
    });

    $this->get('/fr/services')->assertOk()->assertJson(['locale' => 'fr']);
});

test('it translates static segments only keeping placeholders and constraints', function () {
    Route::localized(function () {
        Route::get('/products/{products}/about/{about?}', PageController::class)->name('nested')->where('products', '[0-9]+');
    });

    $route = Route::getRoutes()->getByName('localized.ar.nested');

    expect($route->uri())->toBe('ar/المنتجات/{products}/من-نحن/{about?}')
        ->and($route->wheres)->toBe(['products' => '[0-9]+']);
});

test('it runs before route model binding so bindings see the locale', function () {
    app(Kernel::class);
    $priority = app('router')->middlewarePriority;

    expect(array_search(SetPathLocale::class, $priority, true))->toBeLessThan(array_search(SubstituteBindings::class, $priority, true))
        ->and(array_search(AuthenticatesRequests::class, $priority, true))->toBeLessThan(array_search(SetNegotiatedLocale::class, $priority, true));
});
