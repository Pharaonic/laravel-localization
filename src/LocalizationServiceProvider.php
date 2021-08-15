<?php

namespace Pharaonic\Laravel\Localization;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Localization\Classes\Localization;
use Pharaonic\Laravel\Localization\Middleware\LocaleRemover;

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
        // Publishes
        $this->publishes([
            __DIR__ . '/config.php' => config_path('Pharaonic/localization.php'),
        ], ['pharaonic', 'laravel-localization']);


        $this->app->instance('Localization', new Localization);

        Route::macro('localized', function ($routes) {
            $type = config('Pharaonic.localization.type', config('laravel-localization.type'));

            if ($type == 'sub-directory')
                Route::prefix(locale()->detect($routes))->middleware(LocaleRemover::class)->group($routes);
            else
                locale()->detect($routes);
        });

        URL::macro('LocalizedSignedRoute', function ($locale, $name, $parameters = [], $expiration = null, $absolute = true) {

            $parameters = Arr::wrap($parameters);

            if (array_key_exists('signature', $parameters)) {
                throw new InvalidArgumentException(
                    '"Signature" is a reserved parameter when generating signed routes. Please rename your route parameter.'
                );
            }

            if ($expiration) {
                $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
            }

            ksort($parameters);

            $key = call_user_func($this->keyResolver);

            return locale($locale)->route($name, $parameters + [
                'signature' => hash_hmac('sha256', locale($locale)->route($name, $parameters, $absolute), $key),
            ], $absolute);
        });

        URL::macro('temporaryLocalizedSignedRoute', function ($locale, $name, $expiration, $parameters = [], $absolute = true) {
            return $this->LocalizedSignedRoute($locale, $name, $parameters, $expiration, $absolute);
        });
    }
}
