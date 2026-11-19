<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;

test('it keeps the 1x route localized entry point', function () {
    Route::localized(function () {
        Route::get('/page', PageController::class);
    });

    $this->get('/ar/page')->assertOk()->assertJson(['locale' => 'ar']);
});

test('it keeps the 1x metadata accessors as deprecated aliases', function () {
    expect(locale()->getName('fr'))->toBe(locale()->name('fr'))
        ->and(locale()->getNativeName('fr'))->toBe(locale()->nativeName('fr'))
        ->and(locale()->getDirection('ar'))->toBe('rtl')
        ->and(locale()->isSupportedLocale('ar'))->toBeTrue()
        ->and(locale()->isSupportedLocale('de'))->toBeFalse()
        ->and(locale()->getLanguage('pt_BR'))->toBe('pt')
        ->and(locale()->getRegion('pt_BR'))->toBe('BR')
        ->and(locale()->getRegion('zh_Hant'))->toBeNull()
        ->and(locale()->getRegion('es_419'))->toBe('419');
});

test('it fails loudly on the removed locale code form instead of ignoring the argument', function () {
    expect(fn () => locale('ar'))->toThrow(InvalidArgumentException::class, 'locale($code) was removed after 1.x');
});

test('it no longer adds a locale route parameter', function () {
    Route::localized(function () {
        Route::get('/page/{id}', PageController::class)->name('page');
    });

    $this->get('/fr/page/3')->assertJson(['parameters' => ['id' => '3']]);

    expect(Route::getRoutes()->getByName('localized.fr.page')->parameterNames())->toBe(['id']);
});
