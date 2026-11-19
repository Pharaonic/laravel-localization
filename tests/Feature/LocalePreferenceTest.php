<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Pharaonic\Laravel\Localization\Exceptions\UnsupportedLocaleException;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers\PageController;

beforeEach(function () {
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
});

test('it stores a validated canonical preference in the session and an encrypted cookie', function () {
    $response = $this->post('/language/Ar')->assertOk()->assertJson(['session' => 'ar', 'locale' => 'en']);
    $cookie = responseCookie($response->baseResponse, 'locale');

    expect($cookie)->not->toBeNull()
        ->and($cookie->getValue())->not->toBe('ar')
        ->and($cookie->isHttpOnly())->toBeTrue()
        ->and($cookie->getSameSite())->toBe('lax')
        ->and($cookie->getPath())->toBe('/')
        ->and($cookie->getExpiresTime())->toBeGreaterThan(now()->addDays(364)->getTimestamp());

    $response->assertCookie('locale', 'ar');
});

test('it applies the configured cookie attributes', function () {
    $this->localization(['cookie' => ['key' => 'lang', 'minutes' => 10, 'same_site' => 'strict', 'secure' => true]]);

    $cookie = responseCookie($this->post('/language/fr')->baseResponse, 'lang');

    expect($cookie)->not->toBeNull()
        ->and($cookie->isSecure())->toBeTrue()
        ->and($cookie->getSameSite())->toBe('strict')
        ->and($cookie->getExpiresTime())->toBeLessThanOrEqual(now()->addMinutes(10)->getTimestamp());
});

test('it rejects unsupported locales before storing anything', function () {
    $this->withoutExceptionHandling();

    expect(fn () => $this->post('/language/de'))->toThrow(UnsupportedLocaleException::class)
        ->and(fn () => locale()->remember('ar', 'database'))->toThrow(InvalidArgumentException::class, 'Unknown preference store [database]');
});

test('it fails explicitly when the session is not started', function () {
    $this->withoutExceptionHandling();

    expect(fn () => $this->post('/api/language/ar'))->toThrow(RuntimeException::class, 'needs a started session');
});

test('it does not change the current request locale or a path locale', function () {
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
});

test('it is used by negotiated routes on later requests', function () {
    $this->withHeader('Accept-Language', '');
    $this->post('/language/fr');

    $this->get('/negotiated')->assertJson(['locale' => 'fr', 'source' => 'session']);
});

test('it forgets stored preferences', function () {
    $this->withSession(['locale' => 'ar']);

    $response = $this->post('/language-reset')->assertJson(['session' => null]);
    $cookie = responseCookie($response->baseResponse, 'locale');

    expect($cookie)->not->toBeNull()
        ->and($cookie->isCleared())->toBeTrue();
});

test('it never writes preferences while resolving', function () {
    $this->withSession(['locale' => 'fr'])->withHeader('Accept-Language', 'ar');

    $negotiated = $this->get('/negotiated')->assertJson(['locale' => 'fr']);
    $path = $this->get('/ar/page')->assertJson(['locale' => 'ar']);

    expect(responseCookie($negotiated->baseResponse, 'locale'))->toBeNull()
        ->and(responseCookie($path->baseResponse, 'locale'))->toBeNull()
        ->and(session('locale'))->toBe('fr');
});
