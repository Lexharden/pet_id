<?php

namespace App\Models;

use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Enums\PetStatus;
use Database\Factories\PetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property PetSpecies $species
 * @property string|null $breed
 * @property PetSex|null $sex
 * @property Carbon|null $birth_date_approx
 * @property string|null $color
 * @property string|null $markings
 * @property string|null $description
 * @property string|null $approach_instructions
 * @property PetStatus $status
 * @property string|null $primary_photo_path
 * @property string|null $primary_photo_disk
 * @property string|null $lost_reward
 * @property string|null $lost_instructions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Pet extends Model
{
    /** @use HasFactory<PetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'sex',
        'birth_date_approx',
        'color',
        'markings',
        'description',
        'approach_instructions',
        'status',
        'primary_photo_path',
        'primary_photo_disk',
        'lost_reward',
        'lost_instructions',
    ];

    protected function casts(): array
    {
        return [
            'species' => PetSpecies::class,
            'sex' => PetSex::class,
            'status' => PetStatus::class,
            'birth_date_approx' => 'date',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<PetPhoto, $this> */
    public function photos(): HasMany
    {
        return $this->hasMany(PetPhoto::class)->orderBy('sort_order');
    }

    /** @return HasMany<PetContact, $this> */
    public function contacts(): HasMany
    {
        return $this->hasMany(PetContact::class)->orderBy('sort_order');
    }

    /** @return HasOne<PetEmergencySummary, $this> */
    public function emergencySummary(): HasOne
    {
        return $this->hasOne(PetEmergencySummary::class);
    }

    /** @return HasOne<PetPublicSetting, $this> */
    public function publicSettings(): HasOne
    {
        return $this->hasOne(PetPublicSetting::class);
    }

    /** @return HasMany<TagAssignment, $this> */
    public function tagAssignments(): HasMany
    {
        return $this->hasMany(TagAssignment::class);
    }

    /** @return HasMany<TagAssignment, $this> */
    public function activeTags(): HasMany
    {
        return $this->hasMany(TagAssignment::class)->whereNull('unassigned_at');
    }

    /** @return HasMany<FoundPetReport, $this> */
    public function foundPetReports(): HasMany
    {
        return $this->hasMany(FoundPetReport::class);
    }

    public function isLost(): bool
    {
        return $this->status === PetStatus::Lost;
    }
}
