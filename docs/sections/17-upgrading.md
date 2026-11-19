## Upgrading from 1.x

9.0 is a redesign. Plan the upgrade route by route; `UPGRADING.md` in the repository has the full checklist.

### What changed

| 1.x | 9.x |
| --- | --- |
| `type` = `sub-directory` | Path mode with `Route::localized()` |
| `type` = `sub-domain` or `ccTLDs` | Removed: use Laravel's `Route::domain()` and set the locale in your own middleware or a custom resolver |
| `default` | Laravel's `app.locale` |
| implicit fallback | Laravel's `app.fallback_locale`, for translation strings only |
| `hide-default` | `path.hide_default`, `true` by default |
| `force-redirect` | Removed; `/en/...` → `/...` canonical redirect with `path.canonical_redirect` |
| `prefixes` (`api/*`) | Removed; put `Route::localized()` inside your own `prefix()` group |
| `list`, `ccTLDs-list` | `locales` metadata overrides, ext-intl names |
| `config/Pharaonic/localization.php` | `config/pharaonic/localization.php`; the old file throws with a migration message |
| `{locale?}` route parameter and `LocaleRemover` | Removed: each locale has its own static route |
| `locale('ar')->route($name)` | `locale()->route($name, [], 'ar')`; `locale('ar')` now throws |
| `locale()->current()` returned a URL | Returns the locale code; use `locale()->switchUrl(locale()->current())` |
| `locale()->url($uri)`, `unLocalizedRoute()` | Use named routes with `locale()->route()`, and `route()` |
| `URL::LocalizedSignedRoute()` | `locale()->signedRoute()` (the macros remain, deprecated) |
| `getName()`, `getNativeName()`, `getDirection()`, `isSupportedLocale()` | `name()`, `nativeName()`, `direction()`, `isSupported()` (old names deprecated) |

### Steps

1. Delete `config/Pharaonic/localization.php`, publish the new file, and copy your `supported` list. Make sure `app.locale` is in it.
2. Keep your `Route::localized()` groups. Replace closure actions with controllers if you cache routes.
3. Replace `locale($code)` calls and every `locale()->current()` used as a URL.
4. Move sub-domain or ccTLD setups to `Route::domain()` groups.
5. Run `php artisan route:clear`, then `php artisan localization:doctor`.

:::warning Cached redirects
1.x redirected with `force-redirect`. Browsers may have cached those redirects; test the new URLs in a clean browser, and avoid changing `hide_default` again after release.
:::
