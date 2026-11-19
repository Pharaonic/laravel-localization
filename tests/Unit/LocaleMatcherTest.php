<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Unit;

use InvalidArgumentException;
use Pharaonic\Laravel\Localization\Support\LocaleMatcher;
use Pharaonic\Laravel\Localization\Tests\TestCase;

final class LocaleMatcherTest extends TestCase
{
    /**
     * @dataProvider normalizedCodes
     */
    public function test_it_normalizes_locale_codes_to_bcp_47(string $input, string $expected): void
    {
        $this->assertSame($expected, LocaleMatcher::normalize($input));
    }

    /**
     * @return list<array{string, string}>
     */
    public static function normalizedCodes(): array
    {
        return [
            ['ar', 'ar'],
            ['AR', 'ar'],
            ['ar_EG', 'ar-EG'],
            ['ar-eg', 'ar-EG'],
            ['pt_br', 'pt-BR'],
            ['zh_hant_tw', 'zh-Hant-TW'],
            ['es-419', 'es-419'],
            ['de-CH-1996', 'de-CH-1996'],
        ];
    }

    /**
     * @dataProvider malformedCodes
     */
    public function test_it_rejects_malformed_locale_codes(string $input): void
    {
        $this->assertFalse(LocaleMatcher::isValid($input));

        $this->expectException(InvalidArgumentException::class);

        LocaleMatcher::normalize($input);
    }

    /**
     * @return list<array{string}>
     */
    public static function malformedCodes(): array
    {
        return array_map(static fn (string $code): array => [$code], [
            '', 'a', 'english', 'ar EG', 'ar--EG', '../ar', 'ar/EG', '%61r', '*', 'ar-', "ar\0", 'ar;q=1', 'ع', str_repeat('a', 65),
        ]);
    }

    public function test_it_matches_exactly_then_the_parent_then_the_first_configured_variant(): void
    {
        $matcher = new LocaleMatcher(['en', 'ar', 'pt_BR', 'pt_PT', 'zh_Hant']);

        $this->assertSame('en', $matcher->match('en'));
        $this->assertSame('en', $matcher->match('EN'));
        $this->assertSame('ar', $matcher->match('ar-EG'));
        $this->assertSame('ar', $matcher->match('ar_EG'));
        $this->assertSame('pt_BR', $matcher->match('pt-br'));
        $this->assertSame('pt_PT', $matcher->match('pt_PT'));
        $this->assertSame('pt_BR', $matcher->match('pt'));
        $this->assertSame('pt_BR', $matcher->match('pt-AO'));
        $this->assertSame('zh_Hant', $matcher->match('zh-Hant-TW'));
        $this->assertSame('zh_Hant', $matcher->match('zh-CN'));
        $this->assertNull($matcher->match('fr'));
        $this->assertNull($matcher->match('not a locale'));
    }

    public function test_it_only_matches_exact_tags_with_exact(): void
    {
        $matcher = new LocaleMatcher(['en', 'pt_BR']);

        $this->assertSame('pt_BR', $matcher->exact('pt-br'));
        $this->assertNull($matcher->exact('en-US'));
        $this->assertNull($matcher->exact('pt'));
        $this->assertNull($matcher->exact('???'));
    }

    public function test_it_parses_accept_language_by_quality_then_position(): void
    {
        $this->assertSame(
            ['ar-eg' => 1.0, 'en' => 0.9, 'ar' => 0.9, 'fr' => 0.5, '*' => 0.1],
            LocaleMatcher::parseAcceptLanguage('fr;q=0.5, ar-EG, en;q=0.9, ar;q=0.9, *;q=0.1')
        );
    }

    public function test_it_skips_malformed_accept_language_entries_and_keeps_the_first_of_duplicates(): void
    {
        $this->assertSame(
            ['ar' => 1.0, 'de' => 0.7],
            LocaleMatcher::parseAcceptLanguage('en;q=2, en;q=abc, fr;q=0.8;x=1, , ;q=1, ar, ar;q=0.1, de;q=0.70')
        );
    }

    public function test_it_bounds_accept_language_parsing(): void
    {
        $header = implode(',', array_fill(0, 1000, 'xx-YY;q=0.5')).',ar';

        $this->assertSame(['xx-yy' => 0.5], LocaleMatcher::parseAcceptLanguage($header));
        $this->assertNull((new LocaleMatcher(['en', 'ar']))->negotiate($header, 'en'));

        $many = implode(',', array_map(static fn (int $i): string => 'l'.chr(97 + intdiv($i, 26) % 26).chr(97 + $i % 26), range(0, 40))).',ar';

        $this->assertCount(LocaleMatcher::MAX_HEADER_ENTRIES, LocaleMatcher::parseAcceptLanguage($many));
    }

    /**
     * @dataProvider negotiations
     */
    public function test_it_negotiates_accept_language_against_the_supported_locales(string $header, ?string $expected): void
    {
        $this->assertSame($expected, (new LocaleMatcher(['en', 'ar', 'fr']))->negotiate($header, 'en'));
    }

    /**
     * @return array<string, array{string, ?string}>
     */
    public static function negotiations(): array
    {
        return [
            'regional range' => ['ar-EG,ar;q=0.9,en;q=0.8', 'ar'],
            'weights' => ['en;q=0.2, fr;q=0.7', 'fr'],
            'unsupported only' => ['de, ja', null],
            'q=0 excludes' => ['ar;q=0, fr;q=0.5', 'fr'],
            'q=0 excludes subtags' => ['ar-EG, ar;q=0', null],
            'wildcard accepts the default' => ['de, *;q=0.5', 'en'],
            'wildcard skips rejected default' => ['*, en;q=0', 'ar'],
            'all rejected' => ['*;q=0', null],
            'garbage' => ['@@@, ;;;, =', null],
            'empty' => ['', null],
        ];
    }
}
