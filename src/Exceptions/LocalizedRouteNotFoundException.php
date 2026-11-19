<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Exceptions;

use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * The named route does not exist, or was not registered inside Route::localized().
 */
final class LocalizedRouteNotFoundException extends RouteNotFoundException {}
