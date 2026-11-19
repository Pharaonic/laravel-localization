## API Reference

### `locale()` methods

`locale()` returns the `Pharaonic\Laravel\Localization\LocalizationManager` singleton. It keeps no request state, so it is safe in long-running workers.

| Method | Description | Returns |
| --- | --- | --- |
| `current()` | The active application locale (`App::getLocale()`) | `string` |
| `default()` | Laravel's `app.locale` as configured | `string` |
| `source()` | What decided the request locale: `path`, a resolver ID or class, `default`; `null` outside a localized request | `?string` |
| `mode()` | `path` or `negotiated`; `null` outside a localized request | `?string` |
| `supported()` | The supported locale codes | `array` |
| `isSupported(string $locale)` | Whether the code is supported (no fallback) | `bool` |
| `normalize(string $locale)` | BCP 47 form; throws `InvalidArgumentException` when malformed | `string` |
| `match(string $locale)` | Best supported match | `?string` |
| `name(?string $locale = null)` | English display name | `string` |
| `nativeName(?string $locale = null)` | Display name in the locale itself | `string` |
| `direction(?string $locale = null)` | `ltr` or `rtl` | `string` |
| `isRTL(?string $locale = null)` | Whether the direction is `rtl` | `bool` |
| `meta(string $key, mixed $default = null, ?string $locale = null)` | A metadata value (`name`, `native`, `direction` or a custom key), dot notation | `mixed` |
| `metadata(?string $locale = null)` | Resolved `name`, `native`, `direction` and every custom key | `array<string, mixed>` |
| `route(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true)` | URL of a localized route | `string` |
| `signedRoute(string $name, mixed $parameters = [], $expiration = null, bool $absolute = true, ?string $locale = null)` | Signed URL | `string` |
| `temporarySignedRoute(string $name, $expiration, mixed $parameters = [], bool $absolute = true, ?string $locale = null)` | Expiring signed URL | `string` |
| `switchUrl(string $locale)` | Current page in a locale, with its query string | `?string` |
| `alternates()` | Current page in every available locale | `array<string, string>` |
| `hreflangs()` | `alternates()` keyed by BCP 47 tag, plus `x-default` | `array<string, string>` |
| `preferred(string $profile = 'web')` | A profile's preferred locale, without applying it | `?string` |
| `remember(string $locale, string $store)` | Store a preference in `session` or `cookie` | `void` |
| `forget(string $store)` | Remove a stored preference | `void` |

Deprecated 1.x aliases: `getName()`, `getNativeName()`, `getDirection()`, `isSupportedLocale()`, `getLanguage()`, `getRegion()`.

### Routing and middleware

| Name | Description |
| --- | --- |
| `Route::localized(Closure $routes)` | Registers the routes once per supported locale |
| `localization` / `localization:{profile}` | Negotiated-mode middleware alias (`SetNegotiatedLocale`), profile `api` by default |
| `SetPathLocale` | Path-mode middleware, attached by `Route::localized()` |
| `localized.{locale}.{name}` | Reserved route names of non-default variants |
| `localized.redirect.{name}` | Reserved route names of canonical redirects |
| `localization:doctor` | Diagnostics command |

### Contracts

| Interface | Method |
| --- | --- |
| `Contracts\LocaleResolver` | `resolve(Request $request): ?string` |
| `Contracts\HasLocalizedRouteKey` | `getLocalizedRouteKey(string $locale): ?string` |

### Exceptions

| Class | Extends | Thrown when |
| --- | --- | --- |
| `InvalidLocalizationConfigurationException` | `LogicException` | Invalid configuration, resolver, translation file, or route collision |
| `UnsupportedLocaleException` | `InvalidArgumentException` | An explicit locale is not supported |
| `LocalizedRouteNotFoundException` | Symfony `RouteNotFoundException` | The route is missing or not localized |
| `MissingLocalizedRouteKeyException` | `RuntimeException` | A model has no route key in the locale |

### Publish tags

`pharaonic`, `laravel-localization`, `pharaonic-config`, `localization-config`.
