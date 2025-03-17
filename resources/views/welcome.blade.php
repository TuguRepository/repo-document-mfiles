<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tugu Insurance Document Repository</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --primary: #00205b;
            --secondary: #4a6da7;
            --dark: #111827;
            --light: #f9fafb;
            --gray: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f7;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.7)), url('https://cms-tpi.afedigi.com/files/2022/04/6.-Pict.-News-BERAGAM-PRODUK-DAN-LAYANAN-TUGU-INSURANCE-YANG-OPTIMAL-UNTUK-KEBUTUHAN-PELANGGAN-scaled.jpg');
            background-size: cover;
            background-position: center;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(0) translateX(20px);
            }
            75% {
                transform: translateY(20px) translateX(10px);
            }
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 48px;
            margin-right: 12px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-transparent {
            background: transparent;
            color: var(--primary);
            border: 2px solid rgba(0, 32, 91, 0.3);
        }

        .btn-transparent:hover {
            border-color: var(--primary);
            background: rgba(0, 32, 91, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #001845;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 4rem;
            margin-top: 2rem;
        }

        .hero-text {
            flex: 1;
            color: var(--primary);
        }

        .hero-text h1 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--secondary);
        }

        .card-container {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .feature-list {
            list-style: none;
            margin-bottom: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: #f9f9f9;
            transform: translateY(-3px);
        }

        .feature-icon {
            color: var(--primary);
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon i {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-6px);
            }
            60% {
                transform: translateY(-3px);
            }
        }

        .feature-text {
            flex: 1;
        }

        .feature-title {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .feature-description {
            font-size: 0.875rem;
            color: var(--gray);
        }

        .card-footer {
            text-align: center;
        }

        .btn-card {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
        }

        /* Floating documents animation */
        .floating-docs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .doc {
            position: absolute;
            width: 30px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            animation: fall linear infinite;
        }

        .doc:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .doc:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 2px;
        }

        @keyframes fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(calc(100vh + 100px)) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content {
                flex-direction: column;
                gap: 2rem;
            }

            .hero-text {
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2rem;
            }

            .logo-text {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }

            .nav-buttons {
                gap: 0.5rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .hero-text h1 {
                font-size: 1.75rem;
            }

            .hero-text p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <!-- Floating documents animation -->
    <div class="floating-docs" id="floatingDocs"></div>

    <!-- Particles -->
    <div id="particles"></div>

    <div class="container">
        <nav>
            <div class="logo animate__animated animate__fadeIn">
                <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance Logo">
                <div class="logo-text">Document Repository</div>
            </div>

            @if (Route::has('login'))
            <div class="nav-buttons">
                @auth
                    <a href="{{ url('/stk/database') }}" class="btn btn-primary animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
                        <i class="fas fa-tachometer-alt mr-2" style="margin-right: 8px;"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-transparent animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                        <i class="fas fa-sign-in-alt mr-2" style="margin-right: 8px;"></i> Login
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-primary animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
                        <i class="fas fa-tachometer-alt mr-2" style="margin-right: 8px;"></i> Dashboard
                    </a>
                @endauth
            </div>
            @endif
        </nav>

        <div class="content">
            <div class="hero-text animate__animated animate__fadeInLeft">
                <h1>Tugu Insurance<br>Document Repository</h1>
                <p>Kelola, akses, dan atur semua dokumen dalam satu platform yang aman dan terpusat. Sederhanakan alur kerja dokumen dengan sistem repositori canggih kami.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-folder-open mr-2" style="margin-right: 8px;"></i> Explore Repository
                </a>
            </div>

            <div class="card-container animate__animated animate__fadeInRight">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="card-title">Sistem Tata Kelola</div>
                    </div>

                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="feature-text">
                                <div class="feature-title">Quick Search</div>
                                <div class="feature-description">Temukan dokumen yang telah dipisahkan berdasarkan jenisnya dengan alat pencarian canggih kami.</div>
                            </div>
                        </li>

                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="feature-text">
                                <div class="feature-title">Secure Access</div>
                                <div class="feature-description">Izin berbasis peran memastikan keamanan dan integritas data.</div>
                            </div>
                        </li>

                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="feature-text">
                                <div class="feature-title">Version Control</div>
                                <div class="feature-description">Lacak dokumen berdasarkan grup untuk pengelolaan yang lebih terstruktur dan efisien.</div>
                            </div>
                        </li>

                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="feature-text">
                                <div class="feature-title">Analytics Dashboard</div>
                                <div class="feature-description">Dapatkan wawasan tentang penggunaan dan aktivitas dokumen.</div>
                            </div>
                        </li>
                    </ul>

                    <div class="card-footer">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-card">Get Started Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Create floating document elements
        const floatingDocs = document.getElementById('floatingDocs');
        const docsCount = 20;

        for (let i = 0; i < docsCount; i++) {
            const doc = document.createElement('div');
            doc.className = 'doc';

            // Random position
            doc.style.left = `${Math.random() * 100}%`;

            // Random animation duration
            const duration = 15 + Math.random() * 20;
            doc.style.animationDuration = `${duration}s`;

            // Random delay
            const delay = Math.random() * 15;
            doc.style.animationDelay = `${delay}s`;

            floatingDocs.appendChild(doc);
        }

        // Create particles
        const particles = document.getElementById('particles');
        const particlesCount = 15;

        for (let i = 0; i < particlesCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';

            // Random size
            const size = 5 + Math.random() * 15;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;

            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;

            // Random animation duration
            const duration = 10 + Math.random() * 20;
            particle.style.animationDuration = `${duration}s`;

            // Random delay
            const delay = Math.random() * 10;
            particle.style.animationDelay = `${delay}s`;

            // Add to DOM
            document.body.appendChild(particle);
        }

        // Animate feature icons
        const featureIcons = document.querySelectorAll('.feature-icon i');
        featureIcons.forEach((icon, index) => {
            icon.style.animationDelay = `${index * 0.5}s`;
        });
    </script>
</body>
</html>
