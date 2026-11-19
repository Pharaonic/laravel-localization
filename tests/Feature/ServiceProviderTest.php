<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Localization\LocalizationManager;
use Pharaonic\Laravel\Localization\LocalizationServiceProvider;
use Pharaonic\Laravel\Localization\Middleware\SetNegotiatedLocale;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;

test('it registers the service provider', function () {
    expect($this->app->getProviders(LocalizationServiceProvider::class))->not->toBeEmpty();
});

test('it merges the package configuration', function () {
    expect(config('pharaonic.localization.path.hide_default'))->toBeTrue()
        ->and(config('pharaonic.localization.resolvers.api'))->toBe(['user', 'header']);
});

test('it publishes the configuration under the standard tags', function (string $tag) {
    $paths = ServiceProvider::pathsToPublish(LocalizationServiceProvider::class, $tag);

    expect(array_map('realpath', array_keys($paths)))->toBe([realpath(__DIR__.'/../../config/localization.php')])
        ->and(array_values($paths))->toBe([$this->app->configPath('pharaonic/localization.php')]);
})->with(['pharaonic', 'laravel-localization', 'pharaonic-config', 'localization-config']);

test('it registers the localization middleware alias the macro and the helper', function () {
    expect(app('router')->getMiddleware()['localization'])->toBe(SetNegotiatedLocale::class)
        ->and(Route::hasMacro('localized'))->toBeTrue()
        ->and(locale())->toBeInstanceOf(LocalizationManager::class)
        ->and(locale())->toBe(app(LocalizationManager::class));
});

test('it does not override route url or the application locale at boot', function () {
    Route::get('/plain', PageController::class)->name('plain');
    Route::getRoutes()->refreshNameLookups();

    expect(route('plain'))->toBe(url('/plain'))
        ->and(app()->getLocale())->toBe('en')
        ->and(app()->resolved(LocaleCatalog::class))->toBeFalse();
});

test('it exposes locale metadata and matching through locale', function () {
    config(['pharaonic.localization.locales' => ['ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => ['emoji' => '🇪🇬', 'url' => '/flags/eg.svg']]]]);
    $this->app->forgetInstance(LocaleCatalog::class);
    $this->app->forgetInstance(LocalizationManager::class);

    // App::setLocale() writes app.locale; the default must stay the one configured at boot.
    app()->setLocale('ar');

    expect(locale()->current())->toBe('ar')
        ->and(locale()->default())->toBe('en')
        ->and(locale()->supported())->toBe(['en', 'ar', 'fr'])
        ->and(locale()->isSupported('AR'))->toBeTrue()
        ->and(locale()->isSupported('ar-EG'))->toBeFalse()
        ->and(locale()->match('ar-EG'))->toBe('ar')
        ->and(locale()->normalize('pt_br'))->toBe('pt-BR')
        ->and(locale()->name())->toBe('Arabic')
        ->and(locale()->nativeName())->toBe('العربية')
        ->and(locale()->direction())->toBe('rtl')
        ->and(locale()->isRTL())->toBeTrue()
        ->and(locale()->isRTL('fr'))->toBeFalse()
        ->and(locale()->meta('flag.url'))->toBe('/flags/eg.svg')
        ->and(locale()->meta('flag.emoji', locale: 'ar'))->toBe('🇪🇬')
        ->and(locale()->meta('flag', '🏳️', 'fr'))->toBe('🏳️')
        ->and(locale()->meta('native'))->toBe('العربية')
        ->and(locale()->metadata('fr'))->toBe(['name' => 'French', 'native' => 'français', 'direction' => 'ltr'])
        ->and(locale()->source())->toBeNull();
});

test('it registers 100 routes in 3 locales as 900 static routes', function () {
    Route::localized(function (): void {
        for ($i = 0; $i < 100; $i++) {
            Route::get("/page-{$i}/{id}", PageController::class)->name("page.{$i}");
        }
    });

    // 100 routes x 3 locales, plus one GET canonical redirect per default route.
    expect(Route::getRoutes()->count())->toBe(400)
        ->and(locale()->route('page.99', ['id' => 1], 'fr', false))->toBe('/fr/page-99/1');
});
