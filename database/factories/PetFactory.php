<?php

namespace Database\Factories;

use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Enums\PetStatus;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(PetSpecies::cases()),
            'breed' => fake()->optional()->word(),
            'sex' => fake()->optional()->randomElement(PetSex::cases()),
            'birth_date_approx' => fake()->optional()->date(),
            'color' => fake()->optional()->colorName(),
            'markings' => fake()->optional()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'approach_instructions' => fake()->optional()->sentence(),
            'status' => PetStatus::Home,
            'primary_photo_path' => null,
            'primary_photo_disk' => null,
        ];
    }

    public function lost(): static
    {
        return $this->state(fn () => [
            'status' => PetStatus::Lost,
            'lost_reward' => fake()->optional()->sentence(),
            'lost_instructions' => fake()->optional()->sentence(),
        ]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Pet $pet) {
            $pet->publicSettings()->create([]);
            $pet->emergencySummary()->create([]);
        });
    }
}
