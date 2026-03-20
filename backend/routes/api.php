<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\VisitorCounterController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Resident\LuponCasesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Resident\ServiceRequestController as ResidentServiceRequestController;
use App\Http\Controllers\Lupon\LuponCasesController as LuponController;

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
        Route::post('/appoint', [UserController::class, 'appoint']);
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
        Route::get('/', [ServiceRequestController::class, 'index']);
        Route::put('/{id}/approve', [ServiceRequestController::class, 'approve']);
        Route::put('/{id}/reject', [ServiceRequestController::class, 'reject']);
        Route::put('/{id}/release', [ServiceRequestController::class, 'release']);
    });

    Route::prefix('lupon')->group(function () {
        Route::get('/cases',                    [LuponController::class, 'index']);
        Route::get('/cases/approved',           [LuponController::class, 'approvedCases']);
        Route::get('/cases/{id}',               [LuponController::class, 'show']);
        Route::put('/cases/{id}/approve',       [LuponController::class, 'approve']);
        Route::put('/cases/{id}/disapprove',    [LuponController::class, 'disapprove']);
        Route::put('/cases/{id}/close',         [LuponController::class, 'close']);
        Route::post('/cases/{id}/summon',       [LuponController::class, 'summon']);
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

        Route::get('/lupon-cases', [LuponCasesController::class, 'adminIndex']);
    });

    Route::prefix('resident')->group(function () {
        Route::get('/service-requests', [ResidentServiceRequestController::class, 'index']);
        Route::post('/service-requests', [ResidentServiceRequestController::class, 'store']);
        Route::post('/service-requests/{id}/resubmit', [ResidentServiceRequestController::class, 'resubmit']);
        Route::delete('/service-requests/{id}/cancel', [ResidentServiceRequestController::class, 'cancel']);

        Route::get('/lupon-cases',          [LuponCasesController::class, 'index']);
        Route::post('/lupon-cases',         [LuponCasesController::class, 'store']);
        Route::get('/lupon-cases/{id}',     [LuponCasesController::class, 'viewCase']);
        Route::delete('/lupon-cases/{id}',  [LuponCasesController::class, 'destroy']);
    });
});
