<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/stk/database') }}">
                <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance">
                <span class="database-title">Sistem Tata Kelola</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/stk/database') }}">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="jenisDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Jenis
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="jenisDropdown">
                            <li><a class="dropdown-item" href="{{ url('/stk/category/pedoman') }}">Pedoman</a></li>
                            <li><a class="dropdown-item" href="{{ url('/stk/category/tko') }}">Tata Kerja Organisasi</a></li>
                            <li><a class="dropdown-item" href="{{ url('/stk/category/tki') }}">Tata Kerja Individu</a></li>
                            <li><a class="dropdown-item" href="{{ url('/stk/category/bpcp') }}">BPCP</a></li>
                            <li><a class="dropdown-item" href="{{ url('/stk/category/sop') }}">SOP</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/stk') }}">Statistik</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/stk/approvals') }}">
                            <div class="notification-badge" data-count="7">
                                <i class="fas fa-clipboard-check"></i> Approval
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0051a1&color=fff" alt="Admin Profile">
                    <div>
                        <p class="profile-name">Administrator</p>
                        <p class="profile-role">STK Manager</p>
                    </div>
                    <button id="logout-button" class="btn btn-outline-danger ms-3" onclick="logoutFromSystem()" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

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
                                <input class="form-check-input" type="checkbox" id="pending-check" checked>
                                <label class="form-check-label" for="pending-check">
                                    Pending <span class="badge bg-warning text-dark rounded-pill ms-1">7</span>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="approved-check" checked>
                                <label class="form-check-label" for="approved-check">
                                    Approved <span class="badge bg-success rounded-pill ms-1">24</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rejected-check" checked>
                                <label class="form-check-label" for="rejected-check">
                                    Rejected <span class="badge bg-danger rounded-pill ms-1">9</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="filter-label">Kategori Dokumen</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="pedoman-check" checked>
                                <label class="form-check-label" for="pedoman-check">
                                    Pedoman
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="tko-check" checked>
                                <label class="form-check-label" for="tko-check">
                                    Tata Kerja Organisasi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="tki-check" checked>
                                <label class="form-check-label" for="tki-check">
                                    Tata Kerja Individu
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="bpcp-check" checked>
                                <label class="form-check-label" for="bpcp-check">
                                    BPCP
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sop-check" checked>
                                <label class="form-check-label" for="sop-check">
                                    SOP
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="filter-label">Periode</label>
                            <select class="form-select form-select-sm mb-2">
                                <option selected>Semua waktu</option>
                                <option>Hari ini</option>
                                <option>Minggu ini</option>
                                <option>Bulan ini</option>
                                <option>Tahun ini</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Terapkan Filter
                        </button>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="card-header">
                            Aktivitas Terbaru
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-feed">
                                <div class="activity-item">
                                    <div class="activity-icon approve">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Anda menyetujui permintaan download dari Budi Santoso</div>
                                        <div class="activity-time">15 menit yang lalu</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon reject">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Anda menolak permintaan download dari Dian Purnama</div>
                                        <div class="activity-time">1 jam yang lalu</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon request">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Permintaan baru dari Ahmad Rizal</div>
                                        <div class="activity-time">3 jam yang lalu</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon approve">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Anda menyetujui permintaan download dari Sri Wahyuni</div>
                                        <div class="activity-time">Kemarin, 16:35</div>
                                    </div>
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
                                        Pending <span class="badge bg-warning text-dark rounded-pill ms-1">7</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                                        Approved <span class="badge bg-success rounded-pill ms-1">24</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                                        Rejected <span class="badge bg-danger rounded-pill ms-1">9</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content" id="requestTabsContent">
                                <!-- Pending Requests Tab -->
                                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                    <!-- Request Item 1 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">A-001/3701/2023 - Pengadaan Barang dan Jasa</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Budi Santoso (HRD)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 12 Mar 2025, 10:23</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Pedoman</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Referensi Pekerjaan - Dokumen ini diperlukan sebagai referensi untuk pembuatan SOP baru pengadaan barang di departemen HRD.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(1, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-001">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-001">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Request Item 2 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">B-005/2108/2023 - Struktur Organisasi dan Tata Kerja</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Dewi Anggraini (Legal)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 12 Mar 2025, 09:15</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Tata Kerja Organisasi</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Audit/Compliance - Perlu ditinjau untuk keperluan audit internal departemen Legal.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(2, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-002">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-002">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Request Item 3 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">C-012/3954/2024 - Penilaian Kinerja Karyawan</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Ahmad Rizal (HR Development)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 11 Mar 2025, 16:42</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Tata Kerja Individu</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Sharing Knowledge - Untuk dibahas dalam training karyawan baru bulan April.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(3, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-003">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-003">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Request Item 4 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">D-007/4501/2024 - Standar Operasi Pelaporan Insiden</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Rini Suryani (Risk Management)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 11 Mar 2025, 15:08</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> BPCP</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Referensi Pekerjaan - Untuk dipelajari dan diintegrasikan dengan sistem baru yang sedang dikembangkan.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(4, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-004">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-004">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- More request items... -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">A-002/6700/2023 - Pelaksanaan, Pengendalian, dan Pemantauan Proyek</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Joko Prabowo (Operation)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 10 Mar 2025, 11:30</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Pedoman</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Lainnya - Dokumen ini diperlukan untuk persiapan kick-off project baru di Q2.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(5, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-005">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-005">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">SOP-015/2560/2024 - Sistem & Prosedur Peningkatan Layanan Nasabah</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Siti Aisyah (Customer Service)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 10 Mar 2025, 09:15</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> SOP</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Audit/Compliance - Persiapan untuk audit ISO 9001 bulan depan.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(6, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-006">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-006">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">B-022/3200/2024 - Prosedur Keamanan Data dan Informasi</h5>
                                            <span class="badge badge-pending px-3 py-2">Pending</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Dian Purnama (Cybersecurity)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 9 Mar 2025, 14:22</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Tata Kerja Organisasi</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan:</strong> Referensi Pekerjaan - Diperlukan untuk menyusun kebijakan baru keamanan data sesuai regulasi OJK terbaru.
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(7, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <div>
                                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="REQ-007">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="REQ-007">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pagination -->
                                    <nav aria-label="Pagination">
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
                                    <!-- Approved Request Item 1 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">A-023/4501/2024 - Panduan Pelaksanaan Risk Assessment</h5>
                                            <span class="badge badge-approved px-3 py-2">Approved</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Sri Wahyuni (Risk Management)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 8 Mar 2025, 15:30</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Pedoman</span>
                                            <span><i class="fas fa-check-circle text-success me-1"></i> Disetujui oleh Admin, 9 Mar 2025</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan Permintaan:</strong> Referensi Pekerjaan - Untuk mempersiapkan risk assessment Q2 2025.
                                        </div>
                                        <div class="alert alert-success py-2 px-3 mb-3">
                                            <strong>Catatan Approval:</strong> Disetujui. Dokumen penting untuk pelaksanaan risk assessment sesuai jadwal.
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(8, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history me-1"></i> Lihat Detail
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Approved Request Item 2 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">C-009/5502/2024 - Prosedur Pengelolaan Talenta</h5>
                                            <span class="badge badge-approved px-3 py-2">Approved</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Hendra Gunawan (Human Capital)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 7 Mar 2025, 10:45</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Tata Kerja Individu</span>
                                            <span><i class="fas fa-check-circle text-success me-1"></i> Disetujui oleh Admin, 7 Mar 2025</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan Permintaan:</strong> Sharing Knowledge - Untuk workshop pengembangan karyawan.
                                        </div>
                                        <div class="alert alert-success py-2 px-3 mb-3">
                                            <strong>Catatan Approval:</strong> Disetujui untuk keperluan internal workshop HC.
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(9, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history me-1"></i> Lihat Detail
                                            </button>
                                        </div>
                                    </div>

                                    <!-- More approved items could be added here -->
                                    <div class="text-center pt-3 pb-2">
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-sync-alt me-1"></i> Muat lebih banyak
                                        </button>
                                    </div>
                                </div>

                                <!-- Rejected Requests Tab -->
                                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                    <!-- Rejected Request Item 1 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">SOP-042/6001/2024 - Prosedur Klaim Asuransi</h5>
                                            <span class="badge badge-rejected px-3 py-2">Rejected</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Dian Purnama (External Consultant)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 6 Mar 2025, 11:20</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> SOP</span>
                                            <span><i class="fas fa-times-circle text-danger me-1"></i> Ditolak oleh Admin, 6 Mar 2025</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan Permintaan:</strong> Referensi Pekerjaan - Untuk benchmarking dengan perusahaan lain.
                                        </div>
                                        <div class="alert alert-danger py-2 px-3 mb-3">
                                            <strong>Alasan Penolakan:</strong> Dokumen SOP klaim berisi informasi sensitif dan tidak diizinkan untuk penggunaan benchmarking eksternal. Silakan mengajukan permintaan dokumen publik saja.
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(10, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history me-1"></i> Lihat Detail
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Rejected Request Item 2 -->
                                    <div class="request-item">
                                        <div class="request-header">
                                            <h5 class="document-title">A-018/7230/2023 - Kebijakan Investasi dan Pengelolaan Dana</h5>
                                            <span class="badge badge-rejected px-3 py-2">Rejected</span>
                                        </div>
                                        <div class="request-meta">
                                            <span><i class="fas fa-user me-1"></i> Budi Santoso (Finance)</span>
                                            <span><i class="fas fa-calendar me-1"></i> 5 Mar 2025, 09:15</span>
                                            <span><i class="fas fa-file-pdf me-1"></i> Pedoman</span>
                                            <span><i class="fas fa-times-circle text-danger me-1"></i> Ditolak oleh Admin, 5 Mar 2025</span>
                                        </div>
                                        <div class="request-reason">
                                            <strong>Alasan Permintaan:</strong> Lainnya - Untuk referensi pribadi.
                                        </div>
                                        <div class="alert alert-danger py-2 px-3 mb-3">
                                            <strong>Alasan Penolakan:</strong> Dokumen berisi informasi rahasia tentang strategi investasi perusahaan. Keperluan "referensi pribadi" tidak cukup jelas untuk mendapatkan akses dokumen ini.
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(11, 1)">
                                                <i class="fas fa-eye me-1"></i> Preview Dokumen
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history me-1"></i> Lihat Detail
                                            </button>
                                        </div>
                                    </div>

                                    <!-- More rejected items could be added here -->
                                    <div class="text-center pt-3 pb-2">
                                        <button class="btn btn-outline-primary">
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
                    <button type="button" class="btn btn-success" onclick="approveRequest()">Setujui Permintaan</button>
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
                                <option value="doc1">A-023/4501/2024 - Versi Publik</option>
                                <option value="doc2">SOP-015/2560/2024 - Ringkasan</option>
                                <option value="doc3">Guide-001 - Petunjuk Umum</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="rejectRequest()">Tolak Permintaan</button>
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
                        <li><a href="{{ url('/stk/category/pedoman') }}">Pedoman</a></li>
                        <li><a href="{{ url('/stk/category/tko') }}">Tata Kerja Organisasi</a></li>
                        <li><a href="{{ url('/stk/category/tki') }}">Tata Kerja Individu</a></li>
                        <li><a href="{{ url('/stk/category/bpcp') }}">BPCP</a></li>
                        <li><a href="{{ url('/stk/category/sop') }}">SOP</a></li>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Handle suggestion of alternative document
            const suggestAlternativeCheck = document.getElementById('suggestAlternativeCheck');
            const alternativeDocSection = document.getElementById('alternativeDocSection');

            if (suggestAlternativeCheck && alternativeDocSection) {
                suggestAlternativeCheck.addEventListener('change', function() {
                    alternativeDocSection.style.display = this.checked ? 'block' : 'none';
                });
            }

            // Pass request ID to modals
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');

            if (approveModal) {
                approveModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const requestId = button.getAttribute('data-request-id');
                    approveModal.setAttribute('data-request-id', requestId);
                });
            }

            if (rejectModal) {
                rejectModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const requestId = button.getAttribute('data-request-id');
                    rejectModal.setAttribute('data-request-id', requestId);
                });
            }
        });

        // Function to approve a request
        function approveRequest() {
            const modal = document.getElementById('approveModal');
            const requestId = modal.getAttribute('data-request-id');
            const note = document.getElementById('approvalNote').value;
            const sendEmail = document.getElementById('sendEmailCheck').checked;
            const limitTime = document.getElementById('limitTimeCheck').checked;

            // Here you would send an AJAX request to your server to process the approval
            console.log('Approving request:', {
                requestId,
                note,
                sendEmail,
                limitTime
            });

            // Show a success toast
            showToast('Permintaan Disetujui', 'Permintaan telah berhasil disetujui dan notifikasi telah dikirim ke pemohon.', 'success');

            // Close the modal
            bootstrap.Modal.getInstance(modal).hide();

            // Update the UI to reflect the approval
            simulateApproval(requestId);
        }

        // Function to reject a request
        function rejectRequest() {
            const modal = document.getElementById('rejectModal');
            const requestId = modal.getAttribute('data-request-id');
            const reason = document.getElementById('rejectionReason').value;
            const note = document.getElementById('rejectionNote').value;
            const suggestAlternative = document.getElementById('suggestAlternativeCheck').checked;
            const alternativeDoc = suggestAlternative ? document.getElementById('alternativeDoc').value : null;

            // Form validation
            if (!reason) {
                alert('Silakan pilih alasan penolakan');
                return;
            }

            if (!note) {
                alert('Silakan berikan catatan tambahan');
                return;
            }

            // Here you would send an AJAX request to your server to process the rejection
            console.log('Rejecting request:', {
                requestId,
                reason,
                note,
                suggestAlternative,
                alternativeDoc
            });

            // Show a success toast
            showToast('Permintaan Ditolak', 'Permintaan telah ditolak dan notifikasi telah dikirim ke pemohon.', 'error');

            // Close the modal
            bootstrap.Modal.getInstance(modal).hide();

            // Update the UI to reflect the rejection
            simulateRejection(requestId);
        }

        // Show document in preview modal
        function previewDocument(docId, version) {
            const modal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
            const previewFrame = document.getElementById('documentPreviewFrame');
            const modalTitle = document.getElementById('documentPreviewModalLabel');

            // Update modal title based on document ID (you would fetch this from your API)
            const titles = {
                1: 'A-001/3701/2023 - Pengadaan Barang dan Jasa',
                2: 'B-005/2108/2023 - Struktur Organisasi dan Tata Kerja',
                3: 'C-012/3954/2024 - Penilaian Kinerja Karyawan',
                4: 'D-007/4501/2024 - Standar Operasi Pelaporan Insiden',
                5: 'A-002/6700/2023 - Pelaksanaan, Pengendalian, dan Pemantauan Proyek',
                6: 'SOP-015/2560/2024 - Sistem & Prosedur Peningkatan Layanan Nasabah',
                7: 'B-022/3200/2024 - Prosedur Keamanan Data dan Informasi',
                8: 'A-023/4501/2024 - Panduan Pelaksanaan Risk Assessment',
                9: 'C-009/5502/2024 - Prosedur Pengelolaan Talenta',
                10: 'SOP-042/6001/2024 - Prosedur Klaim Asuransi',
                11: 'A-018/7230/2023 - Kebijakan Investasi dan Pengelolaan Dana'
            };

            modalTitle.textContent = titles[docId] || 'Preview Dokumen';

            // Set iframe source (in a real implementation, this would point to your document viewer)
            previewFrame.src = `/stk/preview/${docId}/${version}`;

            // Show the modal
            modal.show();
        }

        // Simulate approval UI update
        function simulateApproval(requestId) {
            const requestItem = document.querySelector(`button[data-request-id="${requestId}"]`).closest('.request-item');

            // Move the request to the approved tab and update UI
            // In a real implementation, you would refresh the data from the server

            // For demo purposes, just remove it from the current tab
            requestItem.classList.add('fade-out');

            setTimeout(() => {
                // Update counter
                const pendingCounter = document.querySelector('#pending-tab .badge');
                let count = parseInt(pendingCounter.textContent);
                pendingCounter.textContent = (count - 1).toString();

                const approvedCounter = document.querySelector('#approved-tab .badge');
                count = parseInt(approvedCounter.textContent);
                approvedCounter.textContent = (count + 1).toString();

                // Remove from pending list
                requestItem.remove();
            }, 500);
        }

        // Simulate rejection UI update
        function simulateRejection(requestId) {
            const requestItem = document.querySelector(`button[data-request-id="${requestId}"]`).closest('.request-item');

            // Move the request to the rejected tab and update UI
            // In a real implementation, you would refresh the data from the server

            // For demo purposes, just remove it from the current tab
            requestItem.classList.add('fade-out');

            setTimeout(() => {
                // Update counter
                const pendingCounter = document.querySelector('#pending-tab .badge');
                let count = parseInt(pendingCounter.textContent);
                pendingCounter.textContent = (count - 1).toString();

                const rejectedCounter = document.querySelector('#rejected-tab .badge');
                count = parseInt(rejectedCounter.textContent);
                rejectedCounter.textContent = (count + 1).toString();

                // Remove from pending list
                requestItem.remove();
            }, 500);
        }

        // Show toast notification
        function showToast(title, message, type = 'info') {
            const toastContainer = document.querySelector('.toast-container');

            let bgClass;
            let iconClass;

            switch (type) {
                case 'success':
                    bgClass = 'bg-success';
                    iconClass = 'fas fa-check-circle';
                    break;
                case 'error':
                case 'danger':
                    bgClass = 'bg-danger';
                    iconClass = 'fas fa-exclamation-circle';
                    break;
                case 'warning':
                    bgClass = 'bg-warning text-dark';
                    iconClass = 'fas fa-exclamation-triangle';
                    break;
                default:
                    bgClass = 'bg-info';
                    iconClass = 'fas fa-info-circle';
            }

            const toast = document.createElement('div');
            toast.className = `toast ${bgClass} text-white`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
                <div class="toast-header ${bgClass} text-white">
                    <i class="${iconClass} me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <small>Baru saja</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;

            toastContainer.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 5000
            });

            bsToast.show();

            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }

        // Logout function
        function logoutFromSystem() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                // In a real implementation, this would call your logout API

                // Show a toast notification
                showToast('Logout Berhasil', 'Anda telah berhasil keluar dari sistem.', 'info');

                // Redirect to login page after a short delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            }
        }
    </script>

    <!-- Notification script for download requests -->
    <script>
        // This script simulates receiving new download request notifications
        // In a real implementation, this would be handled by WebSockets or Server-Sent Events

        document.addEventListener('DOMContentLoaded', function() {
            // Simulate a new download request after 30 seconds
            setTimeout(showNewRequestNotification, 30000);
        });

        // Function to show notification for new download request
        function showNewRequestNotification() {
            const notification = {
                user: 'Ratna Dewi',
                department: 'Claims',
                document: 'SOP-035/2024 - Prosedur Penanganan Klaim Bencana Alam',
                timestamp: new Date()
            };

            // Show a toast notification
            showToast(
                'Permintaan Download Baru',
                `<strong>${notification.user}</strong> dari <strong>${notification.department}</strong> mengajukan permintaan download dokumen <strong>${notification.document}</strong>.`,
                'info'
            );

            // Update the notification badge count
            const badge = document.querySelector('.notification-badge');
            const currentCount = parseInt(badge.getAttribute('data-count') || '0');
            badge.setAttribute('data-count', (currentCount + 1).toString());

            // Add a new item to the pending list (in a real implementation)
            // ...

            // Schedule another notification for demo purposes
            setTimeout(showNewRequestNotification, Math.random() * 60000 + 60000); // Random time between 1-2 minutes
        }

        // Function to handle actions when a new request is manually submitted
        function handleNewRequestSubmission(requestData) {
            // In a real application, this would be triggered when a user submits a download request form

            // Show a notification to admin
            showToast(
                'Permintaan Download Baru',
                `<strong>${requestData.user}</strong> dari <strong>${requestData.department}</strong> mengajukan permintaan download dokumen <strong>${requestData.document}</strong>.`,
                'info'
            );

            // Update the notification badge count
            const badge = document.querySelector('.notification-badge');
            const currentCount = parseInt(badge.getAttribute('data-count') || '0');
            badge.setAttribute('data-count', (currentCount + 1).toString());

            // Add request to the database (via AJAX)
            // ...

            // Notify the user that their request has been submitted
            // This would happen on the user's page
            /*
            showToast(
                'Permintaan Terkirim',
                'Permintaan download dokumen Anda telah berhasil dikirim dan sedang menunggu persetujuan admin.',
                'success'
            );
            */
           /**
 * Download Request Notification System
 *
 * This script handles the notification system for STK document download requests.
 * It should be integrated with your existing document preview modal.
 */

// Add this to your existing scripts or include it as a separate file

document.addEventListener('DOMContentLoaded', function() {
    // Setup download request form submission
    setupDownloadRequestForm();

    // Initialize notification system (for admin view)
    if (document.querySelector('.notification-badge')) {
        initializeNotificationSystem();
    }
});

/**
 * Setup download request form submission
 */
function setupDownloadRequestForm() {
    const formRequestDownload = document.getElementById('formRequestDownload');

    if (formRequestDownload) {
        formRequestDownload.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const requestData = {
                documentId: formData.get('document_id'),
                documentVersion: formData.get('document_version'),
                reason: formData.get('reason'),
                otherReason: formData.get('other_reason') || '',
                notes: formData.get('notes') || '',
                agreeTerms: formData.get('agree_terms') === 'on'
            };

            // Generate a reference number
            const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);

            // Submit the request to server (here we'll simulate it)
            submitDownloadRequest(requestData, refNumber);

            // Update UI
            document.getElementById('requestReferenceNumber').textContent = refNumber;

            // Hide download request form and show success modal
            const previewModal = bootstrap.Modal.getInstance(document.getElementById('documentPreviewModal'));
            if (previewModal) {
                previewModal.hide();
            }

            const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
            successModal.show();

            // Reset the form
            this.reset();
        });
    }
}

