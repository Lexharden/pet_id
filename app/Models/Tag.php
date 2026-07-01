<?php

namespace App\Models;

use App\Enums\TagStatus;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property TagStatus $status
 * @property string $public_token
 * @property TagActivationCode|null $activationCode
 */
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    protected $fillable = [
        'tag_batch_id',
        'public_token',
        'status',
        'qr_printed_at',
        'nfc_programmed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TagStatus::class,
            'qr_printed_at' => 'datetime',
            'nfc_programmed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<TagBatch, $this> */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(TagBatch::class, 'tag_batch_id');
    }

    /** @return HasOne<TagActivationCode, $this> */
    public function activationCode(): HasOne
    {
        return $this->hasOne(TagActivationCode::class);
    }

    /** @return HasMany<TagAssignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(TagAssignment::class);
    }

    /** @return HasMany<ScanEvent, $this> */
    public function scanEvents(): HasMany
    {
        return $this->hasMany(ScanEvent::class);
    }

    /** @return HasOne<TagAssignment, $this> */
    public function activeAssignment(): HasOne
    {
        return $this->hasOne(TagAssignment::class)->whereNull('unassigned_at');
    }

    public function activePet(): ?Pet
    {
        return $this->activeAssignment?->pet;
    }

    public function publicUrl(): string
    {
        return url('/t/'.$this->public_token);
    }

    public function isActivatable(): bool
    {
        return $this->status === TagStatus::Manufactured
            && $this->activationCode
            && $this->activationCode->used_at === null;
    }
}
