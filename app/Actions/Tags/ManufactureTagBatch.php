<?php

declare(strict_types=1);

namespace App\Actions\Tags;

use App\Enums\TagStatus;
use App\Models\Tag;
use App\Models\TagBatch;
use App\Models\User;
use App\Support\PublicTokenGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class ManufactureTagBatch
{
    /**
     * @return array{tags: list<Tag>, plaintext_codes: list<string>}
     */
    public function execute(User $operator, string $batchCode, int $quantity, string $productType = 'plate'): array
    {
        return DB::transaction(function () use ($operator, $batchCode, $quantity, $productType) {
            $batch = TagBatch::query()->create([
                'code' => $batchCode,
                'product_type' => $productType,
                'quantity' => $quantity,
                'manufactured_at' => now(),
                'created_by' => $operator->id,
            ]);

            $tags = [];
            $plaintextCodes = [];

            for ($i = 0; $i < $quantity; $i++) {
                $plaintext = PublicTokenGenerator::generateActivationCode();

                /** @var Tag $tag */
                $tag = $batch->tags()->create([
                    'public_token' => PublicTokenGenerator::generate(),
                    'status' => TagStatus::Manufactured,
                ]);

                $tag->activationCode()->create([
                    'code_hash' => Hash::make($plaintext),
                ]);

                $tags[] = $tag;
                $plaintextCodes[] = $plaintext;
            }

            return ['tags' => $tags, 'plaintext_codes' => $plaintextCodes];
        });
    }
}
