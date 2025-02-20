<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::controller(Api\AuthController::class)->prefix('auth')->as('auth.')->group(function () {
    Route::post('login', 'login')->name('login');

    Route::delete('logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(Api\MapController::class)
        ->prefix('map')
        ->as('map.')
        ->group(function () {
            Route::get('/', 'index')->name('data');
        });
});
