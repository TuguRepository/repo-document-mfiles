<x-guest-layout>
    <style>
              @media (max-width: 768px) {
    .login-container {
        width: 90%; /* Mengubah lebar kontainer pada layar kecil */
        padding: 15px; /* Mengurangi padding untuk ruang yang lebih baik */
    }
}


        .x-input {
            border: 1px solid #d1d5db; /* Border abu-abu */
            border-radius: 4px; /* Sudut membulat */
            padding: 10px;
            width: 100%;
            transition: border-color 0.3s;
        }

        .x-input:focus {
            border-color: #3b82f6; /* Warna biru saat fokus */
            outline: none;
        }

        .x-button {
            background-color: #3b82f6; /* Warna tombol biru */
            color: white; /* Teks putih */
            padding: 10px 15px;
            border-radius: 4px; /* Sudut membulat tombol */
            transition: background-color 0.3s;
            width: 100%;
            text-align: center;
        }

        .x-button:hover {
            background-color: #2563eb; /* Warna lebih gelap saat hover */
        }

        .status-message {
            background-color: #d1fae5; /* Latar hijau lembut */
            color: #065f46; /* Teks hijau tua */
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .forgot-password-link {
            color: #3b82f6;
            text-decoration: underline;
        }

        .forgot-password-link:hover {
            color: #2563eb;
        }


    </style>

    <div class="login-container">
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo />
                <h2 class="text-center text-2xl font-bold mt-4">Login</h2>
                <p class="text-center text-gray-600">Selamat datang kembali! Silakan login untuk melanjutkan.</p>
            </x-slot>

            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Input Email -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-gray-500"></i> <!-- Ikon email -->
                        <input id="email" class="x-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="flex items-center">
                        <i class="fas fa-lock mr-2 text-gray-500"></i> <!-- Ikon kunci -->
                        <input id="password" class="x-input" type="password" name="password" required autocomplete="current-password" />
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-group flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" />
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</label>
                </div>

                <!-- Forgot Password and Submit Button -->
                <div class="form-group flex justify-between items-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="x-button">
                    {{ __('Log in') }}
                </button>

                <!-- Optional Register Link -->
                <p class="text-center mt-4 text-sm text-gray-600">Belum punya akun?
                    <a href="{{ route('register') }}" class="forgot-password-link">Daftar di sini</a>.
                </p>

            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>
