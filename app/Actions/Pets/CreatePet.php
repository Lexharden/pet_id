<?php

declare(strict_types=1);

namespace App\Actions\Pets;

use App\Enums\PetSpecies;
use App\Enums\PetStatus;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CreatePet
{
    /**
     * @param  array{name: string, species: PetSpecies|string, breed?: ?string, sex?: ?string, birth_date_approx?: ?string, color?: ?string, markings?: ?string, description?: ?string, approach_instructions?: ?string}  $data
     */
    public function execute(User $user, array $data): Pet
    {
        return DB::transaction(function () use ($user, $data): Pet {
            /** @var Pet $pet */
            $pet = $user->pets()->create([
                'name' => $data['name'],
                'species' => $data['species'],
                'breed' => $data['breed'] ?? null,
                'sex' => $data['sex'] ?? null,
                'birth_date_approx' => $data['birth_date_approx'] ?? null,
                'color' => $data['color'] ?? null,
                'markings' => $data['markings'] ?? null,
                'description' => $data['description'] ?? null,
                'approach_instructions' => $data['approach_instructions'] ?? null,
                'status' => PetStatus::Home,
            ]);

            $pet->publicSettings()->create([]);
            $pet->emergencySummary()->create([]);

            $user->notificationPreference()->firstOrCreate([]);

            return $pet->load(['publicSettings', 'emergencySummary']);
        });
    }
}
