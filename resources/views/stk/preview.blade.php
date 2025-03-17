{{-- @extends('layouts.app')

@section('title', 'Preview Dokumen')

@section('styles')
<style>
    /* PDF container with viewer */
    .pdf-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* PDF viewer frame */
    .pdf-viewer {
        width: 100%;
        height: calc(100vh - 120px);
        border: none;
        display: block;
    }

    /* Watermark overlay */
    .watermark-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none; /* Allow clicking through to the PDF */
        z-index: 1000;
        opacity: 0.15; /* Transparent watermark */
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    /* Diagonal watermark text */
    .watermark-text {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        color: #888;
        font-size: 120px;
        font-weight: bold;
        font-family: Arial, sans-serif;
        white-space: nowrap;
        text-align: center;
        width: 100%;
    }

    /* User info at bottom */
    .watermark-user-info {
        position: fixed;
        bottom: 10px;
        left: 15px;
        font-size: 9px;
        color: #555;
        font-family: Arial, sans-serif;
        z-index: 1001;
        pointer-events: none;
    }

    /* Document toolbar */
    .document-toolbar {
        background: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .document-toolbar h4 {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 50%;
    }

    .toolbar-actions .btn {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="document-toolbar">
    <h4>{{ $document['title'] ?? 'Dokumen STK' }}</h4>
    <div class="toolbar-actions">
        <a href="{{ route('stk.documents.download', ['objectId' => $objectId]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-download"></i> Download
        </a>
        <a href="{{ route('stk.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="pdf-container">
    <!-- PDF viewer iframe -->
    <iframe src="{{ route('stk.documents.content', ['objectId' => $objectId]) }}" class="pdf-viewer"></iframe>

    <!-- Watermark overlay -->
    <div class="watermark-overlay">
        <div class="watermark-text">
            {{ request()->header('X-Watermark-Type') === 'download' ? 'CONTROLLED COPY' : 'CONFIDENTIAL' }}
        </div>
    </div>

    <!-- User info at bottom -->
    @if(request()->header('X-Watermark-Type') !== 'download')
    <div class="watermark-user-info">
        Viewed by: {{ request()->header('X-Watermark-User', 'USR') }} on {{ request()->header('X-Watermark-Date', now()) }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adjust watermark size based on viewport
        function adjustWatermark() {
            const watermarkText = document.querySelector('.watermark-text');
            const viewportWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            const viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

            // Calculate diagonal size (Pythagorean theorem)
            const diagonalSize = Math.sqrt(viewportWidth * viewportWidth + viewportHeight * viewportHeight);

            // Set font size proportional to screen size
            const fontSize = Math.max(60, Math.min(120, diagonalSize / 10));
            watermarkText.style.fontSize = fontSize + 'px';
        }

        // Call on load and resize
        adjustWatermark();
        window.addEventListener('resize', adjustWatermark);

        // Get watermark info from headers
        const pdfFrame = document.querySelector('.pdf-viewer');

        pdfFrame.addEventListener('load', function() {
            // Update watermark based on response headers if available
            try {
                const userInfo = document.querySelector('.watermark-user-info');
                const watermarkText = document.querySelector('.watermark-text');

                // Try to read headers from loaded iframe (may not work due to CORS)
                const headers = pdfFrame.contentWindow.document.querySelector('meta[name="watermark-user"]');

                if (headers) {
                    const user = headers.getAttribute('content');
                    if (user && userInfo) {
                        userInfo.textContent = 'Viewed by: ' + user + ' on ' + new Date().toLocaleString();
                    }
                }
            } catch (e) {
                console.log('Could not access iframe headers:', e);
            }
        });
    });
</script>
@endsection --}}
