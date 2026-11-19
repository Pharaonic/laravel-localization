:::badges
- Laravel Package {color=blue}
- {release.label} {color=green}
- {package.license} License {color=purple}
:::

# Localization

Localization gives a Laravel application two ways to pick the language of a response. **Path mode** puts the locale in the URL (`/products`, `/ar/products`, `/fr/produits`) with static, cacheable routes, which suits public websites and SEO. **Negotiated mode** keeps the URL unchanged and picks the locale from an ordered list of sources such as the authenticated user or the `Accept-Language` header, which suits APIs.

:::features
### Static Localized Routes {icon="globe"}
One real route per locale, registered once, safe for route:cache.

### Translated URLs {icon="translate"}
Translate URL segments with ordinary lang files, Unicode included.

### Resolver Pipeline {icon="switch"}
User, session, cookie, header or your own resolver, in the order you choose.

### Laravel Is the Source {icon="laravel"}
The default comes from app.locale and translation fallback stays with Laravel.

### Alternates and hreflang {icon="search"}
Language switcher links and hreflang tags for the current page.

### No Hidden State {icon="shield-check"}
No automatic redirects, no automatic persistence, nothing kept between requests.
:::

:::info Quick Tip
An explicit locale in the URL always wins. `/ar/products` is Arabic whatever the user's cookie, session or browser prefers, and `/products` is always the default language.
:::

### When not to use it

You don't need this package when your application only translates strings with Laravel's `__()` and has no localized URLs, or when an API only needs a three-line middleware that calls `App::setLocale()` from one header.
