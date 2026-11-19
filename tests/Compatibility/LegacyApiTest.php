<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Compatibility;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\TestCase;

final class LegacyApiTest extends TestCase
{
    public function test_it_keeps_the_1x_route_localized_entry_point(): void
    {
        Route::localized(function () {
            Route::get('/page', PageController::class);
        });

        $this->get('/ar/page')->assertOk()->assertJson(['locale' => 'ar']);
    }

    public function test_it_keeps_the_1x_metadata_accessors_as_deprecated_aliases(): void
    {
        $this->assertSame(locale()->name('fr'), locale()->getName('fr'));
        $this->assertSame(locale()->nativeName('fr'), locale()->getNativeName('fr'));
        $this->assertSame('rtl', locale()->getDirection('ar'));
        $this->assertTrue(locale()->isSupportedLocale('ar'));
        $this->assertFalse(locale()->isSupportedLocale('de'));
        $this->assertSame('pt', locale()->getLanguage('pt_BR'));
        $this->assertSame('BR', locale()->getRegion('pt_BR'));
        $this->assertNull(locale()->getRegion('zh_Hant'));
        $this->assertSame('419', locale()->getRegion('es_419'));
    }

    public function test_it_fails_loudly_on_the_removed_locale_code_form_instead_of_ignoring_the_argument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('locale($code) was removed after 1.x');

        locale('ar');
    }

    public function test_it_no_longer_adds_a_locale_route_parameter(): void
    {
        Route::localized(function () {
            Route::get('/page/{id}', PageController::class)->name('page');
        });

        $this->get('/fr/page/3')->assertJson(['parameters' => ['id' => '3']]);
        $this->assertSame(['id'], Route::getRoutes()->getByName('localized.fr.page')->parameterNames());
    }
}
