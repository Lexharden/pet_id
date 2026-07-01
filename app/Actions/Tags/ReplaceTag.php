<?php

declare(strict_types=1);

namespace App\Actions\Tags;

use App\Enums\TagStatus;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

final class ReplaceTag
{
    public function execute(Tag $oldTag, Tag $newTag, Pet $pet, string $reason = 'replacement'): Tag
    {
        return DB::transaction(function () use ($oldTag, $newTag, $pet, $reason) {
            $oldTag->activeAssignment?->update([
                'unassigned_at' => now(),
                'reason' => $reason,
            ]);

            $oldTag->update(['status' => TagStatus::Replaced]);

            $newTag->update(['status' => TagStatus::Activated]);

            $newTag->assignments()->create([
                'pet_id' => $pet->id,
                'assigned_at' => now(),
            ]);

            return $newTag->fresh();
        });
    }
}
