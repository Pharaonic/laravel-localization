<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Resolvers\ResolverPipeline;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Pharaonic\Laravel\Localization\Support\LocaleContext;
use Pharaonic\Laravel\Localization\Support\LocaleMatcher;
use Symfony\Component\HttpFoundation\Response;

/**
 * `localization:{profile}`: resolves the locale through a resolver profile
 * (`api` by default) and keeps the URL unchanged. Never redirects.
 *
 * On a Route::localized() route the path locale wins and nothing is resolved.
 */
final class SetNegotiatedLocale
{
    private const PERSONAL_RESOLVERS = ['user', 'session', 'cookie'];

    public function __construct(
        private Application $app,
        private LocaleCatalog $catalog,
        private ResolverPipeline $pipeline,
    ) {}

    public function handle(Request $request, Closure $next, string $profile = 'api'): mixed
    {
        if (SetPathLocale::localeOf($request->route()) !== null) {
            return $next($request);
        }

        $result = $this->pipeline->resolve($request, $profile);
        $locale = $result['locale'] ?? $this->catalog->default();

        (new LocaleContext($locale, $result['source'] ?? 'default', 'negotiated', $this->app->getLocale(), $result['consulted']))
            ->attachTo($request);
        $this->app->setLocale($locale);

        $response = $next($request);

        if ($response instanceof Response) {
            $this->prepare($response, $locale, $result['consulted']);
        }

        return $response;
    }

    public function terminate(Request $request, mixed $response): void
    {
        $context = LocaleContext::of($request);

        if ($context !== null && $context->mode() === 'negotiated') {
            $this->app->setLocale($context->previous());
        }
    }

    /**
     * @param  list<string>  $consulted
     */
    private function prepare(Response $response, string $locale, array $consulted): void
    {
        if (! $response->headers->has('Content-Language')) {
            $response->headers->set('Content-Language', LocaleMatcher::normalize($locale));
        }

        if (in_array('header', $consulted, true)) {
            $header = $this->catalog->headerName();
            $vary = array_values(array_filter($response->getVary(), 'is_string'));
            $lower = array_map('strtolower', $vary);

            if (! in_array(strtolower($header), $lower, true) && ! in_array('*', $lower, true)) {
                $response->setVary(implode(', ', [...$vary, $header]));
            }
        }

        // A response chosen from the user, session or cookie must not be stored by
        // shared caches. Only an explicitly shared response is changed: Symfony's
        // default is already "no-cache, private", and no-store is left alone.
        $headers = $response->headers;

        if (array_intersect(self::PERSONAL_RESOLVERS, $consulted) !== []
            && ! $headers->hasCacheControlDirective('no-store')
            && ($headers->hasCacheControlDirective('public') || $headers->hasCacheControlDirective('s-maxage'))) {
            $response->setPrivate();
            $headers->removeCacheControlDirective('s-maxage');
        }
    }
}
