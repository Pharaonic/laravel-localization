<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Unit;

use Illuminate\Config\Repository;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;
use Pharaonic\Laravel\Localization\Tests\TestCase;
use stdClass;

final class LocaleCatalogTest extends TestCase
{
    /**
     * @param  array<string, mixed>  $settings
     */
    private static function catalog(array $settings = [], string $locale = 'en', string $fallback = 'fr'): LocaleCatalog
    {
        $config = new Repository([
            'app' => ['locale' => $locale, 'fallback_locale' => $fallback],
            'pharaonic' => ['localization' => array_replace(require __DIR__.'/../../config/localization.php', $settings)],
        ]);

        return LocaleCatalog::fromConfig($config);
    }

    public function test_it_takes_the_default_locale_from_app_locale_never_from_app_fallback_locale(): void
    {
        $catalog = self::catalog(['supported' => ['en', 'ar']], 'ar', 'en');

        $this->assertSame('ar', $catalog->default());
        $this->assertSame(['en', 'ar'], $catalog->supported());
    }

    public function test_it_supports_only_app_locale_when_supported_is_null(): void
    {
        $this->assertSame(['fr'], self::catalog(['supported' => null], 'fr')->supported());
    }

    public function test_it_does_not_define_default_or_fallback_keys(): void
    {
        $config = require __DIR__.'/../../config/localization.php';

        foreach (['default', 'fallback', 'fallback_locale', 'type'] as $key) {
            $this->assertArrayNotHasKey($key, $config);
        }
    }

    /**
     * @dataProvider invalidConfigurations
     *
     * @param  array<string, mixed>  $settings
     */
    public function test_it_fails_fast_on_invalid_configuration(array $settings, string $message, string $locale = 'en'): void
    {
        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage($message);

        self::catalog($settings, $locale);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function invalidConfigurations(): array
    {
        return [
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
        ];
    }

    public function test_it_rejects_the_1x_configuration_file_with_a_migration_hint(): void
    {
        $config = new Repository([
            'app' => ['locale' => 'en'],
            'pharaonic' => ['localization' => require __DIR__.'/../../config/localization.php'],
            'Pharaonic' => ['localization' => ['type' => 'sub-directory', 'default' => 'en', 'supported' => ['en']]],
        ]);

        $this->expectException(InvalidLocalizationConfigurationException::class);
        $this->expectExceptionMessage('config/Pharaonic/localization.php is the 1.x configuration');

        LocaleCatalog::fromConfig($config);
    }

    public function test_it_reads_the_new_file_when_a_case_insensitive_filesystem_loads_it_as_pharaonic(): void
    {
        $config = new Repository([
            'app' => ['locale' => 'en'],
            'pharaonic' => ['localization' => require __DIR__.'/../../config/localization.php'],
            'Pharaonic' => ['localization' => ['supported' => ['en', 'ar']]],
        ]);

        $this->assertSame(['en', 'ar'], LocaleCatalog::fromConfig($config)->supported());
    }

    public function test_it_derives_url_prefixes_from_bcp_47_tags_with_overrides(): void
    {
        $catalog = self::catalog(['supported' => ['en', 'pt_BR', 'zh_Hant'], 'path' => ['prefixes' => ['zh_Hant' => 'tw']]]);

        $this->assertSame('pt-br', $catalog->prefix('pt_BR'));
        $this->assertSame('tw', $catalog->prefix('zh_Hant'));
        $this->assertSame('', $catalog->routePrefix('en'));
        $this->assertSame('en', self::catalog(['path' => ['hide_default' => false]])->routePrefix('en'));
    }

    public function test_it_derives_direction_from_the_script_or_language_never_the_region(): void
    {
        $catalog = self::catalog(['supported' => ['en'], 'locales' => ['ku' => ['direction' => 'rtl']]]);

        $this->assertSame('rtl', $catalog->direction('ar'));
        $this->assertSame('rtl', $catalog->direction('ar_EG'));
        $this->assertSame('rtl', $catalog->direction('fa'));
        $this->assertSame('rtl', $catalog->direction('he'));
        $this->assertSame('ltr', $catalog->direction('en_EG'));
        $this->assertSame('ltr', $catalog->direction('fr_MA'));
        $this->assertSame('rtl', $catalog->direction('uz_Arab'));
        $this->assertSame('ltr', $catalog->direction('sd_Deva'));
        $this->assertSame('rtl', $catalog->direction('ku'));
        $this->assertSame('ltr', $catalog->direction('ku-TR'));
    }

    public function test_it_uses_configured_names_then_ext_intl_then_the_code(): void
    {
        $catalog = self::catalog(['supported' => ['en', 'ar'], 'locales' => ['ar' => ['name' => 'Arabic', 'native' => 'العربية']]]);

        $this->assertSame('Arabic', $catalog->name('ar'));
        $this->assertSame('العربية', $catalog->nativeName('ar'));
        $this->assertSame('Arabic (Egypt)', $catalog->name('ar-EG'));
        $this->assertSame('العربية (مصر)', $catalog->nativeName('ar-EG'));
    }

    public function test_it_keeps_custom_metadata_next_to_the_resolved_name_native_and_direction(): void
    {
        $catalog = self::catalog(['supported' => ['en', 'pt_BR'], 'locales' => [
            'pt-br' => ['native' => 'Português', 'flag' => '/flags/br.svg', 'currency' => ['code' => 'BRL']],
        ]]);

        $this->assertSame([
            'name' => 'Portuguese (Brazil)',
            'native' => 'Português',
            'direction' => 'ltr',
            'flag' => '/flags/br.svg',
            'currency' => ['code' => 'BRL'],
        ], $catalog->all('pt_BR'));
        $this->assertSame(['name' => 'English', 'native' => 'English', 'direction' => 'ltr'], $catalog->all('en'));
    }
}
