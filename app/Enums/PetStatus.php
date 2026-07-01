<?php

namespace App\Enums;

enum PetStatus: string
{
    case Home = 'home';
    case Lost = 'lost';
    case Deceased = 'deceased';

    public function label(): string
    {
        return match ($this) {
            self::Home => __('En casa'),
            self::Lost => __('Perdida'),
            self::Deceased => __('Fallecida'),
        };
    }
}
