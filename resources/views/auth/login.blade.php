<x-guest-layout>
    <style>
        /* Custom Variables */
        :root {
            --primary: #00205b;
            --primary-dark: #001845;
            --secondary: #4a6da7;
            --accent: #007bff;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }

        /* Animation Keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes borderPulse {
            0%, 100% {
                border-color: var(--primary);
            }
            50% {
                border-color: var(--accent);
            }
        }

        /* Base Styles */
        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray-100);
            position: relative;
            overflow-x: hidden;
        }

        /* Login Container Design */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
            background-image: url('https://cms-tpi.afedigi.com/files/2022/04/6.-Pict.-News-BERAGAM-PRODUK-DAN-LAYANAN-TUGU-INSURANCE-YANG-OPTIMAL-UNTUK-KEBUTUHAN-PELANGGAN-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.8));
            z-index: -1;
        }

        .login-container {
            max-width: 900px;
            width: 100%;
            background-color: var(--white);
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            display: flex;
            animation: fadeIn 0.8s ease-out forwards;
            position: relative;
        }

        /* Floating Elements */
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 32, 91, 0.05);
            animation: float 15s infinite ease-in-out;
        }

        /* Left Side (Branding) */
        .login-branding {
            flex: 0 0 45%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
            color: var(--white);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .logo-container {
            margin-bottom: 2rem;
            animation: float 4s ease-in-out infinite;
        }

        .logo-container img {
            width: 150px;
            height: auto;
        }

        .branding-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .branding-subtitle {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .feature-icon {
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Right Side (Form) */
        .login-form-container {
            flex: 1;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--gray-600);
            font-size: 1rem;
        }

        .status-message {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 4px 4px 0;
            font-size: 0.9rem;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            border: 2px solid var(--gray-300);
        }

        .input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 32, 91, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            transition: color 0.3s;
        }

        .x-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: none;
            outline: none;
            font-size: 1rem;
            color: var(--gray-800);
            background: transparent;
        }

        .input-group:focus-within .input-icon {
            color: var(--primary);
        }

        .remember-group {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-label {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin-left: 0.5rem;
            cursor: pointer;
        }

        .remember-checkbox {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-password {
            margin-left: auto;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .submit-button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 32, 91, 0.2);
        }

        .submit-button:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .submit-button:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        .register-link-container {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.95rem;
            color: var(--gray-600);
        }

        .register-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .register-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Document Icons Animation */
        .doc-icons {
            position: absolute;
            bottom: 2rem;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .doc-icon {
            width: 40px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            margin: 0 10px;
            position: relative;
            animation: float 3s infinite ease-in-out;
        }

        .doc-icon:nth-child(1) {
            animation-delay: 0s;
        }

        .doc-icon:nth-child(2) {
            animation-delay: 0.5s;
        }

        .doc-icon:nth-child(3) {
            animation-delay: 1s;
        }

        .doc-icon::before {
            content: "";
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            height: 5px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 2px;
        }

        .doc-icon::after {
            content: "";
            position: absolute;
            top: 20px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 2px;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }

            .login-branding {
                padding: 2rem;
            }

            .feature-list {
                display: none;
            }

            .doc-icons {
                bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .login-wrapper {
                padding: 1rem;
            }

            .login-branding {
                padding: 1.5rem 1rem;
            }

            .login-form-container {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .doc-icons {
                display: none;
            }
        }
    </style>

    <div class="login-wrapper">
        <!-- Floating background shapes -->
        <div class="floating-shapes">
            @for ($i = 0; $i < 10; $i++)
                <div class="shape" style="
                    width: {{ rand(20, 80) }}px;
                    height: {{ rand(20, 80) }}px;
                    left: {{ rand(1, 95) }}%;
                    top: {{ rand(1, 95) }}%;
                    animation-duration: {{ rand(10, 25) }}s;
                    animation-delay: {{ $i * 0.5 }}s;
                "></div>
            @endfor
        </div>

        <div class="login-container">
            <!-- Left side branding -->
            <div class="login-branding">
                <div class="logo-container">
                    <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance Logo">
                </div>

                <h1 class="branding-title">Dokumen Repository</h1>
                <p class="branding-subtitle">Sistem Tata Kelola Tugu Insurance</p>

                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Akses dokumen dengan cepat dan aman</span>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Kelola berkas penting dengan mudah</span>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Sistem terintegrasi dan terorganisir</span>
                    </li>
                </ul>

                <!-- Animated document icons -->
                <div class="doc-icons">
                    <div class="doc-icon"></div>
                    <div class="doc-icon"></div>
                    <div class="doc-icon"></div>
                </div>
            </div>

            <!-- Right side login form -->
            <div class="login-form-container">
                <div class="form-header">
                    <h2 class="form-title">Selamat Datang Kembali</h2>
                    <p class="form-subtitle">Silakan login untuk melanjutkan ke Sistem Tata Kelola</p>
                </div>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" class="x-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
                        </div>
                        @error('email')
                            <p class="text-danger mt-1" style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" class="x-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
                        </div>
                        @error('password')
                            <p class="text-danger mt-1" style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="remember-group">
                        <input id="remember_me" type="checkbox" name="remember" class="remember-checkbox">
                        <label for="remember_me" class="remember-label">{{ __('Remember me') }}</label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-button">
                        <i class="fas fa-sign-in-alt mr-2" style="margin-right: 8px;"></i>
                        {{ __('Log in') }}
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="register-link-container">
                            <p>Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar di sini</a></p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
