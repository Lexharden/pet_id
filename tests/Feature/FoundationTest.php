<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

test('home page loads in spanish', function () {
    app()->setLocale('es');

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Protege a tu mascota con una placa inteligente', false);
});

test('new users have owner role by default', function () {
    $user = User::factory()->create();

    expect($user->role)->toBe(UserRole::Owner);
    expect($user->isAdmin())->toBeFalse();
});

test('user implements must verify email', function () {
    $user = User::factory()->unverified()->create();

    expect($user)->toBeInstanceOf(MustVerifyEmail::class);
    expect($user->hasVerifiedEmail())->toBeFalse();
});

test('app locale defaults to spanish', function () {
    expect(config('app.locale'))->toBe('es');
    expect(config('app.display_timezone'))->toBe('America/Mexico_City');
});
