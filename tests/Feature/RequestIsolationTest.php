<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\LocalizationManager;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\User;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use ReflectionClass;

final class RequestIsolationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::localized(function () {
            Route::get('/page', PageController::class);
        });
        Route::middleware('localization')->get('/api/page', PageController::class);
        Route::get('/plain', PageController::class);
    }

    public function test_it_does_not_carry_locale_source_or_mode_into_the_next_request(): void
    {
        $this->get('/ar/page')->assertJson(['locale' => 'ar', 'source' => 'path']);
        $this->get('/plain')->assertJson(['locale' => 'en', 'source' => null, 'mode' => null]);

        $this->actingAs(User::prefers('fr'))->get('/api/page')->assertJson(['locale' => 'fr', 'source' => 'user']);
        Auth::logout();
        $this->withHeader('Accept-Language', '')->get('/api/page')->assertJson(['locale' => 'en', 'source' => 'default']);
        $this->get('/plain')->assertJson(['locale' => 'en', 'source' => null]);
    }

    public function test_it_restores_the_application_locale_and_config_after_each_request(): void
    {
        $this->get('/fr/page')->assertJson(['locale' => 'fr']);

        $this->assertSame('en', app()->getLocale());
        $this->assertSame('en', config('app.locale'));
        $this->assertSame('en', config('app.fallback_locale'));
        $this->assertSame('en', locale()->default());
    }

    public function test_it_keeps_one_stateless_manager_across_requests(): void
    {
        $manager = app(LocalizationManager::class);

        $this->get('/ar/page');
        $this->get('/fr/page');

        $this->assertSame($manager, app(LocalizationManager::class));
        $this->assertCount(1, (new ReflectionClass($manager))->getProperties());
    }

    public function test_it_does_not_resolve_an_http_locale_in_the_console(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));

        $this->assertNull(locale()->source());
        $this->assertNull(locale()->mode());
        $this->assertSame('en', locale()->current());
    }

    public function test_it_works_without_a_session_cookies_or_a_database_for_path_and_header_only_routes(): void
    {
        config(['session.driver' => 'array', 'database.default' => 'missing']);
        $this->localization(['resolvers' => ['api' => ['header']]]);

        $response = $this->withHeader('Accept-Language', 'ar')->get('/api/page')->assertJson(['locale' => 'ar']);
        $this->get('/fr/page')->assertJson(['locale' => 'fr']);

        $this->assertSame([], $response->headers->getCookies());
        $this->assertFalse(app()->bound('session.store') && app()->resolved('session.store'));
    }
}
