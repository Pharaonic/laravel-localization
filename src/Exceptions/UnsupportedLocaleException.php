<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Exceptions;

use InvalidArgumentException;

/**
 * A locale that is not in `localization.supported` was given explicitly.
 */
final class UnsupportedLocaleException extends InvalidArgumentException
{
    /**
     * @param  list<string>  $supported
     */
    public static function make(string $locale, array $supported): self
    {
        return new self(sprintf(
            'Locale [%s] is not supported; supported locales: %s.', mb_substr($locale, 0, 64), implode(', ', $supported)
        ));
    }
}
