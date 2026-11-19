# Maintaining This Package

This file records package-specific maintainer facts that are safe to keep in the public repository.

## Purpose

Localized routing for Laravel: `Route::localized()` registers static, cacheable routes per supported locale (path mode), and the `localization:{profile}` middleware resolves a locale from an ordered resolver profile without changing the URL (negotiated mode).

## Supported Laravel lines

One branch per Laravel major, from `6.x` to the newest major, all carrying the redesign (first released as `N.0.0` on each line). Each line's PHP range, `illuminate/*` constraint, and tools follow the Pharaonic Laravel line table. `6.x` runs PHPUnit, because Pest cannot install on Laravel 6; later lines run Pest. `develop` follows the newest line. 1.x (`main`, tags `1.0.0`–`1.3.7`) is the legacy engine and is not developed further.

Per-line differences beyond constraints: Laravel 6 and 7 have no `signed:relative` middleware (docs show `hasValidSignature(false)`), and their tests reproduce `route:cache` in-process because Testbench 4 and 5 have no remote process. From 8.x the docs use `signed:relative`, `[Controller::class, 'method']` routes and `App\Models`, and the tests run a real `route:cache` through Testbench's remote process.

## Core library

None. Locale matching and `Accept-Language` parsing are package-local (`Support\LocaleMatcher`); they are small and tied to the configuration.

## Public API invariants

Configuration keys (`pharaonic.localization.*`), publish tags, and registered Blade, Artisan, macro, and validation names are public API.

- `Route::localized()`, the `localization` middleware alias, the `localization:doctor` command and the `locale()` helper.
- Concrete route names: the default locale keeps the application's name; other locales use `localized.{locale}.{name}`, unnamed routes `localized.{locale}.uri.{hash}`, canonical redirects `localized.redirect.{name}`. The `localization` route-action key holds `locale`, `name` and `named` and must survive `route:cache`.
- The `LocalizationManager` methods, the `LocaleResolver` and `HasLocalizedRouteKey` contracts, the four exceptions, and the built-in resolver IDs `user`, `session`, `cookie`, `header`.
- `locale()->source()` values: `path`, a resolver ID, a resolver class name, `default`.
- Deprecated aliases (`getName()`, `getNativeName()`, `getDirection()`, `isSupportedLocale()`, `getLanguage()`, `getRegion()`, `URL::LocalizedSignedRoute()`, `URL::temporaryLocalizedSignedRoute()`) stay until a line explicitly removes them.

## Package-specific constraints

- The default locale is Laravel's `app.locale`, read once in `register()`. Laravel's `App::setLocale()` writes `app.locale`, so reading it later could capture a request locale. Never add a `default` or `fallback` key.
- No request-dependent work at boot or route registration: routes are identical in HTTP, the console and `route:cache`, and registered routes are never mutated afterwards.
- Request state lives only on the request (`LocaleContext`, attribute `pharaonic.localization`); the manager and catalog are stateless singletons. Middleware restores the previous application locale in `terminate()`.
- The locale middleware is inserted before `SubstituteBindings` in the router's middleware priority, at boot and again when the HTTP kernel is resolved (the kernel overwrites the router's list).
- Nothing redirects except the explicit canonical `/{default}/...` → `/...` (GET/HEAD, 301), and nothing writes preferences except `remember()`/`forget()`.
- From 8.x the route-cache test runs a real `route:cache` through Testbench's remote process; `testbench.yaml` registers the package provider and the fixture `RouteCacheServiceProvider` for it. It needs a Testbench release whose testbench-core provides `remote()`, above the line table's floor: ^6.42 on 8.x (core 6.46) and ^7.35 on 9.x (core 7.35). From Testbench 7 the CLI also caches its own `_workbench` routes, which the test ignores.

## Branch notes

Fixes land on the oldest affected line and are ported to every later line and `develop`.

## Documentation

https://pharaonic.dev/packages/laravel/localization

Keep this file factual and package-specific. General Pharaonic engineering policy belongs in the private engineering repository, not here.
