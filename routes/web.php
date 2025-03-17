<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\STKDocumentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DownloadRequestController;
use App\Http\Controllers\STK\ApprovalController;

Route::prefix('api')->group(function () {
    // Route untuk mendapatkan ringkasan data STK
    Route::get('/stk/summary', [App\Http\Controllers\STKDocumentController::class, 'getSTKSummary']);
});



Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/stk/database', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/dashboardstk', function () {
    return view('dashboardstk');
});


Route::get('/mfiles/token', [App\Http\Controllers\MFilesAuthController::class, 'getAuthToken'])->middleware('auth');

// STK Document Routes - Fixed to use consistent format
Route::get('/stk', [STKDocumentController::class, 'index'])->name('stk.index');
Route::get('/stk/documents', [STKDocumentController::class, 'getDocuments'])->name('stk.documents');
Route::get('/stk/documents/{objectId}/content/{version?}', [STKDocumentController::class, 'getFileContent'])
    ->name('stk.documents.content')
    ->where(['objectId' => '[0-9]+']);
Route::get('/stk/documents/{objectId}/preview/{version?}', [STKDocumentController::class, 'getPreview'])
    ->name('stk.documents.preview')
    ->where(['objectId' => '[0-9]+']);
Route::get('/stk/debug', [STKDocumentController::class, 'debugConnection'])->name('stk.debug');
Route::get('stk/count', [STKDocumentController::class, 'countSTKDocuments']);
// In your routes/web.php file
Route::get('/mfiles/token', [App\Http\Controllers\MFilesAuthController::class, 'getAuthToken'])->middleware('auth');
Route::post('/mfiles/verify', [App\Http\Controllers\MFilesAuthController::class, 'verifyToken']);
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents', [App\Http\Controllers\DocumentController::class, 'index']);
Route::get('/stk/summary', [App\Http\Controllers\STKDocumentController::class, 'getSTKSummary']);
Route::get('/stk', [App\Http\Controllers\STKDocumentController::class, 'index']);
Route::get('/stk/preview/{objectId}/{version?}', [App\Http\Controllers\STKDocumentController::class, 'getPreview']);
Route::get('/stk/download/{objectId}/{version?}', [App\Http\Controllers\STKDocumentController::class, 'getFileContent']);
Route::get('/stk/file/{objectId}', [STKDocumentController::class, 'getFileContent'])->name('stk.file');
// Di routes/web.php
Route::get('/stk/database', [App\Http\Controllers\DatabaseController::class, 'index'])->name('stk.database');
// Routes untuk kategori dokumen
Route::get('/stk/category/pedoman', [App\Http\Controllers\STKDocumentController::class, 'showCategoryPedoman'])->name('stk.category.pedoman');
Route::get('/stk/category/tko', [App\Http\Controllers\STKDocumentController::class, 'showCategoryTKO'])->name('stk.category.tko');
Route::get('/stk/category/tki', [App\Http\Controllers\STKDocumentController::class, 'showCategoryTKI'])->name('stk.category.tki');
Route::get('/stk/category/bpcp', [App\Http\Controllers\STKDocumentController::class, 'showCategoryBPCP'])->name('stk.category.bpcp');
Route::get('/stk/category/sop', [App\Http\Controllers\STKDocumentController::class, 'showCategorySOP'])->name('stk.category.sop');

// Route untuk dokumen berdasarkan tahun
Route::get('/stk/year/{year}', [App\Http\Controllers\STKDocumentController::class, 'showDocumentsByYear'])->name('stk.year');

// API untuk mengambil dokumen berdasarkan kategori
Route::get('/api/stk/documents', [App\Http\Controllers\STKDocumentController::class, 'getDocumentsByCategory']);
// API untuk mengambil dokumen berdasarkan tahun
Route::get('/stk/documents-by-year', [App\Http\Controllers\STKDocumentController::class, 'getDocumentsByYear']);
Route::get('/api/stk/diagnostic', [App\Http\Controllers\STKDocumentController::class, 'diagnosticDocuments']);
// API untuk dokumen featured (3 tahun terakhir)
Route::get('/stk/featured-documents', [App\Http\Controllers\STKDocumentController::class, 'getFeaturedDocuments']);

// API untuk pencarian dokumen
Route::get('/stk/search', [App\Http\Controllers\STKDocumentController::class, 'searchDocuments']);
// API untuk pencarian dokumen (alternatif)
Route::get('/stk/simple-search', [App\Http\Controllers\STKDocumentController::class, 'simpleSearchDocuments']);
Route::get('/api/stk/document-info/{objectId}/{version?}', [STKDocumentController::class, 'getDocumentInfo']);
// Admin Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Approval Dashboard
    Route::get('/stk/approvals', [App\Http\Controllers\STK\ApprovalController::class, 'index'])->name('stk.approvals.index');

    // Approval Actions
    Route::post('/stk/approvals/{request}/approve', [App\Http\Controllers\STK\ApprovalController::class, 'approve'])->name('stk.approvals.approve');
    // Tambahkan ini di file routes/web.php
