<?php

use App\Http\Controllers\Panel\Article;
use App\Http\Controllers\Panel\Dashboard;
use App\Http\Controllers\Panel\Map;
use App\Http\Controllers\Panel\User\Detail;
use App\Http\Controllers\Panel\User\Log;
use App\Http\Controllers\Panel\Users;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('panel')->middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/dashboard', [Dashboard::class, 'index'])->name('/');
        Route::get('/logs', [Log::class, 'index'])->name('logs');

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
            Route::post('/update/{id}', [Detail::class, 'update'])->name('user.update');
            Route::post('/photo/{id}', [Detail::class, 'photo'])->name('user.photo');
            Route::post('/change/{id}', [Detail::class, 'change'])->name('user.change');
            Route::delete('/destroy', [Detail::class, 'destroy'])->name('user.destroy');
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [Settings::class, 'index'])->name('settings');
            Route::post('/store', [Settings::class, 'store'])->name('settings.store');
            Route::post('/update', [Settings::class, 'update'])->name('settings.update');
            Route::delete('/destroy', [Settings::class, 'destroy'])->name('settings.destroy');
            Route::post('/related-link-update', [Settings::class, 'updateTautan'])->name('related.link.update');
        });

        Route::prefix('articles')->group(function () {
            Route::get('/', [Article::class, 'index'])->name('articles');
            Route::get('/create', [Article::class, 'create'])->name('articles.create');
            Route::post('/store', [Article::class, 'store'])->name('articles.store');
            Route::get('/edit/{id}', [Article::class, 'edit'])->name('articles.edit');
            Route::post('/update/{id}', [Article::class, 'update'])->name('articles.update');
            Route::delete('/destroy', [Article::class, 'destroy'])->name('articles.destroy');
            Route::post('/category-store', [Article::class, 'category_store'])->name('category.store');
            Route::post('/category-update/{id}', [Article::class, 'category_update'])->name('category.update');
            Route::delete('/category-destroy', [Article::class, 'category_destroy'])->name('category.destroy');
        });
        
        Route::prefix('maps')->group(function () {
            Route::get('/', [Map::class, 'index'])->name('maps');
            Route::get('/create', [Map::class, 'create'])->name('maps.create');
            Route::post('/store', [Map::class, 'store'])->name('maps.store');
            Route::get('/edit/{id}', [Map::class, 'edit'])->name('maps.edit');
            Route::post('/update/{id}', [Map::class, 'update'])->name('maps.update');
            Route::delete('/destroy', [Map::class, 'destroy'])->name('maps.destroy');
            Route::post('/regional-agency-store', [Map::class, 'regional_agency_store'])->name('regional.agency.store');
            Route::post('/regional-agency-update/{id}', [Map::class, 'regional_agency_update'])->name('regional.agency.update');
            Route::delete('/regional-agency-destroy', [Map::class, 'regional_agency_destroy'])->name('regional.agency.destroy');
            Route::post('/sector-store', [Map::class, 'sector_store'])->name('sector.store');
            Route::post('/sector-update/{id}', [Map::class, 'sector_update'])->name('sector.update');
            Route::delete('/sector-destroy', [Map::class, 'sector_destroy'])->name('sector.destroy');
        });
    }
);


require __DIR__ . '/auth.php';