/**
 * Submit download request to server
 * @param {Object} requestData - The request data
 * @param {string} refNumber - The generated reference number
 */
function submitDownloadRequest(requestData, refNumber) {
    // In a real implementation, this would make an AJAX request to your server
    console.log('Submitting download request:', requestData, 'Reference:', refNumber);

    // Simulate a successful submission
    const userData = {
        id: getCurrentUserId(),
        name: getCurrentUserName(),
        department: getCurrentUserDepartment(),
        email: getCurrentUserEmail()
    };

    const documentData = {
        id: requestData.documentId,
        version: requestData.documentVersion,
        title: getDocumentTitle(requestData.documentId) || 'Dokumen STK'
    };

    // Create a notification record
    const notification = {
        type: 'download_request',
        user: userData,
        document: documentData,
        reason: requestData.reason,
        otherReason: requestData.otherReason,
        notes: requestData.notes,
        referenceNumber: refNumber,
        status: 'pending',
        timestamp: new Date().toISOString()
    };

    // In a real implementation, send this to your notification API
    sendNotificationToAdmin(notification);

    // Also store in local storage for demo purposes
    storeRequestInLocalStorage(notification);

    // Show confirmation toast to user
    showToast(
        'Permintaan Berhasil Terkirim',
        `Permintaan download dokumen Anda telah dikirim dengan nomor referensi <strong>${refNumber}</strong>. Admin akan meninjau permintaan Anda segera.`,
        'success'
    );
}

