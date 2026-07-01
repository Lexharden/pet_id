<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'scan_alerts',
        'found_pet_alerts',
        'email_enabled',
        'alert_throttle_minutes',
    ];

    protected function casts(): array
    {
        return [
            'scan_alerts' => 'boolean',
            'found_pet_alerts' => 'boolean',
            'email_enabled' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
