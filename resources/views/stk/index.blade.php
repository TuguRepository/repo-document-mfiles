<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen STK - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>

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

  /* Overlay for when not logged in */
  .auth-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1999;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .auth-overlay-message {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;
  }

  #logout-button {
    display: flex;
    align-items: center;
    transition: all 0.3s;
    border-radius: 8px;
  }

  #logout-button i {
    margin-right: 5px;
  }

  #logout-button:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
  }

  /* Make sure this works on mobile too */
  @media (max-width: 576px) {
    #logout-button span {
      display: none;
    }

    #logout-button i {
      margin-right: 0;
    }
  }

  .sidebar-menu {
    list-style: none;
    padding: 20px 0;
    margin: 0;
    position: relative;
    min-height: 100%;
    display: flex;
    flex-direction: column;
  }

  #sidebar-logout {
    color: #ff6b6b !important;
    margin-top: auto;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
  }

  #sidebar-logout:hover {
    background: rgba(255, 107, 107, 0.2);
  }


        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --sidebar-width: 250px;
            --header-height: 70px;
            --footer-height: 60px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Header Styles */
        .header {
            height: var(--header-height);
            background-color: #fff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .header-title {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
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

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--primary-color);
            position: fixed;
            top: var(--header-height);
            left: 0;
            z-index: 900;
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        .tia-animation {
            width: 100%;
            padding: 20px;
            text-align: center;
            position: absolute;
            bottom: 0;
        }

        .tia-animation img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        /* Content Area */
        .content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height) - var(--footer-height));
        }

        /* Cards Styling */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border: none;
        }

        .card-body {
            padding: 20px;
        }

        /* Summary Cards */
        .summary-card {
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .summary-card .icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
            color: var(--secondary-color);
        }

        /* Scrollable Container Styles */
        .scrollable-container, .scrollable-container-year {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 5px;
        }

        /* Scrollbar styling */
        .scrollable-container::-webkit-scrollbar,
        .scrollable-container-year::-webkit-scrollbar,
        .latest-doc-list::-webkit-scrollbar {
            width: 6px;
        }

        .scrollable-container::-webkit-scrollbar-track,
        .scrollable-container-year::-webkit-scrollbar-track,
        .latest-doc-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scrollable-container::-webkit-scrollbar-thumb,
        .scrollable-container-year::-webkit-scrollbar-thumb,
        .latest-doc-list::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 10px;
        }

        .scrollable-container::-webkit-scrollbar-thumb:hover,
        .scrollable-container-year::-webkit-scrollbar-thumb:hover,
        .latest-doc-list::-webkit-scrollbar-thumb:hover {
            background: #2980b9;
        }

        /* Latest documents list */
        .latest-doc-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .list-group-item {
            border-radius: 8px !important;
            margin-bottom: 8px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .list-group-item:hover {
            background-color: rgba(52, 152, 219, 0.05);
            border-color: rgba(52, 152, 219, 0.1);
        }

        /* Table styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--dark-text);
            font-weight: 600;
            padding: 15px;
            border: none;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: rgba(0, 0, 0, 0.05);
        }

        /* Buttons styling */
        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-sm {
            border-radius: 6px;
            padding: 5px 10px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(41, 128, 185, 0.2);
        }

        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-success:hover {
            background-color: #219a52;
            border-color: #219a52;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.2);
        }

        /* Badge styling */
        .badge {
            padding: 6px 10px;
            font-weight: 500;
            border-radius: 50px;
        }

        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 6px;
            margin: 0 3px;
            color: var(--secondary-color);
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .pagination .page-link:hover {
            background-color: rgba(52, 152, 219, 0.1);
            border-color: rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }

        /* Footer styling */
        .footer {
            background-color: white;
            height: var(--footer-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.05);
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 900;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }

            .content, .footer {
                margin-left: 0;
            }

            .sidebar.active {
                left: 0;
            }

            .content.active, .footer.active {
                margin-left: var(--sidebar-width);
            }

            .toggle-sidebar {
                display: block !important;
            }
        }

        /* Toggle sidebar button */
        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <button class="toggle-sidebar" id="toggle-sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <img src="https://content.weplus.id/production/assets/web-pp/insurance/images/TUGU.png" alt="Tugu Insurance Logo">
            <h4 class="header-title">Sistem Tata Kelola</h4>
        </div>
        <div class="header-right">
  <button class="btn btn-outline-secondary me-2">
    <i class="fas fa-bell"></i>
  </button>
  <div class="profile">
    <img src="https://ui-avatars.com/api/?name=User&background=3498db&color=fff" alt="User Profile">
    <span>Admin User</span>
  </div>
  <button id="logout-button" class="btn btn-outline-danger ms-3" onclick="logoutFromSystem()" title="Logout">
    <i class="fas fa-sign-out-alt"></i> Logout
  </button>
