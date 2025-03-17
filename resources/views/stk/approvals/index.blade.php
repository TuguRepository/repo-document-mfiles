<!-- resources/views/stk/approvals/index.blade.php -->
@extends('layouts.master')

@section('title', 'Approval Requests - Sistem Tata Kelola Tugu Insurance')

@section('page-title', 'Approval Requests')
@section('page-description', 'Kelola permintaan download dokumen STK dari pengguna.')

@section('content')
<section class="content-section">
    <div class="container">
        <div class="row">
            <!-- Sidebar / Filter Section -->
            <div class="col-lg-3">
                <!-- Filters -->
                <div class="filter-section">
                    <h5 class="mb-3">Filter Permintaan</h5>

                    <div class="mb-3">
                        <label for="search-input" class="filter-label">Cari</label>
                        <input type="text" id="search-input" class="search-input" placeholder="Cari berdasarkan nama...">
                    </div>

                    <div class="mb-3">
                        <label class="filter-label">Status</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="pending-check" checked>
                            <label class="form-check-label" for="pending-check">
                                Pending <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $stats['pending_count'] }}</span>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="approved-check" checked>
                            <label class="form-check-label" for="approved-check">
                                Approved <span class="badge bg-success rounded-pill ms-1">{{ $stats['approved_count'] }}</span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rejected-check" checked>
                            <label class="form-check-label" for="rejected-check">
                                Rejected <span class="badge bg-danger rounded-pill ms-1">{{ $stats['rejected_count'] }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="filter-label">Kategori Dokumen</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="pedoman-check" checked>
                            <label class="form-check-label" for="pedoman-check">
                                Pedoman
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="tko-check" checked>
                            <label class="form-check-label" for="tko-check">
                                Tata Kerja Organisasi
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="tki-check" checked>
                            <label class="form-check-label" for="tki-check">
                                Tata Kerja Individu
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="bpcp-check" checked>
                            <label class="form-check-label" for="bpcp-check">
                                BPCP
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sop-check" checked>
                            <label class="form-check-label" for="sop-check">
                                SOP
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="filter-label">Periode</label>
                        <select class="form-select form-select-sm mb-2">
                            <option selected>Semua waktu</option>
                            <option>Hari ini</option>
                            <option>Minggu ini</option>
                            <option>Bulan ini</option>
                            <option>Tahun ini</option>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>

                <!-- Recent Activity -->
                <div class="card-body p-0">
                    <div class="activity-feed">
                      @forelse($recentActivity as $activity)
                        <div class="activity-item">
                          <div class="activity-icon {{ $activity->status === 'approved' ? 'approve' : 'reject' }}">
                            <i class="fas fa-{{ $activity->status === 'approved' ? 'check' : 'times' }}"></i>
                          </div>
                          <div class="activity-content">
                            <div class="activity-title">
                              @if($activity->status === 'approved')
                                Anda menyetujui permintaan download dari {{ $activity->user->name }}
                              @else
                                Anda menolak permintaan download dari {{ $activity->user->name }}
                              @endif
                            </div>
                            <div class="activity-time">{{ $activity->updated_at->diffForHumans() }}</div>
                          </div>
                        </div>
                      @empty
                        <div class="p-3 text-center text-muted">
                          Belum ada aktivitas terbaru
                        </div>
                      @endforelse
                    </div>
                  </div>


            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="requestTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                                    Pending <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $stats['pending_count'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                                    Approved <span class="badge bg-success rounded-pill ms-1">{{ $stats['approved_count'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                                    Rejected <span class="badge bg-danger rounded-pill ms-1">{{ $stats['rejected_count'] }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="requestTabsContent">
                            <!-- Pending Requests Tab -->
                            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                @forelse($pendingRequests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <h5 class="document-title">{{ $request->document->code }} - {{ $request->document->title }}</h5>
                                        <span class="badge badge-pending px-3 py-2">Pending</span>
                                    </div>
                                    <div class="request-meta">
                                        <span><i class="fas fa-user me-1"></i> {{ $request->user->name }} ({{ $request->user->department }})</span>
                                        <span><i class="fas fa-calendar me-1"></i> {{ $request->created_at->format('d M Y, H:i') }}</span>
                                        <span><i class="fas fa-file-pdf me-1"></i> {{ $request->document->type }}</span>
                                    </div>
                                    <div class="request-reason">
                                        <strong>Alasan:</strong> {{ $request->reason_type }} - {{ $request->reason_description }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument({{ $request->document_id }}, {{ $request->document_version }})">
                                            <i class="fas fa-eye me-1"></i> Preview Dokumen
                                        </button>
                                        <div>
                                            <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="{{ $request->id }}">
                                                <i class="fas fa-times me-1"></i> Tolak
                                            </button>
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="{{ $request->id }}">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-check"></i>
                                    <h4>Tidak ada permintaan yang tertunda</h4>
                                    <p>Semua permintaan telah ditinjau. Permintaan baru yang masuk akan muncul di sini.</p>
                                </div>
                                @endforelse

                                @if($pendingRequests->count() > 0)
                                <!-- Pagination -->
                                <nav aria-label="Pagination">
                                    {{ $pendingRequests->links('pagination::bootstrap-5') }}
                                </nav>
                                @endif
                            </div>

                            <!-- Approved Requests Tab -->
                            <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                                @forelse($approvedRequests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <h5 class="document-title">{{ $request->document->code }} - {{ $request->document->title }}</h5>
                                        <span class="badge badge-approved px-3 py-2">Approved</span>
                                    </div>
                                    <div class="request-meta">
                                        <span><i class="fas fa-user me-1"></i> {{ $request->user->name }} ({{ $request->user->department }})</span>
                                        <span><i class="fas fa-calendar me-1"></i> {{ $request->created_at->format('d M Y, H:i') }}</span>
                                        <span><i class="fas fa-file-pdf me-1"></i> {{ $request->document->type }}</span>
                                        <span><i class="fas fa-check-circle text-success me-1"></i> Disetujui oleh {{ $request->approver->name }}, {{ $request->approved_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="request-reason">
                                        <strong>Alasan Permintaan:</strong> {{ $request->reason_type }} - {{ $request->reason_description }}
                                    </div>
                                    @if($request->approval_note)
                                    <div class="alert alert-success py-2 px-3 mb-3">
                                        <strong>Catatan Approval:</strong> {{ $request->approval_note }}
                                    </div>
                                    @endif
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument({{ $request->document_id }}, {{ $request->document_version }})">
                                            <i class="fas fa-eye me-1"></i> Preview Dokumen
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-history me-1"></i> Lihat Detail
                                        </button>
                                    </div>
                                </div>
                                @empty
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-check"></i>
                                    <h4>Tidak ada permintaan yang disetujui</h4>
                                    <p>Permintaan yang disetujui akan muncul di sini.</p>
                                </div>
                                @endforelse

                                @if($approvedRequests->count() > 0 && $approvedRequests->hasMorePages())
                                <div class="text-center pt-3 pb-2">
                                    <button class="btn btn-outline-primary load-more" data-type="approved" data-page="{{ $approvedRequests->currentPage() + 1 }}">
                                        <i class="fas fa-sync-alt me-1"></i> Muat lebih banyak
                                    </button>
                                </div>
                                @endif
                            </div>

                            <!-- Rejected Requests Tab -->
                            <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                @forelse($rejectedRequests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <h5 class="document-title">{{ $request->document->code }} - {{ $request->document->title }}</h5>
                                        <span class="badge badge-rejected px-3 py-2">Rejected</span>
                                    </div>
                                    <div class="request-meta">
                                        <span><i class="fas fa-user me-1"></i> {{ $request->user->name }} ({{ $request->user->department }})</span>
                                        <span><i class="fas fa-calendar me-1"></i> {{ $request->created_at->format('d M Y, H:i') }}</span>
                                        <span><i class="fas fa-file-pdf me-1"></i> {{ $request->document->type }}</span>
                                        <span><i class="fas fa-times-circle text-danger me-1"></i> Ditolak oleh {{ $request->rejector->name }}, {{ $request->rejected_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="request-reason">
                                        <strong>Alasan Permintaan:</strong> {{ $request->reason_type }} - {{ $request->reason_description }}
                                    </div>
                                    <div class="alert alert-danger py-2 px-3 mb-3">
                                        <strong>Alasan Penolakan:</strong> {{ $request->rejection_reason }}
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument({{ $request->document_id }}, {{ $request->document_version }})">
                                            <i class="fas fa-eye me-1"></i> Preview Dokumen
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-history me-1"></i> Lihat Detail
                                        </button>
                                    </div>
                                </div>
                                @empty
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-check"></i>
                                    <h4>Tidak ada permintaan yang ditolak</h4>
                                    <p>Permintaan yang ditolak akan muncul di sini.</p>
                                </div>
                                @endforelse

                                @if($rejectedRequests->count() > 0 && $rejectedRequests->hasMorePages())
                                <div class="text-center pt-3 pb-2">
                                    <button class="btn btn-outline-primary load-more" data-type="rejected" data-page="{{ $rejectedRequests->currentPage() + 1 }}">
                                        <i class="fas fa-sync-alt me-1"></i> Muat lebih banyak
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Approve Modal -->
@include('stk.approvals.partials.approve-modal')

<!-- Reject Modal -->
@include('stk.approvals.partials.reject-modal')

<!-- Document Preview Modal -->
@include('stk.approvals.partials.document-preview-modal')
@endsection

@push('styles')
<style>
    /* Additional styles specific to approval page */
    .content-section {
        padding: 2rem 0;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
        font-weight: 600;
    }

    .card-header-tabs {
        margin-bottom: -1rem;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #637381;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: var(--primary);
    }

    .nav-tabs .nav-link.active {
        border-color: var(--primary);
        color: var(--primary);
        background-color: transparent;
    }

    .request-item {
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem;
        transition: background-color 0.2s;
    }

    .request-item:last-child {
        border-bottom: none;
    }

    .request-item:hover {
        background-color: var(--primary-light);
    }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .document-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark);
        margin: 0;
    }

    .request-meta {
        color: #637381;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .request-meta span {
        margin-right: 1rem;
    }

    .request-reason {
        background-color: var(--bg-light);
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .badge-pending {
        background-color: #ffecbc;
        color: #b78105;
    }

    .badge-approved {
        background-color: #d1f5e0;
        color: #0d6832;
    }

    .badge-rejected {
        background-color: #fee7e7;
        color: #b42318;
    }

    .filter-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .search-input {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.6rem 1rem;
        width: 100%;
    }

    .filter-label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #637381;
        margin-bottom: 0.5rem;
    }

    .admin-note {
        resize: none;
    }

    /* Pagination styles */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
    }

    .pagination .page-link {
        color: var(--primary);
        border-color: #dee2e6;
        margin: 0 3px;
        border-radius: 4px;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #c9d1d9;
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #637381;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Recent Activity Widget */
    .activity-feed {
        margin-top: 0.5rem;
    }

    .activity-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: flex-start;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .activity-icon.approve {
        background-color: #d1f5e0;
        color: #0d6832;
    }

    .activity-icon.reject {
        background-color: #fee7e7;
        color: #b42318;
    }

    .activity-icon.request {
        background-color: #e1f0ff;
        color: #0051a1;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.8rem;
        color: #919eab;
    }

    /* Document Preview Modal */
    #documentPreviewModal .modal-dialog {
        max-width: 90%;
        height: 80vh;
    }

    #documentPreviewModal .modal-content {
        height: 100%;
    }

    #documentPreviewModal .modal-body {
        padding: 0;
        position: relative;
    }

    #documentPreviewFrame {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Fade out animation for removing items */
    .fade-out {
        animation: fadeOut 0.5s;
        opacity: 0;
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle suggestion of alternative document
        const suggestAlternativeCheck = document.getElementById('suggestAlternativeCheck');
        const alternativeDocSection = document.getElementById('alternativeDocSection');

        if (suggestAlternativeCheck && alternativeDocSection) {
            suggestAlternativeCheck.addEventListener('change', function() {
                alternativeDocSection.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Pass request ID to modals
        const approveModal = document.getElementById('approveModal');
        const rejectModal = document.getElementById('rejectModal');

        if (approveModal) {
            approveModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const requestId = button.getAttribute('data-request-id');
                approveModal.setAttribute('data-request-id', requestId);
            });
        }

        if (rejectModal) {
            rejectModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const requestId = button.getAttribute('data-request-id');
                rejectModal.setAttribute('data-request-id', requestId);
            });
        }

        // Handle load more buttons
        const loadMoreButtons = document.querySelectorAll('.load-more');
        loadMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                const page = this.getAttribute('data-page');
                loadMoreRequests(type, page);
            });
        });
    });

    // Function to approve a request
    function approveRequest() {
        const modal = document.getElementById('approveModal');
        const requestId = modal.getAttribute('data-request-id');
        const note = document.getElementById('approvalNote').value;
        const sendEmail = document.getElementById('sendEmailCheck').checked;
        const limitTime = document.getElementById('limitTimeCheck').checked;

        // CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show loading state
        const approveButton = modal.querySelector('.btn-success');
        const originalText = approveButton.innerHTML;
        approveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        approveButton.disabled = true;

                // Ganti baris di index.blade.php sekitar baris 628
        fetch('/stk/approvals/' + requestId + '/approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                approval_note: note,
                send_email: sendEmail,
                limit_time: limitTime
            })
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            approveButton.innerHTML = originalText;
            approveButton.disabled = false;

            if (data.success) {
                // Show success toast
                showToast('Permintaan Disetujui', data.message || 'Permintaan telah berhasil disetujui dan notifikasi telah dikirim ke pemohon.', 'success');

                // Close the modal
                bootstrap.Modal.getInstance(modal).hide();

                // Update the UI to reflect the approval
                updateRequestUI(requestId, 'approved');
            } else {
                // Show error toast
                showToast('Error', data.message || 'Terjadi kesalahan saat menyetujui permintaan.', 'error');
            }
        })
        .catch(error => {
            console.error('Error approving request:', error);
            // Reset button state
            approveButton.innerHTML = originalText;
            approveButton.disabled = false;
            // Show error toast
            showToast('Error', 'Terjadi kesalahan saat menyetujui permintaan.', 'error');
        });
    }

    // Function to reject a request
    function rejectRequest() {
        const modal = document.getElementById('rejectModal');
        const requestId = modal.getAttribute('data-request-id');
        const reason = document.getElementById('rejectionReason').value;
        const note = document.getElementById('rejectionNote').value;
        const suggestAlternative = document.getElementById('suggestAlternativeCheck').checked;
        const alternativeDoc = suggestAlternative ? document.getElementById('alternativeDoc').value : null;

        // Form validation
        if (!reason) {
            alert('Silakan pilih alasan penolakan');
            return;
        }

        if (!note) {
            alert('Silakan berikan catatan tambahan');
            return;
        }

        // CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show loading state
        const rejectButton = modal.querySelector('.btn-danger');
        const originalText = rejectButton.innerHTML;
        rejectButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        rejectButton.disabled = true;

        // Send rejection request to server
        fetch('{{ route("stk.approvals.reject") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                request_id: requestId,
                reason: reason,
                note: note,
                suggest_alternative: suggestAlternative,
                alternative_doc: alternativeDoc
            })
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            rejectButton.innerHTML = originalText;
            rejectButton.disabled = false;

            if (data.success) {
                // Show success toast
                showToast('Permintaan Ditolak', data.message || 'Permintaan telah ditolak dan notifikasi telah dikirim ke pemohon.', 'error');

                // Close the modal
                bootstrap.Modal.getInstance(modal).hide();

                // Update the UI to reflect the rejection
                updateRequestUI(requestId, 'rejected');
            } else {
                // Show error toast
                showToast('Error', data.message || 'Terjadi kesalahan saat menolak permintaan.', 'error');
            }
        })
        .catch(error => {
            console.error('Error rejecting request:', error);
            // Reset button state
            rejectButton.innerHTML = originalText;
            rejectButton.disabled = false;
            // Show error toast
            showToast('Error', 'Terjadi kesalahan saat menolak permintaan.', 'error');
        });
    }

    // Show document in preview modal
    function previewDocument(docId, version) {
        const modal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
        const previewFrame = document.getElementById('documentPreviewFrame');
        const modalTitle = document.getElementById('documentPreviewModalLabel');

        // Show loading in the iframe
        previewFrame.srcdoc = `
            <html>
                <body style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f9fafb;">
                    <div style="text-align: center;">
                        <div style="margin-bottom: 20px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" style="animation: rotate 2s linear infinite;">
                                <style>
                                    @keyframes rotate {
                                        100% { transform: rotate(360deg); }
                                    }
                                </style>
                                <circle cx="12" cy="12" r="10" stroke="#e2e8f0" stroke-width="4" fill="none" />
                                <path d="M12 2a10 10 0 0 1 10 10" stroke="#0051a1" stroke-width="4" fill="none" />
                            </svg>
                        </div>
                        <p style="font-family: system-ui, sans-serif; color: #64748b; font-size: 16px;">Memuat dokumen...</p>
                    </div>
                </body>
            </html>
        `;

        // Fetch document info to update modal title
        fetch(`/api/stk/documents/${docId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalTitle.textContent = `${data.document.code} - ${data.document.title}`;
                }
            })
            .catch(error => {
                console.error('Error fetching document info:', error);
            });

        // Set iframe source
        previewFrame.src = `/stk/preview/${docId}/${version}`;

        // Show the modal
        modal.show();
    }

    // Update the UI after approving or rejecting a request
    function updateRequestUI(requestId, newStatus) {
        const requestItem = document.querySelector(`button[data-request-id="${requestId}"]`).closest('.request-item');

        // Update the counters
        const pendingCounter = document.querySelector('#pending-tab .badge');
        const approvedCounter = document.querySelector('#approved-tab .badge');
        const rejectedCounter = document.querySelector('#rejected-tab .badge');

        let pendingCount = parseInt(pendingCounter.textContent);
        pendingCounter.textContent = (pendingCount - 1).toString();

        if (newStatus === 'approved') {
            let approvedCount = parseInt(approvedCounter.textContent);
            approvedCounter.textContent = (approvedCount + 1).toString();
        } else if (newStatus === 'rejected') {
            let rejectedCount = parseInt(rejectedCounter.textContent);
            rejectedCounter.textContent = (rejectedCount + 1).toString();
        }

        // Animate the removal
        requestItem.classList.add('fade-out');

        setTimeout(() => {
            // Remove the item
            requestItem.remove();

            // Check if there are no more items
            const pendingTab = document.getElementById('pending');
            if (pendingTab.querySelectorAll('.request-item').length === 0) {
                pendingTab.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>Tidak ada permintaan yang tertunda</h4>
                        <p>Semua permintaan telah ditinjau. Permintaan baru yang masuk akan muncul di sini.</p>
                    </div>
                `;
            }
        }, 500);
    }

    // Load more requests for approved and rejected tabs
    function loadMoreRequests(type, page) {
        const loadMoreButton = document.querySelector(`.load-more[data-type="${type}"]`);

        // Show loading state
        const originalText = loadMoreButton.innerHTML;
        loadMoreButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
        loadMoreButton.disabled = true;

        // Fetch more requests
        fetch(`/stk/approvals/${type}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Get the container to append to
                    const container = document.getElementById(type).querySelector('.request-item').parentNode;

                    // Append new items before the load more button
                    const loadMoreContainer = loadMoreButton.parentNode;

                    // Insert HTML for new items
                    data.requests.data.forEach(request => {
                        const requestHtml = createRequestItemHtml(request, type);
                        container.insertBefore(requestHtml, loadMoreContainer);
                    });

                    // Update or remove the load more button
                    if (data.requests.next_page_url) {
                        loadMoreButton.setAttribute('data-page', data.requests.current_page + 1);
                        loadMoreButton.innerHTML = originalText;
                        loadMoreButton.disabled = false;
                    } else {
                        loadMoreContainer.remove();
                    }
                } else {
                    // Show error toast
                    showToast('Error', data.message || 'Terjadi kesalahan saat memuat data.', 'error');
                    loadMoreButton.innerHTML = originalText;
                    loadMoreButton.disabled = false;
                }
            })
            .catch(error => {
                console.error(`Error loading more ${type} requests:`, error);
                // Show error toast
                showToast('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                loadMoreButton.innerHTML = originalText;
                loadMoreButton.disabled = false;
            });
    }

    // Helper function to create request item HTML
    function createRequestItemHtml(request, type) {
        const div = document.createElement('div');
        div.className = 'request-item';

        let statusBadge, statusInfo, actionButtons;

        if (type === 'approved') {
            statusBadge = '<span class="badge badge-approved px-3 py-2">Approved</span>';
            statusInfo = `<span><i class="fas fa-check-circle text-success me-1"></i> Disetujui oleh ${request.approver.name}, ${new Date(request.approved_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</span>`;
            actionButtons = `
                <div class="d-flex">
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(${request.document_id}, ${request.document_version})">
                        <i class="fas fa-eye me-1"></i> Preview Dokumen
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Lihat Detail
                    </button>
                </div>
            `;
        } else if (type === 'rejected') {
            statusBadge = '<span class="badge badge-rejected px-3 py-2">Rejected</span>';
            statusInfo = `<span><i class="fas fa-times-circle text-danger me-1"></i> Ditolak oleh ${request.rejector.name}, ${new Date(request.rejected_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</span>`;
            actionButtons = `
                <div class="d-flex">
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(${request.document_id}, ${request.document_version})">
                        <i class="fas fa-eye me-1"></i> Preview Dokumen
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Lihat Detail
                    </button>
                </div>
            `;
        }

        div.innerHTML = `
            <div class="request-header">
                <h5 class="document-title">${request.document.code} - ${request.document.title}</h5>
                ${statusBadge}
            </div>
            <div class="request-meta">
                <span><i class="fas fa-user me-1"></i> ${request.user.name} (${request.user.department})</span>
                <span><i class="fas fa-calendar me-1"></i> ${new Date(request.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                <span><i class="fas fa-file-pdf me-1"></i> ${request.document.type}</span>
                ${statusInfo}
            </div>
            <div class="request-reason">
                <strong>Alasan Permintaan:</strong> ${request.reason_type} - ${request.reason_description}
            </div>
            ${type === 'approved' && request.approval_note ?
                `<div class="alert alert-success py-2 px-3 mb-3">
                    <strong>Catatan Approval:</strong> ${request.approval_note}
                </div>` : ''
            }
            ${type === 'rejected' ?
                `<div class="alert alert-danger py-2 px-3 mb-3">
                    <strong>Alasan Penolakan:</strong> ${request.rejection_reason}
                </div>` : ''
            }
            ${actionButtons}
        `;

        return div;
    }

    // Initialize notification system for real-time updates
    function initializeNotifications() {
        // Connect to WebSocket for real-time notifications (if applicable)
        if (typeof Echo !== 'undefined') {
            Echo.private('stk.approvals')
                .listen('NewDownloadRequest', (e) => {
                    // Handle new download request
                    showToast(
                        'Permintaan Download Baru',
                        `<strong>${e.user.name}</strong> dari <strong>${e.user.department}</strong> mengajukan permintaan download dokumen <strong>${e.document.title}</strong>.`,
                        'info'
                    );

                    // Update the notification badge count
                    updateNotificationBadge();

                    // Add new item to pending list if we're on the first page
                    if (window.location.search === '' || window.location.search.includes('page=1')) {
                        addNewRequestToList(e);
                    }
                });
        }
    }
    function updateNotificationBadge() {
    // Sesuaikan URL dengan endpoint yang benar
    fetch('/stk/api/pending-count') // atau URL yang benar sesuai aplikasi Anda
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.setAttribute('data-count', data.count.toString());
                }

                const pendingTab = document.querySelector('#pending-tab .badge');
                if (pendingTab) {
                    pendingTab.textContent = data.count.toString();
                }
            }
        })
        .catch(error => {
            console.warn('Error updating notification badge:', error);
            // Tidak crash, hanya log warning
        });
}
// Alternatif: nonaktifkan saja fitur notifikasi jika tidak diperlukan
function initializeNotifications() {
    try {
        // Periksa apakah Echo tersedia sebelum digunakan
        if (typeof Echo !== 'undefined') {
            Echo.private('stk.approvals')
                .listen('NewDownloadRequest', (e) => {
                    // Handler untuk notifikasi masuk
                    showToast(
                        'Permintaan Download Baru',
                        `<strong>${e.user.name}</strong> dari <strong>${e.user.department}</strong> mengajukan permintaan download dokumen <strong>${e.document.title}</strong>.`,
                        'info'
                    );

                    // Update badge notifikasi
                    updateNotificationBadge();

                    // Tambahkan item baru ke list jika kita di halaman pertama
                    if (window.location.search === '' || window.location.search.includes('page=1')) {
                        addNewRequestToList(e);
                    }
                });

            // Panggil updateNotificationBadge untuk mendapatkan hitungan awal
            updateNotificationBadge();
        } else {
            console.warn('Echo tidak tersedia, realtime notifications dinonaktifkan');
        }
    } catch (error) {
        console.warn('Error initializing notifications:', error);
    }
}

    // Add new request to pending list
    function addNewRequestToList(requestData) {
        const pendingTab = document.getElementById('pending');
        const emptyState = pendingTab.querySelector('.empty-state');

        // If there's an empty state, remove it
        if (emptyState) {
            emptyState.remove();
        }

        // Create new request item
        const requestHtml = `
            <div class="request-item" style="animation: fadeIn 0.5s;">
                <div class="request-header">
                    <h5 class="document-title">${requestData.document.code} - ${requestData.document.title}</h5>
                    <span class="badge badge-pending px-3 py-2">Pending</span>
                </div>
                <div class="request-meta">
                    <span><i class="fas fa-user me-1"></i> ${requestData.user.name} (${requestData.user.department})</span>
                    <span><i class="fas fa-calendar me-1"></i> Baru saja</span>
                    <span><i class="fas fa-file-pdf me-1"></i> ${requestData.document.type}</span>
                </div>
                <div class="request-reason">
                    <strong>Alasan:</strong> ${requestData.reason_type} - ${requestData.reason_description}
                </div>
                <div class="d-flex justify-content-between align-items-start">
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(${requestData.document_id}, ${requestData.document_version})">
                        <i class="fas fa-eye me-1"></i> Preview Dokumen
                    </button>
                    <div>
                        <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" data-request-id="${requestData.id}">
                            <i class="fas fa-times me-1"></i> Tolak
                        </button>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" data-request-id="${requestData.id}">
                            <i class="fas fa-check me-1"></i> Setujui
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add to the beginning of the list
        pendingTab.insertAdjacentHTML('afterbegin', requestHtml);
    }

   // Inisialisasi halaman yang lebih handal
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi dengan error handling
    try {
        // Inisialisasi notifikasi (dengan error handling)
        initializeNotifications();
    } catch (error) {
        console.warn('Error during page initialization:', error);
        // Lanjutkan dengan fitur lain jika ini gagal
    }
});

// app.js or a dedicated stats.js file
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    initializeCounters();

    // Set up real-time listeners for Echo if available
    setupRealTimeListeners();

    // Set up refresh interval (every 30 seconds)
    setInterval(updateAllCounters, 30000);

    // Set up dashboard charts if we're on the dashboard page
    if (document.getElementById('request-trend-chart')) {
        initializeDashboardCharts();
    }
});

/**
 * Initialize all counters on page load
 */
function initializeCounters() {
    // Update all counters initially
    updateAllCounters();

    // Set up event listeners for tab changes to refresh specific counters
    const tabButtons = document.querySelectorAll('[data-tab-target]');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-tab-target');
            if (target === 'pending' || target === 'approved' || target === 'rejected') {
                updateStatusCount(target);
            }
        });
    });
}

