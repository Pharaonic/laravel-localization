## Localized Model Binding

A model can have a different route key in each locale, such as a translated slug, by implementing `HasLocalizedRouteKey`. No trait, column or database schema is required.

```php title="app/Models/Post.php"
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Localization\Contracts\HasLocalizedRouteKey;

class Post extends Model implements HasLocalizedRouteKey
{
    protected $casts = ['slugs' => 'array'];

    public function getLocalizedRouteKey(string $locale): ?string
    {
        return $this->slugs[$locale] ?? null;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slugs->'.app()->getLocale(), $value)->first();
    }
}
```

### Generating URLs

`locale()->route()`, `switchUrl()`, `alternates()` and the signed-route methods use `getLocalizedRouteKey($locale)` for every parameter that implements the contract.

```php
locale()->route('posts.show', $post, 'en'); // "https://example.com/posts/clean-architecture"
locale()->route('posts.show', $post, 'ar'); // "https://example.com/ar/posts/معمارية-البرمجيات" (encoded)
```

When the model has no key in a locale, `route()` throws `MissingLocalizedRouteKeyException` instead of building a wrong-language URL, and `switchUrl()`, `alternates()` and `hreflangs()` leave that locale out.

### Resolving incoming URLs

Your model resolves the incoming value, as with any Laravel binding: in `resolveRouteBinding()` read `app()->getLocale()`, which already holds the URL locale. The package places its middleware right before `SubstituteBindings` in Laravel's middleware priority, so the locale is set before bindings run (and after the session and authentication middleware). A slug from another locale, such as the English slug under `/ar/...`, finds nothing and is a `404`.

:::info Your model decides the lookup
The package only sets the locale before binding. A model's `getRouteKeyName()` and numeric IDs keep Laravel's behavior; a model that doesn't implement the contract is generated with its normal route key. Packages such as spatie/laravel-translatable can back `getLocalizedRouteKey()`, but no integration is built in.
:::
