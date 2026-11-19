<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Tests\TestCase;

/**
 * Laravel 6's route:cache serializes every route and the cached file unserializes
 * them into a new RouteCollection; config:cache writes var_export() output. Both
 * are reproduced in-process, without Testbench's remote process (Testbench 6+).
 */
final class RouteCacheTest extends TestCase
{
    private static function cachedRoutesFile(): string
    {
        return __DIR__.'/../Fixtures/routes/localized.php';
    }

    /**
     * @return list<string>
     */
    private static function routeSnapshot(): array
    {
        $routes = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $routes[] = implode('|', $route->methods()).' '.$route->uri().' '.$route->getName().' '.json_encode($route->getAction('localization'));
        }

        sort($routes);

        return $routes;
    }

    /**
     * @return array<string, array{int, mixed}>
     */
    private function responses(): array
    {
        $urls = [
            '/', '/ar', '/fr/a-propos', '/about', '/fr/produits/5', '/ar/'.rawurlencode('المنتجات').'/5', '/en/products/5?x=1',
            '/ar/'.rawurlencode('مقالات').'/'.rawurlencode('معمارية-البرمجيات'), '/posts/clean-architecture', '/de/about', '/fr/items',
        ];
        $results = [];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $results[$url] = [$response->getStatusCode(), $response->headers->get('Location') ?? ($response->isSuccessful() ? $response->json() : null)];
        }

        $response = $this->withHeader('Accept-Language', 'ar')->get('/api/products');
        $results['api'] = [$response->getStatusCode(), $response->json()];
        $results['post'] = [$this->post('/ar/items')->getStatusCode(), null];
        $results['url'] = [200, locale()->route('products.show', 9, 'fr')];

        $signed = locale()->signedRoute('invites.accept', ['invite' => 4], locale: 'ar');
        $results['signed'] = [$this->get($signed)->getStatusCode(), null];
        $results['tampered'] = [$this->get(str_replace('/ar/', '/fr/', $signed))->getStatusCode(), null];

        return $results;
    }

    /**
     * What Laravel 6's route:cache and its cached routes file do.
     */
    private function cacheRoutes(): void
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $this->assertInstanceOf(RoutingRoute::class, $route);
            $route->prepareForSerialization();
        }

        $cached = unserialize(serialize($routes));
        $this->assertInstanceOf(RouteCollection::class, $cached);

        app('router')->setRoutes($cached);
    }

    public function test_it_serves_identical_routes_and_responses_from_cached_routes(): void
    {
        require self::cachedRoutesFile();
        Route::getRoutes()->refreshNameLookups();

        $uncached = self::routeSnapshot();
        $uncachedResponses = $this->responses();

        $this->cacheRoutes();

        $this->assertSame($uncached, self::routeSnapshot());
        $this->assertSame($uncachedResponses, $this->responses());

        $this->assertSame(200, $uncachedResponses['/ar'][0]);
        $this->assertSame('ar', $uncachedResponses['/ar'][1]['locale']);
        $this->assertSame(301, $uncachedResponses['/en/products/5?x=1'][0]);
        $this->assertStringEndsWith('/products/5?x=1', $uncachedResponses['/en/products/5?x=1'][1]);
        $this->assertSame(404, $uncachedResponses['/de/about'][0]);
        $this->assertSame('header', $uncachedResponses['api'][1]['source']);
        $this->assertSame(200, $uncachedResponses['signed'][0]);
        $this->assertSame(403, $uncachedResponses['tampered'][0]);
    }

    public function test_it_lists_localized_routes_with_route_list(): void
    {
        require self::cachedRoutesFile();

        $this->assertSame(0, Artisan::call('route:list'));
        $this->assertStringContainsString('localized.ar.products.show', Artisan::output());
    }

    public function test_it_stores_the_configuration_with_config_cache(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'localization-config');

        try {
            $config = config('pharaonic.localization');
            file_put_contents($file, '<?php return '.var_export($config, true).';'.PHP_EOL);

            $this->assertSame($config, require $file);
        } finally {
            unlink($file);
        }
    }
}
