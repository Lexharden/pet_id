<?php

namespace App\Models;

use App\Enums\ContactType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ContactType $type
 */
class PetContact extends Model
{
    protected $fillable = [
        'pet_id',
        'type',
        'name',
        'phone',
        'whatsapp',
        'email',
        'sort_order',
        'show_phone',
        'show_whatsapp',
        'show_email',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContactType::class,
            'show_phone' => 'boolean',
            'show_whatsapp' => 'boolean',
            'show_email' => 'boolean',
        ];
    }

    /** @return BelongsTo<Pet, $this> */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function isPubliclyVisible(): bool
    {
        return $this->show_phone || $this->show_whatsapp || $this->show_email;
    }
}
