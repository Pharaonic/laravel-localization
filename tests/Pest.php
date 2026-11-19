<?php

declare(strict_types=1);

use Pharaonic\Laravel\Localization\Tests\TestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

uses(TestCase::class)->in('Compatibility', 'Feature', 'Unit');

/**
 * The cookie a response sets under the given name.
 */
function responseCookie(Response $response, string $name): ?Cookie
{
    foreach ($response->headers->getCookies() as $cookie) {
        if ($cookie->getName() === $name) {
            return $cookie;
        }
    }

    return null;
}
