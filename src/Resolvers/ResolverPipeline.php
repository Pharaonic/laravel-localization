<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Resolvers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Pharaonic\Laravel\Localization\Contracts\LocaleResolver;
use Pharaonic\Laravel\Localization\Exceptions\InvalidLocalizationConfigurationException;
use Pharaonic\Laravel\Localization\Support\LocaleCatalog;

/**
 * Runs a resolver profile in order and returns the first supported locale.
 *
 * Null, blank, malformed and unsupported results fall through to the next
 * resolver. An exception thrown by a resolver is not caught.
 *
 * @internal
 */
final class ResolverPipeline
{
    public function __construct(
        private Container $container,
        private LocaleCatalog $catalog,
    ) {
    }

    /**
     * @return array{locale: string|null, source: string|null, consulted: list<string>}
     */
    public function resolve(Request $request, string $profile): array
    {
        $consulted = [];

        foreach ($this->catalog->profile($profile) as $id) {
            $consulted[] = $id;
            $value = $this->resolver($id)->resolve($request);
            $locale = is_string($value) && trim($value) !== '' ? $this->catalog->matcher()->match(trim($value)) : null;

            if ($locale !== null) {
                return ['locale' => $locale, 'source' => $id, 'consulted' => $consulted];
            }
        }

        return ['locale' => null, 'source' => null, 'consulted' => $consulted];
    }

    private function resolver(string $id): LocaleResolver
    {
        $resolver = match ($id) {
            'user' => new UserLocaleResolver,
            'session' => new SessionLocaleResolver($this->catalog->sessionKey()),
            'cookie' => new CookieLocaleResolver($this->catalog->cookie()['key']),
            'header' => new HeaderLocaleResolver($this->catalog->headerName(), $this->catalog->matcher(), $this->catalog->default()),
            default => $this->container->make($id),
        };

        if (! $resolver instanceof LocaleResolver) {
            throw new InvalidLocalizationConfigurationException(sprintf(
                'Resolver [%s] must implement %s.', $id, LocaleResolver::class
            ));
        }

        return $resolver;
    }
}
