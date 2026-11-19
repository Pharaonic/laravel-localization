## Localized URLs

`locale()->route()` builds the URL of a localized route in any locale, through Laravel's URL generator and the concrete route name. It never changes the current locale or a registered route.

```php
locale()->route('products.index');                       // current locale
locale()->route('products.index', locale: 'ar');         // "https://example.com/ar/products"
locale()->route('products.show', ['product' => 5], 'fr'); // "https://example.com/fr/products/5"
locale()->route('products.show', 5, 'fr', false);        // "/fr/products/5"
```

The signature is `route(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true)`. Parameters work as with `route()`: arrays, a single value, models, extra keys as the query string.

### Errors

| Situation | Exception |
| --- | --- |
| The route doesn't exist, or wasn't registered inside `Route::localized()` | `LocalizedRouteNotFoundException` (a Symfony `RouteNotFoundException`) |
| The locale isn't supported (`de`, or `ar-EG` when only `ar` is) | `UnsupportedLocaleException` |
| A `HasLocalizedRouteKey` parameter has no key in that locale | `MissingLocalizedRouteKeyException` |

All three live in `Pharaonic\Laravel\Localization\Exceptions`.

### The current locale

```php
locale()->current();     // "ar", the active App::getLocale()
locale()->default();     // "en", app.locale
locale()->source();      // "path", "user", "session", "cookie", "header", a class name, "default", or null
locale()->mode();        // "path", "negotiated", or null outside a localized request
locale()->supported();   // ["en", "ar", "fr"]
```

### Matching and normalizing

```php
locale()->isSupported('AR');      // true (case and -/_ insensitive, no fallback)
locale()->isSupported('ar-EG');   // false
locale()->match('ar-EG');         // "ar" (exact, then parent, then first configured variant)
locale()->match('pt');            // "pt_BR" when pt_BR is the first configured pt locale
locale()->normalize('pt_br');     // "pt-BR" (BCP 47)
```

`normalize()` throws an `InvalidArgumentException` for a malformed code; `match()` returns `null`.

### Metadata

```php
locale()->name('ar');        // "Arabic"
locale()->nativeName('ar');  // "العربية"
locale()->direction();       // "rtl" for the current locale
locale()->isRTL('fr');       // false
```

```blade title="resources/views/layouts/app.blade.php"
<html lang="{{ locale()->normalize(locale()->current()) }}" dir="{{ locale()->direction() }}">
```
