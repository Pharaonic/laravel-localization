<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Resolvers;

use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;

/**
 * Reads the locale of a tenant from a host => locale map bound in the container.
 */
final class TenantLocaleResolver implements LocaleResolver
{
    /**
     * @param  array<string, string>  $tenants
     */
    public function __construct(private array $tenants = [])
    {
    }

    public function resolve(Request $request): ?string
    {
        return $this->tenants[$request->getHost()] ?? $request->headers->get('X-Tenant-Locale');
    }
}
