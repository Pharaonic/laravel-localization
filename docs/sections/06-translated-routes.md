## Translated Routes

Translate the static segments of localized URIs with a `routes.php` lang file per locale, in `lang/` (or `resources/lang/` in applications upgraded from Laravel 8). Each key is one URI segment, each value its translation.

```php title="lang/ar/routes.php"
return [
    'products' => 'المنتجات',
    'about' => 'من-نحن',
];
```

```php title="lang/fr/routes.php"
return [
    'products' => 'produits',
    'about' => 'a-propos',
];
```

```php title="routes/web.php"
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProductController;

Route::localized(function (): void {
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/about', AboutController::class)->name('about');
});

// /products/{product}        /about
// /ar/المنتجات/{product}     /ar/من-نحن
// /fr/produits/{product}     /fr/a-propos
```

### Rules

- Only static segments are translated. Placeholders (`{product}`, `{page?}`), their constraints and their order never change.
- Segments come from inside `Route::localized()`: an outer group prefix such as `shop` is not translated.
- A missing translation keeps the original segment. The translator's fallback locale is never used for URLs.
- A translation must be one segment: no `/`, `{`, `}`, `?`, `#`, `%` or spaces. Anything else throws when routes are registered.
- Translations are read when routes are registered, so a cached route file needs no lang lookups. Clear and rebuild the route cache after changing them.

### Unicode

Unicode segments are matched by Laravel and percent-encoded once in generated URLs:

```php
locale()->route('products.show', ['product' => 5], 'ar');
// "https://example.com/ar/%D8%A7%D9%84%D9%85%D9%86%D8%AA%D8%AC%D8%A7%D8%AA/5"
```
