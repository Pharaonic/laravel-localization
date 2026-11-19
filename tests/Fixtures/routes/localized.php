<?php

declare(strict_types=1);

// The routes RouteCacheTest caches; TestCase provides the configuration and translations.

use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PostController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\ResourceController;

Route::middleware('web')->group(function (): void {
    Route::localized(function (): void {
        Route::get('/', PageController::class)->name('home');
        Route::get('/about', PageController::class);
        Route::get('/products/{product}', PageController::class)->name('products.show')->where('product', '[0-9]+');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/invites/{invite}', PageController::class)->name('invites.accept')->middleware('signed');
        Route::resource('items', ResourceController::class)->only(['index', 'store']);
    });

    Route::middleware('localization')->get('/api/products', PageController::class)->name('api.products');
});
