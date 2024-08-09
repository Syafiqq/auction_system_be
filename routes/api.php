<?php

use App\Presentation\Http\Controllers\AuctionItemController;
use App\Presentation\Http\Controllers\AuthController;
use App\Presentation\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::any('/', function () {
    return response()->json((object)[]);
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'statelessLogin'])
        ->name('api.auth.login');
});

Route::prefix('profile')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('', [UserController::class, 'index'])
            ->name('api.profile.index');
    });

Route::prefix('auction')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('', [AuctionItemController::class, 'index'])
            ->name('api.auction.index');
        Route::post('', [AuctionItemController::class, 'store'])
            ->name('api.auction.store');
        Route::get('{id}', [AuctionItemController::class, 'show'])
            ->name('api.auction.show');
        Route::put('{id}', [AuctionItemController::class, 'update'])
            ->name('api.auction.update');
        Route::delete('{id}', [AuctionItemController::class, 'delete'])
            ->name('api.auction.delete');
    });
