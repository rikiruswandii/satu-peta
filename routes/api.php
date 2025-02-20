<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::controller(Api\AuthController::class)->prefix('auth')->as('auth.')->group(function () {
    Route::post('login', 'login')->name('login');

    Route::delete('logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::controller(Api\ProfileController::class)
    //     ->prefix('profile')
    //     ->as('profile.')
    //     ->group(function () {
    //         Route::get('/', 'index')->name('detail');
    //         Route::put('/', 'update')->name('update');
    //     });

    // Route::controller(Api\ComplaintController::class)
    //     ->prefix('complaints')
    //     ->as('complaints.')
    //     ->group(function () {
    //         Route::get('/', 'index')->name('list');
    //         Route::post('/', 'create')->name('create');
    //         Route::get('{id}', 'detail')->name('detail')->where(['id' => '[0-9]+']);
    //         Route::put('{id}', 'update')->name('update')->where(['id' => '[0-9]+']);
    //         Route::put('/request_access/{id}', 'request_access')->name('request_access')->where(['id' => '[0-9]+']);
    //         Route::post('/report/{id}', 'report')->name('report')->where(['id' => '[0-9]+']);
    //     });

    // Route::controller(Api\ComplaintTypeController::class)
    //     ->prefix('complaint-types')
    //     ->as('complaint-types.')
    //     ->group(function () {
    //         Route::get('/', 'index')->name('list');
    //         Route::post('/', 'create')->name('create');
    //     });

    // Route::controller(Api\ComplaintWeirController::class)
    //     ->prefix('complaint-weirs')
    //     ->as('complaint-weirs.')
    //     ->group(function () {
    //         Route::get('/', 'index')->name('list');
    //     });

    // Route::controller(Api\DocumentController::class)
    //     ->prefix('documents')
    //     ->as('documents')
    //     ->group(function () {
    //         Route::post('/', 'upload')->name('upload');
    //         Route::get('{id}', 'detail')->name('detail');
    //     });
});
