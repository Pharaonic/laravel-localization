<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Support;

use InvalidArgumentException;

/**
 * Normalizes locale codes and matches them against the supported locales.
 *
 * Matching returns the exact configured code, in this order: the same tag
 * (case and `-`/`_` insensitive), then its parents (`ar-EG` → `ar`), then the
 * first configured variant of the same language (`pt` → `pt_BR`).
 *
 * @internal
 */
final class LocaleMatcher
{
    /** Longest `Accept-Language` value that is parsed; the rest is ignored. */
    public const MAX_HEADER_LENGTH = 4096;

    /** Most `Accept-Language` entries that are considered. */
    public const MAX_HEADER_ENTRIES = 32;

    private const PATTERN = '/^(?<language>[a-z]{2,3})(?:[-_](?<script>[a-z]{4}))?(?:[-_](?<region>[a-z]{2}|\d{3}))?(?<variants>(?:[-_](?:[a-z\d]{5,8}|\d[a-z\d]{3}))*)$/i';

    /** @var array<string, string> lowercase BCP 47 tag => configured code */
    private array $byTag = [];

    /** @var array<string, list<string>> language => configured codes, in configured order */
    private array $byLanguage = [];

    /**
     * @param  list<string>  $supported  valid, unique locale codes
     */
    public function __construct(private array $supported)
    {
        foreach ($supported as $code) {
            $tag = strtolower(self::normalize($code));
            $this->byTag[$tag] = $code;
            $this->byLanguage[explode('-', $tag)[0]][] = $code;
        }
    }

    /**
     * Whether the value is a well-formed locale code (`ar`, `ar_EG`, `zh-Hant-TW`).
     */
    public static function isValid(string $locale): bool
    {
        return strlen($locale) <= 64 && preg_match(self::PATTERN, $locale) === 1;
    }

    /**
     * The BCP 47 form of a locale code: `pt_br` → `pt-BR`, `zh_hant_tw` → `zh-Hant-TW`.
     *
     * @throws InvalidArgumentException when the code is malformed
     */
    public static function normalize(string $locale): string
    {
        if (strlen($locale) > 64 || preg_match(self::PATTERN, $locale, $parts) !== 1) {
            throw new InvalidArgumentException(sprintf('[%s] is not a valid locale code.', mb_substr($locale, 0, 64)));
        }

        $tag = strtolower($parts['language']);

        if ($parts['script'] !== '') {
            $tag .= '-'.ucfirst(strtolower($parts['script']));
        }

        if ($parts['region'] !== '') {
            $tag .= '-'.strtoupper($parts['region']);
        }

        if ($parts['variants'] !== '') {
            $tag .= strtolower(str_replace('_', '-', $parts['variants']));
        }

        return $tag;
    }

    /**
     * The configured code with the same tag, without any fallback.
     */
    public function exact(string $locale): ?string
    {
        if (! self::isValid($locale)) {
            return null;
        }

        return $this->byTag[strtolower(self::normalize($locale))] ?? null;
    }

    /**
     * The configured code that best matches the value, or null.
     */
    public function match(string $locale): ?string
    {
        if (! self::isValid($locale)) {
            return null;
        }

        $subtags = explode('-', strtolower(self::normalize($locale)));

        for ($length = count($subtags); $length > 0; $length--) {
            $code = $this->byTag[implode('-', array_slice($subtags, 0, $length))] ?? null;

            if ($code !== null) {
                return $code;
            }
        }

        return $this->byLanguage[$subtags[0]][0] ?? null;
    }

    /**
     * Pick a configured code from an `Accept-Language` value (RFC 9110, section 12.5.4).
     *
     * Ranges are tried by descending quality, then by position. `q=0` makes a
     * range unacceptable, `*` accepts the preferred locale (or the first
     * acceptable one), malformed entries are skipped, and parsing is bounded.
     */
    public function negotiate(string $header, string $preferred): ?string
    {
        $ranges = self::parseAcceptLanguage($header);
        $rejected = [];

        foreach ($ranges as $range => $quality) {
            if ($quality === 0.0) {
                $rejected[] = $range;
            }
        }

        $acceptable = function (string $code) use ($rejected): bool {
            $tag = strtolower(self::normalize($code));

            foreach ($rejected as $range) {
                if ($range === '*' || $tag === $range || str_starts_with($tag, $range.'-')) {
                    return false;
                }
            }

            return true;
        };

        foreach ($ranges as $range => $quality) {
            if ($quality === 0.0) {
                continue;
            }

            if ($range === '*') {
                foreach (array_merge([$preferred], $this->supported) as $code) {
                    if ($this->exact($code) !== null && $acceptable($code)) {
                        return $code;
                    }
                }

                return null;
            }

            $code = $this->match($range);

            if ($code !== null && $acceptable($code)) {
                return $code;
            }
        }

        return null;
    }

    /**
     * The language ranges of an `Accept-Language` value with their quality,
     * best first; a repeated range keeps its first quality.
     *
     * @return array<string, float> lowercase BCP 47 range (or `*`) => quality
     */
    public static function parseAcceptLanguage(string $header): array
    {
        $entries = [];
        $position = 0;

        foreach (explode(',', substr($header, 0, self::MAX_HEADER_LENGTH)) as $entry) {
            if ($position >= self::MAX_HEADER_ENTRIES) {
                break;
            }

            $params = explode(';', $entry);
            $range = trim(array_shift($params));
            $quality = 1.0;

            foreach ($params as $param) {
                if (preg_match('/^\s*q\s*=\s*(0(?:\.\d{0,3})?|1(?:\.0{0,3})?)\s*$/i', $param, $match) !== 1) {
                    continue 2;
                }

                $quality = (float) $match[1];
            }

            if ($range !== '*') {
                if (! self::isValid($range)) {
                    continue;
                }

                $range = strtolower(self::normalize($range));
            }

            if (! isset($entries[$range])) {
                $entries[$range] = [$quality, $position++];
            }
        }

        uasort($entries, fn (array $a, array $b): int => [$b[0], $a[1]] <=> [$a[0], $b[1]]);

        return array_map(fn (array $entry): float => $entry[0], $entries);
    }
}
