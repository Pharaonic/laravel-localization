<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Exceptions;

use RuntimeException;

/**
 * A HasLocalizedRouteKey parameter has no route key in the requested locale.
 */
final class MissingLocalizedRouteKeyException extends RuntimeException
{
}
