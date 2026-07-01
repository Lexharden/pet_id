<?php

declare(strict_types=1);

namespace App\Actions\Tags;

use App\Enums\TagStatus;
use App\Exceptions\TagActivationException;
use App\Models\Pet;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

final class ActivateTag
{
    public function execute(User $user, string $plaintextCode, Pet $pet): Tag
    {
        $key = 'tag-activation:'.$user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw TagActivationException::tooManyAttempts();
        }

        RateLimiter::hit($key, 60);

        $tag = $this->findTagByCode($plaintextCode);

        if (! $tag) {
            throw TagActivationException::invalidCode();
        }

        if (! $tag->isActivatable()) {
            throw TagActivationException::alreadyUsed();
        }

        if ($pet->user_id !== $user->id) {
            throw TagActivationException::unauthorized();
        }

        return DB::transaction(function () use ($user, $tag, $pet) {
            $tag->activationCode->update([
                'used_at' => now(),
                'used_by_user_id' => $user->id,
            ]);

            $tag->update(['status' => TagStatus::Activated]);

            $tag->assignments()->create([
                'pet_id' => $pet->id,
                'assigned_at' => now(),
            ]);

            return $tag->fresh(['activeAssignment.pet']);
        });
    }

    private function findTagByCode(string $plaintextCode): ?Tag
    {
        $normalized = strtoupper(trim($plaintextCode));

        $candidates = Tag::query()
            ->with('activationCode')
            ->where('status', TagStatus::Manufactured)
            ->whereHas('activationCode', fn ($q) => $q->whereNull('used_at'))
            ->get();

        foreach ($candidates as $tag) {
            $activationCode = $tag->activationCode;
            if ($activationCode && Hash::check($normalized, $activationCode->code_hash)) {
                return $tag;
            }
        }

        return null;
    }
}
