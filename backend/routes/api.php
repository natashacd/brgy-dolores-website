<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\VisitorCounterController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\ServiceRequestController;
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

    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/roles', [UserController::class, 'roles']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::patch('/{id}/reset-password', [UserController::class, 'resetPassword']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Resident Management Routes
    Route::prefix('residents')->group(function () {
        Route::get('/', [ResidentController::class, 'index']);
        Route::get('/roles', [ResidentController::class, 'roles']);
        Route::post('/', [ResidentController::class, 'store']);
        Route::put('/{id}', [ResidentController::class, 'update']);
        Route::patch('/{id}/reset-password', [ResidentController::class, 'resetPassword']);
        Route::delete('/{id}', [ResidentController::class, 'destroy']);
    });

    Route::prefix('admin/service-requests')->group(function () {
        Route::get('/staff', [ServiceRequestController::class, 'getStaff']);
        Route::get('/', [ServiceRequestController::class, 'index']);
        Route::get('/{id}', [ServiceRequestController::class, 'show']);
        Route::put('/{id}/reassign', [ServiceRequestController::class, 'reassign']);
        Route::put('/{id}/approve', [ServiceRequestController::class, 'approve']);
        Route::put('/{id}/cancel', [ServiceRequestController::class, 'cancel']);
    });

    // Admin API routes
    Route::prefix('admin')->group(function () {
        // TRASH ROUTES - MUST COME FIRST
        Route::get('/announcements/trashed', [AnnouncementController::class, 'trashed']);
        Route::post('/announcements/bulk-restore', [AnnouncementController::class, 'bulkRestore']);
        Route::post('/announcements/bulk-force-delete', [AnnouncementController::class, 'bulkForceDelete']);
        Route::post('/announcements/empty-trash', [AnnouncementController::class, 'emptyTrash']);

        // SINGLE ANNOUNCEMENT TRASH OPERATIONS
        Route::post('/announcements/{id}/restore', [AnnouncementController::class, 'restore']);
        Route::delete('/announcements/{id}/force-delete', [AnnouncementController::class, 'forceDelete']);

        // REGULAR ANNOUNCEMENT ROUTES
        Route::get('/announcements', [AnnouncementController::class, 'index']);
        Route::post('/announcements', [AnnouncementController::class, 'store']);
        Route::get('/announcements/{id}', [AnnouncementController::class, 'show']);
        Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);

        // OTHER ANNOUNCEMENT ROUTES
        Route::post('/announcements/{id}/toggle-urgent', [AnnouncementController::class, 'toggleUrgent']);
        Route::post('/announcements/{id}/duplicate', [AnnouncementController::class, 'duplicate']);
        Route::post('/announcements/bulk-delete', [AnnouncementController::class, 'bulkDelete']);
        Route::post('/announcements/bulk-update-status', [AnnouncementController::class, 'bulkUpdateStatus']);
    });
});
