## Installation

Install the package with Composer. The service provider, the `Route::localized()` macro, the `localization` middleware alias and the `locale()` helper are registered automatically.

### Requirements

- PHP 8.1, 8.2 or 8.3
- Laravel 10.x (10.0 or later)
- `ext-intl`: it provides locale display names for `name()` and `nativeName()`

### Composer Installation

```bash title="Terminal" no-line-numbers
composer require pharaonic/laravel-localization
```

### Publish Configuration

Publish the configuration file to `config/pharaonic/localization.php`:

```bash title="Terminal" no-line-numbers
php artisan vendor:publish --tag=localization-config
```

Then list your locales. The default locale is Laravel's `app.locale` and must be one of them:

```php title="config/pharaonic/localization.php"
'supported' => ['en', 'ar', 'fr'],
```

### Check the Setup

```bash title="Terminal" no-line-numbers
php artisan localization:doctor
```

:::success Installation Complete
You're all set! Wrap routes in `Route::localized()` for localized URLs, or add the `localization` middleware to API routes.
:::

:::info No database, session or cookies required
The package ships no migrations and needs no session or cookie middleware for path routes or header-only APIs. Session and cookie resolvers only read what your own middleware already provides.
:::
