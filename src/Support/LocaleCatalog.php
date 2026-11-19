<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Support;

use Illuminate\Contracts\Config\Repository;
use Locale;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;

/**
 * The validated localization configuration of the application: built once,
 * never changed, and free of request state.
 *
 * @internal
 */
final class LocaleCatalog
{
    public const BUILT_IN_RESOLVERS = ['user', 'session', 'cookie', 'header'];

    private const LEGACY_KEYS = ['type', 'default', 'hide-default', 'force-redirect', 'list', 'ccTLDs-list'];

    private const RTL_SCRIPTS = ['Adlm', 'Arab', 'Hebr', 'Mand', 'Mend', 'Nkoo', 'Rohg', 'Samr', 'Syrc', 'Thaa'];

    private const RTL_LANGUAGES = [
        'ar', 'arc', 'azb', 'ckb', 'dv', 'fa', 'glk', 'he', 'ks', 'lrc', 'mzn',
        'nqo', 'pnb', 'prs', 'ps', 'sd', 'syr', 'ug', 'ur', 'yi',
    ];

    private LocaleMatcher $matcher;

    /**
     * @param  list<string>  $supported
     * @param  array<string, string>  $prefixes  locale => URL prefix
     * @param  array<string, list<string>>  $profiles
     * @param  array{key: string}  $session
     * @param  array{key: string, minutes: int, same_site: string|null, secure: bool|null}  $cookie
     * @param  array<string, array{name?: string, native?: string, direction?: string}>  $locales
     * @param  array<string, array<string, mixed>>  $extra  locale => custom metadata
     */
    private function __construct(
        private array $supported,
        private string $default,
        private bool $hideDefault,
        private bool $canonicalRedirect,
        private array $prefixes,
        private array $profiles,
        private array $session,
        private array $cookie,
        private string $header,
        private array $locales,
        private array $extra,
    ) {
        $this->matcher = new LocaleMatcher($supported);
    }

    /**
     * Build the catalog from `pharaonic.localization` and Laravel's `app.locale`
     * (or the `app.locale` value read before the application could change it).
     *
     * @throws InvalidLocalizationConfigurationException
     */
    public static function fromConfig(Repository $config, mixed $default = null): self
    {
        $settings = $config->get('pharaonic.localization', []);
        $settings = is_array($settings) ? $settings : [];
        $legacy = $config->get('Pharaonic.localization');

        if (is_array($legacy)) {
            if (array_intersect(self::LEGACY_KEYS, array_keys($legacy)) !== []) {
                throw new InvalidLocalizationConfigurationException(
                    'config/Pharaonic/localization.php is the 1.x configuration, which this version no longer reads. '
                    .'Delete it, publish the new file with `php artisan vendor:publish --tag=localization-config`, and follow UPGRADING.md.'
                );
            }

            // A case-insensitive filesystem loads config/pharaonic as "Pharaonic".
            $settings = array_replace($settings, $legacy);
        }

        $default ??= $config->get('app.locale');

        if (! is_string($default) || ! LocaleMatcher::isValid($default)) {
            throw new InvalidLocalizationConfigurationException('app.locale must be a valid locale code, e.g. "en".');
        }

        $supported = self::parseSupported($settings['supported'] ?? null, $default);
        $path = self::section($settings, 'path');
        $session = self::section($settings, 'session');
        $cookie = self::section($settings, 'cookie');
        $header = self::section($settings, 'header');

        return new self(
            $supported,
            $default,
            self::boolean($path, 'hide_default', true, 'path.hide_default'),
            self::boolean($path, 'canonical_redirect', true, 'path.canonical_redirect'),
            self::parsePrefixes($path['prefixes'] ?? [], $supported),
            self::parseProfiles($settings['resolvers'] ?? []),
            ['key' => self::parseName($session, 'key', 'locale', 'session.key')],
            [
                'key' => self::parseName($cookie, 'key', 'locale', 'cookie.key'),
                'minutes' => self::minutes($cookie['minutes'] ?? 525600),
                'same_site' => self::sameSite($cookie['same_site'] ?? 'lax'),
                'secure' => self::nullableBoolean($cookie['secure'] ?? null, 'cookie.secure'),
            ],
            self::parseHeaderName($header['name'] ?? 'Accept-Language'),
            ...self::parseLocales($settings['locales'] ?? []),
        );
    }

