<x-guest-layout>
    <style>
        /* CSS for enhancing the forgot password page */
        body {
            background-image: url('https://cms-tpi.afedigi.com/files/2022/04/6.-Pict.-News-BERAGAM-PRODUK-DAN-LAYANAN-TUGU-INSURANCE-YANG-OPTIMAL-UNTUK-KEBUTUHAN-PELANGGAN-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
            z-index: -1;
        }

        /* Floating document elements */
        .floating-docs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .doc {
            position: absolute;
            width: 30px;
            height: 40px;
            background: rgba(0, 32, 91, 0.1);
            border-radius: 3px;
            animation: float 15s linear infinite;
        }

        .doc:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            height: 4px;
            background: rgba(0, 32, 91, 0.2);
            border-radius: 2px;
        }

        .doc:after {
            content: "";
            position: absolute;
            top: 14px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            background: rgba(0, 32, 91, 0.15);
            border-radius: 2px;
        }

        @keyframes float {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(calc(100vh + 100px)) rotate(360deg);
                opacity: 0;
            }
        }

        /* Enhanced authentication card */
        .custom-auth-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 450px;
            margin: 50px auto;
            position: relative;
            z-index: 10;
            animation: fadeIn 0.8s ease-out forwards;
            border: 1px solid rgba(0, 32, 91, 0.1);
        }

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

        /* Logo styling */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 120px;
            height: auto;
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Page title */
        .forgot-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #00205b;
            margin-bottom: 5px;
        }

        .forgot-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 16px;
        }

        /* Description text */
        .forgot-description {
            background-color: #edf6ff;
            border-left: 4px solid #00205b;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            color: #4a6da7;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        /* Status message */
        .status-message {
            background-color: #e6f7ee;
            color: #0d6e3c;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6e3c;
            font-weight: 500;
            font-size: 15px;
        }

        /* Form elements */
        .form-block {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #00205b;
        }

        .form-input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            transition: all 0.3s;
            font-size: 16px;
        }

        .form-input:focus {
            border-color: #00205b;
            box-shadow: 0 0 0 3px rgba(0, 32, 91, 0.1);
            outline: none;
        }

        /* Input with icon */
        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .input-with-icon {
            padding-left: 40px;
        }

        /* Button styling */
        .custom-button {
            background-color: #00205b;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            text-align: center;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
        }

        .custom-button:hover {
            background-color: #001845;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 32, 91, 0.2);
        }

        /* Validation errors */
        .validation-errors {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .validation-errors ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Login link */
        .login-link {
            color: #00205b;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
            margin-right: 15px;
        }

        .login-link:hover {
            color: #001845;
            text-decoration: underline;
        }

        /* Form actions */
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 25px;
        }
    </style>

    <!-- Floating documents animation -->
    <div class="floating-docs" id="floatingDocs"></div>

    <x-authentication-card>
        <x-slot name="logo">
            <div class="logo-container">
                <img src="https://jb-app-backend-public-assets.s3.amazonaws.com/media/career_portal_logo_direct_upload/Logo_Tugu_Insurance_PNG.png" alt="Tugu Insurance Logo">
            </div>
            <h2 class="forgot-title">Lupa Password</h2>
            <p class="forgot-subtitle">Kami akan mengirimkan link reset password ke email Anda</p>
        </x-slot>

        <div class="forgot-description">
            {{ __('Lupa password? Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link reset password yang dapat digunakan untuk mengatur password baru.') }}
        </div>

        @session('status')
            <div class="status-message">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="validation-errors mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-block">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <div class="input-container">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" class="form-input input-with-icon" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan alamat email Anda" />
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('login') }}" class="login-link">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Kembali ke login
                </a>

                <button type="submit" class="custom-button">
                    <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>{{ __('Kirim Link Reset') }}
                </button>
            </div>
        </form>
    </x-authentication-card>

    <script>
        // Create floating document elements
        const floatingDocs = document.getElementById('floatingDocs');
        const docsCount = 15;

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
    </script>
</x-guest-layout>
