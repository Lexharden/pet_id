<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\FoundPetReport;
use App\Models\OwnerAlert;
use App\Models\Pet;
use App\Models\User;
use App\Notifications\FoundPetReportedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

final class NotifyOwnerFoundPet implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FoundPetReport $report,
    ) {}

    public function handle(): void
    {
        $pet = $this->report->pet()->with('user.notificationPreference')->first();

        if (! $pet instanceof Pet) {
            return;
        }

        $user = $pet->user;

        if (! $user instanceof User) {
            return;
        }
        $prefs = $user->notificationPreference;

        if ($prefs && (! $prefs->found_pet_alerts || ! $prefs->email_enabled)) {
            return;
        }

        $throttleMinutes = $prefs->alert_throttle_minutes ?? 15;
        $key = 'owner-alert:found:'.$pet->id;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            return;
        }

        RateLimiter::hit($key, $throttleMinutes * 60);

        Notification::send($user, new FoundPetReportedNotification($this->report));

        OwnerAlert::query()->create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'type' => 'found_pet_report',
            'payload' => [
                'report_id' => $this->report->id,
            ],
            'channel' => 'email',
            'sent_at' => now(),
        ]);
    }
}
