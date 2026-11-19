<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\LocalizationManager;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\User;

beforeEach(function () {
    Route::localized(function () {
        Route::get('/page', PageController::class);
    });
    Route::middleware('localization')->get('/api/page', PageController::class);
    Route::get('/plain', PageController::class);
});

test('it does not carry locale source or mode into the next request', function () {
    $this->get('/ar/page')->assertJson(['locale' => 'ar', 'source' => 'path']);
    $this->get('/plain')->assertJson(['locale' => 'en', 'source' => null, 'mode' => null]);

    $this->actingAs(User::prefers('fr'))->get('/api/page')->assertJson(['locale' => 'fr', 'source' => 'user']);
    Auth::logout();
    $this->withHeader('Accept-Language', '')->get('/api/page')->assertJson(['locale' => 'en', 'source' => 'default']);
    $this->get('/plain')->assertJson(['locale' => 'en', 'source' => null]);
});

test('it restores the application locale and config after each request', function () {
    $this->get('/fr/page')->assertJson(['locale' => 'fr']);

    expect(app()->getLocale())->toBe('en')
        ->and(config('app.locale'))->toBe('en')
        ->and(config('app.fallback_locale'))->toBe('en')
        ->and(locale()->default())->toBe('en');
});

test('it keeps one stateless manager across requests', function () {
    $manager = app(LocalizationManager::class);

    $this->get('/ar/page');
    $this->get('/fr/page');

    expect(app(LocalizationManager::class))->toBe($manager)
        ->and((new ReflectionClass($manager))->getProperties())->toHaveCount(1);
});

test('it does not resolve an http locale in the console', function () {
    expect(Artisan::call('route:list'))->toBe(0)
        ->and(locale()->source())->toBeNull()
        ->and(locale()->mode())->toBeNull()
        ->and(locale()->current())->toBe('en');
});

test('it works without a session cookies or a database for path and header only routes', function () {
    config(['session.driver' => 'array', 'database.default' => 'missing']);
    $this->localization(['resolvers' => ['api' => ['header']]]);

    $response = $this->withHeader('Accept-Language', 'ar')->get('/api/page')->assertJson(['locale' => 'ar']);
    $this->get('/fr/page')->assertJson(['locale' => 'fr']);

    expect($response->headers->getCookies())->toBe([])
        ->and(app()->bound('session.store') && app()->resolved('session.store'))->toBeFalse();
});
