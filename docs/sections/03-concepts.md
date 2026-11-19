## Concepts

The package keeps a few ideas apart that are easy to mix up.

| Concept | Meaning | Where it comes from |
| --- | --- | --- |
| Default locale | The locale of unprefixed URLs, and of negotiation when no source matches | Laravel's `app.locale` |
| Translation fallback | The locale Laravel's translator uses for a missing string | Laravel's `app.fallback_locale` |
| Supported locales | The locales routes are registered for and negotiation may pick | `localization.supported` |
| Mode | `path` (the URL carries the locale) or `negotiated` (it doesn't) | `Route::localized()` or the `localization` middleware |
| Resolver | One source of a locale preference: `user`, `session`, `cookie`, `header` or a class | `localization.resolvers` |
| Source | Which resolver decided the current request, or `path` / `default` | `locale()->source()` |

### Path mode

`Route::localized()` registers each route once per supported locale when your routes file loads. The route itself carries its locale, so the request never decides which routes exist and nothing is redirected based on preferences.

```text
/products         → en (default, hidden prefix)
/ar/المنتجات      → ar
/fr/produits      → fr
/en/products      → 301 to /products
/de/products      → 404 (de is not supported)
```

### Negotiated mode

The `localization` middleware runs a resolver profile in order. The first supported result wins; otherwise the default locale is used. The URL never changes and nothing is redirected.

```text
user preferredLocale() → null, try next
Accept-Language: ar-EG,ar;q=0.9 → ar, stop
```

### What is never done automatically

- No redirect based on a cookie, session, user or header.
- No write to the session, a cookie or the user when a locale is resolved.
- No change to `config('app.fallback_locale')`, and no fallback locale used as a negotiation source.
- No override of `route()`, `url()` or `URL::defaults()`.

:::info App::setLocale() and app.locale
Laravel's `App::setLocale()` writes `config('app.locale')`. The package applies locales with `App::setLocale()` so `app()->getLocale()`, validation messages and Carbon follow the request, reads the default locale once when the application boots, and restores the previous locale when the request ends.
:::
