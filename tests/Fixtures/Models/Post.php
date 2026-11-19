<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Models;

use Illuminate\Contracts\Routing\UrlRoutable;
use Pharaonic\Laravel\Localization\Contracts\HasLocalizedRouteKey;

/**
 * A route-bindable record with per-locale slugs, without a database.
 */
final class Post implements HasLocalizedRouteKey, UrlRoutable
{
    /** @var array<int, array<string, string>> */
    public const SLUGS = [
        1 => ['en' => 'clean-architecture', 'ar' => 'معمارية-البرمجيات'],
        2 => ['en' => 'english-only'],
    ];

    public function __construct(public int $id = 0)
    {
    }

    public function getLocalizedRouteKey(string $locale): ?string
    {
        return self::SLUGS[$this->id][$locale] ?? null;
    }

    public function getRouteKey(): mixed
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        foreach (self::SLUGS as $id => $slugs) {
            if (($slugs[app()->getLocale()] ?? null) === $value) {
                return new self($id);
            }
        }

        return null;
    }

    public function resolveChildRouteBinding($childType, $value, $field): ?self
    {
        return null;
    }
}
