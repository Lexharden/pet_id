<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner = 'owner';
    case Operator = 'operator';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Owner => __('Propietario'),
            self::Operator => __('Operador'),
            self::Admin => __('Administrador'),
        };
    }

    public function isStaff(): bool
    {
        return $this === self::Operator || $this === self::Admin;
    }
}
