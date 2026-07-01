<?php

namespace App\Models;

use App\Enums\ScanChannel;
use App\Enums\ScanResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ScanEvent extends Model
{
    protected $fillable = [
        'tag_id',
        'channel',
        'scanned_at',
        'ip_hash',
        'user_agent',
        'result',
        'alert_sent',
    ];

    protected function casts(): array
    {
        return [
            'channel' => ScanChannel::class,
            'result' => ScanResult::class,
            'scanned_at' => 'datetime',
            'alert_sent' => 'boolean',
        ];
    }

    /** @return BelongsTo<Tag, $this> */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /** @return HasOne<FoundPetReport, $this> */
    public function foundPetReport(): HasOne
    {
        return $this->hasOne(FoundPetReport::class);
    }
}
