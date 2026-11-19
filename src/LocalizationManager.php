<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization;

use DateInterval;
use DateTimeInterface;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cookie\QueueingFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Pharaonic\Laravel\Localization\Contracts\HasLocalizedRouteKey;
use Pharaonic\Laravel\Localization\Exceptions\LocalizedRouteNotFoundException;
use Pharaonic\Laravel\Localization\Exceptions\MissingLocalizedRouteKeyException;
use Pharaonic\Laravel\Localization\Exceptions\UnsupportedLocaleException;
use Pharaonic\Laravel\Localization\Middleware\SetPathLocale;
use Pharaonic\Laravel\Localization\Resolvers\ResolverPipeline;
use Pharaonic\Laravel\Localization\Routing\LocalizedRouteRegistrar;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Pharaonic\Laravel\Localization\Support\LocaleContext;
use Pharaonic\Laravel\Localization\Support\LocaleMatcher;
use RuntimeException;

/**
 * The locale() API. It keeps no request state: every call reads the current
 * request and application locale, so one instance is safe across requests.
 */
final class LocalizationManager
{
    public function __construct(private LocaleCatalog $catalog)
    {
    }

    /**
     * The active application locale (Laravel's App::getLocale()).
     */
    public function current(): string
    {
        return $this->app()->getLocale();
    }

    /**
     * Laravel's default locale (`app.locale` as configured).
     */
    public function default(): string
    {
        return $this->catalog->default();
    }

    /**
     * What decided the request locale: `path`, `user`, `session`, `cookie`,
     * `header`, a custom resolver's class name, or `default`; null outside a
     * localized request.
     */
    public function source(): ?string
    {
        return $this->context()?->source();
    }

    /**
     * `path` or `negotiated`; null outside a localized request.
     */
    public function mode(): ?string
    {
        return $this->context()?->mode();
    }

    /**
     * @return list<string>
     */
    public function supported(): array
    {
        return $this->catalog->supported();
    }

    /**
     * Whether the code is a supported locale (case and `-`/`_` insensitive, no fallback).
     */
    public function isSupported(string $locale): bool
    {
        return $this->catalog->matcher()->exact($locale) !== null;
    }

    /**
     * The BCP 47 form of a locale code: `pt_br` → `pt-BR`.
     *
     * @throws InvalidArgumentException when the code is malformed
     */
    public function normalize(string $locale): string
    {
        return LocaleMatcher::normalize($locale);
    }

    /**
     * The supported locale that best matches the code (`ar-EG` → `ar`), or null.
     */
    public function match(string $locale): ?string
    {
        return $this->catalog->matcher()->match($locale);
    }

    public function name(?string $locale = null): string
    {
        return $this->catalog->name($locale ?? $this->current());
    }

    public function nativeName(?string $locale = null): string
    {
        return $this->catalog->nativeName($locale ?? $this->current());
    }

    /**
     * `rtl` or `ltr`.
     */
    public function direction(?string $locale = null): string
    {
        return $this->catalog->direction($locale ?? $this->current());
    }

    public function isRTL(?string $locale = null): bool
    {
        return $this->direction($locale) === 'rtl';
    }

    /**
     * A metadata value of a locale (the current one by default): `name`, `native`,
     * `direction`, or a custom key from `localization.locales`, in dot notation.
     */
    public function meta(string $key, mixed $default = null, ?string $locale = null): mixed
    {
        return Arr::get($this->metadata($locale), $key, $default);
    }

    /**
     * The resolved `name`, `native` and `direction` of a locale (the current one
     * by default), with its custom metadata from `localization.locales`.
     *
     * @return array<string, mixed>
     */
    public function metadata(?string $locale = null): array
    {
        return $this->catalog->all($locale ?? $this->current());
    }

    /**
     * The URL of a Route::localized() route in a locale (the current one by default).
     *
     * @param  mixed  $parameters  as for route(); HasLocalizedRouteKey values use their key for the locale
     *
     * @throws LocalizedRouteNotFoundException
     * @throws UnsupportedLocaleException
     * @throws MissingLocalizedRouteKeyException
     */
    public function route(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        $locale = $this->target($locale);

        return $this->url()->route($this->concreteName($name, $locale), $this->parameters($parameters, $locale), $absolute);
    }

    /**
     * A signed URL (Laravel's URL::signedRoute()) for a localized route.
     *
     * @throws LocalizedRouteNotFoundException
     * @throws UnsupportedLocaleException
     * @throws MissingLocalizedRouteKeyException
     */
    public function signedRoute(
        string $name,
        mixed $parameters = [],
        DateTimeInterface|DateInterval|int|null $expiration = null,
        bool $absolute = true,
        ?string $locale = null,
    ): string {
        $locale = $this->target($locale);

        return $this->url()->signedRoute($this->concreteName($name, $locale), $this->parameters($parameters, $locale), $expiration, $absolute);
    }

