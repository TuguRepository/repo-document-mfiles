<!-- resources/views/layouts/header.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Tata Kelola Tugu Insurance')</title>
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

        /* Other commonly used styles */
        @yield('additional-styles')
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
                        <a class="nav-link {{ request()->is('stk/database') ? 'active' : '' }}" href="{{ url('/stk/database') }}">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('stk/category') ? 'active' : '' }}" href="#" id="jenisDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Jenis
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="jenisDropdown">
                            <li><a class="dropdown-item {{ request()->is('stk/category/pedoman') ? 'active' : '' }}" href="{{ url('/stk/category/pedoman') }}">Pedoman</a></li>
                            <li><a class="dropdown-item {{ request()->is('stk/category/tko') ? 'active' : '' }}" href="{{ url('/stk/category/tko') }}">Tata Kerja Organisasi</a></li>
                            <li><a class="dropdown-item {{ request()->is('stk/category/tki') ? 'active' : '' }}" href="{{ url('/stk/category/tki') }}">Tata Kerja Individu</a></li>
                            <li><a class="dropdown-item {{ request()->is('stk/category/bpcp') ? 'active' : '' }}" href="{{ url('/stk/category/bpcp') }}">BPCP</a></li>
                            <li><a class="dropdown-item {{ request()->is('stk/category/sop') ? 'active' : '' }}" href="{{ url('/stk/category/sop') }}">SOP</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('stk') ? 'active' : '' }}" href="{{ url('/stk') }}">Statistik</a>
                    </li>

                    <!-- Dihapus role check, langsung tampilkan menu Approval untuk semua pengguna -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('stk/approvals*') ? 'active' : '' }}" href="{{ url('/stk/approvals') }}">
                            <div class="notification-badge" data-count="{{ $pendingCount ?? 0 }}">
                                <i class="fas fa-clipboard-check"></i> Approval
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0051a1&color=fff" alt="User Profile">
                    <div>
                        <p class="profile-name">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="profile-role">{{ Auth::user()->department ?? 'STK Manager' }}</p>
                    </div>
                    <button id="logout-button" class="btn btn-outline-danger ms-3" onclick="logoutFromSystem()" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @hasSection('header')
        @yield('header')
    @else
        <section class="page-header">
            <div class="page-header-pattern"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="page-title">@yield('page-title', 'Sistem Tata Kelola')</h1>
                        <p class="page-description">@yield('page-description', 'Manajemen dokumen standar dan tata kelola PT Asuransi Tugu Pratama Indonesia Tbk.')</p>
                    </div>
                </div>
            </div>
        </section>
    @endif
