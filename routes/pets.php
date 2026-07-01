<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('mascotas')->name('pets.')->group(function () {
    Route::livewire('/', 'pages::owner.pets-index')->name('index');
    Route::livewire('/crear', 'pages::owner.pet-create')->name('create');
    Route::livewire('/{pet}/editar', 'pages::owner.pet-edit')->name('edit');
    Route::livewire('/{pet}/privacidad', 'pages::owner.pet-privacy')->name('privacy');
    Route::livewire('/{pet}/contactos', 'pages::owner.pet-contacts')->name('contacts');
    Route::livewire('/{pet}/vista-previa', 'pages::owner.pet-preview')->name('preview');
});
