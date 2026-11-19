## Negotiated Localization

For APIs and personalized responses, add the `localization` middleware. The URL stays the same, and the locale comes from a resolver profile.

```php title="routes/api.php"
Route::middleware('localization')->group(function (): void {
    Route::get('/products', 'ApiProductController@index');
});
```

The argument is the profile name: `localization` uses `api`, `localization:web` uses `web`.

```text
GET /api/products
Accept-Language: ar-EG,ar;q=0.9,en;q=0.8

→ Content-Language: ar, Vary: Accept-Language, locale()->source() === "header"
```

### The pipeline

Resolvers run in the configured order; the first one returning a supported locale wins.

```php title="config/pharaonic/localization.php"
'resolvers' => [
    'web' => ['user', 'session', 'cookie', 'header'],
    'api' => ['user', 'header'],
],
```

- `null`, blank, malformed and unsupported values fall through to the next resolver, so a stale cookie never breaks a request. Each value is matched like `locale()->match()`, so `ar_EG` from a user becomes `ar`.
- Without a match, the locale is `app.locale` and the source `default`. `app.fallback_locale` is never used: it remains Laravel's fallback for missing translation strings.
- An exception thrown by a resolver is not caught, so a broken resolver fails visibly.

### Built-in resolvers

| ID | Reads | Notes |
| --- | --- | --- |
| `user` | `$request->user()->preferredLocale()` | When the user implements Laravel's `HasLocalePreference`. No trait or column is required. |
| `session` | `session.key` | Only when the session is already started (the `web` group). Never starts one. |
| `cookie` | `cookie.key` | As the request carries it: decrypted by `EncryptCookies` in the `web` group. Never writes one. |
| `header` | `Accept-Language` (or `header.name`) | Quality values, `q=0`, `*`, language-range matching; malformed entries are skipped and parsing is bounded. |

```php title="app/User.php"
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements HasLocalePreference
{
    public function preferredLocale(): ?string
    {
        return $this->locale;
    }
}
```

### Middleware order

The package puts `localization` after authentication in Laravel's middleware priority, so the `user` resolver sees token users whatever order you list them in:

```php title="routes/api.php"
Route::middleware(['auth:sanctum', 'localization'])->get('/me', 'MeController');
```

For `session` and `cookie`, the route needs the `web` group (or `StartSession` and `EncryptCookies`); `localization:doctor` warns when they are missing.

### Response headers

- `Content-Language` is set to the BCP 47 tag of the locale, unless the response already has one.
- When the `header` resolver ran, the header name is added to `Vary`, keeping existing values.
- When the `user`, `session` or `cookie` resolver ran, a response marked `public` or with `s-maxage` is made `private`, so a shared cache can't serve one user's language to another. `no-store` responses and Laravel's default `no-cache, private` are left alone.

:::warning CDNs
A `Vary: Accept-Language` response is cached per header value, which can fragment a CDN cache. Responses chosen from a user, session or cookie are private and shouldn't be cached by a CDN at all. Custom resolvers that read personal data must set their own cache headers.
:::

### Path routes win

On a `Route::localized()` route, `localization` does nothing: the path locale is authoritative. To suggest a language on such a page without changing it, use `preferred()`:

```php
locale()->preferred();       // runs the "web" profile, e.g. "fr", or null
locale()->preferred('api');  // any profile
```
