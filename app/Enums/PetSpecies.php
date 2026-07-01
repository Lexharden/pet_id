<?php

namespace App\Enums;

enum PetSpecies: string
{
    case Dog = 'dog';
    case Cat = 'cat';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Dog => __('Perro'),
            self::Cat => __('Gato'),
            self::Other => __('Otro'),
        };
    }
}
