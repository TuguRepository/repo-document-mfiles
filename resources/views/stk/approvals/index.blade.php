<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Approval Requests - Sistem Tata Kelola Tugu Insurance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0051a1;
            --primary-light: #e1f0ff;
            --secondary: #ff544a;
            --secondary-light: #fff8f7;
            --tertiary: #28ca72;
            --dark: #212b36;
            --bg-light: #f7f9fc;
            --border-color: #e7ecf0;
        }

        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, sans-serif;
            background-color: var(--bg-light);
            color: var(--dark);
            line-height: 1.6;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            height: 38px;
        }

        .database-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-left: 0.75rem;
            color: var(--primary);
            border-left: 1px solid #dee2e6;
            padding-left: 0.75rem;
        }

        .nav-link {
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.75rem 1rem !important;
            color: #495057;
            position: relative;
        }

        .nav-link:hover, .nav-link:focus, .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active:after {
            content: '';
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 0.5rem;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .page-header {
            background: linear-gradient(100deg, #0a296c 0%, #0051a1 100%);
            padding: 2.5rem 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header-pattern {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 50%;
            opacity: 0.1;
            background-size: cover;
            background-position: center;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1' fill-rule='evenodd'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .page-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .page-description {
            max-width: 700px;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .content-section {
            padding: 2rem 0;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .card-header-tabs {
            margin-bottom: -1rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #637381;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: var(--primary);
        }

        .nav-tabs .nav-link.active {
            border-color: var(--primary);
            color: var(--primary);
            background-color: transparent;
        }

        .request-item {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            transition: background-color 0.2s;
        }

        .request-item:last-child {
            border-bottom: none;
        }

        .request-item:hover {
            background-color: var(--primary-light);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .document-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--dark);
            margin: 0;
        }

        .request-meta {
            color: #637381;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .request-meta span {
            margin-right: 1rem;
        }

        .request-reason {
            background-color: var(--bg-light);
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .badge-pending {
            background-color: #ffecbc;
            color: #b78105;
        }

        .badge-approved {
            background-color: #d1f5e0;
            color: #0d6832;
        }

        .badge-rejected {
            background-color: #fee7e7;
            color: #b42318;
        }

        .filter-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .search-input {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            width: 100%;
        }

        .filter-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #637381;
            margin-bottom: 0.5rem;
        }

        .notification-badge {
            position: relative;
            display: inline-block;
        }

        .notification-badge[data-count]:after {
            content: attr(data-count);
            position: absolute;
            top: -10px;
            right: -10px;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            background-color: var(--secondary);
            color: white;
            border-radius: 50%;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }

        .admin-note {
            resize: none;
        }

        /* Pagination styles */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }

        .pagination .page-link {
            color: var(--primary);
            border-color: #dee2e6;
            margin: 0 3px;
            border-radius: 4px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #c9d1d9;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #637381;
            max-width: 400px;
            margin: 0 auto;
        }

        .footer {
            background-color: #1C2255;
            color: #fff;
            padding: 3rem 0 1.5rem;
        }

        .footer-heading {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
        }

        .footer p {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a, .social-link {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
            display: block;
        }

        .footer-links a:hover, .social-link:hover {
            color: #fff;
            transform: translateX(3px);
        }

        .social-links {
            display: flex;
            flex-direction: column;
        }

        /* Loading skeleton */
        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
            background: #e7ecf0;
            border-radius: 4px;
            height: 16px;
            margin-bottom: 8px;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: #e7ecf0;
            }
            100% {
                background-color: #f3f6f9;
            }
        }

        /* Recent Activity Widget */
        .activity-feed {
            margin-top: 0.5rem;
        }

        .activity-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: flex-start;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .activity-icon.approve {
            background-color: #d1f5e0;
            color: #0d6832;
        }

        .activity-icon.reject {
            background-color: #fee7e7;
            color: #b42318;
        }

        .activity-icon.request {
            background-color: #e1f0ff;
            color: #0051a1;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #919eab;
        }

        /* Header profile */
        .header-profile {
            display: flex;
            align-items: center;
        }

        .header-profile img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            margin-right: 0.75rem;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--dark);
            margin: 0;
        }

        .profile-role {
            font-size: 0.8rem;
            color: #637381;
            margin: 0;
        }

        /* Document Preview Modal */
        #documentPreviewModal .modal-dialog {
            max-width: 90%;
            height: 80vh;
        }

        #documentPreviewModal .modal-content {
            height: 100%;
        }

        #documentPreviewModal .modal-body {
            padding: 0;
            position: relative;
        }

        #documentPreviewFrame {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Animation */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s;
        }
    </style>
