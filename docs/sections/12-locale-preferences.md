## Locale Preferences

Resolving a locale never writes anything. To remember a choice for later requests, call `remember()` from a route you control, such as a language-picker form.

```php title="routes/web.php"
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

Route::middleware('web')->post('/language', function (Request $request) {
    $locale = $request->validate([
        'locale' => ['required', Rule::in(locale()->supported())],
    ])['locale'];

    locale()->remember($locale, 'session');
    locale()->remember($locale, 'cookie');

    return redirect(locale()->route('home', locale: $locale));
})->name('language');
```

### Stores

| Store | Writes | Needs |
| --- | --- | --- |
| `session` | `session.key` | A started session (the `web` group). Otherwise a `RuntimeException` explains what is missing. |
| `cookie` | A queued cookie named `cookie.key` | Laravel's cookie middleware: `AddQueuedCookiesToResponse` sends it, `EncryptCookies` encrypts it. |

The cookie is HTTP-only, uses the configured lifetime, SameSite (`lax` by default) and secure flag (`null` follows `session.secure`), with Laravel's default path and domain.

### Rules

- The locale is validated first: an unsupported value throws `UnsupportedLocaleException`, and the configured spelling is stored (`AR` is stored as `ar`).
- The current request keeps its locale. On a path-localized page, redirect to the URL in the new locale to switch now.
- `forget('session')` and `forget('cookie')` remove a stored preference.
- Nothing writes to the user model. Update your own column, then `preferredLocale()` returns it.
- The package registers no route of its own. Keep your preference route behind CSRF protection (the `web` group).
