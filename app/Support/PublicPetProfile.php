<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Pet;
use App\Models\PetContact;
use App\Models\PetEmergencySummary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

final class PublicPetProfile
{
    /**
     * @return array<string, mixed>
     */
    public static function build(Pet $pet): array
    {
        $pet->loadMissing(['publicSettings', 'emergencySummary', 'contacts']);

        $settings = $pet->publicSettings;
        $emergency = $pet->emergencySummary;

        $data = [
            'name' => $settings?->show_name ? $pet->name : null,
            'species' => $settings?->show_species ? $pet->species->label() : null,
            'breed' => $settings?->show_breed ? $pet->breed : null,
            'sex' => $settings?->show_sex ? $pet->sex?->label() : null,
            'color' => $settings?->show_color ? $pet->color : null,
            'markings' => $settings?->show_markings ? $pet->markings : null,
            'description' => $settings?->show_description ? $pet->description : null,
            'approach_instructions' => $settings?->show_approach_instructions ? $pet->approach_instructions : null,
            'photo_url' => ($settings?->show_photo && $pet->primary_photo_path)
                ? self::photoUrl($pet)
                : null,
            'is_lost' => $pet->isLost(),
            'lost_reward' => $pet->isLost() ? $pet->lost_reward : null,
            'lost_instructions' => $pet->isLost() ? $pet->lost_instructions : null,
            'contacts' => $settings?->show_contacts
                ? self::publicContacts($pet)
                : collect(),
            'emergency' => $settings?->show_emergency && $emergency
                ? self::publicEmergency($emergency)
                : [],
        ];

        return $data;
    }

    private static function photoUrl(Pet $pet): ?string
    {
        if (! $pet->primary_photo_path || ! $pet->primary_photo_disk) {
            return null;
        }

        return Storage::disk($pet->primary_photo_disk)
            ->url($pet->primary_photo_path);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private static function publicContacts(Pet $pet): Collection
    {
        return $pet->contacts
            ->filter(fn (PetContact $c) => $c->isPubliclyVisible())
            ->map(function (PetContact $c): array {
                /** @var array<string, mixed> */
                return array_filter([
                    'name' => $c->name,
                    'phone' => $c->show_phone ? $c->phone : null,
                    'whatsapp' => $c->show_whatsapp ? $c->whatsapp : null,
                    'email' => $c->show_email ? $c->email : null,
                    'type' => $c->type->label(),
                ]);
            })
            ->values();
    }

    /**
     * @return array<string, string|null>
     */
    private static function publicEmergency(PetEmergencySummary $emergency): array
    {
        return array_filter([
            'allergies' => $emergency->show_allergies ? $emergency->allergies : null,
            'urgent_medication' => $emergency->show_urgent_medication ? $emergency->urgent_medication : null,
            'condition' => $emergency->show_condition ? $emergency->condition : null,
            'immediate_instructions' => $emergency->show_immediate_instructions ? $emergency->immediate_instructions : null,
            'vet_name' => $emergency->show_vet ? $emergency->vet_name : null,
            'vet_phone' => $emergency->show_vet ? $emergency->vet_phone : null,
        ]);
    }
}
