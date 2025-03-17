    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\STKDocumentController;
    use App\Http\Controllers\DocumentController;
    use App\Http\Controllers\DownloadRequestController;
    use App\Http\Controllers\STK\ApprovalController;
    use App\Http\Controllers\DatabaseController;


    Route::prefix('api')->group(function () {
    // Route untuk mendapatkan ringkasan data STK
    Route::get('/stk/summary', [STKDocumentController::class, 'getSTKSummary']);
    });



    Route::get('/', function () {
    return view('welcome');
    });

    // Route::middleware([
    // 'auth:sanctum',
    // config('jetstream.auth_session'),
    // 'verified',
    // ])->group(function () {
    Route::get('/stk/database', function () {
    return view('dashboard');
    })->name('dashboard');
    // });

    Route::get('/dashboardstk', function () {
    return view('dashboardstk');
    });


    Route::get('/mfiles/token', [MFilesAuthController::class, 'getAuthToken'])->middleware('auth');

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
    Route::get('/mfiles/token', [MFilesAuthController::class, 'getAuthToken']);
    Route::post('/mfiles/verify', [MFilesAuthController::class, 'verifyToken']);
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/stk/summary', [STKDocumentController::class, 'getSTKSummary']);
    Route::get('/stk', [STKDocumentController::class, 'index']);
    Route::get('/stk/preview/{objectId}/{version?}', [STKDocumentController::class, 'getPreview']);
    Route::get('/stk/download/{objectId}/{version?}', [STKDocumentController::class, 'getFileContent']);
    Route::get('/stk/file/{objectId}', [STKDocumentController::class, 'getFileContent'])->name('stk.file');
    // Di routes/web.php
    Route::get('/stk/database', [DatabaseController::class, 'index'])->name('stk.database');
    // Routes untuk kategori dokumen
    Route::get('/stk/category/pedoman', [STKDocumentController::class, 'showCategoryPedoman'])->name('stk.category.pedoman');
    Route::get('/stk/category/tko', [STKDocumentController::class, 'showCategoryTKO'])->name('stk.category.tko');
    Route::get('/stk/category/tki', [STKDocumentController::class, 'showCategoryTKI'])->name('stk.category.tki');
    Route::get('/stk/category/bpcp', [STKDocumentController::class, 'showCategoryBPCP'])->name('stk.category.bpcp');
    Route::get('/stk/category/sop', [STKDocumentController::class, 'showCategorySOP'])->name('stk.category.sop');

    // Route untuk dokumen berdasarkan tahun
    Route::get('/stk/year/{year}', [STKDocumentController::class, 'showDocumentsByYear'])->name('stk.year');

    // API untuk mengambil dokumen berdasarkan kategori
    Route::get('/api/stk/documents', [STKDocumentController::class, 'getDocumentsByCategory']);
    // API untuk mengambil dokumen berdasarkan tahun
    Route::get('/stk/documents-by-year', [STKDocumentController::class, 'getDocumentsByYear']);
    Route::get('/api/stk/diagnostic', [STKDocumentController::class, 'diagnosticDocuments']);
    // API untuk dokumen featured (3 tahun terakhir)
    Route::get('/stk/featured-documents', [STKDocumentController::class, 'getFeaturedDocuments']);

    // API untuk pencarian dokumen
    Route::get('/stk/search', [STKDocumentController::class, 'searchDocuments']);
    // API untuk pencarian dokumen (alternatif)
    Route::get('/stk/simple-search', [STKDocumentController::class, 'simpleSearchDocuments']);
    Route::get('/api/stk/document-info/{objectId}/{version?}', [STKDocumentController::class, 'getDocumentInfo']);
    // Admin Dashboard Routes
    // Route::middleware(['auth'])->group(function () {
    // Approval Dashboard
    Route::get('/stk/approvals', [ApprovalController::class, 'index'])->name('stk.approvals.index');

    // Approval Actions
    Route::post('/stk/approvals/{request}/approve', [STK\ApprovalController::class, 'approve'])->name('stk.approvals.approve');
    // Tambahkan ini di file routes/web.php
    Route::post('stk/approvals/reject', [ApprovalController::class, 'reject'])->name('stk.approvals.reject');
    // Request Details
    Route::get('/stk/approvals/{request}', [STK\ApprovalController::class, 'show'])->name('stk.approvals.show');
    // });

    Route::post('stk/approvals/{request}/approve', [ApprovalController::class, 'approve'])->name('stk.approvals.approve');
    // Di routes/web.php
    Route::post('stk/approvals/approve', [ApprovalController::class, 'approve'])->name('stk.approvals.approve');

    // Add these routes to your existing routes/web.php file
    // Inside the middleware group for authenticated users and admin role

    // Routes for approval counts and statistics
    // Route::prefix('stk/approval')->name('stk.approval.')->middleware(['auth', 'role:admin'])->group(function () {
    // Get all counts
    Route::get('/counts', [STK\ApprovalController::class, 'getRequestCounts'])
    ->name('counts');

    // Get pending count only
    Route::get('/count/pending', [STK\ApprovalController::class, 'getPendingCount'])
    ->name('count.pending');

    // Get specific status count
    Route::get('/count/{status}', [STK\ApprovalController::class, 'getStatusCount'])
    ->name('count.status');

    // Get dashboard statistics
    Route::get('/dashboard-stats', [STK\ApprovalController::class, 'getDashboardStats'])
    ->name('dashboard.stats');

    // Detailed statistics
    Route::get('/statistics', [STK\StatisticsController::class, 'getDetailedStats'])
    ->name('statistics');

    // Detailed statistics with filter
    Route::post('/statistics/filter', [STK\StatisticsController::class, 'getDetailedStats'])
    ->name('statistics.filter');
    // });
    // User download request routes
    // Route::middleware(['auth'])->group(function () {
    // List user's own requests
    Route::get('/download-requests', [DownloadRequestController::class, 'index'])
    ->name('download-requests.index');

    // Store new request
    Route::post('/download-requests', [DownloadRequestController::class, 'store'])
    ->name('download-requests.store');

    // View details of a specific request
    Route::get('/download-requests/{id}', [DownloadRequestController::class, 'show'])
    ->name('download-requests.show');

    // Download a document with token
    Route::get('/download/{token}', [DownloadRequestController::class, 'download'])
    ->name('download.token');
    // });
    // routes/web.php

    // Tambahkan route untuk menangani permintaan download dokumen
    Route::post('/download-request', [DownloadRequestController::class, 'store'])->name('download-request.store');

    // Route untuk melihat detail permintaan
    Route::get('/download-request/{id}', [DownloadRequestController::class, 'show'])->name('download-request.show');

    // Route untuk mengunduh dokumen dengan token
    Route::get('/download/{token}', [DownloadRequestController::class, 'download'])->name('document.download');
    // Admin approval routes
    Route::prefix('stk/approval')->name('stk.approval.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [STK\ApprovalController::class, 'index'])
    ->name('index');

    // View specific request
    Route::get('/request/{id}', [STK\ApprovalController::class, 'show'])
    ->name('show');

    // Approve request
    Route::post('/request/{id}/approve', [STK\ApprovalController::class, 'approve'])
    ->name('approve');

    // Reject request
    Route::post('/request/{id}/reject', [STK\ApprovalController::class, 'reject'])
    ->name('reject');

    // Get counts
    Route::get('/counts', [STK\ApprovalController::class, 'getRequestCounts'])
    ->name('counts');

    // Get pending count only
    Route::get('/count/pending', [STK\ApprovalController::class, 'getPendingCount'])
    ->name('count.pending');

    // Get specific status count
    Route::get('/count/{status}', [STK\ApprovalController::class, 'getStatusCount'])
    ->name('count.status');

    // Dashboard statistics
    Route::get('/dashboard-stats', [STK\ApprovalController::class, 'getDashboardStats'])
    ->name('dashboard.stats');
    });

    // Statistics routes
    Route::prefix('stk/statistics')->name('stk.statistics.')->middleware(['auth', 'role:admin'])->group(function () {
    // Get all counts
    Route::get('/counts', [STK\StatisticsController::class, 'getCounts'])
    ->name('counts');

    // Get detailed statistics
    Route::get('/detailed', [STK\StatisticsController::class, 'getDetailedStats'])
    ->name('detailed');

    // Get detailed statistics with filter
    Route::post('/detailed', [STK\StatisticsController::class, 'getDetailedStats'])
    ->name('detailed.filter');

    // Get user statistics
    Route::get('/users', [STK\StatisticsController::class, 'getUserStats'])
    ->name('users');
    });
    Route::get('/debug/download-requests', function() {
    return App\Models\DownloadRequest::latest()->get();
    });
    // Download Request Routes
    Route::get('/stk/my-requests', [App\Http\Controllers\DownloadRequestController::class, 'myRequests'])
    ->name('stk.download-requests.my-requests');
    Route::get('/stk/featured-documents', [App\Http\Controllers\StkController::class, 'getFeaturedDocuments']);
    // routes/web.php

    // Tambahkan routes untuk halaman dan API approval
    // Route::middleware(['auth'])->group(function () {
    // Halaman utama Approval Requests
    Route::get('/approval-requests', [ApprovalController::class, 'index'])->name('approvals.index');

    // API untuk mengambil data
    Route::get('/api/requests', [ApprovalController::class, 'getRequests'])->name('api.requests');
    Route::get('/api/counts', [ApprovalController::class, 'getCounts'])->name('api.counts');
    Route::get('/api/activities', [ApprovalController::class, 'getActivities'])->name('api.activities');

    // API untuk approval/rejection
    Route::post('/api/approve', [ApprovalController::class, 'approve'])->name('api.approve');
    Route::post('/api/reject', [ApprovalController::class, 'reject'])->name('api.reject');

    // Preview dokumen
    Route::get('/api/document/{id}/preview', [ApprovalController::class, 'previewDocument'])->name('api.document.preview');
    // });
    // Dalam routes/web.php atau routes/api.php
    Route::get('/api/counts', [ApprovalController::class, 'getCounts'])->name('api.counts');
    Route::post('stk/approvals/{request}/approve', [ApprovalController::class, 'approve']);
    Route::post('stk/approvals/approve', [ApprovalController::class, 'approve']);
    // Route::middleware(['auth'])->group(function () {
    // ...
    Route::get('/api/activities', [ApprovalController::class, 'getActivities'])->name('api.activities');
    // ...
    // });