/**
 * Update all status counters
 */
function updateAllCounters() {
    fetch('/stk/approval/counts')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCounterDisplays(data.counts);
            }
        })
        .catch(error => console.error('Error fetching counts:', error));
}

/**
 * Update a specific status counter
 */
function updateStatusCount(status) {
    fetch(`/stk/approval/count/${status}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update just this specific counter
                const counterElement = document.getElementById(`${status}-count`);
                if (counterElement) {
                    counterElement.textContent = data.count;

                    // Add animation class for visual feedback
                    counterElement.classList.add('counter-updated');
                    setTimeout(() => {
                        counterElement.classList.remove('counter-updated');
                    }, 1000);
                }
            }
        })
        .catch(error => console.error(`Error fetching ${status} count:`, error));
}

/**
 * Update all counter displays with the provided data
 */
function updateCounterDisplays(counts) {
    // Update each counter element if it exists
    for (const [status, count] of Object.entries(counts)) {
        const counterElement = document.getElementById(`${status}-count`);
        if (counterElement) {
            counterElement.textContent = count;
        }
    }

    // Update badge counters if they exist
    const pendingBadge = document.getElementById('pending-badge');
    if (pendingBadge && counts.pending > 0) {
        pendingBadge.textContent = counts.pending;
        pendingBadge.classList.remove('hidden');
    } else if (pendingBadge) {
        pendingBadge.classList.add('hidden');
    }
}

/**
 * Set up real-time listeners for Echo/Pusher if available
 */
function setupRealTimeListeners() {
    // Check if Echo is defined (Laravel Echo)
    if (typeof window.Echo !== 'undefined') {
        // Listen for new download requests
        window.Echo.private('stk.approvals')
            .listen('.download.request.new', () => {
                // Update the pending count when a new request comes in
                updateStatusCount('pending');

                // Show notification
                showNotification('Permintaan download baru telah diterima', 'info');
            });

        // Listen for request status changes
        window.Echo.private('stk.approvals')
            .listen('.download.request.status.changed', (e) => {
                // Update all counters when a status changes
                updateAllCounters();

                // Show notification
                const message = `Permintaan ${e.reference_number} telah ${e.status === 'approved' ? 'disetujui' : 'ditolak'}`;
                showNotification(message, 'success');
            });
    }
}

/**
 * Initialize dashboard charts
 */
function initializeDashboardCharts() {
    fetch('/stk/approval/dashboard-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTrendChart(data.weekly_trend);
                renderApprovalRateChart(data.approval_stats);
            }
        })
        .catch(error => console.error('Error fetching dashboard stats:', error));
}

/**
 * Show a notification
 */
function showNotification(message, type = 'info') {
    // Check if we have a notification container
    let container = document.getElementById('notification-container');

    if (!container) {
        // Create one if it doesn't exist
        container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50';
        document.body.appendChild(container);
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type} mb-2 p-3 rounded shadow-md transition-all duration-300 transform translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0 mr-2">
                ${type === 'info' ?
                    '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>' :
                    '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                }
            </div>
            <div>${message}</div>
        </div>
    `;

    // Add to container
    container.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);

    // Remove after delay
    setTimeout(() => {
        notification.classList.add('opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Render weekly trend chart
 */
function renderTrendChart(data) {
    const ctx = document.getElementById('request-trend-chart').getContext('2d');

    // Format dates for display
    const labels = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });

    // Extract data series
    const pendingData = data.map(item => item.pending);
    const approvedData = data.map(item => item.approved);
    const rejectedData = data.map(item => item.rejected);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pending',
                    data: pendingData,
                    borderColor: '#FCD34D',
                    backgroundColor: 'rgba(252, 211, 77, 0.1)',
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Approved',
                    data: approvedData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Rejected',
                    data: rejectedData,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Permintaan 7 Hari Terakhir'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

/**
 * Render approval rate chart
 */
function renderApprovalRateChart(data) {
    const ctx = document.getElementById('approval-rate-chart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Disetujui', 'Ditolak'],
            datasets: [{
                data: [data.approved, data.rejected],
                backgroundColor: [
                    '#10B981', // Green for approved
                    '#EF4444'  // Red for rejected
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Rasio Persetujuan: ${data.approval_rate}%`
                },
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
}
</script>
@endpush
