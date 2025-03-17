<!-- resources/views/stk/partials/document-preview-modal.blade.php -->

<!-- Pastikan meta CSRF token ada di halaman -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal Preview Dokumen -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 90%; height: 80vh;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <h5 class="modal-title" id="documentPreviewModalLabel">Preview Dokumen</h5>
                <div class="ms-auto">
                    <button class="btn btn-outline-primary me-2" id="requestDownloadBtn">
                        <i class="fas fa-paper-plane"></i> Request Download
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex justify-content-center align-items-center h-100" id="documentLoadingIndicator">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Memuat dokumen...</span>
                </div>
                <div id="documentPreviewContainer" class="h-100 d-none">
                    <iframe id="documentPreviewFrame" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div id="documentPreviewError" class="text-center p-5 d-none">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <h4>Gagal memuat dokumen</h4>
                    <p class="text-muted" id="errorMessage">Terjadi kesalahan saat memuat dokumen.</p>
                    <a href="#" class="btn btn-primary mt-3" id="retryLoadButton">
                        <i class="fas fa-sync"></i> Coba Lagi
                    </a>
                </div>

                <!-- Form Request Download -->
                <div id="downloadRequestForm" class="d-none p-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Request Download Dokumen</h5>
                        </div>
                        <div class="card-body">
                            <form id="formRequestDownload">
                                <input type="hidden" id="requestDocId" name="document_id">
                                <input type="hidden" id="requestDocVersion" name="document_version">

                                <div class="mb-3">
                                    <label for="requestReason" class="form-label">Alasan Permintaan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="requestReason" name="reason" required>
                                        <option value="">-- Pilih Alasan --</option>
                                        <option value="reference">Referensi Pekerjaan</option>
                                        <option value="audit">Audit/Compliance</option>
                                        <option value="sharing">Sharing Knowledge</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="otherReasonContainer" style="display: none;">
                                    <label for="otherReason" class="form-label">Alasan Lainnya <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="otherReason" name="other_reason" rows="2"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="requestNotes" class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control" id="requestNotes" name="notes" rows="2"></textarea>
                                </div>

                                <!-- Pernyataan tanpa checkbox -->
                                <div class="mb-3 border p-2 bg-light">
                                    <p class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Saya menyatakan bahwa dokumen ini hanya akan digunakan untuk keperluan internal dan tidak akan dibagikan kepada pihak eksternal tanpa izin.</p>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="cancelRequestBtn">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                    <!-- Ubah onclick untuk tidak memeriksa checkbox -->
                                    <button type="submit" class="btn btn-primary" onclick="handleSubmitRequest(); return false;">
                                        <i class="fas fa-paper-plane"></i> Kirim Permintaan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Request Download -->
<div class="modal fade" id="requestSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Permintaan Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                <h4>Permintaan Download Berhasil Dikirim</h4>
                <p>Permintaan Anda telah berhasil dikirim dan sedang diproses. Anda akan menerima notifikasi jika permintaan Anda disetujui.</p>
                <p class="text-muted">Nomor Referensi: <span id="requestReferenceNumber">REF-12345</span></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- Tambahkan script ini ke bawah modal -->
<script>
    function handleSubmitRequest() {
        // Generate reference number
        const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);

        // Update reference di modal sukses
        document.getElementById('requestReferenceNumber').textContent = refNumber;

        // Sembunyi modal preview
        bootstrap.Modal.getInstance(document.getElementById('documentPreviewModal')).hide();

        // Tampilkan modal sukses
        new bootstrap.Modal(document.getElementById('requestSuccessModal')).show();

        // Reset form
        document.getElementById('formRequestDownload').reset();

        // Kembalikan ke tampilan preview
        document.getElementById('downloadRequestForm').classList.add('d-none');
        document.getElementById('documentPreviewContainer').classList.remove('d-none');
    }
</script>
