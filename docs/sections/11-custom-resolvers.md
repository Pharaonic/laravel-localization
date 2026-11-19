## Custom Resolvers

A resolver is a class implementing `LocaleResolver`. Return any locale code, or `null` when it has no preference.

```php title="app/Localization/TenantLocaleResolver.php"
namespace App\Localization;

use App\Tenancy\TenantRepository;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;

final class TenantLocaleResolver implements LocaleResolver
{
    public function __construct(private TenantRepository $tenants)
    {
    }

    public function resolve(Request $request): ?string
    {
        return $this->tenants->forHost($request->getHost())?->locale;
    }
}
```

List the class in a profile where it should run:

```php title="config/pharaonic/localization.php"
'resolvers' => [
    'api' => [
        App\Localization\TenantLocaleResolver::class,
        'user',
        'header'
    ],
],
```

### Behavior

- The class is resolved from the container on each request, so constructor injection and container bindings work. Keep no request, user or result in properties.
- The value is matched against `supported`; unsupported or empty values fall through to the next resolver.
- `locale()->source()` reports the class name when it wins.
- The class name is stored in the configuration, so `config:cache` keeps working. Closures are not accepted.
- An exception thrown by the resolver propagates, and the request fails. Catch it inside the resolver and return `null` when a failure should fall through.