</div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            {{-- <li>
  <a href="javascript:void(0)" onclick="logoutFromSystem()">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
  </a>
</li> --}}
            {{-- <li>
                <a href="#">
                    <i class="fas fa-file-alt"></i>
                    <span>Dokumen STK</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-upload"></i>
                    <span>Upload Dokumen</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-question-circle"></i>
                    <span>Bantuan</span>
                </a>
            </li> --}}
        </ul>
        <div class="tia-animation">
            <img src="https://tugu.com/_nuxt/img/FA_Call%20TIA-01.e979f4a.png" alt="Tugu Insurance Animation">
            <p class="text-light">TIA at your service</p>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Dashboard Dokumen STK</h2>
            <div>
                <!-- <button class="btn btn-outline-secondary me-2">
                    <i class="fas fa-calendar"></i> Filter by Date
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Dokumen
                </button> -->
            </div>
        </div>

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





        <!-- Summary Cards -->
        <div class="row mb-4" id="summary-cards">
            <div class="col-md-4 mb-3">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-file-alt"></i></div>
                        <h5 class="card-title">Total Dokumen</h5>
                        <p class="summary-number" id="total-docs">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-tags"></i></div>
                        <h5 class="card-title">Jenis Dokumen</h5>
                        <p class="summary-number" id="doc-types">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                        <h5 class="card-title">Tahun Terbaru</h5>
                        <p class="summary-number" id="latest-year">-</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Documents By Type -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Dokumen Berdasarkan Jenis</h5>
                    </div>
                    <div class="card-body">
                        <div id="doc-type-chart">
                            <p class="text-center text-muted" id="no-type-data">Tidak ada data jenis dokumen.</p>
                            <div class="scrollable-container">
                                <ul class="list-group" id="doc-type-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents By Year -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Dokumen Berdasarkan Tahun</h5>
                    </div>
                    <div class="card-body">
                        <div id="doc-year-chart">
                            <p class="text-center text-muted" id="no-year-data">Tidak ada data tahun dokumen.</p>
                            <div class="scrollable-container-year">
                                <ul class="list-group" id="doc-year-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Documents -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dokumen Terbaru</h5>
                <a href="#" class="btn btn-sm btn-outline-light text-white">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div id="latest-docs" class="latest-doc-list">
                    <p class="text-center text-muted" id="no-latest-docs">Tidak ada dokumen terbaru.</p>
                    <div class="list-group" id="latest-docs-list"></div>
                </div>
            </div>
        </div>

        <!-- All Documents -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Semua Dokumen STK</h5>
                <div>
                    <button class="btn btn-sm btn-outline-light text-white me-2" id="refresh-btn">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn btn-sm btn-outline-light text-white" id="export-btn">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-input" placeholder="Cari dokumen...">
                            <button class="btn btn-primary" id="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <select class="form-select" id="filter-type">
                            <option value="">Semua Jenis</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filter-year">
                            <option value="">Semua Tahun</option>
                        </select>
                    </div>
                </div>

                <!-- Documents Table -->
                <div class="table-responsive">
                    <table class="table" id="documents-table">
                        <thead>
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th style="width: 20%">Nomor Dokumen</th>
                                <th style="width: 20%">Jenis STK</th>
                                <th style="width: 30%">Deskripsi</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="documents-list">
                            <tr id="no-documents-row">
                                <td colspan="6" class="text-center">Tidak ada dokumen yang ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="d-flex align-items-center">
                        <label class="me-2">Tampilkan</label>
                        <select id="items-per-page" class="form-select form-select-sm" style="width: auto;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2">dokumen per halaman</span>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0" id="pagination">
                            <li class="page-item disabled" id="prev-page">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span> Previous
                                </a>
                            </li>
                            <!-- Page numbers will be inserted here by JavaScript -->
                            <li class="page-item disabled" id="next-page">
                                <a class="page-link" href="#" aria-label="Next">
                                    Next <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div>
            <span>&copy; 2025 PT Asuransi Tugu Pratama Indonesia Tbk. All rights reserved.</span>
        </div>
        <div>
            <a href="https://tugu.com/tata-kelola-perusahaan/kebijakan-privasi" class="me-3">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

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


        // Toggle sidebar functionality for responsive design
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.content').classList.toggle('active');
            document.querySelector('.footer').classList.toggle('active');
        });

        // Existing JavaScript for the dashboard functionality
        document.addEventListener('DOMContentLoaded', function() {
            fetchSTKSummary();

            // Event listeners
            document.getElementById('refresh-btn').addEventListener('click', fetchSTKSummary);
            document.getElementById('search-btn').addEventListener('click', function() {
                filterDocuments();
            });
            document.getElementById('search-input').addEventListener('keyup', function(e) {
                if(e.key === 'Enter') {
                    filterDocuments();
                }
            });
            document.getElementById('filter-type').addEventListener('change', filterDocuments);
            document.getElementById('filter-year').addEventListener('change', filterDocuments);
            document.getElementById('export-btn').addEventListener('click', exportDocuments);

            // Items per page dropdown
            const itemsPerPage = document.getElementById('items-per-page');
            if (itemsPerPage) {
                itemsPerPage.addEventListener('change', function() {
                    const perPage = parseInt(this.value);
                    fetchSTKSummary(1, perPage);
                });
            }
        });

        // Global variables
        let allDocuments = [];
        let documentTypes = {};
        let documentYears = {};

        function fetchSTKSummary(page = 1, perPage = 10) {
            // Show loading indicator
            document.getElementById('documents-list').innerHTML = '<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

            // Save pagination state
            sessionStorage.setItem('currentPage', page);
            sessionStorage.setItem('perPage', perPage);

            fetch(`/stk/summary?page=${page}&perPage=${perPage}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Display data
                        displaySummary(data.summary);
                        displayDocuments(data.documents || []);

                        // Setup pagination
                        if (data.pagination) {
                            setupPagination(
                                data.pagination.current_page,
                                data.pagination.total_pages,
                                data.pagination.per_page
                            );

                            // Update items per page dropdown
                            document.getElementById('items-per-page').value = data.pagination.per_page;
                        }

                        populateFilters(data.summary);

                        // Save all documents for filtering
                        allDocuments = data.documents || [];
                    } else {
                        console.error('Error fetching STK summary:', data.message);

                        // Show error toast instead of alert
                        showToast('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    showToast('Network Error', 'Could not fetch STK data', 'error');
                });
        }

        // Display summary data with improved visualization
        function displaySummary(summary) {
            // Update summary cards with animation
            animateCounter('total-docs', 0, summary.total_documents || 0);
            animateCounter('doc-types', 0, Object.keys(summary.documents_by_type || {}).length);

            // Get latest year
            const years = Object.keys(summary.documents_by_year || {});
            document.getElementById('latest-year').textContent = years.length > 0 ? Math.max(...years) : '-';

            // Document type mapping for labels
            const documentTypeLabels = {
                'Pedoman': 'A - Pedoman',
                'Tata Kerja Organisasi': 'B - Tata Kerja Organisasi',
                'Tata Kerja Individu': 'C - Tata Kerja Individu',
                'BPCP': 'D - BPCP',
                'Sistem & Prosedur': 'SOP - Sistem & Prosedur'
            };

            // Display documents by type
            const typeList = document.getElementById('doc-type-list');
            typeList.innerHTML = '';

            if (!summary.documents_by_type || Object.keys(summary.documents_by_type).length === 0) {
                document.getElementById('no-type-data').style.display = 'block';
                document.querySelector('.scrollable-container').style.display = 'none';
            } else {
                document.getElementById('no-type-data').style.display = 'none';
                document.querySelector('.scrollable-container').style.display = 'block';

                // Sort by count (descending)
                const typeEntries = Object.entries(summary.documents_by_type)
                    .sort((a, b) => b[1] - a[1]);

                // Create fragment for better performance
                const fragment = document.createDocumentFragment();

                typeEntries.forEach(([type, count]) => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

                    // Use the improved label if available
                    const displayLabel = documentTypeLabels[type] || type;

                    listItem.innerHTML = `
                        <span class="text-truncate" title="${displayLabel}" style="max-width: 70%;">${displayLabel}</span>
                        <span class="badge bg-primary rounded-pill">${count}</span>
                    `;
                    fragment.appendChild(listItem);
                });

                typeList.appendChild(fragment);
            }

            // Display documents by year
            const yearList = document.getElementById('doc-year-list');
            yearList.innerHTML = '';

            if (!summary.documents_by_year || Object.keys(summary.documents_by_year).length === 0) {
                document.getElementById('no-year-data').style.display = 'block';
                document.querySelector('.scrollable-container-year').style.display = 'none';
            } else {
                document.getElementById('no-year-data').style.display = 'none';
                document.querySelector('.scrollable-container-year').style.display = 'block';

                // Create document fragment for years
                const yearFragment = document.createDocumentFragment();

                Object.entries(summary.documents_by_year)
                    .sort((a, b) => b[0] - a[0]) // Sort by year (descending)
                    .forEach(([year, count]) => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        listItem.innerHTML = `
                            <span>${year}</span>
                            <span class="badge bg-success rounded-pill">${count}</span>
                        `;
                        yearFragment.appendChild(listItem);
                    });

                yearList.appendChild(yearFragment);
            }

            // Display latest documents
            const latestDocsList = document.getElementById('latest-docs-list');
            latestDocsList.innerHTML = '';

            if (!summary.latest_documents || summary.latest_documents.length === 0) {
                document.getElementById('no-latest-docs').style.display = 'block';
            } else {
                document.getElementById('no-latest-docs').style.display = 'none';

                summary.latest_documents.forEach(doc => {
                    const docItem = document.createElement('a');
                    docItem.href = '#';
                    docItem.className = 'list-group-item list-group-item-action';
                    docItem.setAttribute('data-id', doc.id);
                    docItem.setAttribute('data-version', doc.version);

                    // Format date if available
                    let createdDate = '-';
                    if (doc.created_date) {
                        try {
                            createdDate = new Date(doc.created_date).toLocaleDateString('id-ID');
                        } catch(e) {
                            console.error("Error formatting date:", e);
                        }
                    }

                    // Determine document type label
                    let typeLabel = doc.jenis_stk || 'Tidak Dikategorikan';
                    if (typeLabel === 'Pedoman') typeLabel = 'A - Pedoman';
                    else if (typeLabel === 'Tata Kerja Organisasi') typeLabel = 'B - Tata Kerja Organisasi';
                    else if (typeLabel === 'Tata Kerja Individu') typeLabel = 'C - Tata Kerja Individu';
                    else if (typeLabel === 'BPCP') typeLabel = 'D - BPCP';
                    else if (typeLabel === 'Sistem & Prosedur') typeLabel = 'SOP - Sistem & Prosedur';

                    docItem.innerHTML = `
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${doc.document_number || 'No. ' + doc.id}</h6>
                            <small class="text-muted">${createdDate}</small>
                        </div>
                        <p class="mb-1">${doc.title || 'Tanpa Judul'}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${typeLabel}</small>
                            <div>
                                <button class="btn btn-sm btn-outline-primary view-doc-btn" data-id="${doc.id}" data-version="${doc.version || ''}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success download-doc-btn" data-id="${doc.id}" data-version="${doc.version || ''}">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    `;

                    latestDocsList.appendChild(docItem);
                });

                // Add event listeners to the buttons in the latest docs list
                document.querySelectorAll('.view-doc-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        viewDocument(this.getAttribute('data-id'), this.getAttribute('data-version'));
                    });
                });

                document.querySelectorAll('.download-doc-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        downloadDocument(this.getAttribute('data-id'), this.getAttribute('data-version'));
                    });
                });

                // Add click event to the list item itself
                document.querySelectorAll('#latest-docs-list a.list-group-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        viewDocument(this.getAttribute('data-id'), this.getAttribute('data-version'));
                    });
                });
            }
        }

        // Display documents in the table
        function displayDocuments(documents) {
            const documentsList = document.getElementById('documents-list');
            documentsList.innerHTML = '';

            if (!documents || documents.length === 0) {
                const noDocRow = document.createElement('tr');
                noDocRow.id = 'no-documents-row';
                noDocRow.innerHTML = '<td colspan="6" class="text-center py-4"><div class="text-muted"><i class="fas fa-file-alt fa-3x mb-3"></i><p>Tidak ada dokumen yang ditemukan.</p></div></td>';
                documentsList.appendChild(noDocRow);
                return;
            }

            documents.forEach((doc, index) => {
                const row = document.createElement('tr');

                // Format date if available
                let createdDate = '-';
                if (doc.created_date) {
                    try {
                        createdDate = new Date(doc.created_date).toLocaleDateString('id-ID');
                    } catch(e) {
                        console.error("Error formatting date:", e);
                    }
                }

                // Get document year from tahun field or extract from document_number
                const docYear = doc.tahun || (doc.document_number && doc.document_number.match(/\/(\d{4})$/) ?
                                           doc.document_number.match(/\/(\d{4})$/)[1] : '');

                // Determine document type with color coding
                let typeLabel = doc.jenis_stk || 'Tidak Dikategorikan';
                let typeBadgeClass = 'bg-secondary';

                if (typeLabel === 'Pedoman') {
                    typeLabel = 'A - Pedoman';
                    typeBadgeClass = 'bg-primary';
                } else if (typeLabel === 'Tata Kerja Organisasi') {
                    typeLabel = 'B - Tata Kerja Organisasi';
                    typeBadgeClass = 'bg-success';
                } else if (typeLabel === 'Tata Kerja Individu') {
                    typeLabel = 'C - Tata Kerja Individu';
                    typeBadgeClass = 'bg-info';
                } else if (typeLabel === 'BPCP') {
                    typeLabel = 'D - BPCP';
                    typeBadgeClass = 'bg-warning';
                } else if (typeLabel === 'Sistem & Prosedur') {
                    typeLabel = 'SOP - Sistem & Prosedur';
                    typeBadgeClass = 'bg-warning';
                }

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><strong>${doc.document_number || 'No. ' + doc.id}</strong></td>
                    <td><span class="badge ${typeBadgeClass}">${typeLabel}</span></td>
                    <td>${doc.title || 'Tanpa Judul'}</td>
                    <td>${createdDate}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-btn me-1" data-id="${doc.id}" data-version="${doc.version || ''}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-success download-btn" data-id="${doc.id}" data-version="${doc.version || ''}">
                            <i class="fas fa-download"></i>
                        </button>
                    </td>
                `;

                documentsList.appendChild(row);
            });

            // Add event listeners to buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewDocument(this.getAttribute('data-id'), this.getAttribute('data-version'));
                });
            });

            document.querySelectorAll('.download-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    downloadDocument(this.getAttribute('data-id'), this.getAttribute('data-version'));
                });
            });
        }

        // Populate filter dropdowns
        function populateFilters(summary) {
            // Type filter
            const typeFilter = document.getElementById('filter-type');
            typeFilter.innerHTML = '<option value="">Semua Jenis</option>';

            // Define document type options with codes
            const documentTypes = [
                { code: 'A', name: 'Pedoman' },
                { code: 'B', name: 'Tata Kerja Organisasi' },
                { code: 'C', name: 'Tata Kerja Individu' },
                { code: 'D', name: 'BPCP' },
                { code: 'SOP', name: 'Sistem & Prosedur' }
            ];

            // Add predefined document types first
            documentTypes.forEach(type => {
                // Only add if this type actually exists in the data
                if (summary.documents_by_type && summary.documents_by_type[type.name]) {
                    const option = document.createElement('option');
                    option.value = type.name;
                    option.textContent = `${type.code} - ${type.name}`;
                    typeFilter.appendChild(option);
                }
            });

            // Then add any other types that might exist in the data
            if (summary.documents_by_type) {
                Object.keys(summary.documents_by_type)
                    .filter(type => !documentTypes.some(dt => dt.name === type) && type !== 'Tidak Dikategorikan')
                    .sort()
                    .forEach(type => {
                        const option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        typeFilter.appendChild(option);
                    });
            }

            // Add "Not Categorized" as the last option if it exists
            if (summary.documents_by_type && summary.documents_by_type['Tidak Dikategorikan']) {
                const option = document.createElement('option');
                option.value = 'Tidak Dikategorikan';
                option.textContent = 'Tidak Dikategorikan';
                typeFilter.appendChild(option);
            }

            // Year filter
            const yearFilter = document.getElementById('filter-year');
            yearFilter.innerHTML = '<option value="">Semua Tahun</option>';

            if (summary.documents_by_year) {
                Object.keys(summary.documents_by_year)
                    .sort((a, b) => b - a) // Sort years descending
                    .forEach(year => {
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        yearFilter.appendChild(option);
                    });
            }
        }

        // Filter documents with improved handling
        function filterDocuments() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const typeFilter = document.getElementById('filter-type').value;
            const yearFilter = document.getElementById('filter-year').value;

            let filteredDocs = [...allDocuments];

            // Apply search term
            if (searchTerm) {
                filteredDocs = filteredDocs.filter(doc =>
                    (doc.title && doc.title.toLowerCase().includes(searchTerm)) ||
                    (doc.document_number && doc.document_number.toLowerCase().includes(searchTerm))
                );
            }

            // Apply type filter
            if (typeFilter) {
                filteredDocs = filteredDocs.filter(doc =>
                    doc.jenis_stk === typeFilter || doc.jenis_kode === typeFilter
                );
            }

            // Apply year filter
            if (yearFilter) {
                filteredDocs = filteredDocs.filter(doc => {
                    // Check tahun field first
                    if (doc.tahun && doc.tahun === yearFilter) {
                        return true;
                    }

                    // Then check document_number for year
                    if (doc.document_number) {
                        const yearMatch = doc.document_number.match(/\/(\d{4})$/);
                        if (yearMatch && yearMatch[1] === yearFilter) {
                            return true;
                        }
                    }

                    // Last resort: check created_date
                    if (doc.created_date && doc.created_date.startsWith(yearFilter)) {
                        return true;
                    }

                    return false;
                });
            }

            // Display filtered documents
            displayDocuments(filteredDocs);

            // Show total count in a toast
            showToast('Filter Applied', `Found ${filteredDocs.length} documents matching your criteria`, 'success');
        }

        // Pagination setup
        function setupPagination(currentPage, totalPages, perPage) {
            const paginationElement = document.getElementById('pagination');
            const prevPageElement = document.getElementById('prev-page');
            const nextPageElement = document.getElementById('next-page');

            // Clear existing page numbers
            while (paginationElement.children.length > 2) {
                paginationElement.removeChild(paginationElement.children[1]);
            }

            // Update Previous button state
            if (currentPage <= 1) {
                prevPageElement.classList.add('disabled');
            } else {
                prevPageElement.classList.remove('disabled');
            }

            // Update Next button state
            if (currentPage >= totalPages) {
                nextPageElement.classList.add('disabled');
            } else {
                nextPageElement.classList.remove('disabled');
            }

            // Add page number buttons
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            // Add first page if needed
            if (startPage > 1) {
                const firstPageItem = createPageItem(1, currentPage);
                paginationElement.insertBefore(firstPageItem, nextPageElement);

                if (startPage > 2) {
                    const ellipsisItem = document.createElement('li');
                    ellipsisItem.className = 'page-item disabled';
                    ellipsisItem.innerHTML = '<span class="page-link">...</span>';
                    paginationElement.insertBefore(ellipsisItem, nextPageElement);
                }
            }

            // Add middle pages
            for (let i = startPage; i <= endPage; i++) {
                const pageItem = createPageItem(i, currentPage);
                paginationElement.insertBefore(pageItem, nextPageElement);
            }

            // Add last page if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsisItem = document.createElement('li');
                    ellipsisItem.className = 'page-item disabled';
                    ellipsisItem.innerHTML = '<span class="page-link">...</span>';
                    paginationElement.insertBefore(ellipsisItem, nextPageElement);
                }

                const lastPageItem = createPageItem(totalPages, currentPage);
                paginationElement.insertBefore(lastPageItem, nextPageElement);
            }

            // Add click handlers for prev/next buttons
            prevPageElement.onclick = function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    fetchSTKSummary(currentPage - 1, perPage);
                }
            };

            nextPageElement.onclick = function(e) {
                e.preventDefault();
                if (currentPage < totalPages) {
                    fetchSTKSummary(currentPage + 1, perPage);
                }
            };
        }

        // Helper function to create a page item
        function createPageItem(pageNumber, currentPage) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${pageNumber === currentPage ? 'active' : ''}`;

            const pageLink = document.createElement('a');
            pageLink.className = 'page-link';
            pageLink.href = '#';
            pageLink.textContent = pageNumber;

            pageLink.onclick = function(e) {
                e.preventDefault();
                fetchSTKSummary(pageNumber, parseInt(document.getElementById('items-per-page').value));
            };

            pageItem.appendChild(pageLink);
            return pageItem;
        }

        // View document function with improved UI
        function viewDocument(id, version) {
            const url = `/stk/preview/${id}${version ? '/' + version : ''}`;

            // Show loading modal
            showLoadingModal('Mengambil dokumen, harap tunggu...');

            // Open in new tab
            const newTab = window.open(url, '_blank');

            // Close loading modal after a short delay
            setTimeout(() => {
                hideLoadingModal();
            }, 1000);
        }

        /**
 * Fungsi download dokumen dengan UI yang lebih baik
 * @param {string|number} id - ID dokumen
 * @param {string|number} version - Versi dokumen (opsional)
 */
function downloadDocument(id, version) {
    // Buat URL download dengan parameter yang benar
    const url = `/stk/download/${id}${version ? '/' + version : ''}?download=true`;

    // Tampilkan toast notification sebelum memulai download
    showToast('Dokumen STK', 'Dokumen sedang diunduh...', 'success');

    // Metode 1: Gunakan fetch API untuk memverifikasi URL sebelum download
    fetch(url, { method: 'HEAD' })
        .then(response => {
            if (response.ok) {
                // Jika URL valid, lakukan download dengan metode <a> element
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', ''); // Atribut download memberi sinyal ke browser
                link.setAttribute('target', '_blank');
                document.body.appendChild(link);
                link.click();

                // Bersihkan elemen setelah download dimulai
                setTimeout(() => {
                    document.body.removeChild(link);
                }, 100);
            } else {
                // Jika ada error, tampilkan pesan
                showToast('Dokumen STK', 'Gagal mengunduh dokumen. Silakan coba lagi.', 'error');
                console.error('Download error:', response.status, response.statusText);
            }
        })
        .catch(error => {
            // Jika terjadi exception, tampilkan pesan error
            showToast('Dokumen STK', 'Gagal mengunduh dokumen. Silakan coba lagi.', 'error');
            console.error('Download error:', error);
        });

    // Tambahkan parameter tracking (opsional)
    logUserActivity('download_document', { document_id: id, version: version });
}

/**
 * Fungsi alternatif download yang lebih sederhana
 * Gunakan jika metode di atas tidak berfungsi dengan baik
 */
function simpleDownloadDocument(id, version) {
    // Buat URL dengan parameter download=true
    const url = `/stk/download/${id}${version ? '/' + version : ''}?download=true`;

    // Tampilkan notifikasi
    showToast('Dokumen STK', 'Dokumen sedang diunduh...', 'success');

    // Buka URL di tab baru untuk memicu download
    window.open(url, '_blank');
}

/**
 * Helper function untuk mencatat aktivitas pengguna (opsional)
 */
function logUserActivity(action, data) {
    // Implementasi pencatatan aktivitas pengguna
    // Bisa digunakan untuk analytics atau logging
    console.log('User activity:', action, data);

    // Dalam implementasi nyata, kirim data ke server
    // fetch('/api/log-activity', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify({ action, data, timestamp: new Date().toISOString() })
    // });
}

        // Export documents to CSV
        function exportDocuments() {
            // Get visible documents in the table
            const table = document.getElementById('documents-table');
            const rows = table.querySelectorAll('tbody tr:not(#no-documents-row)');

            if (rows.length === 0) {
                showToast('Export Error', 'Tidak ada data untuk diekspor', 'error');
                return;
            }

            // Show loading toast
            showToast('Export Data', 'Menyiapkan data untuk diunduh...', 'info');

            let csv = 'No,Nomor Dokumen,Jenis STK,Deskripsi,Tanggal Pembuatan\n';

            rows.forEach(row => {
                const columns = row.querySelectorAll('td');

                // Handle the badge in the type column (third column)
                const typeCell = columns[2];
                const typeBadge = typeCell.querySelector('.badge');
                const typeText = typeBadge ? typeBadge.textContent : typeCell.textContent;

                const rowData = [
                    columns[0].textContent,
                    columns[1].textContent.trim(),
                    '"' + typeText.trim() + '"',
                    '"' + columns[3].textContent.trim().replace(/"/g, '""') + '"', // Escape quotes in title
                    columns[4].textContent.trim()
                ];

                csv += rowData.join(',') + '\n';
            });

            // Create download link
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');

            link.setAttribute('href', url);
            link.setAttribute('download', `stk_documents_${timestamp}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);

            // Trigger download
            link.click();

            // Clean up
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            // Show success toast
            showToast('Export Berhasil', 'Data berhasil diekspor ke CSV', 'success');
        }

        // Helper function to animate counter
        function animateCounter(elementId, start, end, duration = 1000) {
            const element = document.getElementById(elementId);
            if (!element) return;

            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value;

                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    element.textContent = end;
                }
            };

            window.requestAnimationFrame(step);
        }

        // Show loading modal
        function showLoadingModal(message = 'Loading...') {
            // Remove any existing modal
            const existingModal = document.getElementById('loadingModal');
            if (existingModal) {
                document.body.removeChild(existingModal);
            }

            // Create new modal
            const loadingModal = document.createElement('div');
            loadingModal.className = 'modal fade';
            loadingModal.id = 'loadingModal';
            loadingModal.setAttribute('data-bs-backdrop', 'static');
            loadingModal.setAttribute('data-bs-keyboard', 'false');
            loadingModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0">${message}</p>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(loadingModal);

            const modal = new bootstrap.Modal(loadingModal);
            modal.show();

            // Store the modal instance for later use
            window.currentLoadingModal = modal;
        }

        // Hide loading modal
        function hideLoadingModal() {
            if (window.currentLoadingModal) {
                window.currentLoadingModal.hide();
                setTimeout(() => {
                    const modalElement = document.getElementById('loadingModal');
                    if (modalElement) {
                        document.body.removeChild(modalElement);
                    }
                    window.currentLoadingModal = null;
                }, 300);
            }
        }

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






