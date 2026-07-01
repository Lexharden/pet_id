<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetEmergencySummary extends Model
{
    protected $fillable = [
        'pet_id',
        'allergies',
        'urgent_medication',
        'condition',
        'immediate_instructions',
        'vet_name',
        'vet_phone',
        'show_allergies',
        'show_urgent_medication',
        'show_condition',
        'show_immediate_instructions',
        'show_vet',
    ];

    protected function casts(): array
    {
        return [
            'show_allergies' => 'boolean',
            'show_urgent_medication' => 'boolean',
            'show_condition' => 'boolean',
            'show_immediate_instructions' => 'boolean',
            'show_vet' => 'boolean',
        ];
    }

    /** @return BelongsTo<Pet, $this> */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
