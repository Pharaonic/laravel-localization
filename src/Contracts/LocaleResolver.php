<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Contracts;

use Illuminate\Http\Request;

/**
 * A source of the locale a request prefers.
 *
 * Return any locale code (it is matched against the supported locales), or
 * null when the source has no preference. A resolver must not keep the
 * request, the user, or the result between calls.
 */
interface LocaleResolver
{
    public function resolve(Request $request): ?string;
}
