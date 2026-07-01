<?php

namespace Database\Factories;

use App\Models\TagBatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TagBatch>
 */
class TagBatchFactory extends Factory
{
    protected $model = TagBatch::class;

    public function definition(): array
    {
        return [
            'code' => 'BATCH-'.fake()->unique()->numerify('####'),
            'product_type' => 'plate',
            'quantity' => 1,
            'manufactured_at' => now(),
            'created_by' => User::factory(),
        ];
    }
}
