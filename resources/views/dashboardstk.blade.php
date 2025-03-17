<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard STK') }}
        </h2>
    </x-slot>

    <div class="bg-blue-900 text-white min-h-screen">
        <!-- Header -->
        <header class="flex items-center justify-between px-6 py-4 bg-white shadow-md">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('build/assets/logo.png') }}" alt="Tugu Insurance" class="h-5 w-auto">
                <span class="text-xl font-bold text-blue-900">Sistem Tata Kelola</span>
            </div>
            <button class="px-4 py-2 bg-blue-900 text-white rounded-md">Login</button>
        </header>

        <!-- Hero Section -->
        <section class="text-center py-10">
            <h1 class="text-3xl font-bold">SELAMAT DATANG</h1>
            <p class="text-lg mt-2">Di Dashboard Sistem Tata Kelola Tugu Insurance</p>
        </section>

        <!-- Search Bar -->
        <div class="flex justify-center mt-6">
            <input type="text" placeholder="Search..." class="px-4 py-2 w-1/2 border rounded-l-md">
            <button class="px-4 py-2 bg-blue-800 text-white rounded-r-md">Search</button>
        </div>

        <!-- Daftar Dokumen Terbaru -->
        <div class="px-10 mt-8">
            <h2 class="text-2xl font-semibold">Dokumen Terbaru</h2>
            <div class="grid grid-cols-3 gap-6 mt-4">
                <div class="bg-white p-4 rounded-md shadow-md">
                    <h3 class="text-lg font-bold text-blue-900">Dokumen 1</h3>
                    <p class="text-gray-600">Deskripsi singkat dokumen.</p>
                </div>
                <div class="bg-white p-4 rounded-md shadow-md">
                    <h3 class="text-lg font-bold text-blue-900">Dokumen 2</h3>
                    <p class="text-gray-600">Deskripsi singkat dokumen.</p>
                </div>
                <div class="bg-white p-4 rounded-md shadow-md">
                    <h3 class="text-lg font-bold text-blue-900">Dokumen 3</h3>
                    <p class="text-gray-600">Deskripsi singkat dokumen.</p>
                </div>
            </div>
        </div>

        <!-- Statistik Button -->
        <div class="text-center mt-10">
            <button class="px-6 py-3 bg-blue-700 text-white rounded-md">Lihat Statistik</button>
        </div>

        <!-- Kolom untuk menampilkan hasil token -->
        <div class="px-10 mt-8">
            <h2 class="text-2xl font-semibold">Token Autentikasi</h2>
            <div id="tokenContainer" class="bg-white p-4 rounded-md shadow-md text-gray-900 overflow-x-auto">
                <p id="authToken">Menunggu token...</p>
            </div>
        </div>
    </div>

    <!-- Script untuk autentikasi ke M-Files API dan menampilkan token -->
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const tokenContainer = document.getElementById('authToken');
    tokenContainer.textContent = 'Mengambil token...';

    // Call our Laravel backend endpoint instead of directly calling M-Files
    fetch('/mfiles/token')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.token) {
                // Store token for later use
                localStorage.setItem('authToken', data.token);
                tokenContainer.innerHTML = `<span class="text-green-600">Token berhasil didapatkan!</span>
                                           <div class="mt-2 text-xs bg-gray-100 p-2 rounded overflow-x-auto">${data.token}</div>`;
            } else {
                tokenContainer.innerHTML = `<span class="text-red-600">Gagal: ${data.message || 'Unknown error'}</span>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tokenContainer.innerHTML = `<span class="text-red-600">Error: ${error.message}</span>`;
        });
}); 
</script>
</x-app-layout>
