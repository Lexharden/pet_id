<?php

namespace Database\Factories;

use App\Enums\TagStatus;
use App\Models\Tag;
use App\Models\TagBatch;
use App\Support\PublicTokenGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'tag_batch_id' => TagBatch::factory(),
            'public_token' => PublicTokenGenerator::generate(),
            'status' => TagStatus::Manufactured,
        ];
    }

    public function withActivationCode(?string $plaintext = null): static
    {
        $code = $plaintext ?? PublicTokenGenerator::generateActivationCode();

        return $this->afterCreating(function (Tag $tag) use ($code) {
            $tag->activationCode()->create([
                'code_hash' => Hash::make(strtoupper($code)),
            ]);
        })->state(['_plaintext_code' => fn () => $code]);
    }

    public function activated(): static
    {
        return $this->state(['status' => TagStatus::Activated]);
    }

    public function disabled(): static
    {
        return $this->state(['status' => TagStatus::Disabled]);
    }
}
