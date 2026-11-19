<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Routing;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Middleware\SetPathLocale;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Redirects GET/HEAD /{default-prefix}/... to the unprefixed default-locale URL
 * (301), keeping the raw path parameters and the raw query string.
 *
 * Only the route decides the target: cookies, session, user and headers are never read.
 *
 * @internal
 */
final class CanonicalRedirectController
{
    public function __invoke(Request $request, UrlGenerator $url): RedirectResponse
    {
        $route = SetPathLocale::routeOf($request->route());
        $target = $route?->getAction('localization_redirect');

        if ($route === null || ! is_string($target)) {
            throw new NotFoundHttpException;
        }

        $location = $url->route($target, $route->originalParameters(), true);
        $query = $request->server->get('QUERY_STRING');

        if (is_string($query) && $query !== '') {
            $location .= (str_contains($location, '?') ? '&' : '?').$query;
        }

        return new RedirectResponse($location, 301);
    }
}
