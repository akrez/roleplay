<?php

use App\Http\Controllers\Api\GameHokmController;
use App\Http\Controllers\Api\GameXoController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::prefix('game_xos')->name('game_xos.')->group(function () {
        Route::get('/{id}/board/{player}/{token}', [GameXoController::class, 'board'])->name('board');
        Route::post('/{id}/play/{player}/{token}', [GameXoController::class, 'play'])->name('play');
    });
    Route::prefix('game_hokms')->name('game_hokms.')->group(function () {
        Route::post('/{id}/quote/{player}/{token}', [GameHokmController::class, 'quote'])->name('quote');
        Route::get('/{id}/modification/{player}/{token}', [GameHokmController::class, 'modification'])->name('modification');
        Route::get('/{id}/board/{player}/{token}', [GameHokmController::class, 'board'])->name('board');
        Route::post('/{id}/play/{player}/{token}', [GameHokmController::class, 'play'])->name('play');
    });
});
