<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Localization\Console\DoctorCommand;
use Pharaonic\Laravel\Localization\Middleware\SetNegotiatedLocale;
use Pharaonic\Laravel\Localization\Middleware\SetPathLocale;
use Pharaonic\Laravel\Localization\Routing\LocalizedRouteRegistrar;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;

final class LocalizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/localization.php', 'pharaonic.localization');

        // Laravel's App::setLocale() writes app.locale, so the default is read now,
        // before any request can change it, and the catalog is built lazily from it.
        /** @var Repository $config */
        $config = $this->app->make('config');
        $default = $config->get('app.locale');

        $this->app->singleton(LocaleCatalog::class, function (Container $app) use ($default): LocaleCatalog {
            /** @var Repository $config */
            $config = $app->make('config');

            return LocaleCatalog::fromConfig($config, $default);
        });

        $this->app->singleton(LocalizationManager::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/localization.php' => $this->app->configPath('pharaonic/localization.php'),
            ], ['pharaonic', 'laravel-localization', 'pharaonic-config', 'localization-config']);

            $this->commands([DoctorCommand::class]);
        }

        /** @var Router $router */
        $router = $this->app->make('router');
        $router->aliasMiddleware('localization', SetNegotiatedLocale::class);
        $this->prioritizeMiddleware($router);

        // The HTTP kernel copies its own priority list to the router when it is
        // built; when that happens after boot, apply the order again.
        $this->app->afterResolving('Illuminate\Contracts\Http\Kernel', function () use ($router): void {
            $this->prioritizeMiddleware($router);
        });

        $router->macro('localized', function (Closure $routes): void {
            $app = Container::getInstance();

            /** @var Router $router */
            $router = $app->make('router');
            /** @var Loader $loader */
            $loader = $app->make('translation.loader');
            /** @var LocaleCatalog $catalog */
            $catalog = $app->make(LocaleCatalog::class);

            (new LocalizedRouteRegistrar($router, $catalog, $loader))->register($routes);
        });

        $this->registerDeprecatedUrlMacros();
    }

    /**
     * Run the locale middleware right before SubstituteBindings, so localized
     * route model binding sees the locale, and after session and authentication.
     */
    private function prioritizeMiddleware(Router $router): void
    {
        $priority = [];

        foreach ($router->middlewarePriority as $middleware) {
            if (is_string($middleware) && $middleware !== SetPathLocale::class && $middleware !== SetNegotiatedLocale::class) {
                $priority[] = $middleware;
            }
        }

        $index = array_search(SubstituteBindings::class, $priority, true);

        array_splice($priority, $index === false ? count($priority) : $index, 0, [SetPathLocale::class, SetNegotiatedLocale::class]);

        $router->middlewarePriority = $priority;
    }

    private function registerDeprecatedUrlMacros(): void
    {
        /** @deprecated Use locale()->signedRoute($name, $parameters, $expiration, $absolute, $locale). */
        UrlGenerator::macro('LocalizedSignedRoute', function (string $locale, string $name, mixed $parameters = [], DateTimeInterface|DateInterval|int|null $expiration = null, bool $absolute = true): string {
            return locale()->signedRoute($name, $parameters, $expiration, $absolute, $locale);
        });

        /** @deprecated Use locale()->temporarySignedRoute($name, $expiration, $parameters, $absolute, $locale). */
        UrlGenerator::macro('temporaryLocalizedSignedRoute', function (string $locale, string $name, DateTimeInterface|DateInterval|int $expiration, mixed $parameters = [], bool $absolute = true): string {
            return locale()->temporarySignedRoute($name, $expiration, $parameters, $absolute, $locale);
        });
    }
}
