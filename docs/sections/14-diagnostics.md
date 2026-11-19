## Diagnostics

`localization:doctor` checks the setup and exits with `1` when something is broken, so it can run in CI.

```bash title="Terminal" no-line-numbers
php artisan localization:doctor
```

```text
[OK] Default locale [en] (app.locale), supported: en, ar, fr.
[OK] Resolver profiles: web, api.
[WARN] Route [api/profile] uses the session resolver without StartSession; it will never match.
[OK] 12 localized routes (4 logical) checked.

0 error(s), 1 warning(s).
```

### Checks

| Check | Level |
| --- | --- |
| Configuration: supported locales, `app.locale`, prefixes, profiles, cookie and header settings, the 1.x file | error |
| Configuration that `config:cache` can't store | error |
| Custom resolvers that can't be built or don't implement `LocaleResolver` | error |
| Localized routes whose locales don't match `supported` (a stale route cache) | error |
| A localized route name that resolves to another route | error |
| A `localization:{profile}` route with an unknown profile | error |
| `session` resolver without `StartSession`, `cookie` resolver without `EncryptCookies` | warning |
| The `localization` alias pointing to another middleware | warning |

The command reads configuration and routes only. It never reads requests, sessions, cookies or users, so it can't print their data.
