<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Models;

use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;

final class User extends Authenticatable implements HasLocalePreference
{
    public ?string $locale = null;

    public static function prefers(?string $locale): self
    {
        $user = new self;
        $user->locale = $locale;

        return $user;
    }

    public function preferredLocale(): ?string
    {
        return $this->locale;
    }
}
