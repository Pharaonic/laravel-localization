<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Support;

use Illuminate\Http\Request;

/**
 * The locale resolved for one request, kept on the request itself so it can
 * never outlive it (Octane workers, queued jobs, sequential test requests).
 *
 * @internal
 */
final class LocaleContext
{
    public const ATTRIBUTE = 'pharaonic.localization';

    /**
     * @param  list<string>  $consulted  resolvers that ran, in order
     */
    public function __construct(
        private string $locale,
        private string $source,
        private string $mode,
        private string $previous,
        private array $consulted = [],
    ) {
    }

    public static function of(Request $request): ?self
    {
        $context = $request->attributes->get(self::ATTRIBUTE);

        return $context instanceof self ? $context : null;
    }

    public function attachTo(Request $request): void
    {
        $request->attributes->set(self::ATTRIBUTE, $this);
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function mode(): string
    {
        return $this->mode;
    }

    /**
     * The application locale before the middleware changed it.
     */
    public function previous(): string
    {
        return $this->previous;
    }

    /**
     * @return list<string>
     */
    public function consulted(): array
    {
        return $this->consulted;
    }
}
