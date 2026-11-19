# Upgrading

## From 1.x to 12.0

12.0 replaces the 1.x engine. URLs are now static routes registered once per locale, and locale negotiation is a separate, explicit mode. Upgrade route by route and test with a clean browser cache.

### 1. Requirements

12.0 requires PHP 8.2, 8.3, 8.4 or 8.5, Laravel 12.0+ and `ext-intl`. It requires `illuminate/*` packages instead of `laravel/framework`.

### 2. Configuration

Delete `config/Pharaonic/localization.php` (12.0 throws an exception while it exists), then publish the new file:

```bash
php artisan vendor:publish --tag=localization-config
```

| 1.x key | 12.0 |
| --- | --- |
| `type` = `sub-directory` | Path mode, `Route::localized()` |
| `type` = `sub-domain`, `ccTLDs` | Removed (see step 6) |
| `default` | `app.locale` |
| `supported` | `supported`; it must contain `app.locale` |
| `hide-default` | `path.hide_default` (now `true` by default) |
| `force-redirect` | Removed; `path.canonical_redirect` only redirects `/{default}/...` to `/...` |
| `prefixes` (`api/*`) | Removed; nest `Route::localized()` in a `prefix()` group |
| `list` | `locales` (`name`, `native`, `direction`) overrides; names come from ext-intl |
| `ccTLDs-list` | Removed |

Before:

```php
// config/Pharaonic/localization.php
'type' => 'sub-directory',
'default' => 'en',
'hide-default' => true,
'supported' => ['ar', 'en', 'fr'],
```

After:

```php
// config/app.php: 'locale' => 'en'
// config/pharaonic/localization.php
'supported' => ['en', 'ar', 'fr'],
'path' => ['hide_default' => true, 'canonical_redirect' => true, 'prefixes' => []],
```

### 3. Web routes

`Route::localized()` keeps its name. It no longer reads the request: every locale is registered as its own route, and there is no `{locale}` parameter.

```php
use App\Http\Controllers\PageController;

Route::localized(function (): void {
    Route::get('/page', PageController::class)->name('page');
});
```

- Controllers no longer receive a `locale` parameter, and the `LocaleRemover` middleware is gone.
- Use controller actions if you run `route:cache`; closures cannot be cached.
- With `hide_default` off, `/page` no longer redirects to `/en/page` (1.x `force-redirect`); it is a 404.
- An unsupported prefix (`/de/page`) is a 404, never a redirect.

### 4. URL helpers

| 1.x | 12.0 |
| --- | --- |
| `locale('ar')->route('page', $params)` | `locale()->route('page', $params, 'ar')` |
| `locale()->route('page')` | `locale()->route('page')` (current locale) |
| `locale()->current()` (a URL) | `locale()->switchUrl(locale()->current())`; `current()` now returns the code |
| `locale()->url('page')` | Name the route and use `locale()->route()` |
| `locale()->unLocalizedRoute('x')` | `route('x')` |
| `locale()->getName()` | `locale()->name()` (old name deprecated) |
| `locale()->getNativeName()` | `locale()->nativeName()` (deprecated) |
| `locale()->getDirection()`, `isRTL()` | `direction()`, `isRTL()` |
| `locale()->isSupportedLocale('ar')` | `locale()->isSupported('ar')` (deprecated) |
| `locale()->current` / `default` / `supported` properties | `current()`, `default()`, `supported()` |

`locale('ar')` with an argument throws an `InvalidArgumentException` naming the replacement, instead of silently changing shared state.

`locale()->route('page')` only builds routes registered inside `Route::localized()`; use `route()` for others.

### 5. Signed URLs

```php
// 1.x
URL::LocalizedSignedRoute('ar', 'invite', ['invite' => 1]);

// 12.0
locale()->signedRoute('invite', ['invite' => 1], locale: 'ar');
locale()->temporarySignedRoute('invite', now()->addHour(), ['invite' => 1], locale: 'ar');
```

The URLs are signed by Laravel and verified by the `signed` middleware. The 1.x macros remain as deprecated aliases.

### 6. Sub-domains and country domains

There is no built-in domain mode. Register domain groups with Laravel and choose the locale with a custom resolver:

```php
Route::domain('{account}.example.com')->middleware('localization:tenant')->group(function (): void {
    // ...
});
```

```php
// config/pharaonic/localization.php
'resolvers' => ['tenant' => [App\Localization\HostLocaleResolver::class]],
```

### 7. APIs

1.x served JSON requests from localized routes. In 12.0, keep API URLs unprefixed and add the `localization` middleware:

```php
use App\Http\Controllers\ApiProductController;

Route::middleware('localization')->prefix('api')->group(function (): void {
    Route::get('/products', [ApiProductController::class, 'index']);
});
```

### 8. Route-by-route checklist

- [ ] Every route inside `Route::localized()` has a controller action (if routes are cached) and, ideally, a name.
- [ ] No controller expects a `$locale` route parameter.
- [ ] Every `locale($code)` call is rewritten.
- [ ] Every `locale()->current()` used as a URL is replaced.
- [ ] Links to the default locale use the unprefixed URL.
- [ ] Translated segments, if any, live in `lang/{locale}/routes.php` (or `resources/lang/{locale}/routes.php`).
- [ ] `php artisan route:clear && php artisan localization:doctor` passes.

### 9. Cached redirects

1.x `force-redirect` and `hide-default` redirects may be cached by browsers and search engines. When the URL policy changes, old cached redirects can point at URLs that no longer exist. Keep `hide_default` stable once released, check the old URLs with a clean browser, and add explicit redirects in your application for any URL you retire.
