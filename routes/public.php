<?php

use App\Http\Controllers\PublicTagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:public-scan'])->group(function () {
    Route::get('/t/{token}', PublicTagController::class)->name('tags.public');
});
