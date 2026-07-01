<?php

use App\Enums\PetSpecies;
use App\Models\Pet;
use App\Models\User;
use App\Support\PublicPetProfile;
use Livewire\Livewire;

test('owner can create a pet', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('pets.create'))
        ->assertOk();

    Livewire::actingAs($user)
        ->test('pages::owner.pet-create')
        ->set('name', 'Firulais')
        ->set('species', 'dog')
        ->call('save')
        ->assertRedirect();

    expect($user->pets()->count())->toBe(1);
    expect($user->pets()->first()->publicSettings)->not->toBeNull();
});

test('owner cannot view another users pet', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $pet = Pet::factory()->for($owner)->create();

    $this->actingAs($other)
        ->get(route('pets.edit', $pet))
        ->assertForbidden();
});

test('public profile respects privacy settings', function () {
    $pet = Pet::factory()->create([
        'name' => 'Luna',
        'species' => PetSpecies::Cat,
        'description' => 'Muy cariñosa',
    ]);

    $pet->publicSettings->update([
        'show_name' => true,
        'show_description' => false,
        'show_species' => true,
    ]);

    $profile = PublicPetProfile::build($pet->fresh());

    expect($profile['name'])->toBe('Luna');
    expect($profile['species'])->toBe('Gato');
    expect($profile['description'])->toBeNull();
});

test('private contact phone is not in public profile', function () {
    $pet = Pet::factory()->create();

    $pet->publicSettings->update(['show_contacts' => true]);

    $pet->contacts()->create([
        'type' => 'primary',
        'name' => 'Juan',
        'phone' => '5551234567',
        'show_phone' => false,
        'sort_order' => 0,
    ]);

    $profile = PublicPetProfile::build($pet->fresh());

    expect($profile['contacts'])->toHaveCount(0);
});

test('public contact appears when enabled', function () {
    $pet = Pet::factory()->create();

    $pet->publicSettings->update(['show_contacts' => true]);

    $pet->contacts()->create([
        'type' => 'primary',
        'name' => 'Juan',
        'phone' => '5551234567',
        'show_phone' => true,
        'sort_order' => 0,
    ]);

    $profile = PublicPetProfile::build($pet->fresh());

    expect($profile['contacts'])->toHaveCount(1);
    expect($profile['contacts']->first()['phone'])->toBe('5551234567');
});

test('owner can update privacy settings', function () {
    $user = User::factory()->create();
    $pet = Pet::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test('pages::owner.pet-privacy', ['pet' => $pet])
        ->set('show_photo', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($pet->fresh()->publicSettings->show_photo)->toBeTrue();
});
