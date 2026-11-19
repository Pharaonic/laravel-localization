## SEO and Language Switching

On a path-localized page, the package builds the URL of the same page in each locale from the current route and its parameters.

### Language switcher

`switchUrl()` keeps the route parameters and the query string. It returns `null` when the page can't be built in that locale: the route isn't localized, a model has no localized key, or the URL is signed.

```blade title="resources/views/partials/language-switcher.blade.php"
<nav aria-label="Language">
    @foreach (locale()->supported() as $code)
        @if ($url = locale()->switchUrl($code))
            <a href="{{ $url }}" hreflang="{{ locale()->normalize($code) }}" lang="{{ locale()->normalize($code) }}"
               @if ($code === locale()->current()) aria-current="true" @endif>
                @if ($flag = locale()->meta('flag', locale: $code))
                    <img src="{{ $flag }}" alt="" width="20" height="14">
                @endif
                {{ locale()->nativeName($code) }}
            </a>
        @endif
    @endforeach
</nav>
```

`flag` is a [custom metadata](#configuration) key you set in `locales`, e.g. `'ar' => ['flag' => '/flags/eg.svg']`; the package ships no flags.

Switching only builds a link. It doesn't store the choice; see [Locale Preferences](#locale-preferences) for remembering it.

### Alternates and hreflang

`alternates()` returns `locale => absolute URL` for each locale the page exists in, without the query string. `hreflangs()` returns the same URLs keyed by BCP 47 tag, plus `x-default` pointing at the default-locale URL.

```php
locale()->alternates();
// ["en" => "https://example.com/posts/hello", "ar" => "https://example.com/ar/posts/..."]

locale()->hreflangs();
// ["en" => "...", "ar" => "...", "pt-BR" => "...", "x-default" => "https://example.com/posts/hello"]
```

Both return an empty array outside path-localized routes (including negotiated API responses) and on signed URLs.

```blade title="resources/views/layouts/app.blade.php"
<head>
    <link rel="canonical" href="{{ locale()->alternates()[locale()->current()] ?? url()->current() }}">
    @foreach (locale()->hreflangs() as $hreflang => $url)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $url }}">
    @endforeach
</head>
```

Blade's `{{ }}` escapes every value. Nothing is added to your views automatically.

### With Pharaonic SEO

When your application uses `pharaonic/laravel-seo`, hand the links to its alternates element instead, so they render once with the rest of `@seo`. Localization doesn't depend on it.

```php title="app/Http/Middleware/AddHreflangLinks.php"
foreach (locale()->hreflangs() as $hreflang => $url) {
    seo()->alternates->add($hreflang, $url);
}
```

:::info
This snippet uses laravel-seo's `seo()->alternates->add()` API; the package doesn't ship or test an automatic integration. Add the links in one place only, so they aren't printed twice.
:::
