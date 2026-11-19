<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;

/**
 * Gives the Testbench CLI, which runs route:cache for RouteCacheTest, the same
 * locales, route translations and routes as TestCase. Registered in testbench.yaml only.
 */
final class RouteCacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['config']->set('pharaonic.localization.supported', ['en', 'ar', 'fr']);
        $this->app->instance('translation.loader', new FileLoader(new Filesystem, __DIR__.'/lang'));
        $this->app->forgetInstance('translator');

        $this->loadRoutesFrom(__DIR__.'/routes/localized.php');
    }
}
