    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Tata Kelola - Tugu Insurance</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/document-preview.css') }}" rel="stylesheet">
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
    --gradient-start: #0a296c;
    --gradient-end: #0051a1;
    --text-light: rgba(255, 255, 255, 0.9);
}

/* General Styles */
body {
    font-family: 'Inter', 'Segoe UI', -apple-system, sans-serif;
    background-color: var(--bg-light);
    color: var(--dark);
    line-height: 1.6;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(130deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
    padding: 5rem 0 4rem;
    color: white;
    position: relative;
    overflow: hidden;
    min-height: 580px;
}

/* Animated Background Elements */
.animated-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    overflow: hidden;
}

/* Tugu Building - Matching the Photo */
.tugu-building {
    position: absolute;
    bottom: -80px;
    right: 10%;
    width: 350px;
    height: 400px;
    background-image: url("data:image/svg+xml,%3Csvg width='350' height='400' viewBox='0 0 350 400' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3C!-- Left Building --%3E%3Cpath d='M40 400V100H130V400H40Z' fill='white' fill-opacity='0.15'/%3E%3C!-- Main Central Building --%3E%3Cpath d='M130 400V50H220V400H130Z' fill='white' fill-opacity='0.2'/%3E%3C!-- Triangular Right Section --%3E%3Cpath d='M220 400V100L310 200V400H220Z' fill='white' fill-opacity='0.18'/%3E%3C!-- Windows on Main Building --%3E%3Crect x='150' y='100' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='130' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='160' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='190' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='220' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='250' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3Crect x='150' y='280' width='50' height='8' fill='white' fill-opacity='0.3'/%3E%3C!-- Blue Windows on Right Facade --%3E%3Cpath d='M220 120L240 140V160H220V120Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M220 170L240 190V210H220V170Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M220 220L240 240V260H220V220Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M240 160L260 180V200H240V160Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M240 210L260 230V250H240V210Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M240 260L260 280V300H240V260Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M260 220L280 240V260H260V220Z' fill='white' fill-opacity='0.25'/%3E%3Cpath d='M260 270L280 290V310H260V270Z' fill='white' fill-opacity='0.25'/%3E%3C!-- Angular Entrance Structure --%3E%3Cpath d='M90 400L120 320L150 400H90Z' fill='white' fill-opacity='0.22'/%3E%3C!-- Red Accents on Top --%3E%3Cpath d='M160 50L170 40L180 50H160Z' fill='%23ff544a' fill-opacity='0.6'/%3E%3Cpath d='M190 50L200 40L210 50H190Z' fill='%23ff544a' fill-opacity='0.6'/%3E%3C!-- Tugu Logo --%3E%3Ctext x='155' y='340' font-family='Arial' font-size='24' font-weight='bold' fill='white' fill-opacity='0.6'%3ETUGU%3C/text%3E%3C/svg%3E");
    background-repeat: no-repeat;
    opacity: 0.8;
    animation: buildingFloat 6s ease-in-out infinite;
}

/* Cloud Elements */
.animated-icon.cloud {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    opacity: 0.2;
    position: absolute;
}

.cloud-1 {
    top: 10%;
    left: -100px;
    width: 120px;
    height: 60px;
    animation: cloudMove 60s linear infinite;
}

.cloud-2 {
    top: 35%;
    left: -200px;
    width: 100px;
    height: 50px;
    animation: cloudMove 42s linear infinite 10s;
}

.cloud-3 {
    top: 15%;
    left: -150px;
    width: 80px;
    height: 40px;
    animation: cloudMove 70s linear infinite 5s;
}

/* Corrected Airplane Elements - Right to Left, Facing Left */
.animated-icon.airplane {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M2 16.21v-1.895L10 8V4a2 2 0 0 1 4 0v4.105L22 14.42v1.789l-8-2.81V18l3 2v1l-4-1-4 1v-1l3-2v-4.685l-8 2.895z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    opacity: 0.35;
    position: absolute;
}

.airplane-1 {
    top: 20%;
    right: -80px; /* Starting from right */
    width: 80px;
    height: 80px;
    transform: rotate(-10deg) scale(1); /* Facing left */
    animation: airplaneMoveLeft 15s linear infinite;
}

.airplane-2 {
    top: 40%;
    right: -60px; /* Starting from right */
    width: 60px;
    height: 60px;
    transform: rotate(-15deg) scale(1); /* Facing left */
    animation: airplaneMoveLeft 1s linear infinite 8s;
}

/* Animation for airplanes going from right to left */
@keyframes airplaneMoveLeft {
    from {
        transform: translateX(0) rotate(-100deg);
    }
    to {
        transform: translateX(calc(-100vw - 150px)) rotate(-100deg);
    }
}
/* Decorative Elements */
.animated-icon.plus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2z'/%3E%3C/svg%3E");
    opacity: 0.15;
    position: absolute;
}

