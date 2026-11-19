---
view: components.packages.quick-look
title: A quick look
subtitle: Localized pages for the website, negotiated locales for the API.
file: routes/web.php
language: php
code: |
  use App\Http\Controllers\ApiProductController;
  use App\Http\Controllers\ProductController;

  Route::localized(function (): void {
      Route::get('/products', [ProductController::class, 'index'])->name('products.index');
  });
  // /products (en), /ar/المنتجات, /fr/produits

  Route::middleware('localization')
      ->get('/api/products', [ApiProductController::class, 'index']);
  // Accept-Language: ar-EG,ar;q=0.9 → Content-Language: ar

  locale()->route('products.index', locale: 'fr'); // "https://example.com/fr/produits"
---