</head>
<body>
    @include('layouts.stk.header')

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-pattern"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="page-title">Approval Requests</h1>
                    <p class="page-description">Kelola permintaan download dokumen STK dari pengguna.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <!-- Sidebar / Filter Section -->
                <div class="col-lg-3">
                    <!-- Filters -->
                    <div class="filter-section">
                        <h5 class="mb-3">Filter Permintaan</h5>

                        <div class="mb-3">
                            <label for="search-input" class="filter-label">Cari</label>
                            <input type="text" id="search-input" class="search-input" placeholder="Cari berdasarkan nama...">
                        </div>

                        <div class="mb-3">
                            <label class="filter-label">Status</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-status" type="checkbox" id="pending-check" checked value="pending">
                                <label class="form-check-label" for="pending-check">
                                    Pending <span class="badge bg-warning text-dark rounded-pill ms-1 pending-count">0</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-status" type="checkbox" id="approved-check" checked value="approved">
                                <label class="form-check-label" for="approved-check">
                                    Approved <span class="badge bg-success rounded-pill ms-1 approved-count">0</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-status" type="checkbox" id="rejected-check" checked value="rejected">
                                <label class="form-check-label" for="rejected-check">
                                    Rejected <span class="badge bg-danger rounded-pill ms-1 rejected-count">0</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="filter-label">Kategori Dokumen</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-category" type="checkbox" id="pedoman-check" checked value="Pedoman">
                                <label class="form-check-label" for="pedoman-check">
                                    Pedoman
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-category" type="checkbox" id="tko-check" checked value="Tata Kerja Organisasi">
                                <label class="form-check-label" for="tko-check">
                                    Tata Kerja Organisasi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-category" type="checkbox" id="tki-check" checked value="Tata Kerja Individu">
                                <label class="form-check-label" for="tki-check">
                                    Tata Kerja Individu
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-category" type="checkbox" id="bpcp-check" checked value="BPCP">
                                <label class="form-check-label" for="bpcp-check">
                                    BPCP
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-category" type="checkbox" id="sop-check" checked value="SOP">
                                <label class="form-check-label" for="sop-check">
                                    SOP
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="filter-label">Periode</label>
                            <select class="form-select form-select-sm mb-2" id="period-filter">
                                <option value="all" selected>Semua waktu</option>
                                <option value="today">Hari ini</option>
                                <option value="week">Minggu ini</option>
                                <option value="month">Bulan ini</option>
                                <option value="year">Tahun ini</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100" id="apply-filter">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card-header">
                            Aktivitas Terbaru
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-feed" id="activity-feed">
                                <!-- Activity items will be loaded here dynamically -->
                                <div class="empty-state p-3 text-center">
                                    <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="requestTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                                        Pending <span class="badge bg-warning text-dark rounded-pill ms-1 pending-count">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                                        Approved <span class="badge bg-success rounded-pill ms-1 approved-count">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                                        Rejected <span class="badge bg-danger rounded-pill ms-1 rejected-count">0</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="requestTabsContent">
                                <!-- Pending Requests Tab -->
                                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                    <div id="pending-requests-container">
                                        <!-- Pending requests will be dynamically loaded here -->
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h4>Tidak Ada Permintaan</h4>
                                            <p>Belum ada permintaan approval yang perlu ditinjau saat ini.</p>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <nav aria-label="Pagination" id="pending-pagination" style="display: none;">
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                                <!-- Approved Requests Tab -->
                                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                    <div id="approved-requests-container">
                                        <!-- Approved requests will be dynamically loaded here -->
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle"></i>
                                            <h4>Tidak Ada Permintaan Disetujui</h4>
                                            <p>Belum ada permintaan yang telah disetujui.</p>
                                        </div>
                                    </div>

                                    <!-- Load More Button -->
                                    <div class="text-center pt-3 pb-2" id="approved-load-more" style="display: none;">
                                        <button class="btn btn-outline-primary" id="load-more-approved">
                                            <i class="fas fa-sync-alt me-1"></i> Muat lebih banyak
                                        </button>
                                    </div>
                                </div>

                                <!-- Rejected Requests Tab -->
                                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                    <div id="rejected-requests-container">
                                        <!-- Rejected requests will be dynamically loaded here -->
                                        <div class="empty-state">
                                            <i class="fas fa-times-circle"></i>
                                            <h4>Tidak Ada Permintaan Ditolak</h4>
                                            <p>Belum ada permintaan yang telah ditolak.</p>
                                        </div>
                                    </div>

                                    <!-- Load More Button -->
                                    <div class="text-center pt-3 pb-2" id="rejected-load-more" style="display: none;">
                                        <button class="btn btn-outline-primary" id="load-more-rejected">
                                            <i class="fas fa-sync-alt me-1"></i> Muat lebih banyak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">Setujui Permintaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="approveForm">
                        <input type="hidden" id="approve-request-id" value="">
                        <div class="mb-3">
                            <label for="approvalNote" class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control admin-note" id="approvalNote" rows="3" placeholder="Tambahkan catatan untuk pemohon..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opsi Tambahan</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="sendEmailCheck" checked>
                                <label class="form-check-label" for="sendEmailCheck">
                                    Kirim notifikasi email ke pemohon
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="limitTimeCheck">
                                <label class="form-check-label" for="limitTimeCheck">
                                    Batasi akses download (24 jam)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="approveRequestBtn">Setujui Permintaan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Permintaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" id="reject-request-id" value="">
                        <div class="mb-3">
                            <label for="rejectionReason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <select class="form-select mb-2" id="rejectionReason" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="unauthorized">Tidak berwenang mengakses dokumen ini</option>
                                <option value="insufficient">Alasan penggunaan tidak cukup jelas</option>
                                <option value="sensitive">Dokumen berisi informasi sensitif</option>
                                <option value="restricted">Dokumen terbatas untuk departemen tertentu</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rejectionNote" class="form-label">Catatan Tambahan <span class="text-danger">*</span></label>
                            <textarea class="form-control admin-note" id="rejectionNote" rows="3" placeholder="Berikan penjelasan untuk pemohon..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="suggestAlternativeCheck">
                                <label class="form-check-label" for="suggestAlternativeCheck">
                                    Sarankan dokumen alternatif
                                </label>
                            </div>
                        </div>
                        <div class="mb-3" id="alternativeDocSection" style="display: none;">
                            <label for="alternativeDoc" class="form-label">Dokumen Alternatif</label>
                            <select class="form-select" id="alternativeDoc">
                                <option value="">-- Pilih Dokumen --</option>
                                <option value="doc1">C-019/4572/2024 - Versi Publik</option>
                                <option value="doc2">PED-039/3981/2024 - Ringkasan Kebijakan</option>
                                <option value="doc3">MAN-001/2024 - Manual Kepatuhan Umum</option>
                                <option value="doc4">TKO-Basic - Dokumen Orientasi Tata Kerja</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="rejectRequestBtn">Tolak Permintaan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 90%; height: 80vh;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentPreviewModalLabel">Preview Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="documentPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Request Modal -->
    <div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addRequestModalLabel">Tambah Permintaan Download</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRequestForm">
                        <div class="mb-3">
                            <label for="requestUser" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" id="requestUser" required>
                        </div>
                        <div class="mb-3">
                            <label for="requestDepartment" class="form-label">Departemen</label>
                            <select class="form-select" id="requestDepartment" required>
                                <option value="">-- Pilih Departemen --</option>
                                <option value="HRD">HRD</option>
                                <option value="Finance">Finance</option>
                                <option value="IT">IT</option>
                                <option value="Legal">Legal</option>
                                <option value="Risk Management">Risk Management</option>
                                <option value="Operation">Operation</option>
                                <option value="Customer Service">Customer Service</option>
                                <option value="Underwriting">Underwriting</option>
                                <option value="Claims">Claims</option>
                                <option value="Sales">Sales</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestDocument" class="form-label">Dokumen</label>
                            <select class="form-select" id="requestDocument" required>
                                <option value="">-- Pilih Dokumen --</option>
                                <option value="20">B-005/2108/2023 - Struktur Organisasi dan Tata Kerja</option>
                                <option value="21">RM-007/3456/2024 - Panduan Manajemen Risiko Operasional</option>
                                <option value="22">C-019/4572/2024 - Kebijakan Kepatuhan Terhadap Regulasi</option>
                                <option value="23">IT-031/5280/2024 - Prosedur Keamanan Sistem Informasi</option>
                                <option value="24">HC-015/2765/2024 - Pedoman Pengembangan Kompetensi Karyawan</option>
                                <option value="25">UW-042/3871/2024 - SOP Underwriting Marine Cargo</option>
                                <option value="26">FIN-027/4510/2024 - Pedoman Pelaporan Keuangan</option>
                                <option value="27">CLM-018/3920/2024 - Prosedur Penanganan Klaim Bencana Alam</option>
                                <option value="28">COM-009/2740/2024 - Panduan Komunikasi Korporat</option>
                                <option value="29">PD-021/3615/2024 - Kebijakan Pengembangan Produk Digital</option>
                                <option value="33">TKO-027/4310/2024 - Tata Kerja Divisi Reasuransi</option>
                                <option value="35">BPCP-012/2785/2024 - Prosedur Penanggulangan Bencana</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="requestReason" class="form-label">Alasan Permintaan</label>
                            <select class="form-select" id="requestReason" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Referensi Pekerjaan">Referensi Pekerjaan</option>
                                <option value="Audit/Compliance">Audit/Compliance</option>
                                <option value="Sharing Knowledge">Sharing Knowledge</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3" id="otherReasonSection" style="display: none;">
                            <label for="otherReason" class="form-label">Alasan Lainnya</label>
                            <textarea class="form-control" id="otherReason" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requestNotes" class="form-label">Catatan Tambahan (opsional)</label>
                            <textarea class="form-control" id="requestNotes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitRequestBtn">Ajukan Permintaan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container for Notifications -->
    <div class="toast-container"></div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-heading">KANTOR PUSAT</h5>
                    <p class="mb-1">Wisma Tugu I</p>
                    <p class="mb-1">Jalan H.R. Rasuna Said</p>
                    <p class="mb-3">Kav. C8-9, Jakarta 12920 Indonesia</p>

                    <p class="mb-1"><i class="fas fa-phone-alt me-2"></i> (021) 52961777</p>
                    <p class="mb-1"><i class="fas fa-fax me-2"></i> (021) 52961555 ; 52962555</p>
                    <p class="mb-3"><i class="fas fa-envelope me-2"></i> callTIA@tugu.com</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-heading">MEDIA SOSIAL</h5>
                    <div class="social-links">
                        <a href="https://www.instagram.com/tugu_insurance/" class="social-link mb-2"><i class="fab fa-instagram me-2"></i> Instagram</a>
                        <a href="https://twitter.com/tuguinsurance" class="social-link mb-2"><i class="fab fa-twitter me-2"></i> Twitter</a>
                        <a href="https://www.facebook.com/PTAsuransiTuguPratama/" class="social-link mb-2"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
                        <a href="https://www.linkedin.com/company/pt-asuransi-tugu-pratama-indonesia-tbk/" class="social-link mb-2"><i class="fab fa-linkedin-in me-2"></i> LinkedIn</a>
                        <a href="https://www.youtube.com/channel/UC7LO8Y5-x0zPNxV9Q6q0BuA" class="social-link mb-2"><i class="fab fa-youtube me-2"></i> Youtube</a>
                        <a href="https://www.tiktok.com/@tugu_insurance" class="social-link mb-2"><i class="fab fa-tiktok me-2"></i> Tiktok</a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-heading">PRODUK STK</h5>
                    <ul class="footer-links">
                        <li><a href="#">Pedoman</a></li>
                        <li><a href="#">Tata Kerja Organisasi</a></li>
                        <li><a href="#">Tata Kerja Individu</a></li>
                        <li><a href="#">BPCP</a></li>
                        <li><a href="#">SOP</a></li>
                    </ul>

                    <h5 class="footer-heading mt-4">HOTLINE</h5>
                    <p class="mb-1"><i class="fab fa-whatsapp me-2"></i> 0811 97 900 100</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="mb-4">
                        <h5 class="footer-heading">TENTANG SISTEM TATA KELOLA</h5>
                        <p>Sistem Tata Kelola merupakan platform resmi Tugu Insurance yang menghimpun dan menyajikan dokumen Standar dan Tata Kelola dari berbagai unit kerja.</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="footer-heading">KEBIJAKAN PRIVASI</h5>
                        <a href="#" class="text-decoration-underline">Kebijakan Privasi</a>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">

            <div class="text-center">
                <p class="mb-0">&copy; 2025 PT Asuransi Tugu Pratama Indonesia Tbk. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    </script>
    <script src="{{ asset('js/approval-requests.js') }}"></script>

</body>
</html>
