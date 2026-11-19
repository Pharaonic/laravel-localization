---
view: components.packages.quick-look
title: A quick look
subtitle: Localized pages for the website, negotiated locales for the API.
file: routes/web.php
language: php
code: |
  Route::localized(function (): void {
      Route::get('/products', 'ProductController@index')->name('products.index');
  });
  // /products (en), /ar/المنتجات, /fr/produits

  Route::middleware('localization')
      ->get('/api/products', 'ApiProductController@index');
  // Accept-Language: ar-EG,ar;q=0.9 → Content-Language: ar

  locale()->route('products.index', locale: 'fr'); // "https://example.com/fr/produits"
---
