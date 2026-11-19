<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Resolvers;

use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;

/**
 * The authenticated user's preferredLocale(), when the user implements Laravel's HasLocalePreference.
 */
final class UserLocaleResolver implements LocaleResolver
{
    public function resolve(Request $request): ?string
    {
        $user = $request->user();

        if (! $user instanceof HasLocalePreference) {
            return null;
        }

        $locale = $user->preferredLocale();

        return is_string($locale) ? $locale : null;
    }
}
