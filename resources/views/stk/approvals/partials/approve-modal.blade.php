<!-- resources/views/stk/approvals/partials/approve-modal.blade.php -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveModalLabel">Setujui Permintaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approveForm">
                    <!-- Hidden input for request ID -->
                    <input type="hidden" id="approve-request-id" name="request_id" value="">

                    <div class="mb-3">
                        <label for="approvalNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control admin-note" id="approvalNote" name="approval_note" rows="3" placeholder="Tambahkan catatan untuk pemohon..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opsi Tambahan</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sendEmailCheck" name="send_email" checked>
                            <label class="form-check-label" for="sendEmailCheck">
                                Kirim notifikasi email ke pemohon
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sendFileCheck" name="send_file" checked>
                            <label class="form-check-label" for="sendFileCheck">
                                Sertakan file dokumen dalam email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="limitTimeCheck" name="limit_time">
                            <label class="form-check-label" for="limitTimeCheck">
                                Batasi akses download (24 jam)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="approveRequest()">Setujui Permintaan</button>
            </div>
        </div>
    </div>
</div>
