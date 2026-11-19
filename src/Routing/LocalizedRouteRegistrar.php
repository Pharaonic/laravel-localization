<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Routing;

use Closure;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Middleware\SetPathLocale;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;

/**
 * Expands Route::localized() into one static route per supported locale.
 *
 * Nothing here reads the request: the same routes are registered over HTTP,
 * in the console and for route:cache. Each route carries its locale in the
 * `localization` action key, which survives the compiled route cache.
 *
 * @internal
 */
final class LocalizedRouteRegistrar
{
    /** Reserved route-name namespace of the non-default variants: localized.{locale}.{name}. */
    public const NAME_PREFIX = 'localized.';

    /** Name prefix of the canonical redirects: localized.redirect.{name}. */
    public const REDIRECT_PREFIX = 'localized.redirect.';

    /** @var array<string, array<string, string>> */
    private array $translations = [];

    public function __construct(
        private Router $router,
        private LocaleCatalog $catalog,
        private Loader $loader,
    ) {
    }

    /**
     * The concrete route name of a logical route in a locale.
     */
    public static function nameFor(string $name, string $locale, bool $named, string $default): string
    {
        return $named && $locale === $default ? $name : self::NAME_PREFIX.$locale.'.'.$name;
    }

    public function register(Closure $routes): void
    {
        $collection = $this->router->getRoutes();

        if (! $collection instanceof RouteCollection) {
            throw new InvalidLocalizationConfigurationException('Route::localized() cannot register routes while the route cache is loaded.');
        }

        foreach ($this->router->getGroupStack() as $group) {
            if (is_array($group) && in_array(SetPathLocale::class, (array) ($group['middleware'] ?? []), true)) {
                throw new InvalidLocalizationConfigurationException('Route::localized() cannot be nested inside another Route::localized().');
            }
        }

        $collection->refreshNameLookups();
        $outer = trim($this->router->getLastGroupPrefix(), '/');
        $default = $this->catalog->default();

        // Prefixed variants first, so an unprefixed `/{slug}` never shadows `/ar`.
        $locales = array_values(array_diff($this->catalog->supported(), [$default]));
        $locales[] = $default;

        foreach ($locales as $locale) {
            $prefix = $this->catalog->routePrefix($locale);
            $captured = $this->capture($collection, $prefix, $routes);

            foreach ($captured as $route) {
                $this->add($collection, $this->localize($route, $locale, trim($outer.'/'.$prefix, '/'), $outer));
            }

            if ($locale === $default && $this->catalog->redirectsCanonically()) {
                $this->registerCanonicalRedirects($collection, $captured, $outer);
            }
        }

        $collection->refreshNameLookups();
        $collection->refreshActionLookups();
    }

    /**
     * Run the route definitions inside a locale group, into an empty collection.
     *
     * @return list<Route>
     */
    private function capture(RouteCollection $collection, string $prefix, Closure $routes): array
    {
        $captured = new RouteCollection;
        $this->router->setRoutes($captured);

        try {
            $this->router->group(['prefix' => $prefix, 'middleware' => [SetPathLocale::class]], $routes);
        } finally {
            $this->router->setRoutes($collection);
        }

        // RouteCollection::add() only accepts routes; Laravel 6 documents getRoutes() as a plain array.
        /** @var list<Route> $routes */
        $routes = array_values($captured->getRoutes());

        return $routes;
    }

