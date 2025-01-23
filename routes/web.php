<?php

use App\Http\Controllers\Panel\Dashboard;
use App\Http\Controllers\Panel\Users;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('panel')->middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/dashboard', [Dashboard::class, 'index'])->name('/');

        Route::prefix('users')->group(function () {
            Route::get('/', [Users::class, 'index'])->name('users');
            Route::get('/table', [Users::class, 'getDataTable'])->name('users.table');
            Route::get('/detail/{id}', [Users::class, 'detail'])->name('users.detail');
            Route::post('/reset', [Users::class, 'reset'])->name('users.reset');
            Route::delete('/destroy', [Users::class, 'destroy'])->name('users.destroy');
            Route::post('/store', [Users::class, 'store'])->name('users.store');
        });
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