/**
 * Store the request in localStorage (for demo purposes)
 * @param {Object} notification - The notification object
 */
function storeRequestInLocalStorage(notification) {
    // Get existing requests or initialize empty array
    const storedRequests = JSON.parse(localStorage.getItem('stkDownloadRequests') || '[]');

    // Add new request
    storedRequests.push(notification);

    // Store back in localStorage
    localStorage.setItem('stkDownloadRequests', JSON.stringify(storedRequests));
}

/**
 * Send notification to admin
 * @param {Object} notification - The notification object
 */
function sendNotificationToAdmin(notification) {
    // In a real implementation, this would send a notification to the admin via WebSockets/Server-Sent Events
    console.log('Sending notification to admin:', notification);

    // For demo purposes, we'll simulate this by updating the UI if we're on the admin page
    if (window.location.pathname.includes('/stk/approvals')) {
        // We're on the admin page, update the notification badge count
        updateAdminNotificationCount();

        // Show a toast notification
        const message = `<strong>${notification.user.name}</strong> dari <strong>${notification.user.department}</strong> mengajukan permintaan download dokumen <strong>${notification.document.title}</strong>.`;

        showToast('Permintaan Download Baru', message, 'info');
    }
}

/**
 * Initialize notification system for admins
 */
function initializeNotificationSystem() {
    // Check for unread notifications
    const pendingRequests = getPendingRequestCount();

    // Update badge count
    updateAdminNotificationBadge(pendingRequests);

    // Setup WebSocket or Server-Sent Events for real-time notifications
    setupRealTimeNotifications();
}

