<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Exceptions;

use LogicException;

/**
 * The localization configuration, a resolver, or the localized routes are invalid.
 */
final class InvalidLocalizationConfigurationException extends LogicException
{
}
