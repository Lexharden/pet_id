<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetPublicSetting extends Model
{
    protected $fillable = [
        'pet_id',
        'show_name',
        'show_photo',
        'show_species',
        'show_breed',
        'show_sex',
        'show_color',
        'show_markings',
        'show_description',
        'show_approach_instructions',
        'show_contacts',
        'show_emergency',
    ];

    protected function casts(): array
    {
        return [
            'show_name' => 'boolean',
            'show_photo' => 'boolean',
            'show_species' => 'boolean',
            'show_breed' => 'boolean',
            'show_sex' => 'boolean',
            'show_color' => 'boolean',
            'show_markings' => 'boolean',
            'show_description' => 'boolean',
            'show_approach_instructions' => 'boolean',
            'show_contacts' => 'boolean',
            'show_emergency' => 'boolean',
        ];
    }

    /** @return BelongsTo<Pet, $this> */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
