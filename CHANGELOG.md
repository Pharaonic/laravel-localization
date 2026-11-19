# Changelog

All notable changes to this project are documented here.

## 12.0.0 - Unreleased

### Compatibility

- Laravel 12.0+ on PHP 8.2, 8.3, 8.4 or 8.5, with `ext-intl`.
- The same API and behavior as 11.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.

## 11.0.0 - Unreleased

### Compatibility

- Laravel 11.0+ on PHP 8.2, 8.3 or 8.4, with `ext-intl`. PHP 8.0 and 8.1 are not supported on this line.
- The same API and behavior as 10.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.

## 10.0.0 - Unreleased

### Compatibility

- Laravel 10.0+ on PHP 8.1, 8.2 or 8.3, with `ext-intl`. PHP 8.0 is no longer supported on this line.
- The same API and behavior as 9.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.

## 9.0.0 - Unreleased

### Compatibility

- Laravel 9.0+ on PHP 8.0, 8.1 or 8.2, with `ext-intl`.
- The same API and behavior as 8.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.
- Route translations are read from Laravel 9's `lang/` directory, or `resources/lang/` in upgraded applications, through the translator.

## 8.0.0 - Unreleased

### Compatibility

- Laravel 8.75+ on PHP 8.0 or 8.1, with `ext-intl`.
- The same API and behavior as 7.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.
- Relative signed URLs work with Laravel's `signed:relative` middleware.

## 7.0.0 - Unreleased

### Compatibility

- Laravel 7.30+ on PHP 8.0, with `ext-intl`.
- The same API and behavior as 6.0.0, including the deprecated 1.x aliases. 1.x applications follow `UPGRADING.md`.
- Localized routes work with Laravel 7's compiled route cache.

## 6.0.0 - Unreleased

A redesign of the package as a localization engine with two modes. 1.x applications need the steps in `UPGRADING.md`.

### Added

- Path mode: `Route::localized()` registers one static route per supported locale when routes load, independently of the request, so `route:cache`, `route:list` and `config:cache` work. The default locale stays unprefixed (`path.hide_default`), other routes are named `localized.{locale}.{name}`, and `GET`/`HEAD` `/{default}/...` redirects with `301` to the unprefixed URL (`path.canonical_redirect`).
- Translated URI segments from `resources/lang/{locale}/routes.php`, with Unicode support and collision detection at registration.
- Negotiated mode: the `localization:{profile}` middleware runs an ordered resolver profile (`user`, `session`, `cookie`, `header` or a `LocaleResolver` class), never redirects, sets `Content-Language`, extends `Vary` when the header was read, and keeps personalized responses out of shared caches.
- `Accept-Language` negotiation with quality values, `q=0`, wildcards, language-range matching and bounded parsing.
- Custom per-locale metadata: any extra key in `locales` (a flag, a currency, nested data), read with `locale()->meta()` and `locale()->metadata()`.
- `locale()` API: `current()`, `default()`, `source()`, `mode()`, `supported()`, `isSupported()`, `normalize()`, `match()`, `name()`, `nativeName()`, `direction()`, `isRTL()`, `route()`, `signedRoute()`, `temporarySignedRoute()`, `switchUrl()`, `alternates()`, `hreflangs()`, `preferred()`, `remember()`, `forget()`.
- `HasLocalizedRouteKey` contract for per-locale route keys such as translated slugs; the locale is applied before route model binding.
- Explicit preference persistence in the session or an encrypted cookie with `remember()` and `forget()`.
- `localization:doctor` command for configuration, resolvers, middleware and route-cache checks.
- Typed exceptions: `InvalidLocalizationConfigurationException`, `UnsupportedLocaleException`, `LocalizedRouteNotFoundException`, `MissingLocalizedRouteKeyException`.
- Pest, Pint and PHPStan (max) quality gates, CI, and the Pharaonic repository baseline.

### Changed

- The default locale is Laravel's `app.locale` and the translation fallback stays Laravel's `app.fallback_locale`; the package has no `default` key.
- The configuration is `config/pharaonic/localization.php` (`pharaonic.localization`), published with the `pharaonic`, `laravel-localization`, `pharaonic-config` and `localization-config` tags. A 1.x `config/Pharaonic/localization.php` throws an exception with migration steps.
- `locale()->current()` returns the locale code instead of the current URL; use `switchUrl()` for URLs.
- `locale()->route()` takes the locale as an argument: `route($name, $parameters, $locale, $absolute)`.
- Signed localized URLs use Laravel's native signer and verify with the `signed` middleware.
- The locale is kept on the request, and the previous application locale is restored when the request ends.

### Deprecated

- `getName()`, `getNativeName()`, `getDirection()`, `isSupportedLocale()`, `getLanguage()` and `getRegion()`: use `name()`, `nativeName()`, `direction()`, `isSupported()` and `normalize()`.
- `URL::LocalizedSignedRoute()` and `URL::temporaryLocalizedSignedRoute()`: use `locale()->signedRoute()` and `locale()->temporarySignedRoute()`.

### Removed

- The `sub-domain` and `ccTLDs` types; use Laravel's `Route::domain()` with a custom resolver.
- The `type`, `default`, `hide-default`, `force-redirect`, `prefixes` (`api/*`), `list` and `ccTLDs-list` options, and the bundled locale list.
- The `{locale?}` route parameter and the `LocaleRemover` middleware.
- `locale($code)`, which changed shared state; it now throws with the replacement.
- `locale()->url()`, `locale()->unLocalizedRoute()`, `setLocale()`, `setTempLocale()`, `detect()` and the public `current`, `default`, `supported` and `list` properties.

### Compatibility

- PHP 8.0.
- Laravel 6 (`illuminate/*` ^6.20), replacing the `laravel/framework >=6.0` requirement.
- `ext-intl`, which provides locale display names instead of the 1.x `list`.