Route::post('stk/approvals/reject', [ApprovalController::class, 'reject'])->name('stk.approvals.reject');
    // Request Details
    Route::get('/stk/approvals/{request}', [App\Http\Controllers\STK\ApprovalController::class, 'show'])->name('stk.approvals.show');
});

    Route::post('stk/approvals/{request}/approve', [ApprovalController::class, 'approve'])->name('stk.approvals.approve');
    // Di routes/web.php
    Route::post('stk/approvals/approve', [ApprovalController::class, 'approve'])->name('stk.approvals.approve');

    // Add these routes to your existing routes/web.php file
    // Inside the middleware group for authenticated users and admin role

    // Routes for approval counts and statistics
    Route::prefix('stk/approval')->name('stk.approval.')->middleware(['auth', 'role:admin'])->group(function () {
        // Get all counts
        Route::get('/counts', [App\Http\Controllers\STK\ApprovalController::class, 'getRequestCounts'])
            ->name('counts');

        // Get pending count only
        Route::get('/count/pending', [App\Http\Controllers\STK\ApprovalController::class, 'getPendingCount'])
            ->name('count.pending');

        // Get specific status count
        Route::get('/count/{status}', [App\Http\Controllers\STK\ApprovalController::class, 'getStatusCount'])
            ->name('count.status');

        // Get dashboard statistics
        Route::get('/dashboard-stats', [App\Http\Controllers\STK\ApprovalController::class, 'getDashboardStats'])
            ->name('dashboard.stats');

        // Detailed statistics
        Route::get('/statistics', [App\Http\Controllers\STK\StatisticsController::class, 'getDetailedStats'])
            ->name('statistics');

        // Detailed statistics with filter
        Route::post('/statistics/filter', [App\Http\Controllers\STK\StatisticsController::class, 'getDetailedStats'])
            ->name('statistics.filter');
    });
    // User download request routes
Route::middleware(['auth'])->group(function () {
    // List user's own requests
    Route::get('/download-requests', [App\Http\Controllers\DownloadRequestController::class, 'index'])
        ->name('download-requests.index');

    // Store new request
    Route::post('/download-requests', [App\Http\Controllers\DownloadRequestController::class, 'store'])
        ->name('download-requests.store');

    // View details of a specific request
    Route::get('/download-requests/{id}', [App\Http\Controllers\DownloadRequestController::class, 'show'])
        ->name('download-requests.show');

    // Download a document with token
    Route::get('/download/{token}', [App\Http\Controllers\DownloadRequestController::class, 'download'])
        ->name('download.token');
});

// Admin approval routes
Route::prefix('stk/approval')->name('stk.approval.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\STK\ApprovalController::class, 'index'])
        ->name('index');

    // View specific request
    Route::get('/request/{id}', [App\Http\Controllers\STK\ApprovalController::class, 'show'])
        ->name('show');

    // Approve request
    Route::post('/request/{id}/approve', [App\Http\Controllers\STK\ApprovalController::class, 'approve'])
        ->name('approve');

    // Reject request
    Route::post('/request/{id}/reject', [App\Http\Controllers\STK\ApprovalController::class, 'reject'])
        ->name('reject');

    // Get counts
    Route::get('/counts', [App\Http\Controllers\STK\ApprovalController::class, 'getRequestCounts'])
        ->name('counts');

    // Get pending count only
    Route::get('/count/pending', [App\Http\Controllers\STK\ApprovalController::class, 'getPendingCount'])
        ->name('count.pending');

    // Get specific status count
    Route::get('/count/{status}', [App\Http\Controllers\STK\ApprovalController::class, 'getStatusCount'])
        ->name('count.status');

    // Dashboard statistics
    Route::get('/dashboard-stats', [App\Http\Controllers\STK\ApprovalController::class, 'getDashboardStats'])
        ->name('dashboard.stats');
});

// Statistics routes
Route::prefix('stk/statistics')->name('stk.statistics.')->middleware(['auth', 'role:admin'])->group(function () {
    // Get all counts
    Route::get('/counts', [App\Http\Controllers\STK\StatisticsController::class, 'getCounts'])
        ->name('counts');

    // Get detailed statistics
    Route::get('/detailed', [App\Http\Controllers\STK\StatisticsController::class, 'getDetailedStats'])
        ->name('detailed');

    // Get detailed statistics with filter
    Route::post('/detailed', [App\Http\Controllers\STK\StatisticsController::class, 'getDetailedStats'])
        ->name('detailed.filter');

    // Get user statistics
    Route::get('/users', [App\Http\Controllers\STK\StatisticsController::class, 'getUserStats'])
        ->name('users');
});
Route::get('/debug/download-requests', function() {
    return App\Models\DownloadRequest::latest()->get();
})->middleware('auth');
