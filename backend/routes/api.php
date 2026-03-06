<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\VisitorCounterController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/visitor-count', [VisitorCounterController::class, 'getSimpleCount']);
Route::post('/visitor/track', [VisitorCounterController::class, 'trackVisitor']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/roles', [UserController::class, 'roles']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::patch('/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});
