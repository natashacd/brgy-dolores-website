<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitorCounterController;

Route::prefix('v1')->group(function () {
    // Visitor counter routes
    Route::get('/visitor-count', [VisitorCounterController::class, 'getCount']);
    Route::post('/visitor-count/increment', [VisitorCounterController::class, 'incrementCount']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
