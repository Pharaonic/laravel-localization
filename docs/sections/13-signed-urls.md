## Signed URLs

Sign localized routes with Laravel's own signer. The URL is generated for the concrete route of the locale, then signed, and nothing changes it afterwards.

```php
$url = locale()->signedRoute('invites.accept', ['invite' => $invite], locale: 'ar');

$url = locale()->temporarySignedRoute('invites.accept', now()->addMinutes(30), ['invite' => $invite], locale: 'fr');

$url = locale()->signedRoute('invites.accept', ['invite' => $invite], absolute: false, locale: 'fr');
```

The signatures are `signedRoute($name, $parameters = [], $expiration = null, $absolute = true, $locale = null)` and `temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true, $locale = null)`.

Protect the route with Laravel's `signed` middleware:

```php title="routes/web.php"
Route::localized(function (): void {
    Route::get('/invites/{invite}', 'AcceptInviteController')
        ->name('invites.accept')
        ->middleware('signed');
});
```

Laravel 6 has no `signed:relative` middleware. Check a relative URL in the controller instead:

```php title="app/Http/Controllers/AcceptInviteController.php"
abort_unless($request->hasValidSignature(false), 403);
```

### What to know

- The signature covers the locale prefix and translated path, so changing `/ar/` to `/fr/` makes it invalid (`403`).
- `switchUrl()` and `alternates()` return nothing on a signed URL. To offer another language, sign a new URL with `locale()->signedRoute()` for that locale.
- Canonical redirects change the URL, so a signed URL that goes through `/en/...` is no longer valid. Sign the canonical URL, which `locale()->signedRoute()` always does.
- The 1.x `URL::LocalizedSignedRoute()` and `URL::temporaryLocalizedSignedRoute()` macros remain as deprecated aliases of these methods.
