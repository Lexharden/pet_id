<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/activar', 'pages::owner.tag-activate')->name('tags.activate');
    Route::livewire('/mascotas/{pet}/placas', 'pages::owner.pet-tags')->name('pets.tags');
    Route::livewire('/mascotas/{pet}/escaneos', 'pages::owner.pet-scans')->name('pets.scans');
    Route::livewire('/mascotas/{pet}/estado', 'pages::owner.pet-status')->name('pets.status');
});
