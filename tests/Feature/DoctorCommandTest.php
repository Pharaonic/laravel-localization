<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\TenantLocaleResolver;

/**
 * Run localization:doctor and return its exit code and output.
 *
 * @return array{int, string}
 */
function doctor(): array
{
    $code = Artisan::call('localization:doctor');

    return [$code, Artisan::output()];
}

test('it passes a valid setup', function () {
    Route::middleware('web')->group(function () {
        Route::localized(function () {
            Route::get('/products', PageController::class)->name('products');
        });
    });
    Route::middleware(['web', 'localization:web'])->get('/negotiated', PageController::class);

    [$code, $output] = doctor();

    expect($code)->toBe(0)
        ->and($output)->toContain('[OK] Default locale [en] (app.locale), supported: en, ar, fr.')
        ->and($output)->toContain('localized routes (1 logical) checked.')
        ->and($output)->toContain('0 error(s), 0 warning(s).');
});

test('it fails on invalid configuration', function () {
    config(['pharaonic.localization.supported' => ['ar', 'fr']]);

    [$code, $output] = doctor();

    expect($code)->toBe(1)
        ->and($output)->toContain('[ERROR] app.locale [en] is not in localization.supported');
});

test('it fails on unbuildable resolvers and stale localized routes', function () {
    Route::localized(function () {
        Route::get('/products', PageController::class)->name('products');
    });
    config([
        'pharaonic.localization.supported' => ['en', 'ar'],
        'pharaonic.localization.resolvers.api' => [TenantLocaleResolver::class],
    ]);
    $this->app->bind(TenantLocaleResolver::class, function () {
        throw new RuntimeException('no tenant store');
    });

    [$code, $output] = doctor();

    expect($code)->toBe(1)
        ->and($output)->toContain('cannot be built: no tenant store')
        ->and($output)->toContain('Localized route [products] does not match the supported locales (missing: none; unknown: fr)');
});

test('it warns about resolvers whose middleware is missing', function () {
    config(['pharaonic.localization.resolvers.api' => ['session', 'cookie', 'header']]);
    Route::middleware('localization')->get('/api/products', PageController::class);
    Route::middleware('localization:mobile')->get('/api/mobile', PageController::class);

    [$code, $output] = doctor();

    expect($code)->toBe(1)
        ->and($output)->toContain('[WARN] Route [api/products] uses the session resolver without StartSession')
        ->and($output)->toContain('[WARN] Route [api/products] reads the locale cookie without EncryptCookies')
        ->and($output)->toContain('[ERROR] Route [api/mobile]: Unknown resolver profile [mobile]');
});

test('it reports configuration that config cache cannot store', function () {
    config(['pharaonic.localization.locales' => []]);
    config(['pharaonic.localization.extra' => new stdClass]);

    [$code, $output] = doctor();

    expect($code)->toBe(1)
        ->and($output)->toContain('config:cache cannot store it');
});

test('it does not print secrets', function () {
    [$code, $output] = doctor();

    expect($code)->toBe(0)
        ->and($output)->not->toContain((string) config('app.key'));
});