// Session management with M-Files integration
(function() {
    // Constants
    const SESSION_TIMEOUT = 10 * 60 * 1000; // 10 minutes in milliseconds
    const WARNING_TIMEOUT = 60 * 1000; // Show warning 60 seconds before timeout
    const COUNTDOWN_INTERVAL = 1000; // Update countdown every second
    const ACTIVITY_EVENTS = ['mousedown', 'keydown', 'scroll', 'touchstart']; // Events to detect user activity
    const M_FILES_LOGIN_URL = '/mfiles/login'; // Updated to use login endpoint

    // Session variables
    let sessionTimer;
    let warningTimer;
    let countdownTimer;
    let countdownValue = 60;
    let isLoggedIn = false;
    let userName = '';
    let authToken = '';

    // DOM elements
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    const sessionTimeoutModal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'));
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const loginLoading = document.getElementById('login-loading');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const countdownElement = document.getElementById('countdown');
    const logoutNowBtn = document.getElementById('logout-now-btn');
    const stayLoggedInBtn = document.getElementById('stay-logged-in-btn');
    const userProfileElement = document.querySelector('.profile span');

    // Check if user is already logged in
    function checkExistingSession() {
      const savedSession = localStorage.getItem('mfiles_session');
      if (savedSession) {
        try {
          const sessionData = JSON.parse(savedSession);
          // Check if session is still valid
          if (sessionData.expiry > Date.now()) {
            // Use the saved auth token to verify with server
            verifyMFilesToken(sessionData.authToken)
              .then(valid => {
                if (valid) {
                  isLoggedIn = true;
                  userName = sessionData.userName;
                  authToken = sessionData.authToken;

                  userProfileElement.textContent = userName;
                  startSessionTimer();

                  // Make auth token available for API calls
                  sessionStorage.setItem('mfiles_auth_token', authToken);
                } else {
                  clearSession();
                  showLogin();
                }
              })
              .catch(() => {
                clearSession();
                showLogin();
              });
          } else {
            // Session expired
            clearSession();
            showLogin();
          }
        } catch (e) {
          clearSession();
          showLogin();
        }
      } else {
        showLogin();
      }
    }

    // Verify M-Files token is still valid
    async function verifyMFilesToken(token) {
      try {
        // Simple verification - just check the connection
        const response = await fetch('/stk/debug', {
          headers: {
            'X-Authentication': token,
            'Accept': 'application/json'
          }
        });
        const data = await response.json();
        return data.success === true;
      } catch (error) {
        console.error('Token verification failed:', error);
        return false;
      }
    }

    // Show login modal
    function showLogin() {
      loginForm.reset();
      loginError.classList.add('d-none');
      loginLoading.classList.add('d-none');
      loginModal.show();

      // Try to prefill username if remembered
      const rememberedUser = localStorage.getItem('mfiles_username');
      if (rememberedUser) {
        document.getElementById('username').value = rememberedUser;
        document.getElementById('remember-me').checked = true;
      }
    }

    // Handle login form submission
    function handleLogin(e) {
      e.preventDefault();

      // Show loading indicator
      loginForm.classList.add('d-none');
      loginLoading.classList.remove('d-none');
      loginError.classList.add('d-none');

      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const rememberMe = document.getElementById('remember-me').checked;

      // Authenticate with M-Files using fixed vault GUID
      authenticateWithMFiles(username, password)
        .then(result => {
          if (result.success && result.token) {
            isLoggedIn = true;
            userName = username;
            authToken = result.token;

            userProfileElement.textContent = userName;

            // Save auth token to sessionStorage for API calls
            sessionStorage.setItem('mfiles_auth_token', authToken);

            // Save session if "remember me" is checked
            if (rememberMe) {
              saveSession(username, authToken);
              localStorage.setItem('mfiles_username', username);
            } else {
              localStorage.removeItem('mfiles_username');
            }

            loginModal.hide();
            startSessionTimer();
            showToast('Login Successful', `Welcome, ${username}!`, 'success');
          } else {
            showLoginError(result.message || 'Authentication failed');
          }
        })
        .catch(error => {
          console.error('Login error:', error);
          showLoginError('Could not connect to M-Files authentication service');
        });
    }

    // Authenticate with M-Files
    async function authenticateWithMFiles(username, password) {
      try {
        // Always use the fixed vault GUID: 5D8FF911-CE06-4B27-8311-B0AD764921C0
        const response = await fetch(M_FILES_LOGIN_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
          },
          body: JSON.stringify({
            username: username,
            password: password,
            vault_guid: '5D8FF911-CE06-4B27-8311-B0AD764921C0'
          })
        });

        const data = await response.json();
        return {
          success: !!data.token,
          token: data.token,
          message: data.message
        };
      } catch (error) {
        console.error('Authentication error:', error);
        return {
          success: false,
          message: 'Network error during authentication'
        };
      }
    }

    // Show login error message
    function showLoginError(message) {
      loginForm.classList.remove('d-none');
      loginLoading.classList.add('d-none');

      loginError.textContent = message;
      loginError.classList.remove('d-none');
    }

    // Save session to localStorage
    function saveSession(username, token) {
      const sessionData = {
        userName: username,
        authToken: token,
        expiry: Date.now() + SESSION_TIMEOUT
      };
      localStorage.setItem('mfiles_session', JSON.stringify(sessionData));
    }

    // Clear session data
    function clearSession() {
      localStorage.removeItem('mfiles_session');
      sessionStorage.removeItem('mfiles_auth_token');
      localStorage.removeItem('mfiles_username');
      isLoggedIn = false;
      userName = '';
      authToken = '';
    }

    // Start session timer
    function startSessionTimer() {
      // Clear any existing timers
      clearTimeout(sessionTimer);
      clearTimeout(warningTimer);

      // Set a timer for session timeout warning
      warningTimer = setTimeout(() => {
        showTimeoutWarning();
      }, SESSION_TIMEOUT - WARNING_TIMEOUT);

      // Set a timer for session timeout
      sessionTimer = setTimeout(() => {
        logout();
      }, SESSION_TIMEOUT);
    }

    // Reset session timer on user activity
    function resetSessionTimer() {
      if (isLoggedIn) {
        startSessionTimer();
      }
    }

    // Show session timeout warning
    function showTimeoutWarning() {
      // Reset countdown
      countdownValue = 60;
      countdownElement.textContent = countdownValue;

      // Show warning modal
      sessionTimeoutModal.show();

      // Start countdown
      clearInterval(countdownTimer);
      countdownTimer = setInterval(() => {
        countdownValue--;
        countdownElement.textContent = countdownValue;

        if (countdownValue <= 0) {
          clearInterval(countdownTimer);
          logout();
        }
      }, COUNTDOWN_INTERVAL);
    }

    // Log user out - IMPROVED FUNCTION
    function logout() {
      console.log("Logout function called");

      // Clear all timers
      clearTimeout(sessionTimer);
      clearTimeout(warningTimer);
      clearInterval(countdownTimer);

      // Clear all session data
      clearSession();

      // Hide modals if open
      try {
        sessionTimeoutModal.hide();
      } catch (e) {
        console.log("Error hiding session modal:", e);
      }

      // Show logout message
      alert('Anda telah berhasil keluar dari sistem.');

      // Reload the page
      window.location.href = window.location.pathname;
    }

    // Toggle password visibility
    function togglePasswordVisibility() {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePasswordBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
      } else {
        passwordInput.type = 'password';
        togglePasswordBtn.innerHTML = '<i class="fas fa-eye"></i>';
      }
    }

    // Add HTTP interceptor to include auth token in all API calls
    function setupAuthInterceptor() {
      // Override fetch to include auth token
      const originalFetch = window.fetch;
      window.fetch = function(url, options = {}) {
        // Get auth token
        const token = sessionStorage.getItem('mfiles_auth_token');

        // Only add token for our API calls
        if (token && (url.includes('/stk/') || url.includes('/mfiles/') || url.includes('/REST/'))) {
          options.headers = options.headers || {};

          // Don't override if already set
          if (!options.headers['X-Authentication']) {
            options.headers['X-Authentication'] = token;
          }
        }

        return originalFetch(url, options);
      };
    }

    // Make the logout function available globally
    window.logoutUser = logout;

    // Event listeners
    window.addEventListener('DOMContentLoaded', () => {
      console.log("DOM Content Loaded - Setting up event listeners");

      // Setup login form
      if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
      }

      // Setup password toggle
      if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', togglePasswordVisibility);
      }

      // Setup session timeout modal buttons
      if (logoutNowBtn) {
        logoutNowBtn.addEventListener('click', logout);
      }

      if (stayLoggedInBtn) {
        stayLoggedInBtn.addEventListener('click', () => {
          sessionTimeoutModal.hide();
          resetSessionTimer();
        });
      }

      // Add a logout button to the header
      const headerRight = document.querySelector('.header-right');
      if (headerRight && !document.getElementById('logout-button')) {
        console.log("Adding logout button to header");
        const logoutBtn = document.createElement('button');
        logoutBtn.id = 'logout-button';
        logoutBtn.className = 'btn btn-outline-danger ms-3';
        logoutBtn.innerHTML = '<i class="fas fa-sign-out-alt"></i> Logout';
        logoutBtn.setAttribute('title', 'Logout');

        // Add direct onclick attribute
        logoutBtn.setAttribute('onclick', 'window.logoutUser()');

        // Also add event listener as a backup
        logoutBtn.addEventListener('click', logout);

        headerRight.appendChild(logoutBtn);
      }

      // Also check for existing logout button
      const existingLogoutBtn = document.getElementById('logout-button');
      if (existingLogoutBtn) {
        console.log("Found existing logout button, adding click handler");

        // Remove any existing click handlers just in case
        const newBtn = existingLogoutBtn.cloneNode(true);
        existingLogoutBtn.parentNode.replaceChild(newBtn, existingLogoutBtn);

        // Add direct onclick attribute
        newBtn.setAttribute('onclick', 'window.logoutUser()');

        // Also add event listener as backup
        newBtn.addEventListener('click', logout);
      }

      // Add activity event listeners
      ACTIVITY_EVENTS.forEach(event => {
        document.addEventListener(event, resetSessionTimer);
      });

      // Setup auth interceptor
      setupAuthInterceptor();

      // Check for existing session
      checkExistingSession();
    });

    // Also add a window load handler as a backup
    window.addEventListener('load', function() {
      console.log("Window loaded - Final setup");

      // Check again for the logout button
      const existingLogoutBtn = document.getElementById('logout-button');
      if (existingLogoutBtn && !existingLogoutBtn.hasLogoutHandler) {
        console.log("Adding click handler to logout button on window load");
        existingLogoutBtn.hasLogoutHandler = true;

        // Add direct onclick attribute
        existingLogoutBtn.setAttribute('onclick', 'window.logoutUser()');

        // Also add event listener
        existingLogoutBtn.addEventListener('click', function(e) {
          e.preventDefault();
          logout();
        });
      }
    });
})();

function addLogoutHandler() {
    const button = document.getElementById('logout-button');
    if (button) {
      button.onclick = function() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
          localStorage.clear();
          sessionStorage.clear();
          window.location.reload();
        }
      };
    }
  }

  // Try all possible ways to attach the handler
  document.addEventListener('DOMContentLoaded', addLogoutHandler);
  window.addEventListener('load', addLogoutHandler);
  setTimeout(addLogoutHandler, 1000); // Try after a delay
  addLogoutHandler(); // Try immediately
    </script>
</body>
</html>
