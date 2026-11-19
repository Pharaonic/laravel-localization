## Path Routing

Wrap the routes that should exist in every language in `Route::localized()`. Inside it, you write ordinary Laravel routes.

```php title="routes/web.php"
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::localized(function (): void {
    Route::get('/', [ProductController::class, 'home'])->name('home');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});
```

With `supported` set to `['en', 'ar', 'fr']` and `app.locale` set to `en`, this registers:

| URI | Name | Locale |
| --- | --- | --- |
| `/products` | `products.index` | en |
| `/ar/products` | `localized.ar.products.index` | ar |
| `/fr/products` | `localized.fr.products.index` | fr |
| `/en/products` (GET, HEAD) | `localized.redirect.products.index` | 301 to `/products` |

Each route runs the `SetPathLocale` middleware, which applies its locale, sets `Content-Language`, and restores the previous locale when the request ends.

### Route names

- The default-locale route keeps your name, so `route('products.index')` still works and returns the default-locale URL.
- Other locales use the reserved `localized.{locale}.{name}` namespace. Use `locale()->route()` rather than these names.
- An unnamed route gets a deterministic name, `localized.{locale}.uri.{hash}`, so the language switcher still works for it.
- A route name starting with `localized.` inside `Route::localized()` is rejected.

### Groups, resources and constraints

Everything Laravel groups provide keeps working: prefixes, name prefixes, middleware, domains, `where` constraints, optional parameters, resources and controller groups. Outer prefixes come before the locale prefix.

```php title="routes/web.php"
use App\Http\Controllers\ProductController;

Route::middleware('web')->prefix('shop')->name('shop.')->group(function (): void {
    Route::localized(function (): void {
        Route::resource('products', ProductController::class)->only(['index', 'show']);
    });
});

// /shop/products          shop.products.index
// /shop/ar/products       localized.ar.shop.products.index
```

### The default locale

With `path.hide_default` set to `true` (the default), the default locale lives at unprefixed URLs. `/products` is always served in the default locale, even when the user, session, cookie or browser prefers another language. With `false`, every locale has a prefix (`/en/products`), and `/products` doesn't exist.

### Canonical redirects

With `hide_default` and `path.canonical_redirect` (both on by default), `GET` and `HEAD` requests to `/en/...` redirect with `301` to the unprefixed URL, keeping the path parameters and the query string as they were sent. Other methods get Laravel's normal `404` or `405`, never a redirect. With `canonical_redirect` set to `false`, `/en/...` is a `404`.

:::warning Permanent redirects are cached by browsers
Browsers and search engines keep a `301`. If you later turn `hide_default` off, `/en/...` becomes the real URL while some clients still redirect it to `/...`, which then no longer exists. Plan URL policy changes, and test them with a cleared browser cache.
:::

### Unsupported prefixes

There is no catch-all. `/de/products` is simply not a registered route, so it is a `404` (or whatever route your application registers at that URI), and it is never redirected or guessed. Collisions are detected when routes are registered: a localized URI that is already taken, two locales translating to the same URI, or a name already in use throw an `InvalidLocalizationConfigurationException`.

### Route caching

The routes are static, so `php artisan route:cache` works, with the locale stored in each route's action. Use controller or invokable actions: Laravel cannot cache closure routes. After changing `supported` or `prefixes`, run `route:cache` again; `localization:doctor` reports a stale cache.

```bash title="Terminal" no-line-numbers
php artisan route:cache
php artisan route:list --name=localized.ar
```

:::info Registration order
Prefixed locales are registered before the default one, so an unprefixed `/{page}` route can never capture `/ar`.
:::
