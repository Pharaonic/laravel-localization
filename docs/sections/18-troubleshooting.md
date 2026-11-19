## Troubleshooting

### "app.locale [en] is not in localization.supported"

The default locale must be in `supported`, spelled exactly as in `app.locale` (`en_US` and `en-US` are different directories for Laravel's translator). Add it, or set `supported` to `null` to support only the default.

### "config/Pharaonic/localization.php is the 1.x configuration"

The 1.x file is still in your application. Delete it and publish the new one with `php artisan vendor:publish --tag=localization-config`.

### `/ar/...` returns 404 after adding a locale

The route cache was built with the old `supported` list. Run `php artisan route:cache` again (or `route:clear`). `localization:doctor` reports localized routes that don't match the configuration.

### "Route [x] is not registered inside Route::localized()"

`locale()->route()` only builds localized routes. Use Laravel's `route()` for other routes, or move the route inside `Route::localized()`.

### The locale is always the default on an API route

Check that the route has the `localization` middleware and that a resolver can match. Test requests always send a header: an unsupported `Accept-Language` falls back to `app.locale` with source `default`. A `session` or `cookie` resolver needs the `web` group; the doctor warns when it is missing.

### A preference cookie or session value is ignored on `/products`

That's intended. A path-localized URL always has its own locale, and `/products` is always the default language. Link to `/ar/products`, or use `locale()->preferred()` to suggest a language.

### Model binding finds nothing under `/ar/...`

Make `resolveRouteBinding()` read `app()->getLocale()`. If your application replaced Laravel's middleware priority list, keep `SubstituteBindings` in it so the package can run before it.

### "Remembering a locale in the session needs a started session"

The route calling `remember(..., 'session')` has no session. Add it to the `web` group, or use the `cookie` store.

### "Route::localized() cannot register routes while the route cache is loaded"

Routes are being registered at runtime while the cache is active, for example from a package provider. Register localized routes in your routes files.
