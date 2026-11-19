## Examples

### 1. A public website without sessions or cookies

English at `/`, Arabic at `/ar`, French at `/fr`, with canonical redirects for `/en/...` and hreflang tags.

- ===Configuration

  ```php title="config/pharaonic/localization.php"
  'supported' => ['en', 'ar', 'fr'],
  ```

- ===Routes

  ```php title="routes/web.php"
  Route::localized(function (): void {
      Route::get('/', 'HomeController')->name('home');
      Route::get('/about', 'AboutController')->name('about');
  });
  ```

- ===Layout

  ```blade title="resources/views/layouts/app.blade.php"
  <html lang="{{ locale()->normalize(locale()->current()) }}" dir="{{ locale()->direction() }}">
  <head>
      @foreach (locale()->hreflangs() as $hreflang => $url)
          <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $url }}">
      @endforeach
  </head>
  ```

### 2. A header-only API

```php title="config/pharaonic/localization.php"
'resolvers' => [
    'api' => ['header'],
],
```

```php title="routes/api.php"
Route::middleware('localization')->get('/products', 'ApiProductController@index');
```

```text
Accept-Language: ar-EG,ar;q=0.9,en;q=0.8   → ar, source "header"
Accept-Language: de                        → en (app.locale), source "default"
(no header)                                → en, source "default"
```

### 3. Signed-in users, then the browser

With `User` implementing `HasLocalePreference`, the default `api` profile (`user`, then `header`) uses the user's language when they have one:

```php title="routes/api.php"
Route::middleware(['auth:sanctum', 'localization'])->get('/notifications', 'NotificationController');
```

### 4. A tenant resolver before everything else

```php title="config/pharaonic/localization.php"
'resolvers' => [
    'api' => [
      App\Localization\TenantLocaleResolver::class,
      'user',
      'header'
    ],
],
```

```php title="app/Providers/AppServiceProvider.php"
$this->app->bind(TenantLocaleResolver::class, fn ($app) => new TenantLocaleResolver($app->make(TenantRepository::class)));
```

The tenant's locale wins; without one, the user's, then the header's.

### 5. Translated paths, localized slugs and deployment

- ===Translations

  ```php title="resources/lang/ar/routes.php"
  return ['posts' => 'مقالات'];
  ```

- ===Routes

  ```php title="routes/web.php"
  Route::localized(function (): void {
      Route::get('/posts/{post}', 'PostController@show')->name('posts.show');
  });
  ```

- ===Deploy

  ```bash title="Terminal" no-line-numbers
  php artisan config:cache
  php artisan route:cache
  php artisan localization:doctor
  ```

A post with no Arabic slug has no Arabic URL: `locale()->route('posts.show', $post, 'ar')` throws `MissingLocalizedRouteKeyException`, and the switcher and hreflang tags leave Arabic out.
