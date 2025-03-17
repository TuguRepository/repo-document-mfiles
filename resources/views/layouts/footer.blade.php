<!-- resources/views/layouts/footer.blade.php -->
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
            <p class="mb-0">&copy; {{ date('Y') }} PT Asuransi Tugu Pratama Indonesia Tbk. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<style>
    .footer {
        background-color: #1C2255; /* Warna biru tua */
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
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!-- Toast Container for Notifications -->
<div class="toast-container"></div>

<!-- Base Scripts -->
<script>
    // Show toast notification
    function showToast(title, message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container');

        let bgClass;
        let iconClass;

        switch (type) {
            case 'success':
                bgClass = 'bg-success';
                iconClass = 'fas fa-check-circle';
                break;
            case 'error':
            case 'danger':
                bgClass = 'bg-danger';
                iconClass = 'fas fa-exclamation-circle';
                break;
            case 'warning':
                bgClass = 'bg-warning text-dark';
                iconClass = 'fas fa-exclamation-triangle';
                break;
            default:
                bgClass = 'bg-info';
                iconClass = 'fas fa-info-circle';
        }

        const toast = document.createElement('div');
        toast.className = `toast ${bgClass} text-white`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="toast-header ${bgClass} text-white">
                <i class="${iconClass} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <small>Baru saja</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

        toastContainer.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });

        bsToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }

    // Logout function
    function logoutFromSystem() {
        if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
            // In a real implementation, this would call your logout API
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
                    // Show a toast notification
                    showToast('Logout Berhasil', 'Anda telah berhasil keluar dari sistem.', 'info');

                    // Redirect to login page after a short delay
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showToast('Logout Gagal', 'Terjadi kesalahan saat logout. Silakan coba lagi.', 'error');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                showToast('Logout Gagal', 'Terjadi kesalahan saat logout. Silakan coba lagi.', 'error');
            });
        }
    }
</script>

<!-- Page-specific scripts -->
@stack('scripts')
</body>
</html>
