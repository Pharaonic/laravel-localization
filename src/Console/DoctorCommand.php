<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Middleware\SetNegotiatedLocale;
use Pharaonic\Laravel\Localization\Routing\LocalizedRouteRegistrar;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Throwable;

/**
 * Validates the localization configuration, resolvers and localized routes.
 * Exits with 1 when something is broken; warnings alone exit with 0.
 */
final class DoctorCommand extends Command
{
    /** @var string */
    protected $signature = 'localization:doctor';

    /** @var string */
    protected $description = 'Check the localization configuration, resolvers and localized routes';

    private int $errors = 0;

    private int $warnings = 0;

    public function handle(Repository $config, Router $router): int
    {
        $this->errors = 0;
        $this->warnings = 0;

        try {
            $catalog = LocaleCatalog::fromConfig($config);
        } catch (InvalidLocalizationConfigurationException $exception) {
            $this->reportError($exception->getMessage());

            return $this->summary();
        }

        $this->reportOk(sprintf('Default locale [%s] (app.locale), supported: %s.', $catalog->default(), implode(', ', $catalog->supported())));

        // The HTTP kernel gives the router its middleware groups; artisan does not build it.
        if ($this->laravel->bound('Illuminate\Contracts\Http\Kernel')) {
            $this->laravel->make('Illuminate\Contracts\Http\Kernel');
        }

        if (! self::cacheable($config->get('pharaonic.localization'))) {
            $this->reportError('pharaonic.localization holds objects or closures, so config:cache cannot store it.');
        }

        $this->checkResolvers($catalog);
        $this->checkMiddleware($router);
        $this->checkRoutes($catalog, $router);

        return $this->summary();
    }

    private function checkResolvers(LocaleCatalog $catalog): void
    {
        foreach ($catalog->profiles() as $profile => $resolvers) {
            foreach ($resolvers as $resolver) {
                if (in_array($resolver, LocaleCatalog::BUILT_IN_RESOLVERS, true)) {
                    continue;
                }

                try {
                    $instance = $this->laravel->make($resolver);
                } catch (Throwable $exception) {
                    $this->reportError(sprintf('Resolver [%s] of profile [%s] cannot be built: %s', $resolver, $profile, $exception->getMessage()));

                    continue;
                }

                if (! $instance instanceof LocaleResolver) {
                    $this->reportError(sprintf('Resolver [%s] of profile [%s] does not implement %s.', $resolver, $profile, LocaleResolver::class));
                }
            }
        }

        $this->reportOk(sprintf('Resolver profiles: %s.', implode(', ', array_keys($catalog->profiles())) ?: 'none'));
    }

    private function checkMiddleware(Router $router): void
    {
        if (($router->getMiddleware()['localization'] ?? null) !== SetNegotiatedLocale::class) {
            $this->reportWarning('The "localization" middleware alias points elsewhere; negotiated routes need '.SetNegotiatedLocale::class.'.');
        }
    }

    private function checkRoutes(LocaleCatalog $catalog, Router $router): void
    {
        $routes = $router->getRoutes();
        $variants = [];
        $localized = 0;

        foreach ($routes->getRoutes() as $route) {
            if (! $route instanceof Route) {
                continue;
            }

            $data = $route->getAction('localization');

            if (is_array($data) && is_string($data['name'] ?? null) && is_string($data['locale'] ?? null)
                && ! is_string($route->getAction('localization_redirect'))) {
                $localized++;
                $variants[$data['name']][$data['locale']] = $route;
                $concrete = LocalizedRouteRegistrar::nameFor($data['name'], $data['locale'], (bool) ($data['named'] ?? false), $catalog->default());

                if ($routes->getByName($concrete) !== $route) {
                    $this->reportError(sprintf('Route name [%s] resolves to another route; rename the duplicate.', $concrete));
                }
            }

            $this->checkNegotiatedRoute($catalog, $router, $route);
        }

        foreach ($variants as $name => $locales) {
            $missing = array_diff($catalog->supported(), array_keys($locales));
            $unknown = array_diff(array_keys($locales), $catalog->supported());

            if ($missing !== [] || $unknown !== []) {
                $this->reportError(sprintf(
                    'Localized route [%s] does not match the supported locales (missing: %s; unknown: %s); run route:clear or route:cache again.',
                    $name, implode(', ', $missing) ?: 'none', implode(', ', $unknown) ?: 'none'
                ));
            }
        }

        $this->reportOk(sprintf('%d localized routes (%d logical) checked.', $localized, count($variants)));
    }

    private function checkNegotiatedRoute(LocaleCatalog $catalog, Router $router, Route $route): void
    {
        $middleware = $router->gatherRouteMiddleware($route);

        foreach ($middleware as $entry) {
            if (! is_string($entry) || explode(':', $entry)[0] !== SetNegotiatedLocale::class) {
                continue;
            }

            $profile = explode(',', explode(':', $entry, 2)[1] ?? '')[0] ?: 'api';

            try {
                $resolvers = $catalog->profile($profile);
            } catch (InvalidLocalizationConfigurationException $exception) {
                $this->reportError(sprintf('Route [%s]: %s', $route->uri(), $exception->getMessage()));

                return;
            }

            if (in_array('session', $resolvers, true) && ! self::has($middleware, 'Illuminate\Session\Middleware\StartSession')) {
                $this->reportWarning(sprintf('Route [%s] uses the session resolver without StartSession; it will never match.', $route->uri()));
            }

            if (in_array('cookie', $resolvers, true) && ! self::has($middleware, 'Illuminate\Cookie\Middleware\EncryptCookies')) {
                $this->reportWarning(sprintf('Route [%s] reads the locale cookie without EncryptCookies; it reads the raw value.', $route->uri()));
            }
        }
    }

    /**
     * @param  array<array-key, mixed>  $middleware
     */
    private static function has(array $middleware, string $class): bool
    {
        foreach ($middleware as $entry) {
            if (is_string($entry) && (($name = explode(':', $entry)[0]) === $class || is_subclass_of($name, $class))) {
                return true;
            }
        }

        return false;
    }

    private static function cacheable(mixed $value): bool
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (! self::cacheable($item)) {
                    return false;
                }
            }

            return true;
        }

        return $value === null || is_scalar($value);
    }

    private function reportOk(string $message): void
    {
        $this->line('<info>[OK]</info> '.$message);
    }

    private function reportWarning(string $message): void
    {
        $this->warnings++;
        $this->line('<comment>[WARN]</comment> '.$message);
    }

    private function reportError(string $message): void
    {
        $this->errors++;
        $this->line('<error>[ERROR]</error> '.$message);
    }

    private function summary(): int
    {
        $this->line('');
        $this->line(sprintf('%d error(s), %d warning(s).', $this->errors, $this->warnings));

        return $this->errors > 0 ? 1 : 0;
    }
}
