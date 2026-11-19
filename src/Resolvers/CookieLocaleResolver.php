<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Resolvers;

use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;

/**
 * The locale cookie, as the request carries it after Laravel's EncryptCookies middleware.
 */
final class CookieLocaleResolver implements LocaleResolver
{
    public function __construct(private string $key)
    {
    }

    public function resolve(Request $request): ?string
    {
        $locale = $request->cookies->get($this->key);

        return is_string($locale) ? $locale : null;
    }
}
