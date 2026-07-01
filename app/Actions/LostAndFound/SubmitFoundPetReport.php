<?php

declare(strict_types=1);

namespace App\Actions\LostAndFound;

use App\Enums\FoundPetReportStatus;
use App\Jobs\NotifyOwnerFoundPet;
use App\Models\FoundPetReport;
use App\Models\Pet;
use Illuminate\Support\Facades\RateLimiter;

final class SubmitFoundPetReport
{
    /**
     * @param  array{finder_name?: ?string, finder_phone?: ?string, message?: ?string}  $data
     */
    public function execute(Pet $pet, array $data, ?int $scanEventId = null, ?string $ip = null): FoundPetReport
    {
        $key = 'found-report:'.($ip ?? 'unknown').':'.$pet->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw new \RuntimeException(__('Demasiados intentos. Intenta más tarde.'));
        }

        RateLimiter::hit($key, 3600);

        $report = FoundPetReport::query()->create([
            'pet_id' => $pet->id,
            'scan_event_id' => $scanEventId,
            'finder_name' => $data['finder_name'] ?? null,
            'finder_phone' => $data['finder_phone'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => FoundPetReportStatus::Pending,
            'reported_at' => now(),
        ]);

        NotifyOwnerFoundPet::dispatch($report);

        return $report;
    }
}