    private function localize(Route $route, string $locale, string $base, string $outer): Route
    {
        $uri = trim($route->uri(), '/');
        $rest = $base === '' ? $uri : ($uri === $base ? '' : (str_starts_with($uri, $base.'/') ? substr($uri, strlen($base) + 1) : null));

        if ($rest === null) {
            throw new InvalidLocalizationConfigurationException(sprintf('Localized route [%s] is outside its locale prefix [%s].', $uri, $base));
        }

        $name = $route->getName();

        if ($name !== null && str_starts_with($name, self::NAME_PREFIX)) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'Route name [%s] uses the reserved "%s" prefix.', $name, self::NAME_PREFIX
            ));
        }

        $named = $name !== null && $name !== '';
        $logical = $named ? $name : 'uri.'.substr(sha1(self::methods($route).'|'.$route->getDomain().'|'.trim($outer.'/'.$rest, '/')), 0, 16);

        $route->setUri(trim($base.'/'.$this->translate($rest, $locale), '/') ?: '/');
        $route->setAction(array_merge($route->action, [
            'as' => self::nameFor($logical, $locale, $named, $this->catalog->default()),
            'localization' => ['locale' => $locale, 'name' => $logical, 'named' => $named],
        ]));

        return $route;
    }

    /**
     * Translate the static segments of a URI with the routes.php translation file of the locale.
     */
    private function translate(string $uri, string $locale): string
    {
        if ($uri === '') {
            return '';
        }

        $translations = $this->translations($locale);
        $segments = explode('/', $uri);

        foreach ($segments as $index => $segment) {
            if (! str_contains($segment, '{') && isset($translations[$segment])) {
                $segments[$index] = $translations[$segment];
            }
        }

        return implode('/', $segments);
    }

    /**
     * @return array<string, string>
     */
    private function translations(string $locale): array
    {
        if (isset($this->translations[$locale])) {
            return $this->translations[$locale];
        }

        $translations = [];

        foreach ($this->loader->load($locale, 'routes') as $segment => $translation) {
            if (! is_string($segment) || ! is_string($translation)
                || preg_match('#^[^/{}?\#%\s]+$#u', $translation) !== 1) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'Route translations for [%s] (routes.php) must map URI segments to non-empty segments without "/", "{", "}", "?", "#", "%%" or spaces; got [%s].',
                    $locale,
                    (string) $segment,
                ));
            }

            $translations[$segment] = $translation;
        }

        return $this->translations[$locale] = $translations;
    }

    /**
     * GET routes at /{default-prefix}/... that redirect to the unprefixed default URL.
     *
     * @param  list<Route>  $targets
     */
    private function registerCanonicalRedirects(RouteCollection $collection, array $targets, string $outer): void
    {
        $default = $this->catalog->default();
        $prefix = $this->catalog->prefix($default);
        $base = trim($outer.'/'.$prefix, '/');

        $redirects = $this->capture($collection, $prefix, function () use ($targets, $outer): void {
            foreach ($targets as $target) {
                if (! in_array('GET', $target->methods(), true)) {
                    continue;
                }

                $uri = trim($target->uri(), '/');
                $rest = $outer === '' ? $uri : ($uri === $outer ? '' : substr($uri, strlen($outer) + 1));
                $data = $target->action['localization'];

                $redirect = $this->router->get($rest === '' ? '/' : $rest, [
                    'uses' => CanonicalRedirectController::class,
                    'controller' => CanonicalRedirectController::class,
                    'as' => self::REDIRECT_PREFIX.(is_array($data) && is_string($data['name'] ?? null) ? $data['name'] : ''),
                    'localization' => $data,
                    'localization_redirect' => $target->getName(),
                ])->where($target->wheres);

                if ($target->getDomain() !== null) {
                    $redirect->domain($target->getDomain());
                }
            }
        });

        foreach ($redirects as $redirect) {
            $this->add($collection, $redirect->setUri(trim($redirect->uri(), '/') ?: '/'), $base);
        }
    }

    private function add(RouteCollection $collection, Route $route, ?string $redirectBase = null): void
    {
        $name = $route->getName();

        if ($name !== null && $collection->getByName($name) !== null) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'Localized route name [%s] is already registered.', $name
            ));
        }

        if (($existing = $this->conflict($collection, $route)) !== null) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'Localized route [%s %s] (%s) collides with the already registered route [%s].',
                self::methods($route),
                $route->uri(),
                $redirectBase !== null ? 'canonical redirect' : (string) $name,
                $existing->getName() ?? $existing->uri(),
            ));
        }

        $collection->add($route);
    }

    private static function methods(Route $route): string
    {
        return implode('|', array_filter($route->methods(), 'is_string'));
    }

    private function conflict(RouteCollection $collection, Route $route): ?Route
    {
        $routes = $collection->getRoutesByMethod();
        $key = $route->getDomain().$route->uri();

        foreach ($route->methods() as $method) {
            $existing = is_string($method) && is_array($routes[$method] ?? null) ? ($routes[$method][$key] ?? null) : null;

            if ($existing instanceof Route) {
                return $existing;
            }
        }

        return null;
    }
}
