<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers\TenantLocaleResolver;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use RuntimeException;
use stdClass;

final class DoctorCommandTest extends TestCase
{
    /**
     * Run the command and return its exit code and output.
     *
     * @return array{int, string}
     */
    private function doctor(): array
    {
        $code = Artisan::call('localization:doctor');

        return [$code, Artisan::output()];
    }

    public function test_it_passes_a_valid_setup(): void
    {
        Route::middleware('web')->group(function () {
            Route::localized(function () {
                Route::get('/products', PageController::class)->name('products');
            });
        });
        Route::middleware(['web', 'localization:web'])->get('/negotiated', PageController::class);

        [$code, $output] = $this->doctor();

        $this->assertSame(0, $code);
        $this->assertStringContainsString('[OK] Default locale [en] (app.locale), supported: en, ar, fr.', $output);
        $this->assertStringContainsString('localized routes (1 logical) checked.', $output);
        $this->assertStringContainsString('0 error(s), 0 warning(s).', $output);
    }

    public function test_it_fails_on_invalid_configuration(): void
    {
        config(['pharaonic.localization.supported' => ['ar', 'fr']]);

        [$code, $output] = $this->doctor();

        $this->assertSame(1, $code);
        $this->assertStringContainsString('[ERROR] app.locale [en] is not in localization.supported', $output);
    }

    public function test_it_fails_on_unbuildable_resolvers_and_stale_localized_routes(): void
    {
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

        [$code, $output] = $this->doctor();

        $this->assertSame(1, $code);
        $this->assertStringContainsString('cannot be built: no tenant store', $output);
        $this->assertStringContainsString('Localized route [products] does not match the supported locales (missing: none; unknown: fr)', $output);
    }

    public function test_it_warns_about_resolvers_whose_middleware_is_missing(): void
    {
        config(['pharaonic.localization.resolvers.api' => ['session', 'cookie', 'header']]);
        Route::middleware('localization')->get('/api/products', PageController::class);
        Route::middleware('localization:mobile')->get('/api/mobile', PageController::class);

        [$code, $output] = $this->doctor();

        $this->assertSame(1, $code);
        $this->assertStringContainsString('[WARN] Route [api/products] uses the session resolver without StartSession', $output);
        $this->assertStringContainsString('[WARN] Route [api/products] reads the locale cookie without EncryptCookies', $output);
        $this->assertStringContainsString('[ERROR] Route [api/mobile]: Unknown resolver profile [mobile]', $output);
    }

    public function test_it_reports_configuration_that_config_cache_cannot_store(): void
    {
        config(['pharaonic.localization.locales' => []]);
        config(['pharaonic.localization.extra' => new stdClass]);

        [$code, $output] = $this->doctor();

        $this->assertSame(1, $code);
        $this->assertStringContainsString('config:cache cannot store it', $output);
    }

    public function test_it_does_not_print_secrets(): void
    {
        [$code, $output] = $this->doctor();

        $this->assertSame(0, $code);
        $this->assertStringNotContainsString((string) config('app.key'), $output);
    }
}