/**
 * Setup real-time notifications (simulated for demo)
 */
function setupRealTimeNotifications() {
    // In a real implementation, this would connect to WebSockets or SSE
    console.log('Setting up real-time notifications');

    // For demo, we'll simulate a new notification after a random delay
    const randomDelay = Math.floor(Math.random() * 60000) + 30000; // 30-90 seconds

    setTimeout(function() {
        simulateNewRequestNotification();
    }, randomDelay);
}

/**
 * Simulate receiving a new download request notification
 */
function simulateNewRequestNotification() {
    // Create a simulated request
    const randomUsers = [
        { name: 'Andi Pratama', department: 'Underwriting' },
        { name: 'Diana Putri', department: 'Finance' },
        { name: 'Hendra Santoso', department: 'Claims' },
        { name: 'Maya Wijaya', department: 'IT' },
        { name: 'Budi Setiawan', department: 'Risk Management' }
    ];

    const randomDocuments = [
        { id: 100, title: 'SOP-076/2024 - Standar Prosedur Underwriting' },
        { id: 101, title: 'B-015/2024 - Tata Kerja Departemen Finance' },
        { id: 102, title: 'C-032/2024 - Pedoman Pelayanan Nasabah' },
        { id: 103, title: 'A-045/2024 - Kebijakan Keamanan Informasi' },
        { id: 104, title: 'D-019/2024 - Mitigasi Risiko Operasional' }
    ];

    const randomUser = randomUsers[Math.floor(Math.random() * randomUsers.length)];
    const randomDocument = randomDocuments[Math.floor(Math.random() * randomDocuments.length)];

    // Show a toast notification
    const message = `<strong>${randomUser.name}</strong> dari <strong>${randomUser.department}</strong> mengajukan permintaan download dokumen <strong>${randomDocument.title}</strong>.`;

    showToast('Permintaan Download Baru', message, 'info');

    // Update the notification badge count
    updateAdminNotificationCount();

    // Schedule another notification for demo purposes
    const nextDelay = Math.floor(Math.random() * 60000) + 60000; // 60-120 seconds
    setTimeout(simulateNewRequestNotification, nextDelay);
}

