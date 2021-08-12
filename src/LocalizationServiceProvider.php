<?php

namespace Pharaonic\Laravel\Localization;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Localization\Classes\Localization;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Config Merge
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'laravel-localization');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->instance('Localization', new Localization);

        Route::macro('localized', function ($routes) {
            locale()->detect($routes);
        });


        // Publishes
        $this->publishes([
            __DIR__ . '/config.php' => config_path('Pharaonic/localization.php'),
        ], ['pharaonic', 'laravel-localization']);
    }
}
