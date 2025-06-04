<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\FavoriteGifController;
use App\Http\Controllers\Api\GifController;
use App\Http\Controllers\Api\ServiceLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());

// Auth
Route::post('login', [AuthenticationController::class, 'store']);
Route::middleware('auth:api')->post('logout', [AuthenticationController::class, 'destroy']);

// Gifs
Route::prefix('gifs')->middleware('auth:api')->group(function () {
    Route::get('search', [GifController::class, 'search']);
    Route::get('{id}', [GifController::class, 'show']);
    Route::get('favorite/{id}', [GifController::class, 'showFavorite']);
    Route::post('favorite', [FavoriteGifController::class, 'store']);
});

// Logs
Route::middleware('auth:api')->get('logs', [ServiceLogController::class, 'index']);