<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Feature;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Pharaonic\Laravel\Localization\Exceptions\UnsupportedLocaleException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

final class LocalePreferenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->group(function (): void {
            Route::post('/language/{locale}', function (string $locale) {
                locale()->remember($locale, 'session');
                locale()->remember($locale, 'cookie');

                return ['locale' => app()->getLocale(), 'session' => session('locale')];
            });
            Route::post('/language-reset', function () {
                locale()->forget('session');
                locale()->forget('cookie');

                return ['session' => session('locale')];
            });
            Route::localized(function () {
                Route::get('/page', PageController::class);
            });
            Route::middleware('localization:web')->get('/negotiated', PageController::class);
        });
        Route::middleware('localization')->post('/api/language/{locale}', function (string $locale) {
            locale()->remember($locale, 'session');
        });
    }

    private static function cookie(Response $response, string $name): ?Cookie
    {
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === $name) {
                return $cookie;
            }
        }

        return null;
    }

    public function test_it_stores_a_validated_canonical_preference_in_the_session_and_an_encrypted_cookie(): void
    {
        $response = $this->post('/language/Ar')->assertOk()->assertJson(['session' => 'ar', 'locale' => 'en']);
        $cookie = self::cookie($response->baseResponse, 'locale');

        $this->assertNotNull($cookie);
        $this->assertNotSame('ar', $cookie->getValue());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
        $this->assertSame('/', $cookie->getPath());
        $this->assertGreaterThan(now()->addDays(364)->getTimestamp(), $cookie->getExpiresTime());

        $response->assertCookie('locale', 'ar');
    }

    public function test_it_applies_the_configured_cookie_attributes(): void
    {
        $this->localization(['cookie' => ['key' => 'lang', 'minutes' => 10, 'same_site' => 'strict', 'secure' => true]]);

        $cookie = self::cookie($this->post('/language/fr')->baseResponse, 'lang');

        $this->assertNotNull($cookie);
        $this->assertTrue($cookie->isSecure());
        $this->assertSame('strict', $cookie->getSameSite());
        $this->assertLessThanOrEqual(now()->addMinutes(10)->getTimestamp(), $cookie->getExpiresTime());
    }

    public function test_it_rejects_unsupported_locales_before_storing_anything(): void
    {
        $this->withoutExceptionHandling();

        try {
            $this->post('/language/de');
            $this->fail('An unsupported locale was stored.');
        } catch (UnsupportedLocaleException $exception) {
            $this->addToAssertionCount(1);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown preference store [database]');

        locale()->remember('ar', 'database');
    }

    public function test_it_fails_explicitly_when_the_session_is_not_started(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('needs a started session');

        $this->post('/api/language/ar');
    }

    public function test_it_does_not_change_the_current_request_locale_or_a_path_locale(): void
    {
        Route::middleware('web')->group(function () {
            Route::localized(function () {
                Route::post('/choose', function () {
                    locale()->remember('fr', 'session');

                    return ['locale' => app()->getLocale()];
                });
            });
        });

        $this->post('/ar/choose')->assertJson(['locale' => 'ar']);
        $this->get('/page')->assertJson(['locale' => 'en']);
    }

    public function test_it_is_used_by_negotiated_routes_on_later_requests(): void
    {
        $this->withHeader('Accept-Language', '');
        $this->post('/language/fr');

        $this->get('/negotiated')->assertJson(['locale' => 'fr', 'source' => 'session']);
    }

    public function test_it_forgets_stored_preferences(): void
    {
        $this->withSession(['locale' => 'ar']);

        $response = $this->post('/language-reset')->assertJson(['session' => null]);
        $cookie = self::cookie($response->baseResponse, 'locale');

        $this->assertNotNull($cookie);
        $this->assertTrue($cookie->isCleared());
    }

    public function test_it_never_writes_preferences_while_resolving(): void
    {
        $this->withSession(['locale' => 'fr'])->withHeader('Accept-Language', 'ar');

        $negotiated = $this->get('/negotiated')->assertJson(['locale' => 'fr']);
        $path = $this->get('/ar/page')->assertJson(['locale' => 'ar']);

        foreach ([$negotiated, $path] as $response) {
            $this->assertNull(self::cookie($response->baseResponse, 'locale'));
        }

        $this->assertSame('fr', session('locale'));
    }
}
