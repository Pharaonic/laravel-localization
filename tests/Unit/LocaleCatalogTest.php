<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;

/**
 * A catalog built from the package defaults with the given overrides.
 *
 * @param  array<string, mixed>  $settings
 */
function catalog(array $settings = [], string $locale = 'en', string $fallback = 'fr'): LocaleCatalog
{
    $config = new Repository([
        'app' => ['locale' => $locale, 'fallback_locale' => $fallback],
        'pharaonic' => ['localization' => array_replace(require __DIR__.'/../../config/localization.php', $settings)],
    ]);

    return LocaleCatalog::fromConfig($config);
}

test('it takes the default locale from app locale never from app fallback locale', function () {
    $catalog = catalog(['supported' => ['en', 'ar']], 'ar', 'en');

    expect($catalog->default())->toBe('ar')
        ->and($catalog->supported())->toBe(['en', 'ar']);
});

test('it supports only app locale when supported is null', function () {
    expect(catalog(['supported' => null], 'fr')->supported())->toBe(['fr']);
});

test('it does not define default or fallback keys', function () {
    expect(require __DIR__.'/../../config/localization.php')
        ->not->toHaveKey('default')
        ->not->toHaveKey('fallback')
        ->not->toHaveKey('fallback_locale')
        ->not->toHaveKey('type');
});

test('it fails fast on invalid configuration', function (array $settings, string $message, string $locale = 'en') {
    expect(fn () => catalog($settings, $locale))->toThrow(InvalidLocalizationConfigurationException::class, $message);
})->with([
    'default not supported' => [['supported' => ['ar', 'fr']], 'app.locale [en] is not in localization.supported'],
    'default spelled differently' => [['supported' => ['en_US', 'ar']], 'app.locale [en-US] is not in', 'en-US'],
    'empty list' => [['supported' => []], 'non-empty list'],
    'not a list' => [['supported' => 'en,ar'], 'non-empty list'],
    'malformed code' => [['supported' => ['en', 'english']], '[english], which is not a valid locale code'],
    'duplicate canonical' => [['supported' => ['en', 'pt_BR', 'pt-br']], 'lists [pt_BR] and [pt-br], which are the same locale'],
    'prefix for unsupported locale' => [['supported' => ['en'], 'path' => ['prefixes' => ['ar' => 'arabic']]], 'not a supported locale'],
    'malformed prefix' => [['supported' => ['en', 'ar'], 'path' => ['prefixes' => ['ar' => 'AR/x']]], 'lowercase letters'],
    'duplicate prefix' => [['supported' => ['en', 'ar', 'fr'], 'path' => ['prefixes' => ['ar' => 'fr']]], 'share the URL prefix [fr]'],
    'non-boolean hide_default' => [['path' => ['hide_default' => 'yes']], 'path.hide_default must be true or false'],
    'unknown resolver' => [['resolvers' => ['api' => ['user', 'geoip']]], 'localization.resolvers.api has [geoip]'],
    'resolver class without contract' => [['resolvers' => ['api' => [stdClass::class]]], 'has [stdClass]'],
    'duplicate resolver' => [['resolvers' => ['api' => ['user', 'user']]], 'lists a resolver twice'],
    'cookie minutes' => [['cookie' => ['minutes' => 0]], 'cookie.minutes must be a positive integer'],
    'cookie same_site' => [['cookie' => ['same_site' => 'sometimes']], 'cookie.same_site'],
    'cookie secure' => [['cookie' => ['secure' => 'auto']], 'cookie.secure'],
    'session key' => [['session' => ['key' => '']], 'session.key must be a non-empty string'],
    'header name' => [['header' => ['name' => 'Accept Language']], 'valid HTTP header name'],
    'metadata direction' => [['locales' => ['ar' => ['direction' => 'right']]], 'localization.locales.ar.direction must be ltr or rtl'],
    'metadata name' => [['locales' => ['ar' => ['name' => ['Arabic']]]], 'localization.locales.ar.name must be a non-empty string'],
    'metadata list' => [['locales' => ['ar' => ['flag.svg']]], 'localization.locales.ar must use non-empty string keys'],
    'duplicate metadata locale' => [['locales' => ['pt_BR' => [], 'pt-br' => []]], 'localization.locales lists [pt_BR] and [pt-br]'],
    'malformed app.locale' => [[], 'app.locale must be a valid locale code', 'English'],
]);

