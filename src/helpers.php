<?php

/**
 * Getting Localization Object
 *
 * @param string $locale
 * @return Pharaonic\Laravel\Localization\Classes\Localization
 */
function locale(?string $locale)
{
    return app('Localization')->setTempLocale($locale);
}
