<?php

namespace App\Enums;

enum TagStatus: string
{
    case Manufactured = 'manufactured';
    case Activated = 'activated';
    case Disabled = 'disabled';
    case Replaced = 'replaced';
    case Retired = 'retired';

    public function label(): string
    {
        return match ($this) {
            self::Manufactured => __('Fabricada'),
            self::Activated => __('Activada'),
            self::Disabled => __('Deshabilitada'),
            self::Replaced => __('Reemplazada'),
            self::Retired => __('Retirada'),
        };
    }

    public function isScannable(): bool
    {
        return $this === self::Activated;
    }
}
