<p align="center"><a href="https://pharaonic.dev/packages/laravel/localization" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/laravel-localization/develop/docs/cover.webp"></a></p>

<p align="center">
  <a href="https://php.net" target="_blank"><img src="https://img.shields.io/static/v1?label=PHP&message=8.0%20|%208.1%20|%208.2&color=blue&style=flat-square" alt="PHP Version : 8.0 | 8.1 | 8.2"></a>
  <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/static/v1?label=Laravel&message=9.x&color=F05340&style=flat-square" alt="Laravel Version : 9.x"></a>
  <img src="https://img.shields.io/static/v1?label=License&message=MIT&color=brightgreen&style=flat-square" alt="License">
  <img src="https://github.com/Pharaonic/laravel-localization/actions/workflows/build.yml/badge.svg?branch=9.x" alt="Build">
  <br>
  <a href="https://packagist.org/packages/Pharaonic/laravel-localization" target="_blank"><img src="https://img.shields.io/static/v1?label=Packagist&message=pharaonic/laravel-localization&color=blue&logo=packagist&logoColor=white" alt="Source"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-localization" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-localization/v" alt="Packagist Version"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-localization" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-localization/downloads" alt="Packagist Downloads"></a>
</p>

<h3 align="center">Localized routing for Laravel: static locale-prefixed URLs for websites, and an ordered resolver pipeline for APIs.</h3>
<br>

## Installation

```bash
composer require pharaonic/laravel-localization
```

The service provider is registered automatically. Publish the configuration when you need to change it:

```bash
php artisan vendor:publish --tag=localization-config
```

## Usage

```php
use App\Http\Controllers\ApiProductController;
use App\Http\Controllers\ProductController;

// config/pharaonic/localization.php: 'supported' => ['en', 'ar', 'fr'], with app.locale = 'en'

Route::localized(function (): void {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});
// /products (en), /ar/products, /fr/products; /en/products redirects to /products

Route::middleware('localization')->get('/api/products', [ApiProductController::class, 'index']);
// The locale comes from the user's preferredLocale(), then Accept-Language, then app.locale

locale()->route('products.index', locale: 'ar'); // "https://example.com/ar/products"
```

Upgrading from 1.x? See [UPGRADING.md](UPGRADING.md).

## Documentation

Full documentation: [Laravel Localization Documentation](https://pharaonic.dev/packages/laravel/localization).

## Support & Security

See [SUPPORT.md](SUPPORT.md) and [SECURITY.md](SECURITY.md). Contributions are welcome; see [CONTRIBUTING.md](CONTRIBUTING.md).

## License

MIT. See [LICENSE](LICENSE).