    /**
     * A temporary signed URL (Laravel's URL::temporarySignedRoute()) for a localized route.
     *
     * @throws LocalizedRouteNotFoundException
     * @throws UnsupportedLocaleException
     * @throws MissingLocalizedRouteKeyException
     */
    public function temporarySignedRoute(
        string $name,
        DateTimeInterface|DateInterval|int $expiration,
        mixed $parameters = [],
        bool $absolute = true,
        ?string $locale = null,
    ): string {
        $locale = $this->target($locale);

        return $this->url()->temporarySignedRoute($this->concreteName($name, $locale), $expiration, $this->parameters($parameters, $locale), $absolute);
    }

    /**
     * The current page in another locale, keeping its route parameters and query string.
     *
     * Null when it cannot be built: the current route is not localized, a
     * parameter has no localized route key, or the URL is signed (a signature
     * only covers the URL it was made for).
     *
     * @throws UnsupportedLocaleException
     */
    public function switchUrl(string $locale): ?string
    {
        $locale = $this->target($locale);
        $request = $this->request();
        $url = $this->variant($request, $locale);

        if ($url === null) {
            return null;
        }

        $query = $request->server->get('QUERY_STRING');

        return is_string($query) && $query !== '' ? $url.(str_contains($url, '?') ? '&' : '?').$query : $url;
    }

    /**
     * The current page in every supported locale it exists in, without the
     * query string: locale => absolute URL. Empty outside a path-localized
     * route and on signed URLs.
     *
     * @return array<string, string>
     */
    public function alternates(): array
    {
        $request = $this->request();
        $alternates = [];

        foreach ($this->catalog->supported() as $locale) {
            $url = $this->variant($request, $locale);

            if ($url !== null) {
                $alternates[$locale] = $url;
            }
        }

        return $alternates;
    }

    /**
     * alternates() keyed by BCP 47 tag for hreflang, plus `x-default` (the
     * default-locale URL) when that variant exists.
     *
     * @return array<string, string>
     */
    public function hreflangs(): array
    {
        $links = [];
        $alternates = $this->alternates();

        foreach ($alternates as $locale => $url) {
            $links[LocaleMatcher::normalize($locale)] = $url;
        }

        if (isset($alternates[$this->catalog->default()])) {
            $links['x-default'] = $alternates[$this->catalog->default()];
        }

        return $links;
    }

    /**
     * The locale a resolver profile prefers for the current request, without
     * applying it (e.g. to suggest a language on a path-localized page).
     */
    public function preferred(string $profile = 'web'): ?string
    {
        return $this->pipeline()->resolve($this->request(), $profile)['locale'];
    }

    /**
     * Store a locale preference for later requests. The current request's
     * locale does not change; redirect to a localized URL to switch now.
     *
     * @param  string  $store  `session` or `cookie`
     *
     * @throws UnsupportedLocaleException
     * @throws RuntimeException when the session is not started
     */
    public function remember(string $locale, string $store): void
    {
        $locale = $this->target($locale);

        match ($store) {
            'session' => $this->session()->put($this->catalog->sessionKey(), $locale),
            'cookie' => $this->queueCookie($locale),
            default => throw new InvalidArgumentException(sprintf('Unknown preference store [%s]; use session or cookie.', $store)),
        };
    }

    /**
     * Remove a stored locale preference.
     *
     * @param  string  $store  `session` or `cookie`
     *
     * @throws RuntimeException when the session is not started
     */
    public function forget(string $store): void
    {
        $cookie = $this->catalog->cookie();

        match ($store) {
            'session' => $this->session()->forget($this->catalog->sessionKey()),
            'cookie' => $this->cookies()->queue($this->cookies()->forget($cookie['key'])),
            default => throw new InvalidArgumentException(sprintf('Unknown preference store [%s]; use session or cookie.', $store)),
        };
    }

    /**
     * @deprecated Use name().
     */
    public function getName(?string $locale = null): string
    {
        return $this->name($locale);
    }

    /**
     * @deprecated Use nativeName().
     */
    public function getNativeName(?string $locale = null): string
    {
        return $this->nativeName($locale);
    }

    /**
     * @deprecated Use direction().
     */
    public function getDirection(?string $locale = null): string
    {
        return $this->direction($locale);
    }

    /**
     * @deprecated Use isSupported().
     */
    public function isSupportedLocale(string $locale): bool
    {
        return $this->isSupported($locale);
    }

    /**
     * @deprecated Use normalize() and read its first subtag.
     */
    public function getLanguage(?string $locale = null): string
    {
        return explode('-', $this->normalize($locale ?? $this->current()))[0];
    }

