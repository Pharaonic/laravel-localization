## Configuration

The configuration lives in `config/pharaonic/localization.php`. It holds only strings, booleans and arrays, so it works with `config:cache`. Every value is validated the first time the package needs it, with an exception that names the wrong key.

```php title="config/pharaonic/localization.php"
return [
    'supported' => ['en', 'ar', 'fr'],

    'path' => [
        'hide_default' => true,
        'canonical_redirect' => true,
        'prefixes' => [],
    ],

    'resolvers' => [
        'web' => ['user', 'session', 'cookie', 'header'],
        'api' => ['user', 'header'],
    ],

    'session' => ['key' => 'locale'],

    'cookie' => [
        'key' => 'locale',
        'minutes' => 60 * 24 * 365,
        'same_site' => 'lax',
        'secure' => null,
    ],

    'header' => ['name' => 'Accept-Language'],

    'locales' => [],
];
```

### Options

| Key | Default | Description |
| --- | --- | --- |
| `supported` | `null` | List of locale codes. `null` supports only `app.locale`. Must contain `app.locale` exactly as written there. |
| `path.hide_default` | `true` | The default locale lives at unprefixed URLs. With `false`, every locale has a prefix and `/products` is a 404. |
| `path.canonical_redirect` | `true` | With `hide_default`, `GET`/`HEAD` `/{default}/...` redirects (301) to the unprefixed URL. With `false`, it is a 404. |
| `path.prefixes` | `[]` | URL prefix overrides, e.g. `['pt_BR' => 'br']`. By default the prefix is the lowercase BCP 47 tag (`pt-br`). |
| `resolvers` | `web`, `api` | Named profiles: ordered lists of `user`, `session`, `cookie`, `header` or a resolver class name. |
| `session.key` | `locale` | Session key read by the `session` resolver and written by `remember()`. |
| `cookie.key` | `locale` | Cookie name read by the `cookie` resolver and written by `remember()`. |
| `cookie.minutes` | one year | Lifetime of a remembered cookie. |
| `cookie.same_site` | `lax` | `lax`, `strict`, `none` or `null`. |
| `cookie.secure` | `null` | `null` uses Laravel's `session.secure`. |
| `header.name` | `Accept-Language` | Header read by the `header` resolver. |
| `locales` | `[]` | Optional metadata per locale: `name`, `native`, `direction` (`ltr` or `rtl`), and any custom key. |

:::warning There is no default or fallback key
Set the default locale with `app.locale` in `config/app.php` and the translation fallback with `app.fallback_locale`. The package reads both from Laravel and never duplicates them.
:::

### Locale codes

Codes are matched without regard to case or `-`/`_`, but the package always returns the exact code you configured. `pt_BR`, `pt-br` and `PT-BR` are the same locale, so listing two of them is an error, and so are two locales with the same URL prefix.

### Metadata

`locale()->name()` and `nativeName()` use the `locales` entry, then ext-intl (ICU), then the code itself when ICU has no name for it. `direction()` uses the `locales` entry, then the script (`uz_Arab` is RTL), then the language (`ar`, `fa`, `he`, `ur` and other RTL languages). The region never decides the direction.

```php title="config/pharaonic/localization.php"
'locales' => [
    'ar' => ['name' => 'Arabic', 'native' => 'العربية'],
    'ku' => ['direction' => 'rtl'],
],
```

#### Custom metadata

Any other key in a `locales` entry is kept as it is: a flag path or URL, an emoji, a currency, a date format, or a nested array. `locale()->meta()` reads one value with dot notation, for the current locale or the one given, and returns the default when the key is missing. `locale()->metadata()` returns the resolved `name`, `native` and `direction` with every custom key, for example to pass to a frontend.

```php title="config/pharaonic/localization.php"
'locales' => [
    'ar' => ['flag' => ['emoji' => '🇪🇬', 'url' => '/flags/eg.svg'], 'currency' => 'EGP'],
    'en' => ['flag' => ['emoji' => '🇬🇧', 'url' => '/flags/gb.svg'], 'currency' => 'USD'],
],
```

```php
locale()->meta('currency');                  // "EGP" while the locale is ar
locale()->meta('flag.url', locale: 'en');    // "/flags/gb.svg"
locale()->meta('flag', null, 'fr');          // null: fr has no flag
locale()->metadata('ar');
// ["name" => "Arabic", "native" => "العربية", "direction" => "rtl", "flag" => [...], "currency" => "EGP"]
```

Values must be plain data (strings, numbers, booleans, arrays) so `config:cache` can store them. A locale code may appear only once in `locales`, in any spelling.
