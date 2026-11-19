<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Resolvers;

use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;
use Pharaonic\Laravel\Localization\Support\LocaleMatcher;

/**
 * The best supported locale of the Accept-Language header (or the configured header).
 */
final class HeaderLocaleResolver implements LocaleResolver
{
    public function __construct(
        private string $header,
        private LocaleMatcher $matcher,
        private string $default,
    ) {}

    public function resolve(Request $request): ?string
    {
        $value = $request->headers->get($this->header);

        return is_string($value) && $value !== '' ? $this->matcher->negotiate($value, $this->default) : null;
    }
}
