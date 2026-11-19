<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Contracts;

/**
 * A route parameter whose URL value depends on the locale, e.g. a translated slug.
 */
interface HasLocalizedRouteKey
{
    /**
     * The route key for the locale, or null when the model has none in that locale.
     */
    public function getLocalizedRouteKey(string $locale): ?string;
}
