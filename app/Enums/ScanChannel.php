<?php

namespace App\Enums;

enum ScanChannel: string
{
    case Qr = 'qr';
    case Nfc = 'nfc';
    case Direct = 'direct';

    public function label(): string
    {
        return match ($this) {
            self::Qr => 'QR',
            self::Nfc => 'NFC',
            self::Direct => __('Directo'),
        };
    }

    public static function fromRequest(?string $channel): self
    {
        return match ($channel) {
            'nfc' => self::Nfc,
            'direct' => self::Direct,
            default => self::Qr,
        };
    }
}