    /**
     * @return list<string>
     */
    public function supported(): array
    {
        return $this->supported;
    }

    public function default(): string
    {
        return $this->default;
    }

    public function matcher(): LocaleMatcher
    {
        return $this->matcher;
    }

    public function hidesDefault(): bool
    {
        return $this->hideDefault;
    }

    public function redirectsCanonically(): bool
    {
        return $this->hideDefault && $this->canonicalRedirect;
    }

    /**
     * The URL prefix of a supported locale.
     */
    public function prefix(string $locale): string
    {
        return $this->prefixes[$locale];
    }

    /**
     * The URL prefix a supported locale's routes are registered under ('' for a hidden default).
     */
    public function routePrefix(string $locale): string
    {
        return $locale === $this->default && $this->hideDefault ? '' : $this->prefixes[$locale];
    }

    /**
     * @return array<string, list<string>>
     */
    public function profiles(): array
    {
        return $this->profiles;
    }

    /**
     * @return list<string>
     *
     * @throws InvalidLocalizationConfigurationException
     */
    public function profile(string $name): array
    {
        if (! isset($this->profiles[$name])) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'Unknown resolver profile [%s]; configured profiles: %s.', $name, implode(', ', array_keys($this->profiles)) ?: 'none'
            ));
        }

        return $this->profiles[$name];
    }

    public function sessionKey(): string
    {
        return $this->session['key'];
    }

    /**
     * @return array{key: string, minutes: int, same_site: string|null, secure: bool|null}
     */
    public function cookie(): array
    {
        return $this->cookie;
    }

    public function headerName(): string
    {
        return $this->header;
    }

    /**
     * The display name of a locale in English, e.g. `Arabic`.
     */
    public function name(string $locale): string
    {
        return $this->metadata($locale)['name'] ?? (self::displayName($locale, 'en') ?: $locale);
    }

    /**
     * The display name of a locale in itself, e.g. `العربية`.
     */
    public function nativeName(string $locale): string
    {
        return $this->metadata($locale)['native'] ?? (self::displayName($locale, $locale) ?: $locale);
    }

    /**
     * `rtl` or `ltr`: configured, or derived from the script, then the language (never the region).
     */
    public function direction(string $locale): string
    {
        $configured = $this->metadata($locale)['direction'] ?? null;

        if ($configured !== null) {
            return $configured;
        }

        $subtags = explode('-', LocaleMatcher::normalize($locale));

        if (isset($subtags[1]) && strlen($subtags[1]) === 4) {
            return in_array($subtags[1], self::RTL_SCRIPTS, true) ? 'rtl' : 'ltr';
        }

        return in_array($subtags[0], self::RTL_LANGUAGES, true) ? 'rtl' : 'ltr';
    }

    /**
     * The resolved name, native and direction of a locale, with its custom metadata.
     *
     * @return array<string, mixed>
     */
    public function all(string $locale): array
    {
        $code = $this->configuredCode($locale);

        return [
            'name' => $this->name($locale),
            'native' => $this->nativeName($locale),
            'direction' => $this->direction($locale),
        ] + ($code !== null ? $this->extra[$code] : []);
    }

    /**
     * @return array{name?: string, native?: string, direction?: string}
     */
    private function metadata(string $locale): array
    {
        $code = $this->configuredCode($locale);

        return $code !== null ? $this->locales[$code] : [];
    }

    /**
     * The `locales` key configured for a locale, matched like supported codes.
     */
    private function configuredCode(string $locale): ?string
    {
        if (isset($this->locales[$locale])) {
            return $locale;
        }

        $tag = strtolower(LocaleMatcher::normalize($locale));

        foreach (array_keys($this->locales) as $code) {
            if (strtolower(LocaleMatcher::normalize($code)) === $tag) {
                return $code;
            }
        }

        return null;
    }

    /**
     * The ICU display name, or an empty string when ICU has none.
     */
    private static function displayName(string $locale, string $in): string
    {
        $name = Locale::getDisplayName(LocaleMatcher::normalize($locale), LocaleMatcher::normalize($in));

        return is_string($name) ? $name : '';
    }

    /**
     * array_is_list() needs PHP 8.1.
     *
     * @param  array<mixed>  $value
     */
    private static function isList(array $value): bool
    {
        return $value === [] || array_keys($value) === range(0, count($value) - 1);
    }

    /**
     * @return list<string>
     */
    private static function parseSupported(mixed $value, string $default): array
    {
        if ($value === null) {
            return [$default];
        }

        if (! is_array($value) || $value === [] || ! self::isList($value)) {
            throw new InvalidLocalizationConfigurationException('localization.supported must be a non-empty list of locale codes, or null.');
        }

        $tags = [];

        foreach ($value as $code) {
            if (! is_string($code) || ! LocaleMatcher::isValid($code)) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'localization.supported contains [%s], which is not a valid locale code.', is_scalar($code) ? (string) $code : get_debug_type($code)
                ));
            }

            $tag = strtolower(LocaleMatcher::normalize($code));

            if (isset($tags[$tag])) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'localization.supported lists [%s] and [%s], which are the same locale.', $tags[$tag], $code
                ));
            }

            $tags[$tag] = $code;
        }

        if (! in_array($default, $value, true)) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'app.locale [%s] is not in localization.supported [%s]. Add it exactly as written in app.locale.', $default, implode(', ', $value)
            ));
        }

        /** @var list<string> $value */
        return $value;
    }

    /**
     * @param  list<string>  $supported
     * @return array<string, string>
     */
    private static function parsePrefixes(mixed $value, array $supported): array
    {
        if (! is_array($value)) {
            throw new InvalidLocalizationConfigurationException('localization.path.prefixes must be an array of locale => prefix.');
        }

        $prefixes = [];

        foreach ($supported as $code) {
            $prefixes[$code] = strtolower(LocaleMatcher::normalize($code));
        }

        foreach ($value as $code => $prefix) {
            if (! is_string($code) || ! in_array($code, $supported, true)) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'localization.path.prefixes has a prefix for [%s], which is not a supported locale.', $code
                ));
            }

            if (! is_string($prefix) || preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $prefix) !== 1) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'localization.path.prefixes.%s must be lowercase letters, digits and single hyphens, e.g. "pt-br".', $code
                ));
            }

            $prefixes[$code] = $prefix;
        }

        $owners = [];

        foreach ($prefixes as $code => $prefix) {
            if (isset($owners[$prefix])) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'Locales [%s] and [%s] share the URL prefix [%s].', $owners[$prefix], $code, $prefix
                ));
            }

            $owners[$prefix] = $code;
        }

        return $prefixes;
    }

    /**
     * @return array<string, list<string>>
     */
    private static function parseProfiles(mixed $value): array
    {
        if (! is_array($value)) {
            throw new InvalidLocalizationConfigurationException('localization.resolvers must be an array of profile => resolver list.');
        }

        $profiles = [];

        foreach ($value as $name => $resolvers) {
            if (! is_string($name) || $name === '' || ! is_array($resolvers) || ! self::isList($resolvers)) {
                throw new InvalidLocalizationConfigurationException('Each localization.resolvers entry must be a named list of resolvers.');
            }

            $list = [];

            foreach ($resolvers as $resolver) {
                if (! is_string($resolver)
                    || (! in_array($resolver, self::BUILT_IN_RESOLVERS, true) && ! is_subclass_of($resolver, LocaleResolver::class))) {
                    throw new InvalidLocalizationConfigurationException(sprintf(
                        'localization.resolvers.%s has [%s]; use %s or a class implementing %s.',
                        $name,
                        is_string($resolver) ? $resolver : get_debug_type($resolver),
                        implode(', ', self::BUILT_IN_RESOLVERS),
                        LocaleResolver::class,
                    ));
                }

                if (in_array($resolver, $list, true)) {
                    throw new InvalidLocalizationConfigurationException(sprintf('localization.resolvers.%s lists a resolver twice.', $name));
                }

                $list[] = $resolver;
            }

            $profiles[$name] = $list;
        }

        return $profiles;
    }

    /**
     * @return array{array<string, array{name?: string, native?: string, direction?: string}>, array<string, array<string, mixed>>}
     */
    private static function parseLocales(mixed $value): array
    {
        if (! is_array($value)) {
            throw new InvalidLocalizationConfigurationException('localization.locales must be an array of locale => metadata.');
        }

        $locales = [];
        $extra = [];
        $tags = [];

        foreach ($value as $code => $metadata) {
            if (! is_string($code) || ! LocaleMatcher::isValid($code) || ! is_array($metadata)) {
                throw new InvalidLocalizationConfigurationException('localization.locales must map locale codes to metadata arrays.');
            }

            $tag = strtolower(LocaleMatcher::normalize($code));

            if (isset($tags[$tag])) {
                throw new InvalidLocalizationConfigurationException(sprintf(
                    'localization.locales lists [%s] and [%s], which are the same locale.', $tags[$tag], $code
                ));
            }

            $tags[$tag] = $code;
            $entry = [];
            $custom = [];

            foreach ($metadata as $key => $item) {
                if (! is_string($key) || $key === '') {
                    throw new InvalidLocalizationConfigurationException(sprintf(
                        'localization.locales.%s must use non-empty string keys.', $code
                    ));
                }

                if (! in_array($key, ['name', 'native', 'direction'], true)) {
                    $custom[$key] = $item;

                    continue;
                }

                if (! is_string($item) || $item === '' || ($key === 'direction' && ! in_array($item, ['ltr', 'rtl'], true))) {
                    throw new InvalidLocalizationConfigurationException(sprintf(
                        'localization.locales.%s.%s must be %s.', $code, $key, $key === 'direction' ? 'ltr or rtl' : 'a non-empty string'
                    ));
                }

                $entry[$key] = $item;
            }

            $locales[$code] = $entry;
            $extra[$code] = $custom;
        }

        return [$locales, $extra];
    }

    /**
     * @param  array<array-key, mixed>  $settings
     * @return array<array-key, mixed>
     */
    private static function section(array $settings, string $key): array
    {
        $section = $settings[$key] ?? [];

        if (! is_array($section)) {
            throw new InvalidLocalizationConfigurationException(sprintf('localization.%s must be an array.', $key));
        }

        return $section;
    }

    /**
     * @param  array<array-key, mixed>  $section
     */
    private static function boolean(array $section, string $key, bool $default, string $path): bool
    {
        $value = $section[$key] ?? $default;

        if (! is_bool($value)) {
            throw new InvalidLocalizationConfigurationException(sprintf('localization.%s must be true or false.', $path));
        }

        return $value;
    }

    private static function nullableBoolean(mixed $value, string $path): ?bool
    {
        if ($value !== null && ! is_bool($value)) {
            throw new InvalidLocalizationConfigurationException(sprintf('localization.%s must be true, false or null.', $path));
        }

        return $value;
    }

    /**
     * @param  array<array-key, mixed>  $section
     */
    private static function parseName(array $section, string $key, string $default, string $path): string
    {
        $value = $section[$key] ?? $default;

        if (! is_string($value) || $value === '') {
            throw new InvalidLocalizationConfigurationException(sprintf('localization.%s must be a non-empty string.', $path));
        }

        return $value;
    }

    private static function minutes(mixed $value): int
    {
        if (! is_int($value) || $value < 1) {
            throw new InvalidLocalizationConfigurationException('localization.cookie.minutes must be a positive integer.');
        }

        return $value;
    }

    private static function sameSite(mixed $value): ?string
    {
        if ($value !== null && ! in_array($value, ['lax', 'strict', 'none'], true)) {
            throw new InvalidLocalizationConfigurationException('localization.cookie.same_site must be lax, strict, none or null.');
        }

        return $value;
    }

    private static function parseHeaderName(mixed $value): string
    {
        if (! is_string($value) || preg_match('/^[A-Za-z0-9!#$%&\'*+.^_`|~-]+$/', $value) !== 1) {
            throw new InvalidLocalizationConfigurationException('localization.header.name must be a valid HTTP header name.');
        }

        return $value;
    }
}