/**
 * Update the admin notification badge count
 */
function updateAdminNotificationCount() {
    const pendingRequests = getPendingRequestCount();
    updateAdminNotificationBadge(pendingRequests + 1); // Increment by 1
}

/**
 * Update the notification badge UI with the pending count
 * @param {number} count - The number of pending requests
 */
function updateAdminNotificationBadge(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.setAttribute('data-count', count.toString());

        // Also update any tab counters
        const pendingTab = document.querySelector('#pending-tab .badge');
        if (pendingTab) {
            pendingTab.textContent = count.toString();
        }
    }
}

/**
 * Get the number of pending download requests
 * @returns {number} The count of pending requests
 */
function getPendingRequestCount() {
    // In a real implementation, this would come from your API
    // For demo, we'll return a random number or use localStorage
    const storedRequests = JSON.parse(localStorage.getItem('stkDownloadRequests') || '[]');
    const pendingRequests = storedRequests.filter(req => req.status === 'pending').length;

    return pendingRequests || Math.floor(Math.random() * 10) + 5; // Random number between 5-15
}

/**
 * Helper function to get current user ID
 * @returns {string} The current user ID
 */
function getCurrentUserId() {
    // In a real implementation, get this from your auth system
    return 'user-' + Math.floor(Math.random() * 1000);
}

