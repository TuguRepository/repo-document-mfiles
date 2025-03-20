<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen {{ $categoryTitle }} - Sistem Tata Kelola Tugu Insurance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>

        /* Ensure checkbox is clickable */
#agreeTerms {
    z-index: 1000;
    position: relative;
    pointer-events: auto !important;
    opacity: 1 !important;
    cursor: pointer !important;
    width: 20px;
    height: 20px;
}

/* Make the label clickable too */
label[for="agreeTerms"] {
    cursor: pointer;
    user-select: none;
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
            padding: 3rem 0;
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
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .page-description {
            max-width: 700px;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .content-section {
            padding: 3rem 0;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-bar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.25rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
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
            cursor: pointer;
        }

        .document-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
        }

        .document-content {
            flex: 1;
        }

        .document-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
            color: var(--dark);
        }

        .document-number {
            font-size: 0.9rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .document-desc {
            font-size: 0.9rem;
            color: #637381;
            margin-bottom: 0.3rem;
        }

        .document-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .document-date {
            font-size: 0.8rem;
            color: #919eab;
        }

        .document-actions {
            display: flex;
            gap: 0.5rem;
        }

        .document-actions button {
            border: none;
            background: none;
            color: var(--primary);
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .document-actions button:hover {
            background: var(--primary-light);
        }

        .sidebar-filter {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .sidebar-filter h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .filter-group {
            margin-bottom: 1.25rem;
        }

        .filter-group h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #637381;
        }

        .filter-checkbox {
            margin-bottom: 0.5rem;
        }

        .filter-checkbox label {
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination .page-link {
            color: var(--primary);
            border-color: var(--border-color);
            margin: 0 0.25rem;
            border-radius: 6px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .pagination .page-link:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .search-container {
            position: relative;
        }

        .search-input {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #637381;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .no-results {
            display: none;
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .no-results i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
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

        .badge-category {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge-primary {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .badge-secondary {
            background-color: var(--secondary-light);
            color: var(--secondary);
        }

        .footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0 1.5rem;
            margin-top: 3rem;
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

        /* Skeleton loading effect */
        .skeleton-loader {
            animation: skeleton-loading 1s linear infinite alternate;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: #e7ecf0;
            }
            100% {
                background-color: #f3f6f9;
            }
        }

        @media (max-width: 991.98px) {
            .page-header {
                padding: 2.5rem 0;
            }

            .page-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.5rem;
            }

            .page-description {
                font-size: 1rem;
            }

            .filter-bar {
                padding: 1rem;
            }
        }

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

        /* Styling untuk checkbox agreement */
        .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .form-check-input {
        width: 1.25em;
        height: 1.25em;
        cursor: pointer;
        transition: all 0.3s ease;
        }

        .form-check-input:hover {
        border-color: #0d6efd;
        }

        .form-check-label {
        transition: all 0.3s ease;
        padding-left: 0.25rem;
        }

        /* Animasi untuk checkbox ketika dicentang */
        @keyframes checkmark {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
        }

        .form-check-input:checked {
        animation: checkmark 0.3s ease-in-out;
        }

        /* Styling untuk teks ketika disetujui */
        .text-success.fw-bold {
        animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
        }
    </style>
    @include('layouts.stk.header')
    @include('stk.approvals.partials.document-preview-modal')
<script src="{{ asset('js/document-preview.js') }}"></script>
<!-- At the end of your body tag -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    {{-- <!-- Navbar -->
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
                        <a class="nav-link dropdown-toggle active" href="#" id="jenisDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Jenis
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="jenisDropdown">
                            <li><a class="dropdown-item {{ $categoryCode == 'A' ? 'active' : '' }}" href="{{ url('/stk/category/pedoman') }}">Pedoman</a></li>
                            <li><a class="dropdown-item {{ $categoryCode == 'B' ? 'active' : '' }}" href="{{ url('/stk/category/tko') }}">Tata Kerja Organisasi</a></li>
                            <li><a class="dropdown-item {{ $categoryCode == 'C' ? 'active' : '' }}" href="{{ url('/stk/category/tki') }}">Tata Kerja Individu</a></li>
                            <li><a class="dropdown-item {{ $categoryCode == 'D' ? 'active' : '' }}" href="{{ url('/stk/category/bpcp') }}">BPCP</a></li>
                            <li><a class="dropdown-item {{ $categoryCode == 'SOP' ? 'active' : '' }}" href="{{ url('/stk/category/sop') }}">SOP</a></li>
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
                        </div>
                        <button id="logout-button" class="btn btn-outline-danger ms-3" onclick="logoutFromSystem()" title="Logout">
                          <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                      </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </nav> --}}
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-pattern"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="page-title">{{ $categoryTitle }}</h1>
                    <p class="page-description">{{ $categoryDescription }}</p>
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
                    <div class="sidebar-filter">
                        <h3>Filter Dokumen</h3>

                        <div class="filter-group">
                            <h4>Tahun</h4>
                            <div id="year-filters">
                                <!-- Tahun filter akan diisi secara dinamis -->
                                <div class="text-center py-2">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <button id="clear-filters" class="btn btn-outline-secondary w-100">Reset Filter</button>
                        </div>
                    </div>
                </div>

                <!-- Main Content / Document List -->
                <div class="col-lg-9">
                    <div class="filter-bar">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="search-container">
                                    <span class="search-icon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-input" class="search-input" placeholder="Cari dokumen...">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-sort me-1"></i> Urutkan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item" data-sort="newest">Terbaru</button></li>
                                        <li><button class="dropdown-item" data-sort="oldest">Terlama</button></li>
                                        <li><button class="dropdown-item" data-sort="a-z">A-Z</button></li>
                                        <li><button class="dropdown-item" data-sort="z-a">Z-A</button></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="documents-container">
                        <!-- Documents will be loaded here -->
                        <div class="document-card skeleton-loader">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-content" style="width: 100%">
                                <div class="document-title skeleton-loader" style="height: 24px; width: 70%;"></div>
                                <div class="document-number skeleton-loader" style="height: 18px; width: 40%; margin-top: 8px;"></div>
                                <div class="document-desc skeleton-loader" style="height: 16px; width: 90%; margin-top: 8px;"></div>
                                <div class="document-meta skeleton-loader" style="height: 16px; width: 60%; margin-top: 8px;"></div>
                            </div>
                        </div>
                        <div class="document-card skeleton-loader">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-content" style="width: 100%">
                                <div class="document-title skeleton-loader" style="height: 24px; width: 80%;"></div>
                                <div class="document-number skeleton-loader" style="height: 18px; width: 35%; margin-top: 8px;"></div>
                                <div class="document-desc skeleton-loader" style="height: 16px; width: 95%; margin-top: 8px;"></div>
                                <div class="document-meta skeleton-loader" style="height: 16px; width: 50%; margin-top: 8px;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="loading-spinner" class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat dokumen...</p>
                    </div>

                    <div id="no-results" class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Tidak ada dokumen yang ditemukan</h3>
                        <p>Coba ubah kriteria pencarian atau filter Anda.</p>
                    </div>

                    <div id="pagination-container" class="pagination-container">
                        <nav aria-label="Halaman dokumen">
                            <ul class="pagination">
                                <!-- Pagination will be added dynamically -->
                            </ul>
                        </nav>
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

{{-- <!-- Modal Preview Dokumen -->
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
                                    <label class="form-check-label" for="newAgreeTerms">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    @include('layouts.footer')
    <script>
        let idDocument = null;

        document.addEventListener('DOMContentLoaded', function() {
        const newCheckbox = document.getElementById('newAgreeTerms');
        const agreeLabel = document.querySelector('label[for="newAgreeTerms"]');

        if (newCheckbox && agreeLabel) {
            // Add styling to make the checkbox more visible when checked
            newCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Jika dicentang, tambahkan kelas untuk styling
                    agreeLabel.classList.add('text-success', 'fw-bold');
                    // Tambahkan teks konfirmasi dengan ikon centang
                    agreeLabel.innerHTML = 'Saya menyetujui <span class="text-success">✓</span> bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.';
                } else {
                    // Jika tidak dicentang, hapus kelas styling
                    agreeLabel.classList.remove('text-success', 'fw-bold');
                    // Kembalikan teks asli
                    agreeLabel.textContent = 'Saya menyatakan bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.';
                }
            });

            // Tambahkan gaya visual untuk label
            agreeLabel.style.cursor = 'pointer';
        }
    });

        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            const categoryCode = '{{ $categoryCode }}';
            let currentPage = 1;
            let totalPages = 1;
            let currentSort = 'newest';
            let currentSearch = '';
            let selectedYears = [];
            let allDocuments = [];

            // Initialize the page
            fetchDocuments();

            // Setup document preview with modal
            setupDocumentPreview();

            // Fetch documents from the API
            function fetchDocuments() {
                showLoading();

                // Fetch data from API
                fetch(`/api/stk/documents?category=${categoryCode}&page=${currentPage}&sort=${currentSort}&search=${currentSearch}&years=${selectedYears.join(',')}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            allDocuments = data.documents || [];
                            totalPages = data.pagination.total_pages || 1;

                            // Display documents
                            displayDocuments(allDocuments);

                            // Setup pagination
                            setupPagination(currentPage, totalPages);

                            // Setup year filters
                            if (data.years) {
                                setupYearFilters(data.years);
                            }

                            // Show filter stats
                            updateFilterStats(data.total || allDocuments.length);

                            hideLoading();
                        } else {
                            console.error('Failed to fetch documents:', data.message);
                            showNoResults();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching documents:', error);
                        showNoResults();
                    });
            }

            // Display documents in the container
            function displayDocuments(documents) {
                const container = document.getElementById('documents-container');
                container.innerHTML = '';

                if (!documents || documents.length === 0) {
                    showNoResults();
                    return;
                }

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
                            <div class="document-number">${doc.document_number || 'No. Dokumen tidak tersedia'}</div>
                            <p class="document-desc">${doc.jenis_stk || 'Jenis tidak tersedia'}</p>
                            <div class="document-meta">
                                <div class="document-date">Diperbarui: ${formattedDate}</div>
                            </div>
                        </div>
                    `;

                    container.appendChild(documentCard);
                });

                hideNoResults();

                // Add event handlers for the new document cards
                setTimeout(handleDocumentCards, 100);
            }

            // Setup pagination controls
            function setupPagination(currentPage, totalPages) {
                const paginationContainer = document.querySelector('.pagination');
                if (!paginationContainer) return;

                paginationContainer.innerHTML = '';

                // Previous button
                const prevItem = document.createElement('li');
                prevItem.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevItem.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
                prevItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        goToPage(currentPage - 1);
                    }
                });
                paginationContainer.appendChild(prevItem);

                // Page numbers
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);

                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageItem = document.createElement('li');
                    pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    pageItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        goToPage(i);
                    });
                    paginationContainer.appendChild(pageItem);
                }

                // Next button
                const nextItem = document.createElement('li');
                nextItem.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                nextItem.innerHTML = `<a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
                nextItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        goToPage(currentPage + 1);
                    }
                });
                paginationContainer.appendChild(nextItem);
            }

            // Navigate to specific page
            function goToPage(page) {
                currentPage = page;
                fetchDocuments();

                // Scroll to top of results
                const documentsContainer = document.getElementById('documents-container');
                if (documentsContainer) {
                    documentsContainer.scrollIntoView({ behavior: 'smooth' });
                }
            }

            // Setup year filters
            function setupYearFilters(years) {
                const yearFiltersContainer = document.getElementById('year-filters');
                if (!yearFiltersContainer) return;

                yearFiltersContainer.innerHTML = '';

                // Get the years and sort them in descending order
                const sortedYears = Object.keys(years).sort((a, b) => b - a);

                sortedYears.forEach(year => {
                    const count = years[year];

                    const checkboxDiv = document.createElement('div');
                    checkboxDiv.className = 'filter-checkbox';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `year-${year}`;
                    checkbox.value = year;
                    checkbox.checked = selectedYears.includes(year);
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            selectedYears.push(year);
                        } else {
                            selectedYears = selectedYears.filter(y => y !== year);
                        }
                        currentPage = 1; // Reset to first page
                        fetchDocuments();
                    });

                    const label = document.createElement('label');
                    label.htmlFor = `year-${year}`;
                    label.textContent = `${year} (${count})`;

                    checkboxDiv.appendChild(checkbox);
                    checkboxDiv.appendChild(label);
                    yearFiltersContainer.appendChild(checkboxDiv);
                });

                // Populate the tahun dropdown menu as well
                populateTahunDropdown(years);
            }

            // Update filter stats
            function updateFilterStats(totalFound) {
                const filterBar = document.querySelector('.filter-bar');
                if (!filterBar) return;

                // Check if stats element exists
                let statsElement = document.getElementById('filter-stats');

                if (!statsElement) {
                    // Create stats element if it doesn't exist
                    statsElement = document.createElement('div');
                    statsElement.id = 'filter-stats';
                    statsElement.className = 'mt-2 small text-muted';
                    filterBar.appendChild(statsElement);
                }

                // Update stats text
                let statsText = `Ditemukan ${totalFound} dokumen`;

                if (currentSearch) {
                    statsText += ` untuk pencarian "${currentSearch}"`;
                }

                if (selectedYears.length > 0) {
                    statsText += ` pada tahun ${selectedYears.join(', ')}`;
                }

                statsElement.textContent = statsText;
            }

            // Show loading spinner
            function showLoading() {
                document.getElementById('documents-container').style.display = 'none';
                document.getElementById('loading-spinner').style.display = 'block';
                document.getElementById('no-results').style.display = 'none';
            }

            // Hide loading spinner
            function hideLoading() {
                document.getElementById('documents-container').style.display = 'block';
                document.getElementById('loading-spinner').style.display = 'none';
            }

            // Show no results message
            function showNoResults() {
                document.getElementById('documents-container').style.display = 'none';
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('no-results').style.display = 'block';
                document.getElementById('pagination-container').style.display = 'none';
            }

            // Hide no results message
            function hideNoResults() {
                document.getElementById('no-results').style.display = 'none';
                document.getElementById('pagination-container').style.display = 'flex';
            }

            // Search functionality
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                let debounceTimeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimeout);

                    debounceTimeout = setTimeout(() => {
                        currentSearch = this.value.trim();
                        currentPage = 1; // Reset to first page
                        fetchDocuments();
                    }, 500); // 500ms debounce
                });
            }

            // Sort functionality
            const sortButtons = document.querySelectorAll('[data-sort]');
            sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentSort = this.getAttribute('data-sort');
                    currentPage = 1; // Reset to first page
                    fetchDocuments();

                    // Update sort button text
                    const sortText = {
                        'newest': 'Terbaru',
                        'oldest': 'Terlama',
                        'a-z': 'A-Z',
                        'z-a': 'Z-A'
                    };

                    document.querySelector('.dropdown-toggle').innerHTML = `<i class="fas fa-sort me-1"></i> ${sortText[currentSort]}`;
                });
            });

            // Clear filters
            const clearFiltersButton = document.getElementById('clear-filters');
            if (clearFiltersButton) {
                clearFiltersButton.addEventListener('click', function() {
                    selectedYears = [];
                    currentPage = 1;

                    // Uncheck all checkboxes
                    const checkboxes = document.querySelectorAll('#year-filters input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    fetchDocuments();
                });
            }

            // Populate tahun dropdown
            function populateTahunDropdown(years) {
                const tahunDropdownMenu = document.getElementById('tahun-dropdown-menu');
                if (!tahunDropdownMenu) return;

                tahunDropdownMenu.innerHTML = '';

                if (years && Object.keys(years).length > 0) {
                    Object.keys(years).sort((a, b) => b - a).forEach(year => {
                        const count = years[year];
                        const yearLink = document.createElement('li');
                        yearLink.innerHTML = `<a class="dropdown-item" href="{{ url('/stk/year/${year}') }}">${year} <span class="badge bg-primary rounded-pill ms-2">${count}</span></a>`;
                        tahunDropdownMenu.appendChild(yearLink);
                    });
                }
            }
        });

        // Fungsi untuk menampilkan toast notification
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
                        // Don't trigger click if clicking on action buttons
                        if (e.target.closest('.document-actions')) {
                            return;
                        }
                        e.preventDefault();
                        e.stopPropagation();

                        // Try to get ID from attributes
                        let id = this.getAttribute('data-id');
                        let version = this.getAttribute('data-version') || 'latest';

                        // If ID found, show in modal
                        if (id) {
                            viewDocumentInModal(id, version);
                        }
                    });
                }
            });

            // Also handle view buttons directly
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(btn => {
                // Skip already processed buttons
                if (btn.hasAttribute('data-modal-handler')) {
                    return;
                }

                // Mark as processed
                btn.setAttribute('data-modal-handler', 'true');

                // Add click event listener
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    idDocument = this.getAttribute('data-id');
                    const version = this.getAttribute('data-version') || 'latest';
                    if (idDocument) {
                        viewDocumentInModal(idDocument, version);
                    }
                });
            });

            // Handle download buttons
            const downloadButtons = document.querySelectorAll('.download-btn');
            downloadButtons.forEach(btn => {
                // Skip already processed buttons
                if (btn.hasAttribute('data-download-handler')) {
                    return;
                }

                // Mark as processed
                btn.setAttribute('data-download-handler', 'true');

                // Add click event listener
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const version = this.getAttribute('data-version') || 'latest';
                    if (id) {
                        downloadDocument(id, version);
                    }
                });
            });
        }

        // Override standard download function
        window.downloadDocument = function(id, version) {
            // Show toast notification before starting download
            showToast('Dokumen STK', 'Dokumen sedang diunduh...', 'success');

            // Create temporary iframe for download
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = `/stk/download/${id}${version ? '/' + version : ''}?download=true`;
            document.body.appendChild(iframe);

            // Clean up after 1 second
            setTimeout(() => {
                document.body.removeChild(iframe);
            }, 1000);
        };

        function logoutFromSystem() {
            // Display confirmation dialog
            if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                // Make logout request
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '/login';
                    } else {
                        alert('Gagal logout. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    alert('Terjadi kesalahan saat logout.');
                });
            }
        }

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

        // // Initialize on page load
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Update UI based on auth state
        //     authManager.updateUI();

        //     // Check if authenticated before loading data
        //     if (authManager.isLoggedIn()) {
        //         fetchSTKSummary();
        //     } else {
        //         authManager.showLogin();
        //     }

        //     // Login form handler
        //     document.getElementById('login-form').addEventListener('submit', function(e) {
        //         e.preventDefault();

        //         // Show loading
        //         document.getElementById('login-form').classList.add('d-none');
        //         document.getElementById('login-loading').classList.remove('d-none');

        //         const username = document.getElementById('username').value;
        //         const password = document.getElementById('password').value;

        //         // Login request
        //         fetch('http://dashboard-stk.test/api/mfiles/login', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'Accept': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        //             },
        //             body: JSON.stringify({
        //                 username: username,
        //                 password: password
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success && data.token) {
        //                 // Hide modal
        //                 bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();

        //                 // Handle success
        //                 authManager.handleLoginSuccess(data.token, data.username);

        //                 // Show success message
        //                 showToast('Login Successful', `Welcome, ${data.username}!`, 'success');
        //             } else {
        //                 // Show error
        //                 document.getElementById('login-form').classList.remove('d-none');
        //                 document.getElementById('login-loading').classList.add('d-none');
        //                 document.getElementById('login-error').textContent = data.message || 'Login failed';
        //                 document.getElementById('login-error').classList.remove('d-none');
        //             }
        //         })
        //         .catch(error => {
        //             // Show error
        //             document.getElementById('login-form').classList.remove('d-none');
        //             document.getElementById('login-loading').classList.add('d-none');
        //             document.getElementById('login-error').textContent = 'Network error';
        //             document.getElementById('login-error').classList.remove('d-none');
        //         });
        //     });
        // });

//         // Function untuk menampilkan toast notification
// function showToast(title, message, type = 'info') {
//     // Cek apakah sudah ada container untuk toast
//     let toastContainer = document.getElementById('toast-container');

//     if (!toastContainer) {
//         // Buat container baru jika belum ada
//         toastContainer = document.createElement('div');
//         toastContainer.id = 'toast-container';
//         toastContainer.className = 'position-fixed top-0 end-0 p-3';
//         toastContainer.style.zIndex = '1080';
//         document.body.appendChild(toastContainer);
//     }

//     // Buat ID unik untuk toast
//     const toastId = 'toast-' + Date.now();

//     // Tentukan class background berdasarkan type
//     let bgClass = 'bg-info text-white';
//     let iconClass = 'info-circle';

//     if (type === 'success') {
//         bgClass = 'bg-success text-white';
//         iconClass = 'check-circle';
//     } else if (type === 'error' || type === 'danger') {
//         bgClass = 'bg-danger text-white';
//         iconClass = 'exclamation-circle';
//     } else if (type === 'warning') {
//         bgClass = 'bg-warning text-dark';
//         iconClass = 'exclamation-triangle';
//     }

//     // Buat HTML untuk toast
//     const toast = `
//         <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
//             <div class="toast-header ${bgClass}">
//                 <i class="fas fa-${iconClass} me-2"></i>
//                 <strong class="me-auto">${title}</strong>
//                 <small>Baru saja</small>
//                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
//             </div>
//             <div class="toast-body">
//                 ${message}
//             </div>
//         </div>
//     `;

