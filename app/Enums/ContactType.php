<?php

namespace App\Enums;

enum ContactType: string
{
    case Primary = 'primary';
    case Alternate = 'alternate';
    case Family = 'family';
    case Veterinarian = 'veterinarian';

    public function label(): string
    {
        return match ($this) {
            self::Primary => __('Propietario principal'),
            self::Alternate => __('Contacto alterno'),
            self::Family => __('Familiar'),
            self::Veterinarian => __('Veterinario'),
        };
    }
}
