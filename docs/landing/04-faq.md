---
view: components.home.faq
badge: FAQ
title: "{package.name}"
highlight: Questions
subtitle: "Quick answers about installing and using {package.name}."
---

## What is {package.name}?

{card.description} It's a free, open-source {technology.name} package by Pharaonic.

## How do I install {package.name}?

Run `composer require {package.composer}` in your project's root directory.

## What does {package.name} require?

The latest release requires {package.requiresText}.

## Does it work with route:cache?

Yes. Every locale gets its own static route, and the locale is stored in the route itself, so cached routes behave exactly like uncached ones. Use controller actions, since Laravel can't cache closures.

## Can a cookie or the browser change the language of /ar/products?

No. A language in the URL always wins, and the unprefixed URL is always the default language. Preferences only apply to routes that use the `localization` middleware.

## Does it support sub-domains or country domains?

Not as a built-in mode. Use Laravel's `Route::domain()` groups, and a custom resolver when the domain decides the language.

## Is {package.name} free to use?

Yes. {package.name} is open source under the {package.license} license, so you can use it in personal and commercial projects.

## Where can I find the {package.name} documentation?

Read the [{package.name} documentation]({package.docsUrl}) for setup, configuration, and usage examples.

## How do I report a bug or contribute to {package.name}?

Open an issue or a pull request on [GitHub]({package.githubUrl}), ask in [GitHub Discussions](https://github.com/orgs/Pharaonic/discussions), or chat with us on [Discord](https://discord.gg/XQG9RhvEvf).