.plus-1 {
    top: 15%;
    right: 25%;
    width: 25px;
    height: 25px;
    animation: float 8s ease-in-out infinite, rotate 30s linear infinite;
}

.plus-2 {
    bottom: 25%;
    left: 10%;
    width: 20px;
    height: 20px;
    animation: float 7s ease-in-out infinite 1s, rotate 35s linear infinite;
}

.plus-3 {
    top: 40%;
    left: 30%;
    width: 15px;
    height: 15px;
    animation: float 9s ease-in-out infinite 2s, rotate 40s linear infinite;
}

.animated-icon.circle {
    width:100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    opacity: 0.1;
    position: absolute;
}

.circle-1 {
    top: 15%;
    left: 20%;
    width: 15px;
    height: 15px;
    animation: float 10s ease-in-out infinite;
}

.circle-2 {
    bottom: 30%;
    right: 45%;
    width: 25px;
    height: 25px;
    animation: float 12s ease-in-out infinite 3s;
}

/* Hero Content */
.hero-section .container {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    animation: fadeInUp 1s ease-out;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 700px;
    margin: 0 auto;
    animation: fadeInUp 1s ease-out 0.2s both;
}

/* Search Box Styling */
.search-container {
    animation: fadeInUp 1s ease-out 0.4s both;
}

.search-box {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50px;
    padding: 0.35rem;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.search-input {
    border: none;
    background: rgba(255, 255, 255, 0.95);
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 30px 0 0 30px;
}

.search-input:focus {
    outline: none;
    box-shadow: none;
}

.search-btn {
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 0 30px 30px 0;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #e94a41;
}

/* Featured Documents */
.featured-docs {
    margin-top: 1.5rem;
}

.featured-label {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-top: 1rem;
}

.doc-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.25rem;
    height: 100%;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    align-items: flex-start;
}

.animated-card {
    animation: fadeInUp 0.8s ease-out both;
}

.doc-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.15);
}

.doc-icon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.doc-icon i {
    font-size: 1.5rem;
    color: white;
}

.doc-content {
    flex: 1;
}

.doc-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: white;
}

.doc-text {
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.doc-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
}

/* Category Section */
.category-section {
    padding: 4rem 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-title {
    font-weight: 700;
    font-size: 1.8rem;
    color: var(--dark);
    margin: 0;
}

.section-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.section-link:hover {
    color: var(--secondary);
}

.category-card {
    background: white;
    border-radius: 12px;
    padding: 1.75rem;
    height: 100%;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    text-align: center;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-light);
}

.category-icon {
    background: var(--primary-light);
    color: var(--primary);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}

.category-icon i {
    font-size: 1.75rem;
}

.category-title {
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: var(--dark);
}

.category-count {
    color: #637381;
    font-size: 0.95rem;
}

/* Animations */
@keyframes fadeInUp {
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
        transform: translateY(-15px);
    }
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Cloud animation - from left to right */
@keyframes cloudMove {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(calc(100vw + 200px));
    }
}

/* Airplane animation - from left to right but facing left
@keyframes airplaneMoveRight {
    from {
        transform: translateX(0) rotate(-10deg) scaleX(-1);
    }
    to {
        transform: translateX(calc(100vw + 150px)) rotate(-10deg) scaleX(-1);
    }
} */