test('it rejects the 1x configuration file with a migration hint', function () {
    $config = new Repository([
        'app' => ['locale' => 'en'],
        'pharaonic' => ['localization' => require __DIR__.'/../../config/localization.php'],
        'Pharaonic' => ['localization' => ['type' => 'sub-directory', 'default' => 'en', 'supported' => ['en']]],
    ]);

    expect(fn () => LocaleCatalog::fromConfig($config))
        ->toThrow(InvalidLocalizationConfigurationException::class, 'config/Pharaonic/localization.php is the 1.x configuration');
});

test('it reads the new file when a case insensitive filesystem loads it as pharaonic', function () {
    $config = new Repository([
        'app' => ['locale' => 'en'],
        'pharaonic' => ['localization' => require __DIR__.'/../../config/localization.php'],
        'Pharaonic' => ['localization' => ['supported' => ['en', 'ar']]],
    ]);

    expect(LocaleCatalog::fromConfig($config)->supported())->toBe(['en', 'ar']);
});

test('it derives url prefixes from bcp 47 tags with overrides', function () {
    $catalog = catalog(['supported' => ['en', 'pt_BR', 'zh_Hant'], 'path' => ['prefixes' => ['zh_Hant' => 'tw']]]);

    expect($catalog->prefix('pt_BR'))->toBe('pt-br')
        ->and($catalog->prefix('zh_Hant'))->toBe('tw')
        ->and($catalog->routePrefix('en'))->toBe('')
        ->and(catalog(['path' => ['hide_default' => false]])->routePrefix('en'))->toBe('en');
});

test('it derives direction from the script or language never the region', function () {
    $catalog = catalog(['supported' => ['en'], 'locales' => ['ku' => ['direction' => 'rtl']]]);

    expect($catalog->direction('ar'))->toBe('rtl')
        ->and($catalog->direction('ar_EG'))->toBe('rtl')
        ->and($catalog->direction('fa'))->toBe('rtl')
        ->and($catalog->direction('he'))->toBe('rtl')
        ->and($catalog->direction('en_EG'))->toBe('ltr')
        ->and($catalog->direction('fr_MA'))->toBe('ltr')
        ->and($catalog->direction('uz_Arab'))->toBe('rtl')
        ->and($catalog->direction('sd_Deva'))->toBe('ltr')
        ->and($catalog->direction('ku'))->toBe('rtl')
        ->and($catalog->direction('ku-TR'))->toBe('ltr');
});

test('it uses configured names then ext intl then the code', function () {
    $catalog = catalog(['supported' => ['en', 'ar'], 'locales' => ['ar' => ['name' => 'Arabic', 'native' => 'العربية']]]);

    expect($catalog->name('ar'))->toBe('Arabic')
        ->and($catalog->nativeName('ar'))->toBe('العربية')
        ->and($catalog->name('ar-EG'))->toBe('Arabic (Egypt)')
        ->and($catalog->nativeName('ar-EG'))->toBe('العربية (مصر)');
});

test('it keeps custom metadata next to the resolved name native and direction', function () {
    $catalog = catalog(['supported' => ['en', 'pt_BR'], 'locales' => [
        'pt-br' => ['native' => 'Português', 'flag' => '/flags/br.svg', 'currency' => ['code' => 'BRL']],
    ]]);

    expect($catalog->all('pt_BR'))->toBe([
        'name' => 'Portuguese (Brazil)',
        'native' => 'Português',
        'direction' => 'ltr',
        'flag' => '/flags/br.svg',
        'currency' => ['code' => 'BRL'],
    ])->and($catalog->all('en'))->toBe(['name' => 'English', 'native' => 'English', 'direction' => 'ltr']);
});
