<?php

namespace App\Enums;

enum ScanResult: string
{
    case ProfileViewed = 'profile_viewed';
    case NotFound = 'not_found';
    case Disabled = 'disabled';
    case Unassigned = 'unassigned';

    public function label(): string
    {
        return match ($this) {
            self::ProfileViewed => __('Perfil visto'),
            self::NotFound => __('No encontrado'),
            self::Disabled => __('Deshabilitada'),
            self::Unassigned => __('Sin asignar'),
        };
    }
}
