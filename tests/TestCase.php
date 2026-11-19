<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Translation\FileLoader;
use Orchestra\Testbench\TestCase as Orchestra;
use Pharaonic\Laravel\Localization\LocalizationServiceProvider;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;

abstract class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LocalizationServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));
        $app['config']->set('app.url', 'http://localhost');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('pharaonic.localization.supported', ['en', 'ar', 'fr']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Bound after boot: Laravel 6 registers its own loader after defineEnvironment().
        $this->app->instance('translation.loader', self::translations('lang'));
        $this->app->forgetInstance('translator');
    }

    /**
     * A loader for one fixture lang directory; FileLoader::addPath() needs Laravel 10.
     */
    protected static function translations(string $directory): FileLoader
    {
        return new FileLoader(new Filesystem, __DIR__.'/Fixtures/'.$directory);
    }

    /**
     * Change the localization configuration and rebuild the catalog.
     *
     * @param  array<string, mixed>  $config
     */
    protected function localization(array $config): void
    {
        foreach ($config as $key => $value) {
            $this->app['config']->set(str_starts_with($key, 'app.') ? $key : 'pharaonic.localization.'.$key, $value);
        }

        $this->app->forgetInstance(LocaleCatalog::class);
        $this->app->singleton(LocaleCatalog::class, fn ($app) => LocaleCatalog::fromConfig($app['config']));
        $this->app->forgetInstance(\Pharaonic\Laravel\Localization\LocalizationManager::class);
    }
}
