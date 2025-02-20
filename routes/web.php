<?php

use App\Http\Controllers\Download;
use App\Http\Controllers\Guest\Article as GuestArticle;
use App\Http\Controllers\Guest\Explorer;
use App\Http\Controllers\Guest\Home;
use App\Http\Controllers\Guest\Search;
use App\Http\Controllers\Panel\Article;
use App\Http\Controllers\Panel\Dashboard;
use App\Http\Controllers\Panel\DatasetsCategory;
use App\Http\Controllers\Panel\Grup;
use App\Http\Controllers\Panel\Map;
use App\Http\Controllers\Panel\User\Detail;
use App\Http\Controllers\Panel\User\Log;
use App\Http\Controllers\Panel\Users;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', [Home::class, 'index'])->name('/');
Route::get('/explorer', [Explorer::class, 'index'])->name('explorer');
Route::get('/search', [Search::class, 'index'])->name('search');
Route::get('/get-maps-by-viewport', [Search::class, 'getMapsByViewport'])->name('get-maps-by-viewport');
Route::get('article/list', [GuestArticle::class, 'index'])->name('article.list');
Route::get('article/detail/{article_slug}', [GuestArticle::class, 'show'])->name('article.show');
Route::get('article/category/{category_slug}', [GuestArticle::class, 'category'])->name('article.category');

Route::prefix('panel')->middleware(['auth', 'verified'])->group(
    function () {
        Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
        Route::get('/download/{id}', action: [Download::class, 'download'])->name('download');
        Route::prefix('logs')->group(function () {
            Route::get('/', [Log::class, 'index'])->name('logs');
            Route::get('/datatable', [Log::class, 'datatable'])->name('logs.datatable');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [Users::class, 'index'])->name('users');
            Route::get('/datatable', [Users::class, 'datatable'])->name('users.datatable');
            Route::get('/table', [Users::class, 'getDataTable'])->name('users.table');
            Route::post('/reset', [Users::class, 'reset'])->name('users.reset');
            Route::delete('/destroy', [Users::class, 'destroy'])->name('users.destroy');
            Route::post('/store', [Users::class, 'store'])->name('users.store');
        });

        Route::prefix('user')->group(function () {
            Route::get('/detail/{id}', [Detail::class, 'index'])->name('user.detail');
            Route::get('/log/{id}', [Log::class, 'userLog'])->name('user.log');
            Route::get('/log/{id}/user', [Log::class, 'datatable_id'])->name('user.datatable');
            Route::post('/update/{id}', [Detail::class, 'update'])->name('user.update');
            Route::post('/photo/{id}', [Detail::class, 'photo'])->name('user.photo');
            Route::post('/change/{id}', [Detail::class, 'change'])->name('user.change');
            Route::delete('/destroy', [Detail::class, 'destroy'])->name('user.destroy');
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [Settings::class, 'index'])->name('settings');
            Route::get('/datatable', [Settings::class, 'datatable'])->name('related.link.datatable');
            Route::post('/store', [Settings::class, 'store'])->name('settings.store');
            Route::post('/update', [Settings::class, 'update'])->name('settings.update');
            Route::delete('/destroy', [Settings::class, 'destroy'])->name('settings.destroy');
            Route::post('/related-link-update', [Settings::class, 'updateTautan'])->name('related.link.update');
        });

        Route::prefix('articles')->group(function () {
            Route::get('/', [Article::class, 'index'])->name('articles');
            Route::get('/datatable', [Article::class, 'datatable'])->name('articles.datatable');
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
            Route::get('/datatable', [Map::class, 'datatable'])->name('maps.datatable');
            Route::post('/store', [Map::class, 'store'])->name('maps.store');
            Route::post('/update', [Map::class, 'update'])->name('maps.update');
            Route::delete('/destroy', [Map::class, 'destroy'])->name('maps.destroy');
            Route::post('/activated', [Map::class, 'activate'])->name('maps.activate');
            Route::get('maps/{map}/download/{id}', [Map::class, 'download'])->name('maps.download');
        });

        // grup
        Route::prefix('groups')->group(function () {
            Route::get('/', [Grup::class, 'index'])->name('groups');
            Route::get('/datatable', [Grup::class, 'datatable'])->name('groups.datatable');
            Route::get('/sync', [Grup::class, 'sync'])->name('groups.sync');
            Route::post('/store', [Grup::class, 'store'])->name('groups.store');
            Route::post('/update', [Grup::class, 'update'])->name('groups.update');
            Route::delete('/destroy', [Grup::class, 'destroy'])->name('groups.destroy');
        });

        // dataset categories
        Route::prefix('datasets')->group(function () {
            Route::get('/', [DatasetsCategory::class, 'index'])->name('datasets');
            Route::get('/datatable', [DatasetsCategory::class, 'datatable'])->name('datasets.datatable');
            Route::post('/store', [DatasetsCategory::class, 'store'])->name('datasets.store');
            Route::post('/update', [DatasetsCategory::class, 'update'])->name('datasets.update');
            Route::delete('/destroy', [DatasetsCategory::class, 'destroy'])->name('datasets.destroy');
        });
    }
);

require __DIR__.'/auth.php';
