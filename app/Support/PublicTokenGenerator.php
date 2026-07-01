<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

final class PublicTokenGenerator
{
    public static function generate(): string
    {
        return Str::lower(Str::random(32));
    }

    public static function generateActivationCode(): string
    {
        return strtoupper(Str::random(12));
    }
}
