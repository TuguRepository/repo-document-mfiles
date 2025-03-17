@extends('layouts.app')

@section('title', 'Download Requests')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3">Permintaan Download Dokumen</h1>
            <p class="text-muted">Daftar permintaan download dokumen Anda</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ route('stk.user-downloads', ['status' => 'all']) }}" class="btn {{ !request('status') || request('status') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                <a href="{{ route('stk.user-downloads', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <span class="badge bg-warning me-1">{{ $counters['pending'] ?? 0 }}</span>
                    Menunggu
                </a>
                <a href="{{ route('stk.user-downloads', ['status' => 'approved']) }}" class="btn {{ request('status') == 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <span class="badge bg-success me-1">{{ $counters['approved'] ?? 0 }}</span>
                    Disetujui
                </a>
                <a href="{{ route('stk.user-downloads', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <span class="badge bg-danger me-1">{{ $counters['rejected'] ?? 0 }}</span>
                    Ditolak
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($requests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Dokumen</th>
                            <th>Tanggal Request</th>
                            <th>Status</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr>
                            <td>
                                <strong class="d-block">{{ $req->document_title ?: 'Document #'.$req->document_id }}</strong>
                                <small class="text-muted">{{ $req->document_number }}</small>
                            </td>
                            <td>
                                <div>{{ $req->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $req->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $req->status_color }}">{{ $req->status_label }}</span>
                            </td>
                            <td>
                                @if($req->status === 'approved')
                                    <div><small>Disetujui: {{ $req->reviewed_at ? $req->reviewed_at->format('d M Y') : '-' }}</small></div>
                                    @if($req->isTokenExpired())
                                    <small class="text-danger">Token kedaluwarsa</small>
                                    @elseif($req->token_expires_at)
                                    <small class="text-muted">Tersedia hingga: {{ $req->token_expires_at->format('d M Y') }}</small>
                                    @endif
                                @elseif($req->status === 'rejected')
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="viewRejectionReason('{{ $req->rejection_reason }}', '{{ $req->reviewed_by }}', '{{ $req->reviewed_at ? $req->reviewed_at->format('d M Y H:i') : '-' }}')">
                                        <i class="fas fa-info-circle"></i> Alasan Penolakan
                                    </button>
                                @else
                                    <small class="text-muted">Menunggu persetujuan</small>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'approved' && !$req->isTokenExpired())
                                <a href="{{ $req->download_url }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @elseif($req->status === 'approved' && $req->isTokenExpired())
                                <button class="btn btn-sm btn-primary" onclick="renewToken({{ $req->id }})">
                                    <i class="fas fa-sync-alt"></i> Perpanjang
                                </button>
                                @elseif($req->status === 'rejected')
                                <button class="btn btn-sm btn-outline-primary" onclick="resubmitRequest({{ $req->id }}, {{ $req->document_id }}, '{{ $req->document_version }}')">
                                    <i class="fas fa-redo"></i> Ajukan Ulang
                                </button>
                                @else
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelRequest({{ $req->id }})">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $requests->appends(['status' => request('status')])->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h5>Tidak Ada Permintaan</h5>
                <p class="text-muted">Anda belum memiliki permintaan download dokumen{{ request('status') && request('status') != 'all' ? ' dengan status '.strtolower($statusLabel) : '' }}.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan alasan penolakan -->
<div class="modal fade" id="rejectionReasonModal" tabindex="-1" aria-labelledby="rejectionReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionReasonModalLabel">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan</label>
                    <div id="rejection-reason-display" class="form-control bg-light" style="min-height: 100px; white-space: pre-wrap;"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ditolak Oleh</label>
                    <div id="rejected-by-display" class="form-control bg-light"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Penolakan</label>
                    <div id="rejected-at-display" class="form-control bg-light"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // View rejection reason
    function viewRejectionReason(reason, reviewedBy, reviewedAt) {
        document.getElementById('rejection-reason-display').textContent = reason;
        document.getElementById('rejected-by-display').textContent = reviewedBy;
        document.getElementById('rejected-at-display').textContent = reviewedAt;

        const rejectionReasonModal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));
        rejectionReasonModal.show();
    }

    // Renew download token
    function renewToken(id) {
        if (confirm('Apakah Anda ingin memperpanjang masa berlaku token download?')) {
            fetch(`/stk/download-requests/${id}/renew`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Berhasil', data.message, 'success');
                    // Reload page after short delay
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'Terjadi kesalahan saat memproses permintaan', 'error');
                console.error('Error:', error);
            });
        }
    }

    // Cancel download request
    function cancelRequest(id) {
        if (confirm('Apakah Anda yakin ingin membatalkan permintaan download ini?')) {
            fetch(`/stk/download-requests/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Berhasil', data.message, 'success');
                    // Reload page after short delay
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('Gagal', data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Error', 'Terjadi kesalahan saat memproses permintaan', 'error');
                console.error('Error:', error);
            });
        }
    }

    // Resubmit request
    function resubmitRequest(oldRequestId, documentId, version) {
        // Redirect to the document preview with resubmit flag
        window.location.href = `/stk/preview/${documentId}/${version}?resubmit=true&old_request=${oldRequestId}`;
    }
</script>
@endsection
