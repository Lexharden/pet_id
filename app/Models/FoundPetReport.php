<?php

namespace App\Models;

use App\Enums\FoundPetReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoundPetReport extends Model
{
    protected $fillable = [
        'pet_id',
        'scan_event_id',
        'finder_name',
        'finder_phone',
        'message',
        'status',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => FoundPetReportStatus::class,
            'reported_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Pet, $this> */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /** @return BelongsTo<ScanEvent, $this> */
    public function scanEvent(): BelongsTo
    {
        return $this->belongsTo(ScanEvent::class);
    }
}
