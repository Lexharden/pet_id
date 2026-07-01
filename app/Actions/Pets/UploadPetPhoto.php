<?php

declare(strict_types=1);

namespace App\Actions\Pets;

use App\Models\Pet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class UploadPetPhoto
{
    public function execute(Pet $pet, UploadedFile $file, bool $isPublic = false): void
    {
        $disk = $isPublic ? 'pets-public' : 'pets-private';
        $filename = Str::uuid()->toString().'.'.$file->guessExtension();
        $path = $file->storeAs((string) $pet->id, $filename, $disk);

        DB::transaction(function () use ($pet, $path, $disk, $isPublic) {
            $pet->photos()->where('is_primary', true)->update(['is_primary' => false]);

            $pet->photos()->create([
                'path' => $path,
                'disk' => $disk,
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            if ($isPublic) {
                $pet->update([
                    'primary_photo_path' => $path,
                    'primary_photo_disk' => $disk,
                ]);
            }
        });
    }
}