@keyframes buildingFloat {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }

    .doc-card {
        flex-direction: column;
    }

    .doc-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .tugu-building {
        right: 5%;
        transform: scale(0.8);
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 4rem 0 3rem;
    }

    .hero-title {
        font-size: 1.75rem;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .tugu-building {
        right: 0;
        transform: scale(0.6);
    }
}

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

    .card {
        transition: all 0.2s;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .card-title {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
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

            .hero-section {
                background: linear-gradient(100deg, #0a296c 0%, #0051a1 100%);
                padding: 4.5rem 0;
                position: relative;
                overflow: hidden;
                color: white;
            }

            .hero-pattern {
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

            .hero-title {
                font-weight: 800;
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
            }

            .hero-subtitle {
                font-weight: 400;
                font-size: 1.15rem;
                opacity: 0.9;
                max-width: 550px;
                margin-bottom: 2rem;
            }

            .search-container {
                max-width: 650px;
                margin: 0 auto;
                position: relative;
            }

            .search-box {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                padding: 0.35rem;
            }

            .search-input {
                border: none;
                font-size: 1rem;
                padding: 0.75rem 1rem;
                border-radius: 8px;
            }

            .search-input:focus {
                box-shadow: none;
            }

            .search-btn {
                background: var(--primary);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,81,161,0.2);
                transition: all 0.2s;
            }

            .search-btn:hover {
                background: #003d7a;
                transform: translateY(-1px);
                box-shadow: 0 5px 15px rgba(0,81,161,0.3);
            }

            .featured-docs {
                margin-top: 2rem;
            }

            .featured-docs .card {
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 12px;
                backdrop-filter: blur(5px);
                padding: 1.25rem;
                height: 100%;
                transition: all 0.25s;
            }

            .featured-docs .card:hover {
                background: rgba(255,255,255,0.15);
                transform: translateY(-2px);
                box-shadow: 0 8px 18px rgba(0,0,0,0.1);
            }

            .featured-docs .card-title {
                font-weight: 600;
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }

            .featured-docs .card-text {
                font-size: 0.9rem;
                opacity: 0.9;
            }

            .content-section {
                padding: 3.5rem 0;
            }

            .section-title {
                font-weight: 700;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .section-title .view-all {
                font-size: 0.9rem;
                font-weight: 500;
                color: var(--primary);
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .section-title .view-all:hover {
                text-decoration: none;
            }

            .category-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 1.5rem;
                height: 100%;
                transition: all 0.2s;
                border: 1px solid var(--border-color);
            }

            .category-card:hover {
                box-shadow: 0 8px 20px rgba(0,0,0,0.08);
                transform: translateY(-3px);
                border-color: #d0d9e4;
            }

            .category-icon {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                background: var(--primary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary);
                font-size: 1.5rem;
                margin-bottom: 1.2rem;
            }

            .category-title {
                font-weight: 600;
                font-size: 1.15rem;
                margin-bottom: 0.75rem;
            }

            .category-desc {
                font-size: 0.9rem;
                color: #637381;
                margin-bottom: 1.25rem;
            }

            .category-link {
                color: var(--primary);
                font-size: 0.9rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .category-link:hover {
                text-decoration: none;
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
            }

            .document-icon {
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: var(--secondary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--secondary);
            }

            .document-content {
                flex: 1;
            }

            .document-title {
                font-weight: 600;
                font-size: 1rem;
                margin-bottom: 0.3rem;
                color: var(--dark);
            }

            .document-desc {
                font-size: 0.9rem;
                color: #637381;
                margin-bottom: 0.3rem;
            }

            .document-meta {
                font-size: 0.8rem;
                color: #919eab;
            }

            .statistics {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                padding: 1.5rem;
                border: 1px solid var(--border-color);
                margin-bottom: 1.5rem;
            }

            .statistic-item {
                padding: 1rem;
                border-radius: 8px;
                text-align: center;
            }

            .statistic-value {
                font-weight: 700;
                font-size: 1.8rem;
                margin-bottom: 0.25rem;
                color: var(--primary);
            }

            .statistic-label {
                font-size: 0.85rem;
                color: #637381;
            }

            .recent-activity {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                padding: 1.5rem;
                border: 1px solid var(--border-color);
            }

            .activity-item {
                display: flex;
                gap: 1rem;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--border-color);
            }

            .activity-item:last-child {
                border-bottom: none;
            }

            .activity-icon {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: var(--tertiary);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }

            .activity-content {
                flex: 1;
            }

            .activity-title {
                font-weight: 500;
                font-size: 0.9rem;
                margin-bottom: 0.2rem;
            }

            .activity-time {
                font-size: 0.8rem;
                color: #919eab;
            }

            .footer {
                background: var(--dark);
                color: white;
                padding: 3rem 0 1.5rem;
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

            @media (max-width: 991.98px) {
                .hero-section {
                    padding: 3rem 0;
                }

                .hero-title {
                    font-size: 2rem;
                }

                .category-card {
                    margin-bottom: 1rem;
                }
            }

            @media (max-width: 767.98px) {
                .hero-title {
                    font-size: 1.75rem;
                }

                .database-title {
                    display: none;
                }

                .hero-pattern {
                    opacity: 0.05;
                }
            }
        </style>
        <!-- Di bagian bawah, tepat sebelum </body> -->
        @include('layouts.stk.header')

@include('stk.approvals.partials.document-preview-modal')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi yang diperlukan halaman
        // ...

        // Inisialisasi proses kartu dokumen
        if (typeof handleDocumentCards === 'function') {
            setTimeout(handleDocumentCards, 100);
        }
    });
</script>
    </head>
    <body>    <!-- Login Modal styled like M-Files with fixed vault GUID -->
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


<!-- Hero Section -->
<section class="hero-section">
    <!-- Animated Background Elements -->
    <div class="animated-bg">
        <!-- Tugu Building -->
        <div class="tugu-building"></div>

        <!-- Moving Clouds - Left to Right -->
        <div class="animated-icon cloud cloud-1"></div>
        <div class="animated-icon cloud cloud-2"></div>
        <div class="animated-icon cloud cloud-3"></div>

        <!-- Moving Airplanes - Left to Right but Facing Left -->
        <div class="animated-icon airplane airplane-1"></div>
        <div class="animated-icon airplane airplane-2"></div>

        <!-- Decorative Elements -->
        <div class="animated-icon plus plus-1"></div>
        <div class="animated-icon plus plus-2"></div>
        <div class="animated-icon plus plus-3"></div>
        <div class="animated-icon circle circle-1"></div>
        <div class="animated-icon circle circle-2"></div>
    </div>

    <div class="container">
        <!-- Main content -->
        <div class="row mb-5">
            <div class="col-lg-12 text-center">
                <h1 class="hero-title animated-title">Sistem Tata Kelola Tugu Insurance</h1>
                <p class="hero-subtitle animated-subtitle">Portal manajemen dokumen yang menghimpun dan menyajikan
                    berbagai standar, pedoman, dan tata kelola perusahaan.</p>
            </div>
        </div>

        <!-- Search Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 col-md-10 col-sm-12">
                <div class="search-container animated-search">
                    <div class="input-group search-box">
                        <input type="text" class="form-control search-input" id="global-search-input" placeholder="Cari dokumen STK...">
                        <button class="search-btn" id="global-search-button">
                            <i class="fas fa-search me-2"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Documents Section -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="featured-docs">
                    <div class="row g-4" id="featured-docs-container">
                        <!-- Document Card 1 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="doc-card animated-card" style="animation-delay: 0.1s;">
                                <div class="doc-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="doc-content">
                                    <h5 class="doc-title">Pedoman Tata Kelola</h5>
                                    <p class="doc-text">PD-021/3615/2024 - Kebijakan Pengembangan Produk Digital</p>
                                    <div class="doc-meta">
                                        <span><i class="fas fa-eye"></i> 45 views</span>
                                        <span><i class="fas fa-download"></i> 12</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Card 2 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="doc-card animated-card" style="animation-delay: 0.2s;">
                                <div class="doc-icon">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="doc-content">
                                    <h5 class="doc-title">SOP Underwriting</h5>
                                    <p class="doc-text">UW-042/3871/2024 - SOP Underwriting Marine Cargo</p>
                                    <div class="doc-meta">
                                        <span><i class="fas fa-eye"></i> 37 views</span>
                                        <span><i class="fas fa-download"></i> 9</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Card 3 -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="doc-card animated-card" style="animation-delay: 0.3s;">
                                <div class="doc-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="doc-content">
                                    <h5 class="doc-title">Tata Kerja Organisasi</h5>
                                    <p class="doc-text">TKO-027/4310/2024 - Tata Kerja Divisi Reasuransi</p>
                                    <div class="doc-meta">
                                        <span><i class="fas fa-eye"></i> 29 views</span>
                                        <span><i class="fas fa-download"></i> 8</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="featured-label">Dokumen terpopuler 2 minggu terakhir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Modal -->
    <div class="modal fade" id="searchResultModal" tabindex="-1" aria-labelledby="searchResultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchResultModalLabel">Hasil Pencarian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search input in modal -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="modal-search-input" class="form-control" placeholder="Cari dokumen..." aria-label="Cari dokumen">
                            <button class="btn btn-primary" type="button" id="modal-search-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Container hasil pencarian -->
                    <div id="search-results-container">
                        <!-- Search results will be displayed here -->
                    </div>

                    <!-- Loading indicator -->
                    <div class="text-center py-5" id="search-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Mencari dokumen...</p>
                    </div>

                    <!-- No results message -->
                    <div id="search-no-results" class="d-none">
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>Tidak ada hasil</h4>
                            <p class="text-muted">Tidak ditemukan dokumen dengan kata kunci tersebut. Coba gunakan kata kunci lain.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</section>



        <section class="content-section">
            <div class="container">
                <!-- Categories Section -->
                <h2 class="section-title">
                    Klasifikasi STK
                    <a href="#" class="view-all">Lihat Statistik <i class="fas fa-arrow-right"></i></a>
                </h2>

                <div class="row g-4">
                    <!-- Pedoman (Kode A) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="category-title">Pedoman</h3>
                            <p class="category-desc">Berisi kumpulan pedoman yang berlaku di Tugu Insurance.</p>
                            <a href="{{ url('/stk/category/pedoman') }}" class="category-link" data-type="Pedoman">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tata Kerja Organisasi (Kode B) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <h3 class="category-title">Tata Kerja Organisasi</h3>
                            <p class="category-desc">Berisi kumpulan tata kerja organisasi dalam perusahaan.</p>
                            <a href="{{ url('/stk/category/tko') }}" class="category-link" data-type="Tata Kerja Organisasi">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Tata Kerja Individu (Kode C) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3 class="category-title">Tata Kerja Individu</h3>
                            <p class="category-desc">Berisi kumpulan tata kerja untuk individu dalam perusahaan.</p>
                            <a href="{{ url('/stk/category/tki') }}" class="category-link" data-type="Tata Kerja Individu">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- BPCP (Kode D) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h3 class="category-title">BPCP</h3>
                            <p class="category-desc">Berisi kumpulan Batasan Pelayanan dan Catatan Prosedur.</p>
                            <a href="{{ url('/stk/category/bpcp') }}" class="category-link" data-type="BPCP">
                                Lihat Dokumen <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Documents Section -->
                <div class="row mt-5">
                    <div class="col-lg-8">
                        <h2 class="section-title">
                            Dokumen Terbaru
                            <a href="#" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                        </h2>

                        <!-- Dokumen terbaru akan ditampilkan secara dinamis di sini -->
                        <div id="latest-documents-container">
                            <div class="document-card skeleton-loader">
                                <div class="document-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="document-content">
                                    <h4 class="document-title">Memuat...</h4>
                                    <p class="document-desc">Memuat...</p>
                                    <div class="document-meta">Memuat...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="statistics">
                            <h2 class="section-title" style="margin-bottom: 0.75rem;">Statistik</h2>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="total-documents">-</div>
                                        <div class="statistic-label">Total Dokumen</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="document-types">-</div>
                                        <div class="statistic-label">Kategori Dokumen</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="latest-year">-</div>
                                        <div class="statistic-label">Tahun Terbaru</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="statistic-item">
                                        <div class="statistic-value" id="document-years">-</div>
                                        <div class="statistic-label">Rentang Tahun</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="recent-activity mt-4">
                            <h2 class="section-title" style="margin-bottom: 0.75rem;">Dokumen per Tahun</h2>
                            <div id="documents-by-year">
                                <!-- Dokumen per tahun akan ditampilkan secara dinamis di sini -->
                                <div class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        @include('layouts.footer')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Setup search functionality
                setupSearch();

                // Set up document preview in modal
                setupDocumentPreview();

                // Fetch data from API
                fetchSTKData();
            });

            // Function to set up search functionality
            function setupSearch() {
                // Get elements
                const searchInput = document.getElementById('global-search-input');
                const searchButton = document.getElementById('global-search-button');
                const searchModal = new bootstrap.Modal(document.getElementById('searchResultModal'));

                // Modal search elements
                const modalSearchInput = document.getElementById('modal-search-input');
                const modalSearchButton = document.getElementById('modal-search-button');

                // Handle search button click (global)
                searchButton.addEventListener('click', function() {
                    performSearch(searchInput.value);
                });

                // Handle Enter key press (global)
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch(searchInput.value);
                    }
                });

                // Handle search button click (modal)
                if (modalSearchButton) {
                    modalSearchButton.addEventListener('click', function() {
                        performSearch(modalSearchInput.value);
                    });
                }

                // Handle Enter key press (modal)
                if (modalSearchInput) {
                    modalSearchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            performSearch(this.value);
                        }
                    });
                }

                // When modal is shown, copy query from global input
                document.getElementById('searchResultModal').addEventListener('show.bs.modal', function () {
                    if (modalSearchInput && searchInput) {
                        modalSearchInput.value = searchInput.value;
                    }
                });

                // Function to perform search
                function performSearch(query) {
                    if (!query.trim()) {
                        return; // Don't search empty queries
                    }

                    console.log('Starting search for:', query);

                    // Show modal and loading state
                    searchModal.show();
                    document.getElementById('search-loading').classList.remove('d-none');
                    document.getElementById('search-no-results').classList.add('d-none');
                    document.getElementById('search-results-container').innerHTML = '';

                    // Update modal search input
                    if (modalSearchInput) {
                        modalSearchInput.value = query;
                    }

                    // Perform search with API
                    fetch(`/api/stk/simple-search?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            console.log('Search response status:', response.status);
                            if (!response.ok) {
                                return fallbackSearch(query);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Hide loading
                            document.getElementById('search-loading').classList.add('d-none');
                            console.log('Search results:', data);

                            if (data.success && data.documents && data.documents.length > 0) {
                                console.log('Rendering', data.documents.length, 'documents');
                                displaySearchResults(data.documents);
                            } else {
                                console.log('No results found or empty documents array');
                                document.getElementById('search-no-results').classList.remove('d-none');
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            document.getElementById('search-loading').classList.add('d-none');
                            document.getElementById('search-results-container').innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Terjadi kesalahan saat mencari. Silakan coba lagi. (${error.message})
                                </div>
                            `;
                        });
                }

                // Fallback search function
                function fallbackSearch(query) {
                    console.log('Using fallback search for:', query);

                    return fetch(`/stk/search?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Fallback search also failed: ' + response.status);
                            }
                            return response.json();
                        });
                }

                // Function to display search results
                function displaySearchResults(documents) {
                    const resultsContainer = document.getElementById('search-results-container');
                    resultsContainer.innerHTML = '';

                    if (!documents || documents.length === 0) {
                        document.getElementById('search-no-results').classList.remove('d-none');
                        return;
                    }

                    // Create results heading
                    const heading = document.createElement('div');
                    heading.className = 'mb-3';
                    heading.innerHTML = `<h6>Ditemukan ${documents.length} dokumen</h6>`;
                    resultsContainer.appendChild(heading);

                    // Create results list
                    documents.forEach((doc, index) => {
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

                        const resultItem = document.createElement('div');
                        resultItem.className = 'search-result-item p-3 border-bottom';
                        resultItem.setAttribute('data-id', doc.id);
                        resultItem.setAttribute('data-version', doc.version || 'latest');
                        resultItem.style.cursor = 'pointer';
                        // Set index for animation delay
                        resultItem.style.setProperty('--index', index);

                        resultItem.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="document-icon" style="width:40px;height:40px;background:#e1f0ff;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0051a1;">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1" style="color: #000000">${doc.title || 'Dokumen Tanpa Judul'}</h5>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div>
                                            <span class="badge bg-primary me-2">${doc.jenis_stk || 'Tidak Dikategorikan'}</span>
                                            <small class="text-muted">${doc.document_number || ''}</small>
                                        </div>
                                        <small class="text-muted">Diperbarui: ${formattedDate}</small>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Make item clickable
                        resultItem.addEventListener('click', function() {
                            // Hide modal
                            searchModal.hide();
                            // Open document in modal
                            viewDocumentInModal(this.getAttribute('data-id'), this.getAttribute('data-version'));
                        });

                        resultsContainer.appendChild(resultItem);
                    });

                    // Process any new document items
                    setTimeout(handleDocumentCards, 100);
                }
            }

            // Function to set up document preview
            function setupDocumentPreview() {
                // Get modal elements
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

                // Variables to store current document info
                let currentDocumentId = null;
                let currentDocumentVersion = null;

                // Event listener for request download button
                // Event listener for request download button
                if (requestDownloadBtn) {
                    console.log('Found download button, attaching event listener');
                    requestDownloadBtn.addEventListener('click', function(e) {
                        console.log('Download button clicked');
                        e.preventDefault(); // Prevent any default behavior
                        e.stopPropagation(); // Stop event from propagating

                        // Hide preview container
                        if (previewContainer) {
                            console.log('Hiding preview container');
                            previewContainer.classList.add('d-none');
                        } else {
                            console.error('Preview container not found');
                        }

                        // Show download request form
                        if (downloadRequestForm) {
                            console.log('Showing download request form');
                            downloadRequestForm.classList.remove('d-none');
                        } else {
                            console.error('Download request form not found');
                        }

                        // Set values for hidden fields
                        document.getElementById('requestDocId').value = currentDocumentId;
                        document.getElementById('requestDocVersion').value = currentDocumentVersion;
                    });
                } else {
                    console.error('Download button not found');
                }

                // Event listener for cancel button
                if (cancelRequestBtn) {
                    cancelRequestBtn.addEventListener('click', function() {
                        // Hide request form
                        downloadRequestForm.classList.add('d-none');
                        // Show preview container
                        previewContainer.classList.remove('d-none');
                    });
                }

                // Event listener for reason dropdown
                if (requestReason) {
                    requestReason.addEventListener('change', function() {
                        if (this.value === 'other') {
                            otherReasonContainer.style.display = 'block';
                            document.getElementById('otherReason').setAttribute('required', true);
                        } else {
                            otherReasonContainer.style.display = 'none';
                            document.getElementById('otherReason').removeAttribute('required');
                        }
                    });
                }

                // Event listener for download request form submission
                const formRequestDownload = document.getElementById('formRequestDownload');
                if (formRequestDownload) {
                    formRequestDownload.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate form
                        if (!this.checkValidity()) {
                            e.stopPropagation();
                            this.classList.add('was-validated');
                            return;
                        }

                        // Collect form data
                        const formData = new FormData(this);

                        // Generate reference number
                        const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);
                        document.getElementById('requestReferenceNumber').textContent = refNumber;

                        // Log form data
                        console.log('Sending download request:', Object.fromEntries(formData));

                        // Close preview modal
                        bootstrap.Modal.getInstance(previewModal).hide();

                        // Show success modal
                        const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
                        successModal.show();

                        // Reset form
                        this.reset();
                        downloadRequestForm.classList.add('d-none');
                        previewContainer.classList.remove('d-none');

                        // Show success toast
                        showToast('Permintaan Terkirim', 'Permintaan download dokumen telah berhasil dikirim.', 'success');
                    });
                }

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
.then(response => {
    console.log('Document info response status:', response.status);
    return response.json();
})
.then(data => {
    console.log('Document info data:', data);
    if (data.success && data.document) {
        console.log('Updating title to:', data.document.title);

        // Update judul di modal header
        const modalTitleElement = document.getElementById('documentPreviewModalLabel');
        if (modalTitleElement) {
            modalTitleElement.textContent = data.document.title || 'Preview Dokumen';
        } else {
            console.warn('Modal title element not found');

            // Fallback menggunakan querySelector jika ID tidak ditemukan
            const titleByQuery = document.querySelector('#documentPreviewModal .modal-title');
            if (titleByQuery) {
                titleByQuery.textContent = data.document.title || 'Preview Dokumen';
            }
        }
    } else {
        console.warn('Invalid document data received:', data);
    }
})
.catch(error => {
    console.error('Error fetching document info:', error);
    // Set default title jika fetch gagal
    const modalTitleElement = document.getElementById('documentPreviewModalLabel');
    if (modalTitleElement) {
        modalTitleElement.textContent = 'Preview Dokumen';
    }
});

// Tambahkan juga pembaruan judul di onload iframe
previewFrame.onload = function() {
    loadingIndicator.classList.add('d-none');
    previewContainer.classList.remove('d-none');

    // Pastikan judul sudah diupdate saat dokumen dimuat
    const modalTitle = document.getElementById('documentPreviewModalLabel');
    if (modalTitle && modalTitle.textContent === 'Memuat Dokumen...') {
        modalTitle.textContent = 'Preview Dokumen';
    }


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
               // Function to process document cards
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

                       // Get document ID from attributes
                       let docId = card.getAttribute('data-id');
                       let docVersion = card.getAttribute('data-version') || 'latest';

                       // Check onclick attribute
                       const onclickAttr = card.getAttribute('onclick');
                       if (!docId && onclickAttr && onclickAttr.includes('previewDocument')) {
                           const match = onclickAttr.match(/previewDocument\((\d+)(?:,\s*['"]?([^'"\)]+)['"]?)?\)/);
                           if (match) {
                               docId = match[1];
                               docVersion = match[2] || 'latest';

                               // Replace onclick with modal function
                               card.setAttribute('onclick', `event.preventDefault(); event.stopPropagation(); viewDocumentInModal(${docId}, '${docVersion}'); return false;`);
                           }
                       }

                       // Add click event listener if no onclick exists
                       if (!onclickAttr) {
                           card.addEventListener('click', function(e) {
                               e.preventDefault();
                               e.stopPropagation();

                               // Try to get ID from card attributes
                               let id = this.getAttribute('data-id');
                               let version = this.getAttribute('data-version') || 'latest';

                               // If no ID, try to get from child button
                               if (!id) {
                                   const viewButton = this.querySelector('.view-btn, .view-doc-btn');
                                   if (viewButton) {
                                       id = viewButton.getAttribute('data-id');
                                       version = viewButton.getAttribute('data-version') || 'latest';
                                   }
                               }

                               // If ID found, show document in modal
                               if (id) {
                                   viewDocumentInModal(id, version);
                               }
                           });
                       }
                   });

                   // Process view buttons directly
                   const viewButtons = document.querySelectorAll('.view-btn, .view-doc-btn');
                   viewButtons.forEach(btn => {
                       // Skip already processed buttons
                       if (btn.hasAttribute('data-modal-handler')) {
                           return;
                       }

                       // Mark as processed
                       btn.setAttribute('data-modal-handler', 'true');

                       // Get document ID and version
                       const id = btn.getAttribute('data-id');
                       const version = btn.getAttribute('data-version') || 'latest';

                       if (id) {
                           // Add click event handler
                           btn.addEventListener('click', function(e) {
                               e.preventDefault();
                               e.stopPropagation();
                               viewDocumentInModal(id, version);
                           });
                       }
                   });
               }

               // Function to fetch STK data
               function fetchSTKData() {
                   fetch('/api/stk/summary')
                       .then(response => {
                           if (!response.ok) {
                               throw new Error('Network response was not ok');
                           }
                           return response.json();
                       })
                       .then(data => {
                           if (data.success) {
                               const summary = data.summary;

                               // Display latest documents
                               displayLatestDocuments(summary.latest_documents);

                               // Display statistics
                               displayStatistics(summary);

                               // Populate year dropdown
                               populateTahunDropdown(summary.documents_by_year);
                           } else {
                               console.error('Failed to fetch STK data:', data.message);
                           }
                       })
                       .catch(error => {
                           console.error('Error fetching STK data:', error);
                       });
               }

               // Function to display latest documents
               function displayLatestDocuments(documents) {
                   const container = document.getElementById('latest-documents-container');
                   if (!container) return;

                   container.innerHTML = '';

                   if (!documents || documents.length === 0) {
                       container.innerHTML = '<div class="alert alert-info">Belum ada dokumen terbaru.</div>';
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
                               <p class="document-desc">${doc.jenis_stk || 'Tidak Dikategorikan'}</p>
                               <div class="document-meta">Diperbarui: ${formattedDate}</div>
                           </div>
                       `;

                       container.appendChild(documentCard);
                   });

                   // Process new document cards
                   setTimeout(handleDocumentCards, 100);
               }

               // Function to display statistics
               function displayStatistics(summary) {
                   // Update total documents count
                   document.getElementById('total-documents').textContent = summary.total_documents || 0;

                   // Update document types count
                   const docTypes = summary.documents_by_type ? Object.keys(summary.documents_by_type).length : 0;
                   document.getElementById('document-types').textContent = docTypes;

                   // Update latest year and number of years
                   const years = summary.documents_by_year ? Object.keys(summary.documents_by_year) : [];
                   const sortedYears = [...years].sort((a, b) => b - a);
                   document.getElementById('latest-year').textContent = sortedYears[0] || '-';
                   document.getElementById('document-years').textContent = years.length || 0;

                   // Display documents by year
                   displayDocumentsByYear(summary.documents_by_year);
               }

               // Function to display documents by year
               function displayDocumentsByYear(documentsByYear) {
                   const container = document.getElementById('documents-by-year');
                   if (!container) return;

                   container.innerHTML = '';

                   if (!documentsByYear || Object.keys(documentsByYear).length === 0) {
                       container.innerHTML = '<div class="alert alert-info">Data tahun tidak tersedia.</div>';
                       return;
                   }

                   // Sort years in descending order
                   const sortedYears = Object.keys(documentsByYear).sort((a, b) => b - a);

                   sortedYears.forEach(year => {
                       const count = documentsByYear[year];

                       const yearItem = document.createElement('div');
                       yearItem.className = 'activity-item';
                       yearItem.innerHTML = `
                           <div class="activity-icon">
                               <i class="fas fa-calendar-alt"></i>
                           </div>
                           <div class="activity-content">
                               <div class="activity-title">Tahun ${year}</div>
                               <div class="activity-time">${count} dokumen</div>
                           </div>
                       `;

                       container.appendChild(yearItem);
                   });
               }

               // Function to populate year dropdown
               function populateTahunDropdown(years) {
                   const tahunDropdownMenu = document.getElementById('tahun-dropdown-menu');
                   if (!tahunDropdownMenu) return;

                   tahunDropdownMenu.innerHTML = '';

                   if (years && Object.keys(years).length > 0) {
                       Object.keys(years).sort((a, b) => b - a).forEach(year => {
                           const count = years[year];
                           const yearLink = document.createElement('li');
                           yearLink.innerHTML = `<a class="dropdown-item" href="#">${year} <span class="badge bg-primary rounded-pill ms-2">${count}</span></a>`;
                           tahunDropdownMenu.appendChild(yearLink);
                       });
                   } else {
                       // If no years data available, add default years (last 15 years)
                       const currentYear = new Date().getFullYear();
                       for (let i = 0; i < 15; i++) {
                           const year = currentYear - i;
                           const yearLink = document.createElement('li');
                           yearLink.innerHTML = `<a class="dropdown-item" href="#">${year}</a>`;
                           tahunDropdownMenu.appendChild(yearLink);
                       }
                   }
               }

               // Toggle password visibility
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

               // Function to show toast notifications
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

               // Function to logout from system
               function logoutFromSystem() {
                   if (confirm('Apakah Anda yakin ingin keluar?')) {
                       localStorage.clear();
                       sessionStorage.clear();
                       window.location.href = `${env('APP_URL_SSO')}/dashboard`;
                   }
               }
           </script>
           <script src="{{ asset('js/document-preview.js') }}"></script>
           <script src="{{ asset('js/approval-requests.js') }}"></script>
           <!-- Tambahkan script untuk fitur metadata dokumen -->
            <script src="{{ asset('js/document-metadata.js') }}"></script>
        </body>
        </html>
