<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Tahun {{ $year }} - Sistem Tata Kelola Tugu Insurance</title>
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance">
                <span class="database-title">Sistem Tata Kelola</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/stk') }}">Beranda</a>
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
                        <a class="nav-link dropdown-toggle active" href="#" id="tahunDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tahun
                        </a>
                        <ul class="dropdown-menu tahun-dropdown" aria-labelledby="tahunDropdown" id="tahun-dropdown-menu">
                            <!-- Tahun akan diisi secara dinamis melalui JavaScript -->
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/stk/statistics') }}">Statistik</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="position-relative me-3">
                        <button class="btn btn-light btn-sm rounded-circle" type="button">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        </button>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name=User&background=3498db&color=fff" alt="User Profile" class="rounded-circle" width="32" height="32">
                            <span class="ms-2 d-none d-sm-inline">Admin User</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="logoutFromSystem()"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
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
                    <h1 class="page-title">{{ $title }}</h1>
                    <p class="page-description">{{ $description }}</p>
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
                            <h4>Jenis Dokumen</h4>
                            <div id="type-filters">
                                <!-- Jenis filter akan diisi secara dinamis -->
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
                                <div style="height: 24px; width: 70%; background-color: #e7ecf0;"></div>
                                <div style="height: 18px; width: 40%; margin-top: 8px; background-color: #e7ecf0;"></div>
                                <div style="height: 16px; width: 90%; margin-top: 8px; background-color: #e7ecf0;"></div>
                                <div style="height: 16px; width: 60%; margin-top: 8px; background-color: #e7ecf0;"></div>
                            </div>
                        </div>
                        <div class="document-card skeleton-loader">
                            <div class="document-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="document-content" style="width: 100%">
                                <div style="height: 24px; width: 80%; background-color: #e7ecf0;"></div>
                                <div style="height: 18px; width: 35%; margin-top: 8px; background-color: #e7ecf0;"></div>
                                <div style="height: 16px; width: 95%; margin-top: 8px; background-color: #e7ecf0;"></div>
                                <div style="height: 16px; width: 50%; margin-top: 8px; background-color: #e7ecf0;"></div>
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

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <h5>Tentang Sistem Tata Kelola</h5>
                    <p>Sistem Tata Kelola merupakan platform resmi Tugu Insurance yang menghimpun dan menyajikan dokumen Standar dan Tata Kelola dari berbagai unit kerja.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Beranda</a></li>
                        <li><a href="#" class="footer-link">Dokumen</a></li>
                        <li><a href="#" class="footer-link">Statistik</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Jenis Dokumen</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/stk/category/pedoman') }}" class="footer-link">Pedoman</a></li>
                        <li><a href="{{ url('/stk/category/tko') }}" class="footer-link">Tata Kerja Organisasi</a></li>
                        <li><a href="{{ url('/stk/category/tki') }}" class="footer-link">Tata Kerja Individu</a></li>
                        <li><a href="{{ url('/stk/category/bpcp') }}" class="footer-link">BPCP</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Kontak</h5>
                    <p>Email: stk@tugu.com<br>
                    Telepon: (021) 5211966</p>
                </div>
            </div>
            <div class="copyright">
                <p>© 2025 Tugu Insurance. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            const year = '{{ $year }}';
            let currentPage = 1;
            let totalPages = 1;
            let currentSort = 'newest';
            let currentSearch = '';
            let selectedTypes = [];
            let allDocuments = [];

            // Initialize the page
            fetchDocumentsByYear();

            // Fetch documents by year from the API
            function fetchDocumentsByYear() {
                showLoading();

                // Fetch data from API
                fetch(`/api/stk/documents-by-year?year=${year}&page=${currentPage}&sort=${currentSort}&search=${currentSearch}&types=${selectedTypes.join(',')}`)
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

                            // Setup type filters
                            if (data.types) {
                                setupTypeFilters(data.types);
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
                                <div class="document-actions">
                                    <button onclick="previewDocument(${doc.id}, ${doc.version})" title="Preview"><i class="fas fa-eye"></i></button>
                                    <button onclick="downloadDocument(${doc.id}, ${doc.version})" title="Download"><i class="fas fa-download"></i></button>
                                </div>
                            </div>
                        </div>
                    `;

                    // Make the entire card clickable for preview
                    documentCard.addEventListener('click', function(e) {
                        // Don't trigger click if clicking on action buttons
                        if (!e.target.closest('.document-actions')) {
                            previewDocument(doc.id, doc.version);
                        }
                    });

                    container.appendChild(documentCard);
                });

                hideNoResults();
            }

            // Setup type filters
            function setupTypeFilters(types) {
                const typeFiltersContainer = document.getElementById('type-filters');
                if (!typeFiltersContainer) return;

                typeFiltersContainer.innerHTML = '';

                Object.entries(types).forEach(([type, count]) => {
                    const checkboxDiv = document.createElement('div');
                    checkboxDiv.className = 'filter-checkbox';

                    const checkbox = document.createElement('input');
                   checkbox.type = 'checkbox';
                   checkbox.id = `type-${type}`;
                   checkbox.value = type;
                   checkbox.checked = selectedTypes.includes(type);
                   checkbox.addEventListener('change', function() {
                       if (this.checked) {
                           selectedTypes.push(type);
                       } else {
                           selectedTypes = selectedTypes.filter(t => t !== type);
                       }
                       currentPage = 1; // Reset to first page
                       fetchDocumentsByYear();
                   });

                   const label = document.createElement('label');
                   label.htmlFor = `type-${type}`;
                   label.textContent = `${type} (${count})`;

                   checkboxDiv.appendChild(checkbox);
                   checkboxDiv.appendChild(label);
                   typeFiltersContainer.appendChild(checkboxDiv);
               });
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
               fetchDocumentsByYear();

               // Scroll to top of results
               const documentsContainer = document.getElementById('documents-container');
               if (documentsContainer) {
                   documentsContainer.scrollIntoView({ behavior: 'smooth' });
               }
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
               let statsText = `Ditemukan ${totalFound} dokumen untuk tahun ${year}`;

               if (currentSearch) {
                   statsText += ` dengan pencarian "${currentSearch}"`;
               }

               if (selectedTypes.length > 0) {
                   statsText += ` bertipe ${selectedTypes.join(', ')}`;
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
                       fetchDocumentsByYear();
                   }, 500); // 500ms debounce
               });
           }

           // Sort functionality
           const sortButtons = document.querySelectorAll('[data-sort]');
           sortButtons.forEach(button => {
               button.addEventListener('click', function() {
                   currentSort = this.getAttribute('data-sort');
                   currentPage = 1; // Reset to first page
                   fetchDocumentsByYear();

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
                   selectedTypes = [];
                   currentPage = 1;

                   // Uncheck all checkboxes
                   const checkboxes = document.querySelectorAll('#type-filters input[type="checkbox"]');
                   checkboxes.forEach(checkbox => {
                       checkbox.checked = false;
                   });

                   fetchDocumentsByYear();
               });
           }
       });

       // Global functions for document actions
       function previewDocument(id, version) {
           window.location.href = `/stk/preview/${id}/${version}`;
       }

       function downloadDocument(id, version) {
           // Show feedback
           const toast = document.createElement('div');
           toast.className = 'position-fixed bottom-0 end-0 p-3';
           toast.style.zIndex = '1050';
           toast.innerHTML = `
               <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                   <div class="toast-header bg-primary text-white">
                       <strong class="me-auto">Unduh Dokumen</strong>
                       <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                   </div>
                   <div class="toast-body">
                       Dokumen sedang diunduh...
                   </div>
               </div>
           `;
           document.body.appendChild(toast);

           // Hide toast after 3 seconds
           setTimeout(() => {
               toast.remove();
           }, 3000);

           // Create temporary iframe for download
           const iframe = document.createElement('iframe');
           iframe.style.display = 'none';
           iframe.src = `/stk/download/${id}/${version}`;
           document.body.appendChild(iframe);

           // Clean up after 1 second
           setTimeout(() => {
               document.body.removeChild(iframe);
           }, 1000);
       }

       function logoutFromSystem() {
           // Display confirmation dialog
           if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
               // Make logout request
               fetch('/logout', {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
   </script>
</body>
</html>
