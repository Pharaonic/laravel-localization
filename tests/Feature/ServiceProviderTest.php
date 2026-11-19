<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\Localization\LocalizationManager;
use Pharaonic\Laravel\Localization\LocalizationServiceProvider;
use Pharaonic\Laravel\Localization\Middleware\SetNegotiatedLocale;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\TestCase;

final class ServiceProviderTest extends TestCase
{
    public function test_it_registers_the_service_provider(): void
    {
        $this->assertNotEmpty($this->app->getProviders(LocalizationServiceProvider::class));
    }

    public function test_it_merges_the_package_configuration(): void
    {
        $this->assertTrue(config('pharaonic.localization.path.hide_default'));
        $this->assertSame(['user', 'header'], config('pharaonic.localization.resolvers.api'));
    }

    /**
     * @dataProvider publishTags
     */
    public function test_it_publishes_the_configuration_under_the_standard_tags(string $tag): void
    {
        $paths = ServiceProvider::pathsToPublish(LocalizationServiceProvider::class, $tag);

        $this->assertSame([realpath(__DIR__.'/../../config/localization.php')], array_map('realpath', array_keys($paths)));
        $this->assertSame([$this->app->configPath('pharaonic/localization.php')], array_values($paths));
    }

    /**
     * @return list<array{string}>
     */
    public static function publishTags(): array
    {
        return [['pharaonic'], ['laravel-localization'], ['pharaonic-config'], ['localization-config']];
    }

    public function test_it_registers_the_localization_middleware_alias_the_macro_and_the_helper(): void
    {
        $this->assertSame(SetNegotiatedLocale::class, app('router')->getMiddleware()['localization']);
        $this->assertTrue(Route::hasMacro('localized'));
        $this->assertInstanceOf(LocalizationManager::class, locale());
        $this->assertSame(app(LocalizationManager::class), locale());
    }

    public function test_it_does_not_override_route_url_or_the_application_locale_at_boot(): void
    {
        Route::get('/plain', PageController::class)->name('plain');
        Route::getRoutes()->refreshNameLookups();

        $this->assertSame(url('/plain'), route('plain'));
        $this->assertSame('en', app()->getLocale());
        $this->assertFalse(app()->resolved(LocaleCatalog::class));
    }

    public function test_it_exposes_locale_metadata_and_matching_through_locale(): void
    {
        config(['pharaonic.localization.locales' => ['ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => ['emoji' => '🇪🇬', 'url' => '/flags/eg.svg']]]]);
        $this->app->forgetInstance(LocaleCatalog::class);
        $this->app->forgetInstance(LocalizationManager::class);

        // App::setLocale() writes app.locale; the default must stay the one configured at boot.
        app()->setLocale('ar');

        $this->assertSame('ar', locale()->current());
        $this->assertSame('en', locale()->default());
        $this->assertSame(['en', 'ar', 'fr'], locale()->supported());
        $this->assertTrue(locale()->isSupported('AR'));
        $this->assertFalse(locale()->isSupported('ar-EG'));
        $this->assertSame('ar', locale()->match('ar-EG'));
        $this->assertSame('pt-BR', locale()->normalize('pt_br'));
        $this->assertSame('Arabic', locale()->name());
        $this->assertSame('العربية', locale()->nativeName());
        $this->assertSame('rtl', locale()->direction());
        $this->assertTrue(locale()->isRTL());
        $this->assertFalse(locale()->isRTL('fr'));
        $this->assertSame('/flags/eg.svg', locale()->meta('flag.url'));
        $this->assertSame('🇪🇬', locale()->meta('flag.emoji', locale: 'ar'));
        $this->assertSame('🏳️', locale()->meta('flag', '🏳️', 'fr'));
        $this->assertSame('العربية', locale()->meta('native'));
        $this->assertSame(['name' => 'French', 'native' => 'français', 'direction' => 'ltr'], locale()->metadata('fr'));
        $this->assertNull(locale()->source());
    }

    public function test_it_registers_100_routes_in_3_locales_as_300_routes_and_100_canonical_redirects(): void
    {
        Route::localized(function (): void {
            for ($i = 0; $i < 100; $i++) {
                Route::get("/page-{$i}/{id}", PageController::class)->name("page.{$i}");
            }
        });

        // 100 routes x 3 locales, plus one GET canonical redirect per default route.
        $this->assertSame(400, Route::getRoutes()->count());
        $this->assertSame('/fr/page-99/1', locale()->route('page.99', ['id' => 1], 'fr', false));
    }
}
