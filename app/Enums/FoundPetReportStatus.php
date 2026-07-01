<?php

namespace App\Enums;

enum FoundPetReportStatus: string
{
    case Pending = 'pending';
    case Acknowledged = 'acknowledged';
    case Resolved = 'resolved';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Pendiente'),
            self::Acknowledged => __('Revisado'),
            self::Resolved => __('Resuelto'),
            self::Spam => __('Spam'),
        };
    }
}
