<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers;

use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;
use RuntimeException;

final class ThrowingResolver implements LocaleResolver
{
    public function resolve(Request $request): ?string
    {
        throw new RuntimeException('Tenant service is down.');
    }
}
