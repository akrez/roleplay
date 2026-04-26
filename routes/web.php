<?php

use App\Http\Controllers\GameHokmController;
use App\Http\Controllers\GameXoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFriendshipController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('user_friendships', UserFriendshipController::class)->parameter('user_friendships', 'id')->only(['index', 'store', 'destroy']);
    Route::prefix('user_friendships')->name('user_friendships.')->group(function () {
        Route::post('/{id}/{status}', [UserFriendshipController::class, 'status'])->name('status');
    });
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/filter', [UserController::class, 'filter'])->name('filter')->withoutMiddleware(VerifyCsrfToken::class);
});

Route::prefix('game_xos')->name('game_xos.')->group(function () {
    Route::get('/{id}/board/{player}/{token}', [GameXoController::class, 'board'])->name('board');
    Route::middleware('auth')->group(function () {
        Route::get('/', [GameXoController::class, 'index'])->name('index');
        Route::post('/', [GameXoController::class, 'store'])->name('store');
    });
});

Route::prefix('game_hokms')->name('game_hokms.')->group(function () {
    Route::get('/{id}/board/{player}/{token}', [GameHokmController::class, 'board'])->name('board');
    Route::middleware('auth')->group(function () {
        Route::get('/', [GameHokmController::class, 'index'])->name('index');
        Route::post('/', [GameHokmController::class, 'store'])->name('store')->withoutMiddleware(VerifyCsrfToken::class);
    });
});
