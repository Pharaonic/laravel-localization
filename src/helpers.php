<?php

/**
 * Getting Localization Object
 *
 * @param string $locale
 * @return Pharaonic\Laravel\Localization\Classes\Localization
 */
function locale(string $locale = null)
{
    return app('Localization')->setTempLocale($locale);
}
