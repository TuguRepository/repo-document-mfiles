    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Tata Kelola - Tugu Insurance</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/document-preview.css') }}" rel="stylesheet">
        <style>
    /* CSS untuk melindungi konten dan mencegah download */

    /* Styling untuk modal preview dokumen */
    #documentPreviewModal .modal-dialog {
        max-width: 90%;
        width: 90%;
        height: 80vh;
        margin: 5vh auto;
    }

    #documentPreviewModal .modal-content {
        height: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    #documentPreviewModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 1.5rem;
    }

    #documentPreviewModal .modal-body {
        padding: 0;
        overflow: hidden;
        position: relative;
    }

    /* Atur iframe untuk mencegah interaksi langsung dengan konten */
    #documentPreviewFrame {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
        position: relative;
    }

    /* Tambahkan watermark pada container */
    #documentPreviewContainer {
        position: relative;
    }

    #documentPreviewContainer::before {
        content: "PREVIEW ONLY - TIDAK UNTUK DIUNDUH";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 2rem;
        color: rgba(200, 0, 0, 0.2);
        white-space: nowrap;
        z-index: 10;
        pointer-events: none;
        font-weight: bold;
    }

    /* Styling untuk form request download */
    #downloadRequestForm {
        padding: 1.5rem;
        background-color: #f8f9fa;
    }

    #downloadRequestForm .card {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: none;
    }

    #downloadRequestForm .card-header {
        background-color: #0051a1;
        color: white;
        font-weight: 500;
        padding: 1rem 1.5rem;
    }

    #downloadRequestForm .form-label {
        font-weight: 500;
    }

    #downloadRequestForm .text-danger {
        font-weight: bold;
    }

    /* Tampilan indikator loading */
    #documentLoadingIndicator {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255,255,255,0.9);
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #documentLoadingIndicator .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 1rem;
    }

    /* Tampilan pesan error */
    #documentPreviewError {
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 2rem !important;
    }

    /* Animasi untuk watermark (opsional) */
    @keyframes watermark-pulse {
        0% { opacity: 0.1; }
        50% { opacity: 0.2; }
        100% { opacity: 0.1; }
    }

    #documentPreviewContainer::before {
        animation: watermark-pulse 4s infinite;
    }

    /* Request success modal */
    #requestSuccessModal .modal-header {
        background-color: #28a745;
    }

    #requestSuccessModal .fa-check-circle {
        color: #28a745;
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }

    #requestReferenceNumber {
        font-weight: bold;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Toolbar dokumen */
    .document-toolbar {
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .document-toolbar .zoom-controls {
        display: flex;
        align-items: center;
    }

    .document-toolbar .zoom-controls button {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 2px;
        padding: 4px 8px;
        cursor: pointer;
    }

    .document-toolbar .zoom-controls button:hover {
        background-color: #e9ecef;
    }

    .document-toolbar .zoom-controls .zoom-level {
        margin: 0 10px;
        font-size: 0.9rem;
    }

    /* Loading indicator dengan sedikit animasi */
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }

    #documentLoadingIndicator {
        animation: pulse 1.5s infinite;
        color: #0051a1;
        font-weight: 500;
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


    .header-right {
                margin-left: auto;
                display: flex;
                align-items: center;
            }

            .header-right .profile {
                display: flex;
                align-items: center;
                margin-left: 20px;
            }

            .header-right .profile img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                margin-right: 10px;
            }

    .card {
        transition: all 0.2s;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .card-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
    }
    #loginModal .modal-content,
    #sessionTimeoutModal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    #loginModal .modal-header,
    #sessionTimeoutModal .modal-header {
        border-bottom: none;
        padding-bottom: 0;
        padding: 20px 20px 0;
    }

    #loginModal .modal-footer,
    #sessionTimeoutModal .modal-footer {
        border-top: none;
        padding-top: 0;
        padding: 0 20px 20px;
    }

    #loginModal .modal-body,
    #sessionTimeoutModal .modal-body {
        padding: 20px;
    }

    #login-form .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    #login-form .form-control {
        border-left: none;
    }

    #login-form .btn-outline-secondary {
        border-left: none;
    }

    #login-form .btn-primary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        padding: 10px;
    }

    #sessionTimeoutModal .fa-clock {
        color: var(--warning-color, #ffc107);
    }


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

            .hero-section {
                background: linear-gradient(100deg, #0a296c 0%, #0051a1 100%);
                padding: 4.5rem 0;
                position: relative;
                overflow: hidden;
                color: white;
            }

            .hero-pattern {
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

            .hero-title {
                font-weight: 800;
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
            }

            .hero-subtitle {
                font-weight: 400;
                font-size: 1.15rem;
                opacity: 0.9;
                max-width: 550px;
                margin-bottom: 2rem;
            }

            .search-container {
                max-width: 650px;
                margin: 0 auto;
                position: relative;
            }

            .search-box {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                padding: 0.35rem;
            }

            .search-input {
                border: none;
                font-size: 1rem;
                padding: 0.75rem 1rem;
                border-radius: 8px;
            }

            .search-input:focus {
                box-shadow: none;
            }

            .search-btn {
                background: var(--primary);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,81,161,0.2);
                transition: all 0.2s;
            }

            .search-btn:hover {
                background: #003d7a;
                transform: translateY(-1px);
                box-shadow: 0 5px 15px rgba(0,81,161,0.3);
            }

            .featured-docs {
                margin-top: 2rem;
            }

            .featured-docs .card {
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 12px;
                backdrop-filter: blur(5px);
                padding: 1.25rem;
                height: 100%;
                transition: all 0.25s;
            }

            .featured-docs .card:hover {
                background: rgba(255,255,255,0.15);
                transform: translateY(-2px);
                box-shadow: 0 8px 18px rgba(0,0,0,0.1);
            }

            .featured-docs .card-title {
                font-weight: 600;
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }

            .featured-docs .card-text {
                font-size: 0.9rem;
                opacity: 0.9;
            }

            .content-section {
                padding: 3.5rem 0;
            }

            .section-title {
                font-weight: 700;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .section-title .view-all {
                font-size: 0.9rem;
                font-weight: 500;
                color: var(--primary);
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .section-title .view-all:hover {
                text-decoration: none;
            }

            .category-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 1.5rem;
                height: 100%;
                transition: all 0.2s;
                border: 1px solid var(--border-color);
            }

            .category-card:hover {
                box-shadow: 0 8px 20px rgba(0,0,0,0.08);
                transform: translateY(-3px);
                border-color: #d0d9e4;
            }

            .category-icon {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                background: var(--primary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary);
                font-size: 1.5rem;
                margin-bottom: 1.2rem;
            }

            .category-title {
                font-weight: 600;
                font-size: 1.15rem;
                margin-bottom: 0.75rem;
            }

            .category-desc {
                font-size: 0.9rem;
                color: #637381;
                margin-bottom: 1.25rem;
            }

            .category-link {
                color: var(--primary);
                font-size: 0.9rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .category-link:hover {
                text-decoration: none;
            }

            .document-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                padding: 1.25rem;
                margin-bottom: 1rem;
                transition: all 0.2s;
                border: 1px solid var(--border-color);
                display: flex;
                gap: 1rem;
            }

            .document-card:hover {
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                transform: translateY(-2px);
            }

            .document-icon {
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: var(--secondary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--secondary);
            }

            .document-content {
                flex: 1;
            }

            .document-title {
                font-weight: 600;
                font-size: 1rem;
                margin-bottom: 0.3rem;
                color: var(--dark);
            }

            .document-desc {
                font-size: 0.9rem;
                color: #637381;
                margin-bottom: 0.3rem;
            }

            .document-meta {
                font-size: 0.8rem;
                color: #919eab;
            }

            .statistics {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                padding: 1.5rem;
                border: 1px solid var(--border-color);
                margin-bottom: 1.5rem;
            }

            .statistic-item {
                padding: 1rem;
                border-radius: 8px;
                text-align: center;
            }

            .statistic-value {
                font-weight: 700;
                font-size: 1.8rem;
                margin-bottom: 0.25rem;
                color: var(--primary);
            }

            .statistic-label {
                font-size: 0.85rem;
                color: #637381;
            }

            .recent-activity {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                padding: 1.5rem;
                border: 1px solid var(--border-color);
            }

            .activity-item {
                display: flex;
                gap: 1rem;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .activity-item:last-child {
                border-bottom: none;
            }

            .activity-icon {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: var(--tertiary);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .activity-content {
                flex: 1;
            }

            .activity-title {
                font-weight: 500;
                font-size: 0.9rem;
                margin-bottom: 0.2rem;
            }

            .activity-time {
                font-size: 0.8rem;
                color: #919eab;
            }

            .footer {
                background: var(--dark);
                color: white;
                padding: 3rem 0 1.5rem;
            }

            .footer h5 {
                font-weight: 600;
                font-size: 1.1rem;
                margin-bottom: 1.25rem;
            }

            .footer p {
                font-size: 0.9rem;
                color: rgba(255,255,255,0.7);
            }

            .footer-link {
                color: rgba(255,255,255,0.7);
                font-size: 0.9rem;
                display: block;
                margin-bottom: 0.75rem;
                transition: all 0.2s;
            }

            .footer-link:hover {
                color: white;
                transform: translateX(2px);
            }

            .copyright {
                font-size: 0.85rem;
                color: rgba(255,255,255,0.5);
                text-align: center;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(255,255,255,0.1);
                margin-top: 2rem;
            }

            .btn-login {
                background: var(--primary);
                color: white;
                border: none;
                padding: 0.6rem 1.25rem;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s;
            }

            .btn-login:hover {
                background: #003d7a;
                color: white;
                transform: translateY(-1px);
            }

            .dropdown-menu {
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                border: 1px solid var(--border-color);
                padding: 0.5rem;
            }

            .dropdown-item {
                border-radius: 6px;
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .dropdown-item:hover, .dropdown-item:focus {
                background-color: var(--primary-light);
                color: var(--primary);
            }

            @media (max-width: 991.98px) {
                .hero-section {
                    padding: 3rem 0;
                }

                .hero-title {
                    font-size: 2rem;
                }

                .category-card {
                    margin-bottom: 1rem;
                }
            }

            @media (max-width: 767.98px) {
                .hero-title {
                    font-size: 1.75rem;
                }

                .database-title {
                    display: none;
                }

                .hero-pattern {
                    opacity: 0.05;
                }
            }
        </style>
        <!-- Di bagian bawah, tepat sebelum </body> -->
        @include('layouts.stk.header')

@include('stk.approvals.partials.document-preview-modal')
<script src="{{ asset('js/document-preview.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi yang diperlukan halaman
        // ...

        // Inisialisasi proses kartu dokumen
        if (typeof handleDocumentCards === 'function') {
            setTimeout(handleDocumentCards, 100);
        }
    });
</script>
    </head>
    <body>
        {{-- <header class="header">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
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
                        <a class="nav-link active" href="#">Beranda</a>
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

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tahunDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tahun
                        </a>
                        <ul class="dropdown-menu tahun-dropdown" aria-labelledby="tahunDropdown" id="tahun-dropdown-menu">
                            <!-- Tahun akan diisi secara dinamis melalui JavaScript -->
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/stk') }}">Statistik</a>
                    </li>
                </ul>

                <div class="header-right">
                    <button id="logout-button" class="btn btn-outline-danger" onclick="logoutFromSystem()" title="Logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
    </header> --}}

    {{-- <!-- Modal Preview Dokumen (Tidak Fullscreen) -->
    <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 90%; height: 80vh;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentPreviewModalLabel">Preview Dokumen</h5>
                    <div class="ms-auto">
                        <button class="btn btn-outline-primary me-2" id="requestDownloadBtn">
                            <i class="fas fa-paper-plane"></i> Request Download
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <div class="d-flex justify-content-center align-items-center h-100" id="documentLoadingIndicator">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Memuat dokumen...</span>
                    </div>
                    <div id="documentPreviewContainer" class="h-100 d-none">
                        <iframe id="documentPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
                    </div>
                    <div id="documentPreviewError" class="text-center p-5 d-none">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <h4>Gagal memuat dokumen</h4>
                        <p class="text-muted" id="errorMessage">Terjadi kesalahan saat memuat dokumen.</p>
                        <a href="#" class="btn btn-primary mt-3" id="retryLoadButton">
                            <i class="fas fa-sync"></i> Coba Lagi
                        </a>
                    </div>

                    <!-- Form Request Download -->
                    <div id="downloadRequestForm" class="d-none p-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Request Download Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <form id="formRequestDownload">
                                    <input type="hidden" id="requestDocId" name="document_id">
                                    <input type="hidden" id="requestDocVersion" name="document_version">

                                    <div class="mb-3">
                                        <label for="requestReason" class="form-label">Alasan Permintaan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="requestReason" name="reason" required>
                                            <option value="">-- Pilih Alasan --</option>
                                            <option value="reference">Referensi Pekerjaan</option>
                                            <option value="audit">Audit/Compliance</option>
                                            <option value="sharing">Sharing Knowledge</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="otherReasonContainer" style="display: none;">
                                        <label for="otherReason" class="form-label">Alasan Lainnya <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="otherReason" name="other_reason" rows="2"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="requestNotes" class="form-label">Catatan Tambahan</label>
                                        <textarea class="form-control" id="requestNotes" name="notes" rows="2"></textarea>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" name="agree_terms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            Saya menyatakan bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary" id="cancelRequestBtn">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Kirim Permintaan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Request Download -->
    <div class="modal fade" id="requestSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Permintaan Berhasil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <h4>Permintaan Download Berhasil Dikirim</h4>
                    <p>Permintaan Anda telah berhasil dikirim dan sedang diproses. Anda akan menerima notifikasi jika permintaan Anda disetujui.</p>
                    <p class="text-muted">Nomor Referensi: <span id="requestReferenceNumber">REF-12345</span></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Login Modal styled like M-Files with fixed vault GUID -->
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">M-Files Authentication</h5>
            </div>
            <div class="modal-body">
            <div class="text-center mb-4">
                <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance Logo" height="60">
                <h5 class="mt-2">Sistem Tata Kelola</h5>
            </div>
            <div id="login-error" class="alert alert-danger d-none" role="alert">
                Authentication failed. Please check your credentials.
            </div>
            <div id="login-loading" class="d-none text-center mb-3">
                <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Authenticating with M-Files...</p>
            </div>
            <form id="login-form">
                <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" placeholder="M-Files Username" required>
                </div>
                </div>
                <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" placeholder="M-Files Password" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                    <i class="fas fa-eye"></i>
                    </button>
                </div>
                </div>
                <!-- Hidden vault GUID field -->
                <input type="hidden" id="vault" value="5D8FF911-CE06-4B27-8311-B0AD764921C0">
                <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember-me">
                <label class="form-check-label" for="remember-me">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            </div>
        </div>
        </div>
    </div>


    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-pattern"></div>
        <div class="container">
            <!-- Judul dan subtitle di atas -->
            <div class="row mb-4">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title">Sistem Tata Kelola Tugu Insurance</h1>
                    <p class="hero-subtitle">Portal manajemen dokumen yang menghimpun dan menyajikan berbagai standar, pedoman, dan tata kelola perusahaan.</p>
                </div>
            </div>

            <!-- Search dan Featured docs di bawah -->
            <div class="row justify-content-center">
                <!-- Kolom pencarian -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="search-container">
                        <div class="input-group search-box">
                            <input type="text" class="form-control search-input" id="global-search-input" placeholder="Cari dokumen STK...">
                            <button class="search-btn" id="global-search-button">
                                <i class="fas fa-search me-2"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row untuk dokumen -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="featured-docs">
                        <div class="row g-3" id="featured-docs-container">
                            <!-- Tiga kolom berjajar -->
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card h-100">
                                    <h5 class="card-title skeleton-loader" style="height: 20px; width: 80%; background-color: #e7ecf0;"></h5>
                                    <p class="card-text skeleton-loader" style="height: 16px; width: 90%; background-color: #e7ecf0; margin-top: 10px;"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card h-100">
                                    <h5 class="card-title skeleton-loader" style="height: 20px; width: 70%; background-color: #e7ecf0;"></h5>
                                    <p class="card-text skeleton-loader" style="height: 16px; width: 85%; background-color: #e7ecf0; margin-top: 10px;"></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card h-100">
                                    <h5 class="card-title skeleton-loader" style="height: 20px; width: 75%; background-color: #e7ecf0;"></h5>
                                    <p class="card-text skeleton-loader" style="height: 16px; width: 90%; background-color: #e7ecf0; margin-top: 10px;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-white opacity-75">Dokumen terpopuler 2 minggu terakhir</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Popup untuk Hasil Pencarian -->
        <div class="modal fade" id="searchResultModal" tabindex="-1" aria-labelledby="searchResultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchResultModalLabel">Hasil Pencarian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="search-results-container">
                            <!-- Hasil pencarian akan ditampilkan di sini -->
                            <div class="text-center py-5" id="search-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Mencari dokumen...</p>
                            </div>
                            <div id="search-no-results" class="d-none">
                                <div class="text-center py-4">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h4>Tidak ada hasil</h4>
                                    <p class="text-muted">Tidak ditemukan dokumen dengan kata kunci tersebut. Coba gunakan kata kunci lain.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section class="content-section">
            <div class="container">
                <!-- Categories Section -->
                <h2 class="section-title">
                    Klasifikasi STK
                    <a href="#" class="view-all">Lihat Statistik <i class="fas fa-arrow-right"></i></a>
                </h2>

                <div class="row g-4">
                    <!-- Pedoman (Kode A) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="category-title">Pedoman</h3>
                            <p class="category-desc">Berisi kumpulan pedoman yang berlaku di Tugu Insurance.</p>
                            <a href="{{ url('/stk/category/pedoman') }}" class="category-link" data-type="Pedoman">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tata Kerja Organisasi (Kode B) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <h3 class="category-title">Tata Kerja Organisasi</h3>
                            <p class="category-desc">Berisi kumpulan tata kerja organisasi dalam perusahaan.</p>
                            <a href="{{ url('/stk/category/tko') }}" class="category-link" data-type="Tata Kerja Organisasi">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tata Kerja Individu (Kode C) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3 class="category-title">Tata Kerja Individu</h3>
                            <p class="category-desc">Berisi kumpulan tata kerja untuk individu dalam perusahaan.</p>
                            <a href="{{ url('/stk/category/tki') }}" class="category-link" data-type="Tata Kerja Individu">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- BPCP (Kode D) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h3 class="category-title">BPCP</h3>
                            <p class="category-desc">Berisi kumpulan Batasan Pelayanan dan Catatan Prosedur.</p>
                            <a href="{{ url('/stk/category/bpcp') }}" class="category-link" data-type="BPCP">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Documents Section -->
                <div class="row mt-5">
                    <div class="col-lg-8">
                        <h2 class="section-title">
                            Dokumen Terbaru
                            <a href="#" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                        </h2>

                        <!-- Dokumen terbaru akan ditampilkan secara dinamis di sini -->
                        <div id="latest-documents-container">
                            <div class="document-card skeleton-loader">
                                <div class="document-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-content">
                                    <h4 class="document-title">Memuat...</h4>
                                    <p class="document-desc">Memuat...</p>
                                    <div class="document-meta">Memuat...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="statistics">
                            <h2 class="section-title" style="margin-bottom: 0.75rem;">Statistik</h2>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="total-documents">-</div>
                                        <div class="statistic-label">Total Dokumen</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="document-types">-</div>
                                        <div class="statistic-label">Kategori Dokumen</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="latest-year">-</div>
                                        <div class="statistic-label">Tahun Terbaru</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="document-years">-</div>
                                        <div class="statistic-label">Rentang Tahun</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="recent-activity mt-4">
                            <h2 class="section-title" style="margin-bottom: 0.75rem;">Dokumen per Tahun</h2>
                            <div id="documents-by-year">
                                <!-- Dokumen per tahun akan ditampilkan secara dinamis di sini -->
                                <div class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- <!-- Footer -->
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
    </footer> --}}

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        @include('layouts.footer')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Load featured documents
                loadFeaturedDocuments();

                // Setup search functionality
                setupSearch();

                // Set up document preview in modal
                setupDocumentPreview();
            });

            // Fungsi untuk setup preview dokumen dengan request download
    function setupDocumentPreview() {
        // Cari elemen-elemen modal
        const previewModal = document.getElementById('documentPreviewModal');
        const previewFrame = document.getElementById('documentPreviewFrame');
        const loadingIndicator = document.getElementById('documentLoadingIndicator');
        const previewContainer = document.getElementById('documentPreviewContainer');
        const errorContainer = document.getElementById('documentPreviewError');
        const requestDownloadBtn = document.getElementById('requestDownloadBtn');
        const downloadRequestForm = document.getElementById('downloadRequestForm');
        const cancelRequestBtn = document.getElementById('cancelRequestBtn');
        const requestReason = document.getElementById('requestReason');
        const otherReasonContainer = document.getElementById('otherReasonContainer');

        // Inisialisasi variabel untuk menyimpan dokumen yang sedang dilihat
        let currentDocumentId = null;
        let currentDocumentVersion = null;

        // Event listener untuk tombol request download
        requestDownloadBtn.addEventListener('click', function() {
            // Sembunyikan container preview
            previewContainer.classList.add('d-none');
            // Tampilkan form request download
            downloadRequestForm.classList.remove('d-none');

            // Set nilai hidden field
            document.getElementById('requestDocId').value = currentDocumentId;
            document.getElementById('requestDocVersion').value = currentDocumentVersion;
        });

        // Event listener untuk tombol batal
        cancelRequestBtn.addEventListener('click', function() {
            // Sembunyikan form request
            downloadRequestForm.classList.add('d-none');
            // Tampilkan preview dokumen
            previewContainer.classList.remove('d-none');
        });

        // Event listener untuk alasan lainnya
        requestReason.addEventListener('change', function() {
            if (this.value === 'other') {
                otherReasonContainer.style.display = 'block';
                document.getElementById('otherReason').setAttribute('required', true);
            } else {
                otherReasonContainer.style.display = 'none';
                document.getElementById('otherReason').removeAttribute('required');
            }
        });

        // Event listener untuk submit form request
        document.getElementById('formRequestDownload').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi form
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            // Buat objek FormData untuk mengumpulkan data form
            const formData = new FormData(this);

            // Generate nomor referensi acak
            const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);

            // Tampilkan nomor referensi di modal sukses
            document.getElementById('requestReferenceNumber').textContent = refNumber;

            // Simulasi pengiriman permintaan ke server
            // Dalam implementasi sebenarnya, gunakan fetch untuk mengirim data ke server
            console.log('Sending download request:', Object.fromEntries(formData));

            // Tutup modal preview
            bootstrap.Modal.getInstance(previewModal).hide();

            // Tampilkan modal sukses
            const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
            successModal.show();

            // Reset form
            this.reset();
            downloadRequestForm.classList.add('d-none');
            previewContainer.classList.remove('d-none');
        });

        // Fungsi untuk menampilkan preview dokumen dalam modal
        window.viewDocumentInModal = function(id, version = 'latest') {
            // Simpan ID dan versi saat ini
            currentDocumentId = id;
            currentDocumentVersion = version;

            // Reset tampilan
            loadingIndicator.classList.remove('d-none');
            previewContainer.classList.add('d-none');
            errorContainer.classList.add('d-none');
            downloadRequestForm.classList.add('d-none');

            // Update judul modal
            document.getElementById('documentPreviewModalLabel').textContent = 'Memuat Dokumen...';

            // Tampilkan modal
            const modal = new bootstrap.Modal(previewModal);
            modal.show();

            // Siapkan URL untuk iframe
            const previewUrl = `/stk/preview/${id}${version ? '/' + version : ''}`;

            // Coba dapatkan informasi dokumen
            fetch(`/api/stk/document-info/${id}${version ? '/' + version : ''}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Authentication': sessionStorage.getItem('mfiles_auth_token') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.document) {
                    document.getElementById('documentPreviewModalLabel').textContent = data.document.title || 'Preview Dokumen';
                }
            })
            .catch(error => console.error('Error fetching document info:', error));

            // Coba muat dokumen dalam iframe
            previewFrame.onload = function() {
                loadingIndicator.classList.add('d-none');
                previewContainer.classList.remove('d-none');

                // Periksa apakah iframe memuat konten yang valid
                try {
                    // Coba akses contentDocument (akan gagal jika ada error CORS)
                    const frameContent = previewFrame.contentDocument || previewFrame.contentWindow.document;

                    // Jika dokumen ada tetapi isinya error JSON
                    const frameText = frameContent.body.textContent;
                    if (frameText.includes('"success":false') || frameText.includes('error')) {
                        try {
                            const errorData = JSON.parse(frameText);
                            showErrorMessage(errorData.message || 'Terjadi kesalahan saat memuat dokumen');
                        } catch (e) {
                            // Jika bukan JSON valid, tampilkan kontennya apa adanya
                            if (frameText.trim().length > 0) {
                                // Ada konten teks, mungkin bisa dibaca
                            } else {
                                showErrorMessage('Konten dokumen tidak dapat dimuat');
                            }
                        }
                    }

                    // Tambahkan style untuk mencegah download/print
                    try {
                        const style = frameContent.createElement('style');
                        style.textContent = `
                            @media print { body { display: none; } }
                            * { user-select: none !important; }
                        `;
                        frameContent.head.appendChild(style);
                    } catch (e) {
                        console.log('Could not inject anti-print styles');
                    }
                } catch (e) {
                    // Error CORS biasanya berarti dokumen PDF dimuat dengan benar
                    console.log('Cross-origin frame access - expected for PDFs');
                }
            };

            // Handler untuk error loading iframe
            previewFrame.onerror = function() {
                showErrorMessage('Gagal memuat dokumen');
            };

            // Set iframe src untuk memuat dokumen
            previewFrame.src = previewUrl;

            // Fungsi untuk menampilkan pesan error
            function showErrorMessage(message) {
                loadingIndicator.classList.add('d-none');
                previewContainer.classList.add('d-none');
                errorContainer.classList.remove('d-none');
                document.getElementById('errorMessage').textContent = message;

                // Set tombol retry
                document.getElementById('retryLoadButton').onclick = function(e) {
                    e.preventDefault();
                    previewFrame.src = previewUrl;
                    loadingIndicator.classList.remove('d-none');
                    errorContainer.classList.add('d-none');
                };
            }
        };

        // Override fungsi previewDocument standard
        window.previewDocument = function(id, version) {
            // Gunakan modal untuk preview
            viewDocumentInModal(id, version);
            return false; // Prevent default navigation
        };

        // Process all document cards to open in modal
        handleDocumentCards();

        // Add mutation observer for dynamically added cards
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    setTimeout(handleDocumentCards, 100);
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }

    // Process document cards to show in modal
    function handleDocumentCards() {
        // Target all document-cards and cards
        const cards = document.querySelectorAll('.document-card, .card');

        cards.forEach(card => {
            // Skip already processed cards
            if (card.hasAttribute('data-modal-handler')) {
                return;
            }

            // Mark as processed
            card.setAttribute('data-modal-handler', 'true');
            card.style.cursor = 'pointer';

            // Get document ID from various sources
            let docId = card.getAttribute('data-id');
            let docVersion = card.getAttribute('data-version') || 'latest';

            // Check onclick attribute
            const onclickAttr = card.getAttribute('onclick');
            if (!docId && onclickAttr && onclickAttr.includes('previewDocument')) {
                const match = onclickAttr.match(/previewDocument\((\d+)(?:,\s*['"]?([^'"\)]+)['"]?)?\)/);
                if (match) {
                    docId = match[1];
                    docVersion = match[2] || 'latest';

                    // Replace onclick with our modal function
                    card.setAttribute('onclick', `event.preventDefault(); event.stopPropagation(); viewDocumentInModal(${docId}, '${docVersion}'); return false;`);
                }
            }

            // Add click event listener if no onclick exists
            if (!onclickAttr) {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Try to get ID from attributes
                    let id = this.getAttribute('data-id');
                    let version = this.getAttribute('data-version') || 'latest';

                    // If no ID, try from view button
                    if (!id) {
                        const viewButton = this.querySelector('.view-btn, .view-doc-btn');
                        if (viewButton) {
                            id = viewButton.getAttribute('data-id');
                            version = viewButton.getAttribute('data-version') || 'latest';
                        }
                    }

                    // If ID found, show in modal
                    if (id) {
                        viewDocumentInModal(id, version);
                    }
                });
            }
        });

        // Also handle view buttons directly
        const viewButtons = document.querySelectorAll('.view-btn, .view-doc-btn');
        viewButtons.forEach(btn => {
            // Skip already processed buttons
            if (btn.hasAttribute('data-modal-handler')) {
                return;
            }

            // Mark as processed
            btn.setAttribute('data-modal-handler', 'true');

            // Get document ID and version
            const id = btn.getAttribute('data-id');
            const version = btn.getAttribute('data-version') || 'latest';

            if (id) {
                // Add click event listener
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    viewDocumentInModal(id, version);
                });
            }
        });
    }

    // Add to document load event
    document.addEventListener('DOMContentLoaded', function() {
        setupDocumentPreview();
    });


            // Process document cards to open in modal
            function handleDocumentCards() {
                // All document cards and featured cards
                const cards = document.querySelectorAll('.document-card, .card');

                cards.forEach(card => {
                    // Skip already processed cards
                    if (card.hasAttribute('data-modal-handler')) {
                        return;
                    }

                    // Mark as processed
                    card.setAttribute('data-modal-handler', 'true');
                    card.style.cursor = 'pointer';

                    // Check if card has onclick attribute
                    const onclickAttr = card.getAttribute('onclick');
                    if (onclickAttr && onclickAttr.includes('previewDocument')) {
                        // Extract ID and version
                        const match = onclickAttr.match(/previewDocument\((\d+)(?:,\s*['"]?([^'"\)]+)['"]?)?\)/);
                        if (match) {
                            const id = match[1];
                            const version = match[2] || 'latest';

                            // Replace onclick with our modal function
                            card.setAttribute('onclick', `event.preventDefault(); event.stopPropagation(); viewDocumentInModal(${id}, '${version}'); return false;`);
                        }
                    } else {
                        // Add click handler if no onclick exists
                        card.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Try to find ID from data attributes
                            let id = this.getAttribute('data-id');
                            let version = this.getAttribute('data-version') || 'latest';

                            // If no ID found, check for view button
                            if (!id) {
                                const viewBtn = this.querySelector('.view-btn, .view-doc-btn');
                                if (viewBtn) {
                                    id = viewBtn.getAttribute('data-id');
                                    version = viewBtn.getAttribute('data-version') || 'latest';
                                }
                            }

                            // If ID found, show preview
                            if (id) {
                                viewDocumentInModal(id, version);
                            } else {
                                console.warn('Could not find document ID for preview');
                            }
                        });
                    }
                });

                // Also handle view buttons directly
                const viewButtons = document.querySelectorAll('.view-btn, .view-doc-btn');
                viewButtons.forEach(btn => {
                    // Skip already processed buttons
                    if (btn.hasAttribute('data-modal-handler')) {
                        return;
                    }

                    // Mark as processed
                    btn.setAttribute('data-modal-handler', 'true');

                    // Add click handler
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const id = this.getAttribute('data-id');
                        const version = this.getAttribute('data-version') || 'latest';

                        if (id) {
                            viewDocumentInModal(id, version);
                        }
                    });
                });
            }

            // Function to load featured documents (3 tahun terakhir)
            function loadFeaturedDocuments() {
                // Get current year
                const currentYear = new Date().getFullYear();

                // Make API request
                fetch('/api/stk/featured-documents')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.documents) {
                            displayFeaturedDocuments(data.documents);
                        } else {
                            console.error('Failed to load featured documents:', data.message);
                            // Use fallback data
                            const fallbackDocs = [
                                {
                                    title: `STK No. 1 Tahun ${currentYear}`,
                                    description: 'Pedoman Umum Pelaksanaan Pengadaan dan Anggaran',
                                    id: 1,
                                    version: 1
                                },
                                {
                                    title: `STK No. 23 Tahun ${currentYear-1}`,
                                    description: 'Pendistribusian Dokumen Internal',
                                    id: 2,
                                    version: 1
                                },
                                {
                                    title: `STK No. 13 Tahun ${currentYear-2}`,
                                    description: 'Kebijakan Pengelolaan Dokumen',
                                    id: 3,
                                    version: 1
                                }
                            ];
                            displayFeaturedDocuments(fallbackDocs);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading featured documents:', error);
                        // Use fallback data on error
                        const fallbackDocs = [
                            {
                                title: `STK No. 1 Tahun ${currentYear}`,
                                description: 'Pedoman Umum Pelaksanaan Pengadaan dan Anggaran',
                                id: 1,
                                version: 1
                            },
                            {
                                title: `STK No. 23 Tahun ${currentYear-1}`,
                                description: 'Pendistribusian Dokumen Internal',
                                id: 2,
                                version: 1
                            },
                            {
                                title: `STK No. 13 Tahun ${currentYear-2}`,
                                description: 'Kebijakan Pengelolaan Dokumen',
                                id: 3,
                                version: 1
                            }
                        ];
                        displayFeaturedDocuments(fallbackDocs);
                    });
            }

            // Function to display featured documents
            function displayFeaturedDocuments(documents) {
                const container = document.getElementById('featured-docs-container');
                container.innerHTML = '';

                if (documents.length >= 3) {
                    // Display first two documents side by side
                    const row1 = document.createElement('div');
                    row1.className = 'row g-3';

                    // First document
                    const col1 = document.createElement('div');
                    col1.className = 'col-md-6';
                    col1.innerHTML = `
                        <div class="card" data-id="${documents[0].id}" data-version="${documents[0].version}">
                            <h5 class="card-title">${documents[0].title}</h5>
                            <p class="card-text">${documents[0].description}</p>
                        </div>
                    `;
                    row1.appendChild(col1);

                    // Second document
                    const col2 = document.createElement('div');
                    col2.className = 'col-md-6';
                    col2.innerHTML = `
                        <div class="card" data-id="${documents[1].id}" data-version="${documents[1].version}">
                            <h5 class="card-title">${documents[1].title}</h5>
                            <p class="card-text">${documents[1].description}</p>
                        </div>
                    `;
                    row1.appendChild(col2);

                    container.appendChild(row1);

                    // Third document in full width
                    const row2 = document.createElement('div');
                    row2.className = 'col-12';
                    row2.innerHTML = `
                        <div class="card" data-id="${documents[2].id}" data-version="${documents[2].version}">
                            <h5 class="card-title">${documents[2].title}</h5>
                            <p class="card-text">${documents[2].description}</p>
                        </div>
                    `;
                    container.appendChild(row2);
                } else {
                    // If less than 3 documents, display what's available
                    documents.forEach(doc => {
                        const docEl = document.createElement('div');
                        docEl.className = 'col-12';
                        docEl.innerHTML = `
                            <div class="card" data-id="${doc.id}" data-version="${doc.version}">
                                <h5 class="card-title">${doc.title}</h5>
                                <p class="card-text">${doc.description}</p>
                            </div>
                        `;
                        container.appendChild(docEl);
                    });
                }

                // Process new cards to open in modal
                setTimeout(handleDocumentCards, 100);
            }

            // Function to set up search functionality
            function setupSearch() {
                const searchInput = document.getElementById('global-search-input');
                const searchButton = document.getElementById('global-search-button');
                const searchModal = new bootstrap.Modal(document.getElementById('searchResultModal'));

                // Handle search button click
                searchButton.addEventListener('click', function() {
                    performSearch(searchInput.value);
                });

                // Handle Enter key press
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch(searchInput.value);
                    }
                });

                // Function to perform search
                function performSearch(query) {
                    if (!query.trim()) {
                        return; // Don't search empty queries
                    }

                    // Show modal and loading state
                    searchModal.show();
                    document.getElementById('search-loading').classList.remove('d-none');
                    document.getElementById('search-no-results').classList.add('d-none');
                    document.getElementById('search-results-container').innerHTML = '';

                    // Perform search with alternative endpoint
                    fetch(`/api/stk/simple-search?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            console.log('Search response status:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Hide loading
                            document.getElementById('search-loading').classList.add('d-none');

    console.log('Search results:', data);

    if (data.success && data.documents && data.documents.length > 0) {
        console.log('Rendering', data.documents.length, 'documents');
        displaySearchResults(data.documents);
    } else {
        console.log('No results found or empty documents array');
        // Show no results message
        document.getElementById('search-no-results').classList.remove('d-none');
    }
    })
    .catch(error => {
    console.error('Search error:', error);
    // Show error message
    document.getElementById('search-loading').classList.add('d-none');
    document.getElementById('search-results-container').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            Terjadi kesalahan saat mencari. Silakan coba lagi.
        </div>
    `;
    });
    }

    // Function to display search results
    function displaySearchResults(documents) {
    const resultsContainer = document.getElementById('search-results-container');
    resultsContainer.innerHTML = '';

    if (!documents || documents.length === 0) {
    document.getElementById('search-no-results').classList.remove('d-none');
    return;
    }

    // Create results heading
    const heading = document.createElement('div');
    heading.className = 'mb-3';
    heading.innerHTML = `<h6>Ditemukan ${documents.length} dokumen</h6>`;
    resultsContainer.appendChild(heading);

    // Create results list
    documents.forEach(doc => {
    // Format date
    let formattedDate = 'Tanggal tidak tersedia';
    try {
    if (doc.modified_date) {
        const date = new Date(doc.modified_date);
        formattedDate = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }
    } catch (e) {
    console.error('Error formatting date:', e);
    }

    const resultItem = document.createElement('div');
    resultItem.className = 'search-result-item p-3 border-bottom';
    resultItem.setAttribute('data-id', doc.id);
    resultItem.setAttribute('data-version', doc.version || 'latest');
    resultItem.style.cursor = 'pointer';
    resultItem.innerHTML = `
    <div class="d-flex">
        <div class="flex-shrink-0">
            <div class="document-icon" style="width:40px;height:40px;background:#e1f0ff;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0051a1;">
                <i class="fas fa-file-pdf"></i>
            </div>
        </div>
        <div class="flex-grow-1 ms-3">
            <h5 class="mb-1">${doc.title}</h5>
            <div class="d-flex flex-wrap justify-content-between">
                <div>
                    <span class="badge bg-primary me-2">${doc.jenis_stk || 'Tidak Dikategorikan'}</span>
                    <small class="text-muted">${doc.document_number || ''}</small>
                </div>
                <small class="text-muted">Diperbarui: ${formattedDate}</small>
            </div>
        </div>
    </div>
    `;

    // Make item clickable
    resultItem.addEventListener('click', function() {
    // Hide modal
    searchModal.hide();
    // Open document in modal
    viewDocumentInModal(this.getAttribute('data-id'), this.getAttribute('data-version'));
    });

    resultsContainer.appendChild(resultItem);
    });

    // Process any new document items
    setTimeout(handleDocumentCards, 100);
    }
    }

    // Toggle password visibility
    document.getElementById("toggle-password").addEventListener("click", function () {
    var passwordField = document.getElementById("password");
    var icon = this.querySelector("i");

    if (passwordField.type === "password") {
    passwordField.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
    } else {
    passwordField.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
    }
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Ambil data dari API
    fetchSTKData();

    // Fungsi untuk mengisi dropdown tahun secara dinamis
    function populateTahunDropdown(years) {
    const tahunDropdownMenu = document.getElementById('tahun-dropdown-menu');
    if (tahunDropdownMenu) {
    // Bersihkan dropdown menu tahun terlebih dahulu
    tahunDropdownMenu.innerHTML = '';

    // Tambahkan tahun-tahun yang ada
    if (years && Object.keys(years).length > 0) {
    Object.keys(years).sort((a, b) => b - a).forEach(year => {
        const yearLink = document.createElement('li');
        yearLink.innerHTML = `<a class="dropdown-item" href="#">${year} <span class="badge bg-primary rounded-pill ms-2">${years[year]}</span></a>`;
        tahunDropdownMenu.appendChild(yearLink);
    });
    } else {
    // Jika tidak ada item tahun, tambahkan tahun default (15 tahun)
    const currentYear = new Date().getFullYear();
    for (let i = 0; i < 15; i++) {
        const year = currentYear - i;
        const yearLink = document.createElement('li');
        yearLink.innerHTML = `<a class="dropdown-item" href="#">${year}</a>`;
        tahunDropdownMenu.appendChild(yearLink);
    }
    }
    }
    }

    // Fungsi untuk menampilkan dokumen terbaru
    function displayLatestDocuments(documents) {
    const container = document.getElementById('latest-documents-container');
    if (!container) return;

    container.innerHTML = '';

    if (!documents || documents.length === 0) {
    container.innerHTML = '<div class="alert alert-info">Belum ada dokumen terbaru.</div>';
    return;
    }

    documents.forEach(doc => {
    // Format tanggal
    let formattedDate = 'Tanggal tidak tersedia';
    try {
    if (doc.modified_date) {
        const date = new Date(doc.modified_date);
        formattedDate = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }
    } catch (e) {
    console.error('Error formatting date:', e);
    }

    const documentCard = document.createElement('div');
    documentCard.className = 'document-card';
    documentCard.setAttribute('data-id', doc.id);
    documentCard.setAttribute('data-version', doc.version || 'latest');
    documentCard.innerHTML = `
    <div class="document-icon">
        <i class="fas fa-file-pdf"></i>
    </div>
    <div class="document-content">
        <h4 class="document-title">${doc.title}</h4>
        <p class="document-desc">${doc.jenis_stk || 'Tidak Dikategorikan'}</p>
        <div class="document-meta">Diperbarui: ${formattedDate}</div>
    </div>
    `;

    container.appendChild(documentCard);
    });

    // Process new document cards
    setTimeout(handleDocumentCards, 100);
    }

    // Fungsi untuk menampilkan statistik
    function displayStatistics(summary) {
    // Total dokumen
    document.getElementById('total-documents').textContent = summary.total_documents || 0;

    // Jumlah jenis dokumen
    const docTypes = summary.documents_by_type ? Object.keys(summary.documents_by_type).length : 0;
    document.getElementById('document-types').textContent = docTypes;

    // Tahun terbaru dan jumlah tahun
    const years = summary.documents_by_year ? Object.keys(summary.documents_by_year) : [];
    const sortedYears = [...years].sort((a, b) => b - a);
    document.getElementById('latest-year').textContent = sortedYears[0] || '-';
    document.getElementById('document-years').textContent = years.length || 0;

    // Dokumen per tahun
    displayDocumentsByYear(summary.documents_by_year);
    }

    // Fungsi untuk menampilkan dokumen per tahun
    function displayDocumentsByYear(documentsByYear) {
    const container = document.getElementById('documents-by-year');
    if (!container) return;

    container.innerHTML = '';

    if (!documentsByYear || Object.keys(documentsByYear).length === 0) {
    container.innerHTML = '<div class="alert alert-info">Data tahun tidak tersedia.</div>';
    return;
    }

    // Urutkan tahun secara descending
    const sortedYears = Object.keys(documentsByYear).sort((a, b) => b - a);

    sortedYears.forEach(year => {
    const count = documentsByYear[year];

    const yearItem = document.createElement('div');
    yearItem.className = 'activity-item';
    yearItem.innerHTML = `
    <div class="activity-icon">
        <i class="fas fa-calendar-alt"></i>
    </div>
    <div class="activity-content">
        <div class="activity-title">Tahun ${year}</div>
        <div class="activity-time">${count} dokumen</div>
    </div>
    `;

    container.appendChild(yearItem);
    });
    }

    // Fungsi untuk mengambil data STK dari API
    function fetchSTKData() {
    fetch('/api/stk/summary')
    .then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
    })
    .then(data => {
    if (data.success) {
        const summary = data.summary;

        // Tampilkan dokumen terbaru
        displayLatestDocuments(summary.latest_documents);

        // Tampilkan statistik
        displayStatistics(summary);

        // Isi dropdown tahun
        populateTahunDropdown(summary.documents_by_year);
    } else {
        console.error('Failed to fetch STK data:', data.message);
    }
    })
    .catch(error => {
    console.error('Error fetching STK data:', error);
    });
    }
    });

    // Authentication state management
    const authManager = {
    // Check if logged in
    isLoggedIn: function() {
    return !!sessionStorage.getItem('mfiles_auth_token');
    },

    // Update UI based on auth state
    updateUI: function() {
    const headerRight = document.querySelector('.header-right');
    if (!headerRight) return;

    const username = sessionStorage.getItem('username') || 'User';

    if (this.isLoggedIn()) {
    // Show logout button and user info
    headerRight.innerHTML = `
    <div class="profile">
        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(username)}&background=3498db&color=fff" alt="User Profile">
        <span>${username}</span>
    </div>
    <button id="logout-button" class="btn btn-outline-danger ms-3" onclick="authManager.logout()">
        <i class="fas fa-sign-out-alt"></i> Logout
    </button>
    `;
    } else {
    // Show login button
    headerRight.innerHTML = `
    <button id="login-button" class="btn btn-primary" onclick="authManager.showLogin()">
        <i class="fas fa-sign-in-alt"></i> Login
    </button>
    `;
    }
    },

    // Show login modal
    showLogin: function() {
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
    },

    // Handle login success
    handleLoginSuccess: function(token, username) {
    sessionStorage.setItem('mfiles_auth_token', token);
    sessionStorage.setItem('username', username);
    this.updateUI();
    // Reload data
    fetchSTKSummary();
    },

    // Logout
    logout: function() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
    fetch('/mfiles/logout', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    }
    })
    .finally(() => {
    // Clear session storage
    sessionStorage.removeItem('mfiles_auth_token');
    sessionStorage.removeItem('username');

    // Update UI
    this.updateUI();

    // Show message
    alert('Logout berhasil');

    // Reload page
    window.location.reload();
    });
    }
    }
    };

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
    // Update UI based on auth state
    authManager.updateUI();

    // Check if authenticated before loading data
    if (authManager.isLoggedIn()) {
    fetchSTKSummary();
    } else {
    authManager.showLogin();
    }

    // Login form handler
    document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Show loading
    document.getElementById('login-form').classList.add('d-none');
    document.getElementById('login-loading').classList.remove('d-none');

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Login request
    fetch('http://dashboard-stk.test/api/mfiles/login', {
    method: 'POST',
    headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({
    username: username,
    password: password
    })
    })
    .then(response => response.json())
    .then(data => {
    if (data.success && data.token) {
    // Hide modal
    bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();

    // Handle success
    authManager.handleLoginSuccess(data.token, data.username);

    // Show success message
    showToast('Login Successful', `Welcome, ${data.username}!`, 'success');
    } else {
    // Show error
    document.getElementById('login-form').classList.remove('d-none');
    document.getElementById('login-loading').classList.add('d-none');
    document.getElementById('login-error').textContent = data.message || 'Login failed';
    document.getElementById('login-error').classList.remove('d-none');
    }
    })
    .catch(error => {
    // Show error
    document.getElementById('login-form').classList.remove('d-none');
    document.getElementById('login-loading').classList.add('d-none');
    document.getElementById('login-error').textContent = 'Network error';
    document.getElementById('login-error').classList.remove('d-none');
    });
    });
    });

    // Show toast notification
    function showToast(title, message, type = 'info') {
    // Remove existing toast container
    const existingContainer = document.querySelector('.toast-container');
    if (existingContainer) {
    document.body.removeChild(existingContainer);
    }

    // Create new toast container
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';

    // Determine icon and background based on type
    let icon, bgClass;
    switch (type) {
    case 'success':
    icon = 'fas fa-check-circle';
    bgClass = 'text-bg-success';
    break;
    case 'error':
    icon = 'fas fa-exclamation-circle';
    bgClass = 'text-bg-danger';
    break;
    case 'warning':
    icon = 'fas fa-exclamation-triangle';
    bgClass = 'text-bg-warning';
    break;
    default:
    icon = 'fas fa-info-circle';
    bgClass = 'text-bg-info';
    }

    toastContainer.innerHTML = `
    <div class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
    <div class="toast-body">
        <div class="d-flex align-items-center">
            <i class="${icon} me-2"></i>
            <div>
                <strong>${title}</strong>
                <div>${message}</div>
            </div>
        </div>
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    </div>
    `;

    document.body.appendChild(toastContainer);

    const toastEl = toastContainer.querySelector('.toast');
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();

    // Auto remove after hiding
    toastEl.addEventListener('hidden.bs.toast', () => {
    if (document.body.contains(toastContainer)) {
    document.body.removeChild(toastContainer);
    }
    });
    }

    function logoutFromSystem() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
    localStorage.clear();
    sessionStorage.clear();
    window.location.reload();
    }
    }
    </script>
    </body>
    </html>
