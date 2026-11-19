---
name: Localization

action:
  label: View on Packagist
  href: "{package.packagistUrl}"

views: components.packages

breadcrumbs:
  - label: Home
    href: route:home
  - label: Packages
    href: route:packages.index
  - label: "{technology.name} Packages"
    href: "url:/packages/{technology.slug}"
  - label: "{package.name}"

card:
  topic: localization
  icon: translate
  tags: localization locale multilingual i18n localized-routes translated-urls accept-language hreflang language-switcher
  description: Localized routing for Laravel. Static, cacheable locale-prefixed URLs for websites, and an ordered resolver pipeline (user, session, cookie, Accept-Language) for APIs.

seo:
  title: "{package.fullName} - Localized Routes and Locale Negotiation"
  description: "{package.name} is a Laravel package for localized, translated and cacheable URLs, Accept-Language negotiation for APIs, language switchers and hreflang links. {package.downloadsShort}+ downloads, {package.license} licensed."
  keywords: laravel localization, localized routes, laravel multilingual, translated urls, accept-language, laravel locale, hreflang, language switcher
  author: Pharaonic
  images:
    - "{package.cover}"
  openGraph:
    type: website
    siteName: Pharaonic
  twitter:
    card: summary_large_image

schema:
  "@type": SoftwareSourceCode
  name: "{package.name}"
  description: "{package.name} is a Laravel package for localized, translated and cacheable URLs and Accept-Language negotiation for APIs."
  image: "{package.cover}"
  codeRepository: "{package.githubUrl}"
  programmingLanguage: PHP
  runtimePlatform: "{technology.name}"
  version: "{package.version}"
  datePublished: "{package.publishedAt}"
  dateModified: "{package.updatedAt}"
  license: "https://opensource.org/licenses/{package.license}"
  isAccessibleForFree: true
  sameAs:
    - "{package.githubUrl}"
    - "{package.packagistUrl}"
  author:
    "@id": url:/#organization
  publisher:
    "@id": url:/#organization
  interactionStatistic:
    "@type": InteractionCounter
    interactionType: https://schema.org/DownloadAction
    userInteractionCount: "{package.downloads}"
---
