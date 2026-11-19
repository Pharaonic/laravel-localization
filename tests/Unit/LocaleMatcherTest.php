<?php

declare(strict_types=1);

use Pharaonic\Laravel\Localization\Support\LocaleMatcher;

test('it normalizes locale codes to bcp 47', function (string $input, string $expected) {
    expect(LocaleMatcher::normalize($input))->toBe($expected);
})->with([
    ['ar', 'ar'],
    ['AR', 'ar'],
    ['ar_EG', 'ar-EG'],
    ['ar-eg', 'ar-EG'],
    ['pt_br', 'pt-BR'],
    ['zh_hant_tw', 'zh-Hant-TW'],
    ['es-419', 'es-419'],
    ['de-CH-1996', 'de-CH-1996'],
]);

test('it rejects malformed locale codes', function (string $input) {
    expect(LocaleMatcher::isValid($input))->toBeFalse()
        ->and(fn () => LocaleMatcher::normalize($input))->toThrow(InvalidArgumentException::class);
})->with([
    '', 'a', 'english', 'ar EG', 'ar--EG', '../ar', 'ar/EG', '%61r', '*', 'ar-', "ar\0", 'ar;q=1', 'ع', str_repeat('a', 65),
]);

test('it matches exactly then the parent then the first configured variant', function () {
    $matcher = new LocaleMatcher(['en', 'ar', 'pt_BR', 'pt_PT', 'zh_Hant']);

    expect($matcher->match('en'))->toBe('en')
        ->and($matcher->match('EN'))->toBe('en')
        ->and($matcher->match('ar-EG'))->toBe('ar')
        ->and($matcher->match('ar_EG'))->toBe('ar')
        ->and($matcher->match('pt-br'))->toBe('pt_BR')
        ->and($matcher->match('pt_PT'))->toBe('pt_PT')
        ->and($matcher->match('pt'))->toBe('pt_BR')
        ->and($matcher->match('pt-AO'))->toBe('pt_BR')
        ->and($matcher->match('zh-Hant-TW'))->toBe('zh_Hant')
        ->and($matcher->match('zh-CN'))->toBe('zh_Hant')
        ->and($matcher->match('fr'))->toBeNull()
        ->and($matcher->match('not a locale'))->toBeNull();
});

test('it only matches exact tags with exact', function () {
    $matcher = new LocaleMatcher(['en', 'pt_BR']);

    expect($matcher->exact('pt-br'))->toBe('pt_BR')
        ->and($matcher->exact('en-US'))->toBeNull()
        ->and($matcher->exact('pt'))->toBeNull()
        ->and($matcher->exact('???'))->toBeNull();
});

test('it parses accept language by quality then position', function () {
    expect(LocaleMatcher::parseAcceptLanguage('fr;q=0.5, ar-EG, en;q=0.9, ar;q=0.9, *;q=0.1'))
        ->toBe(['ar-eg' => 1.0, 'en' => 0.9, 'ar' => 0.9, 'fr' => 0.5, '*' => 0.1]);
});

test('it skips malformed accept language entries and keeps the first of duplicates', function () {
    expect(LocaleMatcher::parseAcceptLanguage('en;q=2, en;q=abc, fr;q=0.8;x=1, , ;q=1, ar, ar;q=0.1, de;q=0.70'))
        ->toBe(['ar' => 1.0, 'de' => 0.7]);
});

test('it bounds accept language parsing', function () {
    $header = implode(',', array_fill(0, 1000, 'xx-YY;q=0.5')).',ar';
    $many = implode(',', array_map(static fn (int $i): string => 'l'.chr(97 + intdiv($i, 26) % 26).chr(97 + $i % 26), range(0, 40))).',ar';

    expect(LocaleMatcher::parseAcceptLanguage($header))->toBe(['xx-yy' => 0.5])
        ->and((new LocaleMatcher(['en', 'ar']))->negotiate($header, 'en'))->toBeNull()
        ->and(LocaleMatcher::parseAcceptLanguage($many))->toHaveCount(LocaleMatcher::MAX_HEADER_ENTRIES);
});

test('it negotiates accept language against the supported locales', function (string $header, ?string $expected) {
    expect((new LocaleMatcher(['en', 'ar', 'fr']))->negotiate($header, 'en'))->toBe($expected);
})->with([
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
]);
