<?php

namespace App\Enums;

enum PetSex: string
{
    case Male = 'male';
    case Female = 'female';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Male => __('Macho'),
            self::Female => __('Hembra'),
            self::Unknown => __('Desconocido'),
        };
    }
}
