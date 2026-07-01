<?php

use App\Actions\Tags\ActivateTag;
use App\Actions\Tags\ManufactureTagBatch;
use App\Enums\TagStatus;
use App\Enums\UserRole;
use App\Exceptions\TagActivationException;
use App\Models\Pet;
use App\Models\Tag;
use App\Models\User;
use App\Support\PublicTokenGenerator;
use Illuminate\Support\Facades\Hash;

test('tag public token is unique and unpredictable', function () {
    $token1 = PublicTokenGenerator::generate();
    $token2 = PublicTokenGenerator::generate();

    expect($token1)->toHaveLength(32);
    expect($token1)->not->toBe($token2);
    expect(is_numeric($token1))->toBeFalse();
});

test('valid activation assigns tag to pet', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'TEST-001', 1);
    $plaintext = $result['plaintext_codes'][0];
    $tag = $result['tags'][0];

    $activated = app(ActivateTag::class)->execute($user, $plaintext, $pet);

    expect($activated->status)->toBe(TagStatus::Activated);
    expect($activated->activeAssignment->pet_id)->toBe($pet->id);
    expect($tag->activationCode->fresh()->isUsed())->toBeTrue();
});

test('reused activation code fails', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'TEST-002', 1);
    $plaintext = $result['plaintext_codes'][0];

    app(ActivateTag::class)->execute($user, $plaintext, $pet);

    expect(fn () => app(ActivateTag::class)->execute($user, $plaintext, $pet))
        ->toThrow(TagActivationException::class);
});

test('invalid activation code fails', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();

    expect(fn () => app(ActivateTag::class)->execute($user, 'INVALIDCODE1', $pet))
        ->toThrow(TagActivationException::class);
});

test('disabled tag cannot be activated via manufactured status', function () {
    $tag = Tag::factory()->disabled()->create();
    $tag->activationCode()->create(['code_hash' => Hash::make('TESTCODE1234')]);

    expect($tag->isActivatable())->toBeFalse();
});

test('owner cannot activate tag for another users pet', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $pet = Pet::factory()->for($other)->create();
    $operator = User::factory()->create(['role' => UserRole::Admin]);

    $result = app(ManufactureTagBatch::class)->execute($operator, 'TEST-003', 1);
    $plaintext = $result['plaintext_codes'][0];

    expect(fn () => app(ActivateTag::class)->execute($owner, $plaintext, $pet))
        ->toThrow(TagActivationException::class);
});

test('tag public url uses token not pet id', function () {
    $tag = Tag::factory()->create(['public_token' => 'xyzuniquepublictokenvalue']);
    $pet = Pet::factory()->create();

    expect($tag->publicUrl())->toBe(url('/t/xyzuniquepublictokenvalue'));
    expect($tag->publicUrl())->not->toContain('/pets/'.$pet->id);
});
