<!-- resources/views/stk/approvals/partials/reject-modal.blade.php -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Permintaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="reject-request-id" name="request_id">
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <select class="form-select mb-2" id="rejectionReason" name="rejection_reason" required>
                            <option value="">-- Pilih Alasan --</option>
                            <option value="unauthorized">Tidak berwenang mengakses dokumen ini</option>
                            <option value="insufficient">Alasan penggunaan tidak cukup jelas</option>
                            <option value="sensitive">Dokumen berisi informasi sensitif</option>
                            <option value="restricted">Dokumen terbatas untuk departemen tertentu</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rejectionNote" class="form-label">Catatan Tambahan <span class="text-danger">*</span></label>
                        <textarea class="form-control admin-note" id="rejectionNote" name="rejection_note" rows="3" placeholder="Berikan penjelasan untuk pemohon..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="suggestAlternativeCheck" name="suggest_alternative">
                            <label class="form-check-label" for="suggestAlternativeCheck">
                                Sarankan dokumen alternatif
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="alternativeDocSection" style="display: none;">
                        <label for="alternativeDoc" class="form-label">Dokumen Alternatif</label>
                        <select class="form-select" id="alternativeDoc" name="alternative_doc">
                            <option value="">-- Pilih Dokumen --</option>
                            @foreach($alternativeDocuments ?? [] as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->code }} - {{ $doc->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="rejectRequest()">Tolak Permintaan</button>
            </div>
        </div>
    </div>
</div>
