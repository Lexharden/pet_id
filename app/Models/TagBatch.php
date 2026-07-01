<?php

namespace App\Models;

use Database\Factories\TagBatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagBatch extends Model
{
    /** @use HasFactory<TagBatchFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'product_type',
        'quantity',
        'manufactured_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'manufactured_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return HasMany<Tag, $this> */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
