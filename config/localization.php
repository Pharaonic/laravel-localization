<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Supported locales
    |--------------------------------------------------------------------------
    |
    | The locales that localized routes are registered for and that negotiation
    | may select, e.g. ['en', 'ar', 'pt_BR']. The default locale is Laravel's
    | `app.locale` and must be in this list; translation fallback stays with
    | `app.fallback_locale`. `null` supports only `app.locale`.
    |
    */

    'supported' => null,

    /*
    |--------------------------------------------------------------------------
    | Path mode (Route::localized)
    |--------------------------------------------------------------------------
    |
    | hide_default:       the default locale lives at unprefixed URLs (/products).
    | canonical_redirect: with hide_default, GET/HEAD /{default}/... redirects
    |                     (301) to the unprefixed URL; otherwise it is a 404.
    | prefixes:           URL prefix overrides, e.g. ['pt_BR' => 'br']. By default
    |                     a locale's prefix is its lowercase BCP 47 tag (pt-br).
    |
    */

    'path' => [
        'hide_default' => true,
        'canonical_redirect' => true,
        'prefixes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resolver profiles
    |--------------------------------------------------------------------------
    |
    | Ordered sources of a locale preference. The first supported result wins;
    | without one, `app.locale` is used. Entries are the built-in `user`,
    | `session`, `cookie`, `header`, or a class implementing
    | Pharaonic\Laravel\Localization\Contracts\LocaleResolver.
    |
    | `localization:{profile}` middleware negotiates with a profile (`api` when
    | none is given). A path-localized URL always wins over every profile; the
    | `web` profile only feeds locale()->preferred() on those pages.
    |
    */

    'resolvers' => [
        'web' => ['user', 'session', 'cookie', 'header'],
        'api' => ['user', 'header'],
    ],

    'session' => [
        'key' => 'locale',
    ],

    'cookie' => [
        'key' => 'locale',
        'minutes' => 60 * 24 * 365,
        'same_site' => 'lax',
        'secure' => null,
    ],

    'header' => [
        'name' => 'Accept-Language',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale metadata
    |--------------------------------------------------------------------------
    |
    | Optional overrides, e.g. 'ar' => ['name' => 'Arabic', 'native' => 'العربية',
    | 'direction' => 'rtl']. Without them, names come from ext-intl (ICU) and
    | direction from the script, then the language. Any other key, such as
    | 'flag' => '/flags/eg.svg', is custom data read with locale()->meta('flag').
    |
    */

    'locales' => [],

];
