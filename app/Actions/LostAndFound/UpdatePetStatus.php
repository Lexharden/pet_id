<?php

declare(strict_types=1);

namespace App\Actions\LostAndFound;

use App\Enums\PetStatus;
use App\Models\Pet;

final class UpdatePetStatus
{
    public function markLost(Pet $pet, ?string $reward = null, ?string $instructions = null): Pet
    {
        $pet->update([
            'status' => PetStatus::Lost,
            'lost_reward' => $reward,
            'lost_instructions' => $instructions,
        ]);

        return $pet->fresh();
    }

    public function markHome(Pet $pet): Pet
    {
        $pet->update([
            'status' => PetStatus::Home,
            'lost_reward' => null,
            'lost_instructions' => null,
        ]);

        return $pet->fresh();
    }
}
