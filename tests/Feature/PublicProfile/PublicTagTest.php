<?php

use App\Actions\LostAndFound\SubmitFoundPetReport;
use App\Actions\LostAndFound\UpdatePetStatus;
use App\Actions\Tags\ActivateTag;
use App\Actions\Tags\ManufactureTagBatch;
use App\Enums\PetStatus;
use App\Enums\ScanResult;
use App\Enums\TagStatus;
use App\Enums\UserRole;
use App\Jobs\NotifyOwnerFoundPet;
use App\Models\Pet;
use App\Models\ScanEvent;
use App\Models\User;
use App\Notifications\FoundPetReportedNotification;
use Illuminate\Support\Facades\Notification;

test('public profile returns 404 for unknown token', function () {
    $this->get('/t/unknown-token-that-does-not-exist-xyz')
        ->assertNotFound();
});

test('activated tag shows public profile with noindex', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create(['name' => 'Rocky']);
    $pet->publicSettings->update(['show_name' => true, 'show_species' => true]);
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'SCAN-001', 1);
    app(ActivateTag::class)->execute($user, $result['plaintext_codes'][0], $pet);

    $tag = $result['tags'][0]->fresh();
    $token = $tag->public_token;

    $response = $this->get("/t/{$token}");

    $response->assertOk();
    $response->assertSee('Rocky', false);
    $response->assertSee('noindex', false);
});

test('scan is recorded when tag is viewed', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'SCAN-002', 1);
    app(ActivateTag::class)->execute($user, $result['plaintext_codes'][0], $pet);

    $token = $result['tags'][0]->public_token;

    $this->get("/t/{$token}?c=qr");

    expect(ScanEvent::query()->count())->toBe(1);
    expect(ScanEvent::first()->channel->value)->toBe('qr');
    expect(ScanEvent::first()->result)->toBe(ScanResult::ProfileViewed);
});

test('disabled tag shows unavailable page', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'SCAN-003', 1);
    app(ActivateTag::class)->execute($user, $result['plaintext_codes'][0], $pet);

    $tag = $result['tags'][0];
    $tag->update(['status' => TagStatus::Disabled]);

    $this->get("/t/{$tag->public_token}")
        ->assertOk()
        ->assertSee(__('Perfil no disponible'), false);
});

test('owner can mark pet as lost', function () {
    $pet = Pet::factory()->create();

    app(UpdatePetStatus::class)->markLost($pet, 'Recompensa $500', 'Llama primero');

    expect($pet->fresh()->status)->toBe(PetStatus::Lost);
    expect($pet->fresh()->lost_reward)->toBe('Recompensa $500');
});

test('found pet report notifies owner with throttle', function () {
    Notification::fake();

    $user = User::factory()->create();
    $user->notificationPreference()->create([]);
    $pet = Pet::factory()->for($user)->lost()->create();

    $report = app(SubmitFoundPetReport::class)->execute($pet, [
        'finder_name' => 'María',
        'finder_phone' => '5559998877',
        'message' => 'La vi en el parque',
    ]);

    NotifyOwnerFoundPet::dispatchSync($report);

    Notification::assertSentTo($user, FoundPetReportedNotification::class);
});
