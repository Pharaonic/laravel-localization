<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Pharaonic\Laravel\Localization\LocalizationManager;

if (! function_exists('locale')) {
    /**
     * The localization manager.
     *
     * The 1.x locale($code) form changed shared state and is removed; pass the
     * locale to the method instead: locale()->route($name, $parameters, $code).
     */
    function locale(?string $locale = null): LocalizationManager
    {
        if ($locale !== null) {
            throw new InvalidArgumentException(
                'locale($code) was removed after 1.x; pass the locale to the method instead, e.g. locale()->route($name, $parameters, $code).'
            );
        }

        /** @var LocalizationManager */
        return Container::getInstance()->make(LocalizationManager::class);
    }
}
