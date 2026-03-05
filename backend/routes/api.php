<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitorCounterController;

Route::prefix('v1')->group(function () {
    // Visitor counter routes
    Route::get('/visitor-count', [VisitorCounterController::class, 'getSimpleCount']);
    Route::post('/visitor/track', [VisitorCounterController::class, 'trackVisitor']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
