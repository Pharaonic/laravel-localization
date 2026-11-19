<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Resolvers;

use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;

/**
 * The locale stored in the session, read only when the session has already been started.
 */
final class SessionLocaleResolver implements LocaleResolver
{
    public function __construct(private string $key)
    {
    }

    public function resolve(Request $request): ?string
    {
        if (! $request->hasSession() || ! $request->session()->isStarted()) {
            return null;
        }

        $locale = $request->session()->get($this->key);

        return is_string($locale) ? $locale : null;
    }
}
