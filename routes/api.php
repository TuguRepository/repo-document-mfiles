<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\STK\ApprovalController;
use App\Http\Controllers\DownloadRequestController;
use App\Http\Controllers\STKDocumentController;
use App\Http\Controllers\MFilesAuthController;
use App\Http\Controllers\STK\DocumentController;
Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/stk/summary', [STKDocumentController::class, 'getSTKSummary']);
Route::get('/mfiles/test_watermark', [App\Http\Controllers\STKDocumentController::class, 'test_watermark']);

Route::post('/mfiles/login', [App\Http\Controllers\MFilesAuthController::class, 'login']);
Route::post('/mfiles/logout', [App\Http\Controllers\MFilesAuthController::class, 'logout']);

// Download Request API Routes
// Route::middleware(['auth'])->group(function () {
// Submit download request
Route::post('/api/stk/download-requests', [DownloadRequestController::class, 'store'])->name('api.stk.download-requests.store');

// Get user's download requests
Route::get('/api/stk/download-requests', [DownloadRequestController::class, 'index'])->name('api.stk.download-requests.index');

// Get specific request details
Route::get('/api/stk/download-requests/{request}', [DownloadRequestController::class, 'show'])->name('api.stk.download-requests.show');
// });

// Notification Routes
// Route::middleware(['auth'])->group(function () {
// Get notifications for current user
Route::get('/api/stk/notifications', [DownloadRequestController::class, 'notifications'])->name('api.stk.notifications');

// Mark notification as read
Route::post('/api/stk/notifications/{notification}/read', [DownloadRequestController::class, 'markAsRead'])->name('api.stk.notifications.read');
// });

// Download Document (only after approval)
// Route::middleware(['auth'])->group(function () {
// Download approved document
Route::get('/stk/download/{id}/{version}', [DownloadRequestController::class, 'download'])
    ->name('stk.download');
// });

// Notification API Routes
// Route::middleware(['auth:sanctum'])->group(function () {
// Get pending approval count for admin
Route::get('/stk/pending-count', [ApprovalController::class, 'getPendingCount'])
    ->name('api.stk.pending-count');
// });

// Download Request API Routes
Route::post('/stk/download-requests', [App\Http\Controllers\DownloadRequestController::class, 'store'])
    ->name('api.download-requests.store');

// Di routes/api.php
Route::get('/stk/simple-search', [App\Http\Controllers\STKDocumentController::class, 'simpleSearchDocuments']);

// Perbaiki route yang konflik dengan menggunakan ApprovalController secara langsung
// dari namespace App\Http\Controllers\STK tanpa alias tambahan
Route::post('/approve', [ApprovalController::class, 'approve']);
// Route::middleware(['auth.jwt'])->group(function () {
//     // Approval endpoints
//     Route::post('/approve', [ApprovalController::class, 'approve']);
//     Route::post('/reject', [ApprovalController::class, 'reject']);
//     Route::get('/counts', [ApprovalController::class, 'getCounts']);
//     Route::get('/requests', [ApprovalController::class, 'getRequests']);
//     Route::get('/activities', [ApprovalController::class, 'getActivities']);

//     // Document endpoints - Sesuaikan dengan method yang ada di controller
//     Route::get('/document/{id}/preview', [ApprovalController::class, 'previewDocument']);
// });
