<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Pharaonic\Laravel\Localization\Support\LocaleContext;
use Pharaonic\Laravel\Localization\Support\LocaleMatcher;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the locale a Route::localized() route was registered for.
 *
 * Attached by Route::localized(); the route, never the request, decides the locale.
 */
final class SetPathLocale
{
    public function __construct(private Application $app) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $locale = self::localeOf($request->route());

        if ($locale === null) {
            return $next($request);
        }

        $previous = $this->app->getLocale();
        (new LocaleContext($locale, 'path', 'path', $previous))->attachTo($request);
        $this->app->setLocale($locale);

        $response = $next($request);

        if ($response instanceof Response && ! $response->headers->has('Content-Language')) {
            $response->headers->set('Content-Language', LocaleMatcher::normalize($locale));
        }

        return $response;
    }

    public function terminate(Request $request, mixed $response): void
    {
        $context = LocaleContext::of($request);

        if ($context !== null && $context->mode() === 'path') {
            $this->app->setLocale($context->previous());
        }
    }

    /**
     * The locale a route was registered for by Route::localized(), if any.
     *
     * @internal
     */
    public static function localeOf(mixed $route): ?string
    {
        $locale = self::routeOf($route)?->getAction('localization.locale');

        return is_string($locale) ? $locale : null;
    }

    /**
     * The matched route; Request::route() is typed differently across Laravel releases.
     *
     * @internal
     */
    public static function routeOf(mixed $route): ?Route
    {
        return $route instanceof Route ? $route : null;
    }
}