/**
 * Helper function to get current user name
 * @returns {string} The current user name
 */
function getCurrentUserName() {
    // In a real implementation, get this from your auth system
    return document.querySelector('.profile-name')?.textContent || 'Pengguna';
}

/**
 * Helper function to get current user department
 * @returns {string} The current user department
 */
function getCurrentUserDepartment() {
    // In a real implementation, get this from your auth system
    return document.querySelector('.profile-role')?.textContent || 'Karyawan';
}

/**
 * Helper function to get current user email
 * @returns {string} The current user email
 */
function getCurrentUserEmail() {
    // In a real implementation, get this from your auth system
    return 'user@tugu.com';
}

/**
 * Helper function to get document title by ID
 * @param {string|number} documentId - The document ID
 * @returns {string|null} The document title if found, null otherwise
 */
function getDocumentTitle(documentId) {
    // In a real implementation, you would look this up from your document data
    const documentTitles = {
        1: 'A-001/3701/2023 - Pengadaan Barang dan Jasa',
        2: 'B-005/2108/2023 - Struktur Organisasi dan Tata Kerja',
        3: 'C-012/3954/2024 - Penilaian Kinerja Karyawan',
        4: 'D-007/4501/2024 - Standar Operasi Pelaporan Insiden',
        5: 'A-002/6700/2023 - Pelaksanaan, Pengendalian, dan Pemantauan Proyek',
        6: 'SOP-015/2560/2024 - Sistem & Prosedur Peningkatan Layanan Nasabah',
        7: 'B-022/3200/2024 - Prosedur Keamanan Data dan Informasi',
        8: 'A-023/4501/2024 - Panduan Pelaksanaan Risk Assessment',
        9: 'C-009/5502/2024 - Prosedur Pengelolaan Talenta',
        10: 'SOP-042/6001/2024 - Prosedur Klaim Asuransi',
        11: 'A-018/7230/2023 - Kebijakan Investasi dan Pengelolaan Dana'
    };

    return documentTitles[documentId] || null;
}
        }
    </script>
</body>
</html>
