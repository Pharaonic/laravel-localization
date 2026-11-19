<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Closure;
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
use Pharaonic\Laravel\Localization\Tests\TestCase;

final class PathRoutingTest extends TestCase
{
    private static function pages(): void
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
     * @return list<string>
     */
    private static function routeUris(): array
    {
        $uris = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $uris[] = implode('|', $route->methods()).' '.$route->uri();
        }

        return $uris;
    }

    public function test_it_registers_one_static_route_per_supported_locale_default_unprefixed(): void
    {
        self::pages();

        $uris = self::routeUris();

        foreach ([
            'GET|HEAD /', 'GET|HEAD products', 'GET|HEAD products/{product}', 'POST contact',
            'GET|HEAD ar', 'GET|HEAD ar/المنتجات', 'GET|HEAD ar/المنتجات/{product}', 'GET|HEAD ar/من-نحن', 'POST ar/contact',
            'GET|HEAD fr', 'GET|HEAD fr/produits', 'GET|HEAD fr/a-propos',
            'GET|HEAD en', 'GET|HEAD en/products',
        ] as $uri) {
            $this->assertContains($uri, $uris);
        }

        $this->assertNotContains('POST en/contact', $uris);
        $this->assertTrue(Route::has(['products.index', 'localized.ar.products.index', 'localized.fr.products.index']));
        $this->assertFalse(Route::has('localized.en.products.index'));
    }

    /**
     * @dataProvider variants
     */
    public function test_it_serves_each_variant_in_its_locale_with_the_path_as_source(string $uri, string $locale, string $route, string $translated): void
    {
        self::pages();

        $this->get($uri)
            ->assertOk()
            ->assertHeader('Content-Language', $locale)
            ->assertJson(['locale' => $locale, 'current' => $locale, 'source' => 'path', 'mode' => 'path', 'route' => $route, 'translated' => $translated]);
    }

    /**
     * @return list<array{string, string, string, string}>
     */
    public static function variants(): array
    {
        return [
            ['/', 'en', 'home', 'Hello'],
            ['/products', 'en', 'products.index', 'Hello'],
            ['/ar', 'ar', 'localized.ar.home', 'مرحبا'],
            ['/ar/'.rawurlencode('المنتجات'), 'ar', 'localized.ar.products.index', 'مرحبا'],
            ['/fr/produits/42', 'fr', 'localized.fr.products.show', 'Bonjour'],
        ];
    }

    public function test_it_lets_an_explicit_url_locale_win_over_user_session_cookie_and_header(): void
    {
        self::pages();

        $this->actingAs(User::prefers('fr'))
            ->withSession(['locale' => 'fr'])
            ->withUnencryptedCookie('locale', 'fr')
            ->withHeader('Accept-Language', 'fr')
            ->get('/ar')
            ->assertJson(['locale' => 'ar', 'source' => 'path']);
    }

    public function test_it_serves_the_unprefixed_url_in_the_default_locale_whatever_the_preferences(): void
    {
        self::pages();

        $this->actingAs(User::prefers('ar'))
            ->withSession(['locale' => 'ar'])
            ->withUnencryptedCookie('locale', 'ar')
            ->withHeader('Accept-Language', 'ar')
            ->get('/products')
            ->assertOk()
            ->assertJson(['locale' => 'en', 'source' => 'path']);
    }

    public function test_it_keeps_unsupported_prefixes_as_plain_404s_without_redirects_or_shadowing(): void
    {
        self::pages();
        Route::get('/xx/products', fn () => 'application route');

        $this->get('/de/products')->assertNotFound();
        $this->get('/xx/products')->assertOk()->assertSee('application route');
    }

    public function test_it_redirects_the_default_locale_prefix_to_the_canonical_url_for_get_and_head_only(): void
    {
        self::pages();

        $this->get('/en/products/7?sort=price&page=2')->assertStatus(301)->assertRedirect('http://localhost/products/7?sort=price&page=2');
        $this->get('/en')->assertStatus(301)->assertRedirect('http://localhost');
        $this->call('HEAD', '/en/products')->assertStatus(301);
        $this->post('/en/contact')->assertNotFound();
        $this->post('/en/products')->assertStatus(405);
        $this->get('/en/products/abc')->assertNotFound();
    }

    public function test_it_decides_the_canonical_redirect_from_the_route_only(): void
    {
        self::pages();

        $this->actingAs(User::prefers('ar'))
            ->withHeader('Accept-Language', 'fr')
            ->get('/en/products')
            ->assertRedirect('http://localhost/products');
    }

    public function test_it_does_not_register_redirects_when_canonical_redirect_is_off(): void
    {
        $this->localization(['path' => ['hide_default' => true, 'canonical_redirect' => false]]);
        self::pages();

        $this->get('/en/products')->assertNotFound();
    }

    public function test_it_prefixes_every_locale_when_the_default_is_not_hidden(): void
    {
        $this->localization(['path' => ['hide_default' => false]]);
        self::pages();

        $this->get('/products')->assertNotFound();
        $this->get('/')->assertNotFound();
        $this->get('/en/products')->assertOk()->assertJson(['locale' => 'en', 'route' => 'products.index']);
        $this->get('/ar/'.rawurlencode('المنتجات'))->assertOk()->assertJson(['locale' => 'ar']);
    }

    public function test_it_uses_configured_prefixes(): void
    {
        $this->localization(['supported' => ['en', 'ar', 'pt_BR'], 'path' => ['prefixes' => ['pt_BR' => 'br']]]);
        self::pages();

        $this->get('/br/products')->assertOk()->assertHeader('Content-Language', 'pt-BR')->assertJson(['locale' => 'pt_BR']);
        $this->get('/pt-br/products')->assertNotFound();
    }

    public function test_it_keeps_group_prefixes_names_middleware_domains_and_constraints(): void
    {
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

        $this->assertContains('throttle:60,1', Route::getRoutes()->getByName('localized.ar.shop.products')->gatherMiddleware());
    }

    public function test_it_localizes_resource_routes(): void
    {
        Route::localized(function (): void {
            Route::resource('products', ResourceController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        });

        $this->get('/fr/produits')->assertOk()->assertJson(['action' => 'index', 'locale' => 'fr']);
        $this->get('/fr/produits/9')->assertOk()->assertJson(['action' => 'show', 'product' => '9', 'locale' => 'fr']);
        $this->post('/ar/'.rawurlencode('المنتجات'))->assertStatus(201)->assertJson(['action' => 'store', 'locale' => 'ar']);
        $this->put('/products/3')->assertOk()->assertJson(['action' => 'update', 'locale' => 'en']);
        $this->delete('/fr/produits/3')->assertOk()->assertJson(['action' => 'destroy', 'locale' => 'fr']);
        $this->put('/en/products/3')->assertStatus(405);

        $this->assertTrue(Route::has(['products.index', 'localized.ar.products.store', 'localized.fr.products.destroy']));
    }

    public function test_it_names_unnamed_routes_deterministically(): void
    {
        self::pages();

        $names = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            if (str_ends_with($route->uri(), 'about') || str_ends_with($route->uri(), 'من-نحن') || str_ends_with($route->uri(), 'a-propos')) {
                $names[] = (string) $route->getName();
            }
        }

        $this->assertCount(4, $names);

        foreach ($names as $name) {
            $this->assertMatchesRegularExpression('/^localized\.(en|ar|fr|redirect)\.uri\.[0-9a-f]{16}$/', $name);
        }

        $this->assertCount(1, array_unique(array_map(fn (string $name): string => substr($name, -16), $names)));
    }

    public function test_it_registers_prefixed_variants_before_the_default_so_a_root_slug_cannot_shadow_them(): void
    {
        Route::localized(function (): void {
            Route::get('/', PageController::class)->name('home');
            Route::get('/{page}', PageController::class)->name('page');
        });

        $this->get('/ar')->assertJson(['locale' => 'ar', 'route' => 'localized.ar.home']);
        $this->get('/contact')->assertJson(['locale' => 'en', 'route' => 'page', 'parameters' => ['page' => 'contact']]);
    }

    /**
     * @dataProvider invalidDefinitions
     */
    public function test_it_rejects_colliding_reserved_and_nested_definitions(Closure $routes, string $message): void
    {
        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage($message);

        $routes();
    }

    /**
     * @return array<string, array{Closure, string}>
     */
    public static function invalidDefinitions(): array
    {
        return [
            'URI taken by the application' => [function () {
                Route::get('/fr/produits', fn () => 'taken');
                self::pages();
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

    public function test_it_detects_an_existing_name_that_a_variant_needs(): void
    {
        Route::get('/elsewhere', fn () => 'x')->name('home');
        Route::getRoutes()->refreshNameLookups();

        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage('Localized route name [home] is already registered.');

        Route::localized(function () {
            Route::get('/', PageController::class)->name('home');
        });
    }

    public function test_it_rejects_route_translations_that_would_change_the_uri_structure(): void
    {
        $this->app->instance('translation.loader', self::translations('broken-lang'));

        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage('Route translations for [ar] (routes.php) must map URI segments');

        Route::localized(function () {
            Route::get('/products', PageController::class);
        });
    }

    public function test_it_keeps_untranslated_segments_instead_of_falling_back_to_another_locale(): void
    {
        $this->localization(['app.fallback_locale' => 'ar']);
        Route::localized(function () {
            Route::get('/services', PageController::class)->name('services');
        });

        $this->get('/fr/services')->assertOk()->assertJson(['locale' => 'fr']);
    }

    public function test_it_translates_static_segments_only_keeping_placeholders_and_constraints(): void
    {
        Route::localized(function () {
            Route::get('/products/{products}/about/{about?}', PageController::class)->name('nested')->where('products', '[0-9]+');
        });

        $route = Route::getRoutes()->getByName('localized.ar.nested');

        $this->assertSame('ar/المنتجات/{products}/من-نحن/{about?}', $route->uri());
        $this->assertSame(['products' => '[0-9]+'], $route->wheres);
    }

    public function test_it_runs_before_route_model_binding_so_bindings_see_the_locale(): void
    {
        app(Kernel::class);
        $priority = app('router')->middlewarePriority;
        $bindings = array_search(SubstituteBindings::class, $priority, true);

        $this->assertLessThan($bindings, array_search(SetPathLocale::class, $priority, true));
        $this->assertLessThan(
            array_search(SetNegotiatedLocale::class, $priority, true),
            array_search(AuthenticatesRequests::class, $priority, true)
        );
    }
}