    /**
     * @deprecated Use normalize() and read its region subtag.
     */
    public function getRegion(?string $locale = null): ?string
    {
        foreach (array_slice(explode('-', $this->normalize($locale ?? $this->current())), 1) as $subtag) {
            if (strlen($subtag) === 2 || ctype_digit($subtag)) {
                return $subtag;
            }
        }

        return null;
    }

    /**
     * The current route in a locale, or null when it cannot be built.
     */
    private function variant(Request $request, string $locale): ?string
    {
        $route = SetPathLocale::routeOf($request->route());
        $data = $route?->getAction('localization');

        if ($route === null || ! is_array($data) || ! is_string($data['name'] ?? null) || $request->query->has('signature')) {
            return null;
        }

        $name = LocalizedRouteRegistrar::nameFor($data['name'], $locale, (bool) ($data['named'] ?? false), $this->catalog->default());

        if ($this->namedRoute($name) === null) {
            return null;
        }

        try {
            return $this->url()->route($name, $this->parameters($route->parameters(), $locale), true);
        } catch (MissingLocalizedRouteKeyException) {
            return null;
        }
    }

    /**
     * @throws LocalizedRouteNotFoundException
     */
    private function concreteName(string $name, string $locale): string
    {
        $route = $this->namedRoute($name);

        if (! $route instanceof Route) {
            throw new LocalizedRouteNotFoundException(sprintf('Route [%s] not defined.', $name));
        }

        $data = $route->getAction('localization');

        if (! is_array($data) || ! is_string($data['name'] ?? null)) {
            throw new LocalizedRouteNotFoundException(sprintf('Route [%s] is not registered inside Route::localized().', $name));
        }

        $concrete = LocalizedRouteRegistrar::nameFor($data['name'], $locale, (bool) ($data['named'] ?? false), $this->catalog->default());

        if ($this->namedRoute($concrete) === null) {
            throw new LocalizedRouteNotFoundException(sprintf(
                'Route [%s] has no [%s] variant; clear the route cache after changing the supported locales.', $name, $locale
            ));
        }

        return $concrete;
    }

    /**
     * @return array<array-key, mixed>
     *
     * @throws MissingLocalizedRouteKeyException
     */
    private function parameters(mixed $parameters, string $locale): array
    {
        $parameters = is_array($parameters) ? $parameters : [$parameters];

        foreach ($parameters as $key => $value) {
            if ($value instanceof HasLocalizedRouteKey) {
                $routeKey = $value->getLocalizedRouteKey($locale);

                if ($routeKey === null || $routeKey === '') {
                    throw new MissingLocalizedRouteKeyException(sprintf(
                        'Route parameter [%s] (%s) has no route key in [%s].', $key, $value::class, $locale
                    ));
                }

                $parameters[$key] = $routeKey;
            }
        }

        return $parameters;
    }

    /**
     * A supported locale given explicitly, or the current one.
     *
     * @throws UnsupportedLocaleException
     */
    private function target(?string $locale): string
    {
        $locale ??= $this->current();
        $code = $this->catalog->matcher()->exact($locale);

        if ($code === null) {
            throw UnsupportedLocaleException::make($locale, $this->catalog->supported());
        }

        return $code;
    }

    private function queueCookie(string $locale): void
    {
        $cookie = $this->catalog->cookie();
        $cookies = $this->cookies();

        $cookies->queue($cookies->make(
            $cookie['key'], $locale, $cookie['minutes'], null, null, $cookie['secure'], true, false, $cookie['same_site']
        ));
    }

    private function session(): \Illuminate\Contracts\Session\Session
    {
        $request = $this->request();

        if (! $request->hasSession() || ! $request->session()->isStarted()) {
            throw new RuntimeException('Remembering a locale in the session needs a started session; add the StartSession middleware (the web group has it) to this route.');
        }

        return $request->session();
    }

    private function context(): ?LocaleContext
    {
        return LocaleContext::of($this->request());
    }

    private function app(): Application
    {
        /** @var Application */
        return Container::getInstance()->make(Application::class);
    }

    private function request(): Request
    {
        /** @var Request */
        return Container::getInstance()->make('request');
    }

    private function url(): UrlGenerator
    {
        /** @var UrlGenerator */
        return Container::getInstance()->make('url');
    }

    private function namedRoute(string $name): ?Route
    {
        /** @var Router $router */
        $router = Container::getInstance()->make(Router::class);

        return $router->getRoutes()->getByName($name);
    }

    private function cookies(): QueueingFactory
    {
        /** @var QueueingFactory */
        return Container::getInstance()->make(QueueingFactory::class);
    }

    private function pipeline(): ResolverPipeline
    {
        /** @var ResolverPipeline */
        return Container::getInstance()->make(ResolverPipeline::class);
    }
}
