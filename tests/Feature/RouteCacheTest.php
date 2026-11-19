<?php

declare(strict_types=1);

use Illuminate\Routing\CompiledRouteCollection;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Laravel 7's route:cache compiles the routes and the cached file loads the
// var_export() of that compilation into a CompiledRouteCollection; config:cache
// writes var_export() output. Both are reproduced in-process, without
// Testbench's remote process (Testbench 6+).

function cachedRoutesFile(): string
{
    return __DIR__.'/../Fixtures/routes/localized.php';
}

/**
 * Every registered route with its name and localization action, sorted.
 *
 * @return list<string>
 */
function routeSnapshot(): array
{
    $routes = [];

    foreach (Route::getRoutes()->getRoutes() as $route) {
        $routes[] = implode('|', $route->methods()).' '.$route->uri().' '.$route->getName().' '.json_encode($route->getAction('localization'));
    }

    sort($routes);

    return $routes;
}

/**
 * What Laravel 7's route:cache and its cached routes file do.
 */
function cacheRoutes(): void
{
    $routes = Route::getRoutes();
    expect($routes)->toBeInstanceOf(RouteCollection::class);

    foreach ($routes as $route) {
        expect($route)->toBeInstanceOf(RoutingRoute::class);
        $route->prepareForSerialization();
    }

    $file = tempnam(sys_get_temp_dir(), 'localization-routes');

    try {
        file_put_contents($file, '<?php app(\'router\')->setCompiledRoutes('.var_export($routes->compile(), true).');'.PHP_EOL);
        require $file;
    } finally {
        unlink($file);
    }

    expect(Route::getRoutes())->toBeInstanceOf(CompiledRouteCollection::class);
}

test('it serves identical routes and responses from cached routes', function () {
    $responses = function (): array {
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
    };

    require cachedRoutesFile();
    Route::getRoutes()->refreshNameLookups();

    $uncached = routeSnapshot();
    $uncachedResponses = $responses();

    cacheRoutes();

    expect(routeSnapshot())->toBe($uncached)
        ->and($responses())->toBe($uncachedResponses)
        ->and($uncachedResponses['/ar'][0])->toBe(200)
        ->and($uncachedResponses['/ar'][1]['locale'])->toBe('ar')
        ->and($uncachedResponses['/en/products/5?x=1'][0])->toBe(301)
        ->and($uncachedResponses['/en/products/5?x=1'][1])->toEndWith('/products/5?x=1')
        ->and($uncachedResponses['/de/about'][0])->toBe(404)
        ->and($uncachedResponses['api'][1]['source'])->toBe('header')
        ->and($uncachedResponses['signed'][0])->toBe(200)
        ->and($uncachedResponses['tampered'][0])->toBe(403);
});

test('it lists localized routes with route list', function () {
    require cachedRoutesFile();

    expect(Artisan::call('route:list'))->toBe(0)
        ->and(Artisan::output())->toContain('localized.ar.products.show');
});

test('it stores the configuration with config cache', function () {
    $file = tempnam(sys_get_temp_dir(), 'localization-config');

    try {
        $config = config('pharaonic.localization');
        file_put_contents($file, '<?php return '.var_export($config, true).';'.PHP_EOL);

        expect(require $file)->toBe($config);
    } finally {
        unlink($file);
    }
});
