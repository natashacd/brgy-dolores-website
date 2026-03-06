<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\VisitorCounterController;
use App\Http\Controllers\Api\AnnouncementController;
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

// API routes
Route::prefix('admin')->group(function () {
    // Announcement routes
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show']);
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);
    
    // Additional announcement actions
    Route::post('/announcements/{id}/toggle-urgent', [AnnouncementController::class, 'toggleUrgent']);
    Route::post('/announcements/{id}/duplicate', [AnnouncementController::class, 'duplicate']);
    Route::post('/announcements/bulk-delete', [AnnouncementController::class, 'bulkDelete']);
    Route::post('/announcements/bulk-update-status', [AnnouncementController::class, 'bulkUpdateStatus']);
});

    
});
