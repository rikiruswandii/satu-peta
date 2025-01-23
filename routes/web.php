<?php

use App\Http\Controllers\Panel\Dashboard;
use App\Http\Controllers\Panel\User\Detail;
use App\Http\Controllers\Panel\User\Log;
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
            Route::post('/reset', [Users::class, 'reset'])->name('users.reset');
            Route::delete('/destroy', [Users::class, 'destroy'])->name('users.destroy');
            Route::post('/store', [Users::class, 'store'])->name('users.store');
        });
        
        Route::prefix('user')->group(function () {
            Route::get('/detail/{id}', [Detail::class, 'index'])->name('user.detail');
            Route::get('/log/{id}', [Log::class, 'userLog'])->name('user.log');
            Route::post('/update', [Detail::class, 'update'])->name('user.update');
            Route::post('/photo', [Detail::class, 'photo'])->name('user.photo');
            Route::post('/change', [Detail::class, 'change'])->name('user.change');
            Route::delete('/destroy', [Detail::class, 'destroy'])->name('user.destroy');
        });
    }
);


require __DIR__ . '/auth.php';
