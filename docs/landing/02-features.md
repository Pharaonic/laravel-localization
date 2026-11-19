---
view: components.packages.features
variant: compact
badge: Key Features
title: Everything a multilingual Laravel app needs
subtitle: Wrap routes in one macro for websites, add one middleware for APIs.
items:
  - icon: globe
    title: Static Localized Routes
    text: '`Route::localized()` registers one real route per locale, safe for `route:cache`.'
  - icon: translate
    title: Translated URLs
    text: Translate path segments with `resources/lang/{locale}/routes.php`, Unicode included.
  - icon: switch
    title: Resolver Pipeline
    text: User, session, cookie, `Accept-Language` or your own class, in your order.
  - icon: search
    title: Switcher & hreflang
    text: '`switchUrl()`, `alternates()` and `hreflangs()` for the current page.'
  - icon: lock
    title: Native Signed URLs
    text: Sign localized routes with Laravel's own signer and `signed` middleware.
  - icon: shield-check
    title: Predictable
    text: No hidden redirects, no automatic writes, and nothing carried between requests.
---