//     // Tambahkan toast ke container
//     toastContainer.insertAdjacentHTML('beforeend', toast);

//     // Inisialisasi toast dan tampilkan
//     const toastElement = document.getElementById(toastId);
//     const bsToast = new bootstrap.Toast(toastElement);
//     bsToast.show();

//     // Hapus toast dari DOM setelah ditutup
//     toastElement.addEventListener('hidden.bs.toast', function() {
//         toastElement.remove();
//     });
// }

// // Improved updateNotificationBadge function
// function updateNotificationBadge() {
//     // Perbaikan route API sesuai dengan struktur aplikasi
//     fetch('/api/stk/pending-count')
//         .then(response => {
//             if (!response.ok) {
//                 // If response not OK, log and stop
//                 console.warn('Notification API tidak tersedia:', response.status);
//                 return Promise.reject('API returned error status');
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data && data.success) {
//                 const badge = document.querySelector('.notification-badge');
//                 if (badge) {
//                     badge.setAttribute('data-count', data.count.toString());
//                 }

//                 const pendingTab = document.querySelector('#pending-tab .badge');
//                 if (pendingTab) {
//                     pendingTab.textContent = data.count.toString();
//                 }
//             }
//         })
//         .catch(error => {
//             // More elegant error handling without crashing
//             console.warn('Error updating notification badge:', error);
//         });
// }

// // Script utama
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('DOM loaded - completely replacing form');

//     // Get the form container
//     const downloadRequestForm = document.getElementById('downloadRequestForm');

//     if (downloadRequestForm) {
//         console.log('Form container found, replacing content');
//         // Replace the entire form with new HTML
//         downloadRequestForm.innerHTML = `
//             <div class="card">
//                 <div class="card-header bg-primary text-white">
//                     <h5 class="mb-0">Request Download Dokumen</h5>
//                 </div>
//                 <div class="card-body">
//                     <form method="POST" id="formRequestDownload" action="{{ route('download-requests.store')}}">
//                         @csrf
//                         <input type="hidden" id="requestDocId" name="document_id">
//                         <input type="hidden" id="requestDocVersion" name="document_version">

//                         <div class="mb-3">
//                             <label for="requestReason" class="form-label">Alasan Permintaan <span class="text-danger">*</span></label>
//                             <select class="form-select" id="requestReason" name="reason" required>
//                                 <option value="">-- Pilih Alasan --</option>
//                                 <option value="reference">Referensi Pekerjaan</option>
//                                 <option value="audit">Audit/Compliance</option>
//                                 <option value="sharing">Sharing Knowledge</option>
//                                 <option value="other">Lainnya</option>
//                             </select>
//                         </div>

//                         <div class="mb-3" id="otherReasonContainer" style="display: none;">
//                             <label for="otherReason" class="form-label">Alasan Lainnya <span class="text-danger">*</span></label>
//                             <textarea class="form-control" id="otherReason" name="other_reason" rows="2"></textarea>
//                         </div>

//                         <div class="mb-3">
//                             <label for="requestNotes" class="form-label">Catatan Tambahan</label>
//                             <textarea class="form-control" id="requestNotes" name="notes" rows="2"></textarea>
//                         </div>

//                         <div class="form-check mb-3">
//                             <input class="form-check-input" type="checkbox" id="newAgreeTerms" name="agree_terms" required>
//                             <label class="form-check-label" for="newAgreeTerms">
//                                 Saya menyatakan bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.
//                             </label>
//                         </div>

//                         <div class="d-flex justify-content-between">
//                             <button type="button" class="btn btn-secondary" id="newCancelRequestBtn">
//                                 <i class="fas fa-times"></i> Batal
//                             </button>
//                             <button type="submit" class="btn btn-primary" id="newSubmitRequestBtn">
//                                 <i class="fas fa-paper-plane"></i> Kirim Permintaan
//                             </button>
//                         </div>
//                     </form>
//                 </div>
//             </div>
//         `;

//         // Set up event handlers for the new elements
//         const newReasonSelect = document.getElementById('requestReason');
//         const newOtherReasonContainer = document.getElementById('otherReasonContainer');
//         const newCancelButton = document.getElementById('newCancelRequestBtn');
//         const newSubmitButton = document.getElementById('newSubmitRequestBtn');
//         const formRequestDownload = document.getElementById('formRequestDownload');
//         // Get the checkbox element
//         const newCheckbox = document.getElementById('newAgreeTerms');
//         const agreeLabel = document.querySelector('label[for="newAgreeTerms"]');

//         // Add click event listener to both the checkbox and the label
//         if (newCheckbox) {
//             // Add styling to make the checkbox more visible when checked
//             newCheckbox.addEventListener('change', function() {
//                 if (this.checked) {
//                     // Jika dicentang, tambahkan kelas untuk styling
//                     agreeLabel.classList.add('text-success', 'fw-bold');
//                     // Tambahkan teks konfirmasi
//                     agreeLabel.innerHTML = 'Saya menyetujui <span class="text-success">✓</span> bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.';
//                 } else {
//                     // Jika tidak dicentang, hapus kelas styling
//                     agreeLabel.classList.remove('text-success', 'fw-bold');
//                     // Kembalikan teks asli
//                     agreeLabel.textContent = 'Saya menyatakan bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.';
//                 }
//             });

//             // Juga tambahkan kemampuan untuk mengklik label untuk mencentang checkbox
//             agreeLabel.addEventListener('click', function(e) {
//                 // Prevent default to avoid double-toggling since label is already linked to checkbox
//                 e.preventDefault();

//                 // Toggle checkbox state
//                 newCheckbox.checked = !newCheckbox.checked;

//                 // Trigger the change event manually
//                 const event = new Event('change');
//                 newCheckbox.dispatchEvent(event);
//             });
//         }

//         // Tambahkan gaya visual untuk checkbox saat hover
//         if (agreeLabel) {
//             agreeLabel.style.cursor = 'pointer';
//             agreeLabel.addEventListener('mouseover', function() {
//                 this.classList.add('text-primary');
//             });

//             agreeLabel.addEventListener('mouseout', function() {
//                 if (!newCheckbox.checked) {
//                     this.classList.remove('text-primary');
//                 }
//             });
//         }

//        // Form submission handler
// if (formRequestDownload) {
//     formRequestDownload.addEventListener('submit', function(e) {
//         e.preventDefault(); // Prevent default form submission
//         console.log('Form submitted');

//         // Check if checkbox is checked
//         const agreeTerms = document.getElementById('newAgreeTerms');
//         if (agreeTerms && !agreeTerms.checked) {
//             alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu!');
//             return;
//         }

//         // Show loading state
//         const submitButton = document.getElementById('newSubmitRequestBtn');
//         if (submitButton) {
//             submitButton.disabled = true;
//             submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
//         }

//         // Get form data
//         const formData = new FormData(this);

//         // Set document ID from global variable
//         formData.set('document_id', idDocument);

//         // Get CSRF token
//         const csrfToken = document.querySelector('meta[name="csrf-token"]');

//         // Debug information
//         console.log('Sending form data:', Object.fromEntries(formData));
//         console.log('Document ID:', idDocument);

//         // Submit form data using fetch API
//         fetch('/api/stk/download-requests', {
//             method: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
//                 'Accept': 'application/json',
//                 'X-Requested-With': 'XMLHttpRequest'
//             },
//             body: formData
//         })
//         .then(response => {
//             // Always reset button state
//             if (submitButton) {
//                 submitButton.disabled = false;
//                 submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Permintaan';
//             }

//             // Check if response is OK
//             if (!response.ok) {
//                 return response.text().then(text => {
//                     console.error('Server response:', text);
//                     throw new Error('Network response was not ok');
//                 });
//             }

//             return response.json();
//         })
//         .then(data => {
//             console.log('Success:', data);

//             // Generate reference number for display
//             const refNumber = data.request_id ? 'REF-' + data.request_id : 'REF-' + Math.floor(Math.random() * 900000 + 100000);
//             if (document.getElementById('requestReferenceNumber')) {
//                 document.getElementById('requestReferenceNumber').textContent = refNumber;
//             }

//             // Hide preview modal
//             const previewModal = bootstrap.Modal.getInstance(document.getElementById('documentPreviewModal'));
//             if (previewModal) {
//                 previewModal.hide();
//             }

//             // Show success modal
//             const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
//             successModal.show();

//             // Show toast notification
//             showToast(
//                 'Permintaan Terkirim',
//                 'Permintaan download dokumen Anda telah berhasil dikirim dan sedang menunggu persetujuan.',
//                 'success'
//             );

//             // Reset form and restore preview container
//             formRequestDownload.reset();
//             document.getElementById('downloadRequestForm').classList.add('d-none');
//             document.getElementById('documentPreviewContainer').classList.remove('d-none');
//         })
//         .catch(error => {
//             console.error('Error:', error);

//             // Show error toast
//             showToast(
//                 'Terjadi Kesalahan',
//                 'Gagal mengirim permintaan. Silakan coba lagi nanti.',
//                 'error'
//             );
//         });
//     });
// }
// console.log('Form data before submit:', Object.fromEntries(formData));
// console.log('Current document ID:', idDocument);
//         // Toggle other reason field visibility
//         if (newReasonSelect) {
//             newReasonSelect.addEventListener('change', function() {
//                 if (this.value === 'other') {
//                     newOtherReasonContainer.style.display = 'block';
//                 } else {
//                     newOtherReasonContainer.style.display = 'none';
//                 }
//             });
//         }

//         // Cancel button handler
//         if (newCancelButton) {
//             newCancelButton.addEventListener('click', function() {
//                 downloadRequestForm.classList.add('d-none');
//                 document.getElementById('documentPreviewContainer').classList.remove('d-none');
//             });
//         }

//         // Submit button handler
//         if (newSubmitButton) {
//             newSubmitButton.addEventListener('click', function() {
//                 console.log('Submit button clicked');

//                 // Check if checkbox is checked
//                 if (newCheckbox && !newCheckbox.checked) {
//                     alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu!');
//                     return;
//                 }

//                 // Generate reference number
//                 const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);
//                 document.getElementById('requestReferenceNumber').textContent = refNumber;

//                 // Show success modal and hide preview modal
//                 const previewModal = bootstrap.Modal.getInstance(document.getElementById('documentPreviewModal'));
//                 if (previewModal) {
//                     previewModal.hide();
//                 }
// // When showing success modal
// const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
// successModal.show();

// // After showing modal, move focus to an element inside it
// setTimeout(() => {
//     const okButton = document.querySelector('#requestSuccessModal .btn-primary');
//     if (okButton) okButton.focus();
// }, 500);

//                 // Tampilkan notifikasi toast bahwa permintaan telah berhasil dikirim
//                 showToast(
//                     'Permintaan Terkirim',
//                     'Permintaan download dokumen Anda telah berhasil dikirim dan sedang menunggu persetujuan.',
//                     'success'
//                 );

//                 // Reset form and restore preview container
//                 formRequestDownload.reset();
//                 downloadRequestForm.classList.add('d-none');
//                 document.getElementById('documentPreviewContainer').classList.remove('d-none');
//             });
//         }
//     } else {
//         console.error('Form container not found');
//     }

//     // Inisialisasi notifikasi dengan penanganan error yang lebih baik
//     try {
//         // Inisialisasi sistem notifikasi
//         if (typeof Echo !== 'undefined') {
//             Echo.private('stk.approvals')
//                 .listen('NewDownloadRequest', (e) => {
//                     // Handle new download request notifications
//                     showToast(
//                         'Permintaan Download Baru',
//                         `<strong>${e.user.name}</strong> dari <strong>${e.user.department}</strong> mengajukan permintaan download dokumen <strong>${e.document.title}</strong>.`,
//                         'info'
//                     );

//                     // Update badge count
//                     updateNotificationBadge();

//                     // Add to list if on first page
//                     if (window.location.search === '' || window.location.search.includes('page=1')) {
//                         addNewRequestToList(e);
//                     }
//                 });
//         }

//         // Coba update notification badge
//         try {
//             updateNotificationBadge();
//         } catch (error) {
//             console.warn('Tidak dapat memperbarui badge notifikasi:', error);
//         }
//     } catch (error) {
//         console.warn('Error saat menginisialisasi notifikasi:', error);
//     }
// });

</script>
<script src="{{ asset('js/approval-requests.js') }}"></script>
<script src="{{ asset('js/document-metadata.js') }}"></script>
</body>
</html>
