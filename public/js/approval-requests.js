// Define global variables at the top level
const itemsPerPage = 5;
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadCounts();
    loadRequests('pending', currentPage);
    loadActivities();

    // Setup event listeners
    setupEventListeners();
    // addFloatingButton();
});

// Load request counts
function loadCounts() {
    fetch('/api/counts')
        .then(response => response.json())
        .then(data => {
            // Update all counter elements
            document.querySelectorAll('.pending-count').forEach(el => {
                el.textContent = data.pending.toString();
            });

            document.querySelectorAll('.approved-count').forEach(el => {
                el.textContent = data.approved.toString();
            });

            document.querySelectorAll('.rejected-count').forEach(el => {
                el.textContent = data.rejected.toString();
            });

            // Update notification badge
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.setAttribute('data-count', data.pending.toString());
            }

            // Toggle visibility of pagination/load more buttons
            const pendingPagination = document.getElementById('pending-pagination');
            if (pendingPagination) {
                pendingPagination.style.display = data.pending > itemsPerPage ? 'block' : 'none';
            }

            const approvedLoadMore = document.getElementById('approved-load-more');
            if (approvedLoadMore) {
                approvedLoadMore.style.display = data.approved > itemsPerPage ? 'block' : 'none';
            }

            const rejectedLoadMore = document.getElementById('rejected-load-more');
            if (rejectedLoadMore) {
                rejectedLoadMore.style.display = data.rejected > itemsPerPage ? 'block' : 'none';
            }
        })
        .catch(error => {
            console.error('Error loading counts:', error);
        });
}

// Load requests based on status
function loadRequests(status = 'pending', page = 1, append = false) {
    // Get filter values
    const searchTerm = document.getElementById('search-input')?.value || '';
    const periodFilter = document.getElementById('period-filter')?.value || 'all';

    // Get selected categories
    const selectedCategories = Array.from(document.querySelectorAll('.filter-category:checked'))
        .map(el => el.value);

    // Build query params
    const params = new URLSearchParams({
        status: status,
        page: page,
        per_page: itemsPerPage
    });

    if (searchTerm) {
        params.append('search', searchTerm);
    }

    if (periodFilter !== 'all') {
        params.append('period', periodFilter);
    }

    if (selectedCategories.length > 0) {
        selectedCategories.forEach(category => {
            params.append('categories[]', category);
        });
    }

    // Make API request
    fetch(`/api/requests?${params.toString()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            processRequestsData(data, status, page, append);
        })
        .catch(error => {
            console.error(`Error loading ${status} requests:`, error);

            // Try to find a container to show the error in
            const containerId = `${status}-requests-container`;
            let container = document.getElementById(containerId) || document.getElementById(status);

            if (container) {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error: ${error.message || 'Failed to load requests'}
                    </div>
                `;
            } else {
                // If we can't find a container, show a popup
                showToast('Error', `Failed to load ${status} requests: ${error.message}`, 'error');
            }
        });
}

// Process and display received data
function processRequestsData(data, status, page, append) {
    // Find the correct container for this status
    let container = document.getElementById(`${status}-requests-container`);

    if (!container) {
        // Fallback container
        container = document.getElementById(status);
    }

    if (!container) {
        console.error(`No container found for status: ${status}`);
        return;
    }

    // Clear container if not appending
    if (!append) {
        // Save the original empty state if it exists
        const emptyState = container.querySelector('.empty-state');
        const savedEmptyState = emptyState ? emptyState.cloneNode(true) : null;

        container.innerHTML = '';

        // If we have the original empty state but no data, restore it
        if (savedEmptyState && (!data.data || data.data.length === 0)) {
            container.appendChild(savedEmptyState);
        }
    }

    // Check if data has the expected structure
    if (!data || !data.data) {
        console.error('Unexpected API response structure:', data);
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Error: Unexpected data format received from server
            </div>
        `;
        return;
    }

    // Display requests or empty state
    if (data.data.length === 0 && !append) {
        let emptyMessage = 'Tidak ada permintaan';
        if (status === 'pending') {
            emptyMessage = 'Tidak ada permintaan yang perlu ditinjau';
        } else if (status === 'approved') {
            emptyMessage = 'Tidak ada permintaan yang telah disetujui';
        } else if (status === 'rejected') {
            emptyMessage = 'Tidak ada permintaan yang telah ditolak';
        }

        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-${status === 'pending' ? 'inbox' : (status === 'approved' ? 'check-circle' : 'times-circle')}"></i>
                <h4>Tidak Ada Permintaan</h4>
                <p>${emptyMessage}</p>
            </div>
        `;
    } else if (data.data.length > 0) {
        // If there's data but it's currently showing "No requests", clear that first
        const currentEmptyState = container.querySelector('.empty-state');
        if (currentEmptyState) {
            container.innerHTML = '';
        }

        // Create a wrapper if it doesn't exist
        let requestsWrapper = container.querySelector('.requests-wrapper');
        if (!requestsWrapper) {
            requestsWrapper = document.createElement('div');
            requestsWrapper.className = 'requests-wrapper';
            container.appendChild(requestsWrapper);
        }

        // Loop through requests and create items
        data.data.forEach(request => {
            try {
                // Validate required data
                if (!request || !request.id) {
                    throw new Error('Invalid request data: missing ID');
                }

                // Ensure status is set if not present
                if (!request.status) {
                    request.status = status;
                }

                const requestItem = createRequestItem(request);

                if (!requestItem) {
                    throw new Error('Failed to create request item');
                }

                requestsWrapper.appendChild(requestItem);
            } catch (error) {
                console.error('Error creating request item:', error, request);

                // Add a placeholder for the failed item
                const errorItem = document.createElement('div');
                errorItem.className = 'request-item error';
                errorItem.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error rendering request ${request.id || 'unknown'}: ${error.message}
                    </div>
                `;
                requestsWrapper.appendChild(errorItem);
            }
        });

        // Update pagination for pending tab
        if (status === 'pending') {
            updatePagination(data.total, data.current_page, data.last_page);
        }

        // Update load more button visibility
        if (status === 'approved' || status === 'rejected') {
            const loadMoreId = `${status}-load-more`;
            const loadMoreBtn = document.getElementById(loadMoreId);

            if (loadMoreBtn) {
                if (data.current_page < data.last_page) {
                    loadMoreBtn.style.display = 'block';
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            }
        }
    }
}

// Load activities
function loadActivities() {
    fetch('/api/activities')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const activityFeed = document.getElementById('activity-feed');
            if (!activityFeed) {
                console.warn('Activity feed container not found');
                return;
            }

            if (!data || data.length === 0) {
                activityFeed.innerHTML = `
                    <div class="empty-state p-3 text-center">
                        <p class="text-muted mb-0">Belum ada aktivitas terbaru</p>
                    </div>
                `;
                return;
            }

            activityFeed.innerHTML = '';

            data.forEach(activity => {
                const activityItem = document.createElement('div');
                activityItem.className = 'activity-item';

                let iconClass, title;

                if (activity.type === 'approve') {
                    iconClass = 'approve';
                    title = `Anda menyetujui permintaan download dari ${activity.download_request?.user_name || 'pengguna'}`;
                } else if (activity.type === 'reject') {
                    iconClass = 'reject';
                    title = `Anda menolak permintaan download dari ${activity.download_request?.user_name || 'pengguna'}`;
                } else {
                    iconClass = 'request';
                    title = `Permintaan baru dari ${activity.download_request?.user_name || 'pengguna'}`;
                }

                activityItem.innerHTML = `
                    <div class="activity-icon ${iconClass}">
                        <i class="fas fa-${activity.type === 'approve' ? 'check' : (activity.type === 'reject' ? 'times' : 'download')}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">${title}</div>
                        <div class="activity-time">${formatDate(activity.created_at)}</div>
                    </div>
                `;

                activityFeed.appendChild(activityItem);
            });
        })
        .catch(error => {
            console.error('Error loading activities:', error);

            const activityFeed = document.getElementById('activity-feed');
            if (activityFeed) {
                activityFeed.innerHTML = `
                    <div class="alert alert-danger p-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error: ${error.message || 'Failed to load activities'}
                    </div>
                `;
            }
        });
}

// Create request item HTML
function createRequestItem(request) {
    const requestItem = document.createElement('div');
    requestItem.className = 'request-item';
    requestItem.dataset.id = request.id || '';

    const documentTitle = request.document_title || 'Dokumen Tidak Dikenal';
    const documentNumber = request.document_number || '';
    const documentFullName = documentNumber ? `${documentNumber} - ${documentTitle}` : documentTitle;

    // Determine status badge
    let statusBadge;
    if (request.status === 'pending') {
        statusBadge = '<span class="badge badge-pending px-3 py-2">Pending</span>';
    } else if (request.status === 'approved') {
        statusBadge = '<span class="badge badge-approved px-3 py-2">Approved</span>';
    } else {
        statusBadge = '<span class="badge badge-rejected px-3 py-2">Rejected</span>';
    }

    let actionButtons = '';
    let actionInfo = ''; // Initialize actionInfo here

    if (request.status === 'pending') {
        const documentId = request.document_id || 0;

        actionButtons = `
            <div class="d-flex justify-content-between align-items-start">
                <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(${documentId}, 1)">
                    <i class="fas fa-eye me-1"></i> Preview Dokumen
                </button>
                <div>
                    <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal" onclick="prepareReject('${request.id}')">
                        <i class="fas fa-times me-1"></i> Tolak
                    </button>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal" onclick="prepareApprove('${request.id}')">
                        <i class="fas fa-check me-1"></i> Setujui
                    </button>
                </div>
            </div>
        `;
    } else {
        // Action history for approved/rejected
        if (request.status === 'approved') {
            const approvedBy = request.approved_by_name || 'Admin';
            actionInfo = `<span><i class="fas fa-check-circle text-success me-1"></i> Disetujui oleh ${approvedBy}, ${formatDate(request.approved_at || request.updated_at)}</span>`;
        } else if (request.status === 'rejected') {
            const rejectedBy = request.rejected_by_name || 'Admin';
            actionInfo = `<span><i class="fas fa-times-circle text-danger me-1"></i> Ditolak oleh ${rejectedBy}, ${formatDate(request.rejected_at || request.updated_at)}</span>`;
        }

        // Note section
        let noteSection = '';
        if (request.admin_note) {
            const alertClass = request.status === 'approved' ? 'success' : 'danger';
            const noteTitle = request.status === 'approved' ? 'Catatan Approval' : 'Alasan Penolakan';

            noteSection = `
                <div class="alert alert-${alertClass} py-2 px-3 mb-3">
                    <strong>${noteTitle}:</strong> ${request.admin_note}
                </div>
            `;
        }

        const documentId = request.document_id || 0;

        actionButtons = `
            ${noteSection}
            <div class="d-flex">
                <button class="btn btn-sm btn-outline-primary me-2" onclick="previewDocument(${documentId}, 1)">
                    <i class="fas fa-eye me-1"></i> Preview Dokumen
                </button>
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-history me-1"></i> Lihat Detail
                </button>
            </div>
        `;
    }

    const userName = request.user_name || request.username || 'Unknown User';
    const userDepartment = request.department || request.user_department || 'N/A';
    const requestReason = request.reason || request.download_reason || 'Tidak ada alasan yang diberikan';

    const documentCategory =
        (request.document && request.document.category) ||
        (request.document_data && request.document_data.category) ||
        request.document_category ||
        'Dokumen';

    const createdAt = request.created_at || request.request_date || 'Waktu tidak tersedia';

    requestItem.innerHTML = `
        <div class="request-header">
            <h5 class="document-title">${documentFullName}</h5>
            ${statusBadge}
        </div>
        <div class="request-meta">
            <span><i class="fas fa-user me-1"></i> ${userName} (${userDepartment})</span>
            <span><i class="fas fa-calendar me-1"></i> ${formatDate(createdAt)}</span>
            <span><i class="fas fa-file-pdf me-1"></i> ${documentCategory}</span>
            ${request.status !== 'pending' ? actionInfo : ''}
        </div>
        <div class="request-reason">
            <strong>Alasan${request.status !== 'pending' ? ' Permintaan' : ''}:</strong> ${requestReason}
        </div>
        ${actionButtons}
    `;

    return requestItem;
}

// Update pagination for pending tab
function updatePagination(totalItems, currentPage, lastPage) {
    const paginationElement = document.getElementById('pending-pagination');
    if (!paginationElement) return;

    if (lastPage <= 1) {
        paginationElement.style.display = 'none';
        return;
    }

    paginationElement.style.display = 'block';

    const paginationList = paginationElement.querySelector('ul');
    if (!paginationList) return;

    paginationList.innerHTML = '';

    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `
        <a class="page-link" href="#" tabindex="-1" aria-disabled="${currentPage === 1}" onclick="changePage(${currentPage - 1})">
            <i class="fas fa-chevron-left"></i>
        </a>
    `;
    paginationList.appendChild(prevLi);

    // Page numbers
    const startPage = Math.max(1, currentPage - 1);
    const endPage = Math.min(lastPage, startPage + 2);

    for (let i = startPage; i <= endPage; i++) {
        const pageLi = document.createElement('li');
        pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
        pageLi.innerHTML = `
            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
        `;
        paginationList.appendChild(pageLi);
    }

    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === lastPage ? 'disabled' : ''}`;
    nextLi.innerHTML = `
        <a class="page-link" href="#" aria-disabled="${currentPage === lastPage}" onclick="changePage(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
        </a>
    `;
    paginationList.appendChild(nextLi);
}

// Setup event listeners
function setupEventListeners() {
    // Document suggestion toggle
    const suggestAlternativeCheck = document.getElementById('suggestAlternativeCheck');
    const alternativeDocSection = document.getElementById('alternativeDocSection');

    if (suggestAlternativeCheck && alternativeDocSection) {
        suggestAlternativeCheck.addEventListener('change', function() {
            alternativeDocSection.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Other reason toggle
    const requestReason = document.getElementById('requestReason');
    const otherReasonSection = document.getElementById('otherReasonSection');

    if (requestReason && otherReasonSection) {
        requestReason.addEventListener('change', function() {
            otherReasonSection.style.display = this.value === 'Lainnya' ? 'block' : 'none';
        });
    }

    // Button event listeners
    const approveBtn = document.getElementById('approveRequestBtn');
    if (approveBtn) {
        approveBtn.addEventListener('click', approveRequest);
    }

    const rejectBtn = document.getElementById('rejectRequestBtn');
    if (rejectBtn) {
        rejectBtn.addEventListener('click', rejectRequest);
    }

    const submitBtn = document.getElementById('submitRequestBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', submitNewRequest);
    }

    const applyFilterBtn = document.getElementById('apply-filter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', applyFilters);
    }

    // Load more buttons
    const approvedLoadMoreBtn = document.getElementById('load-more-approved');
    if (approvedLoadMoreBtn) {
        approvedLoadMoreBtn.addEventListener('click', function() {
            const currentItems = document.querySelectorAll('#approved-requests-container .request-item').length;
            const page = Math.floor(currentItems / itemsPerPage) + 1;
            loadRequests('approved', page, true);
        });
    }

    const rejectedLoadMoreBtn = document.getElementById('load-more-rejected');
    if (rejectedLoadMoreBtn) {
        rejectedLoadMoreBtn.addEventListener('click', function() {
            const currentItems = document.querySelectorAll('#rejected-requests-container .request-item').length;
            const page = Math.floor(currentItems / itemsPerPage) + 1;
            loadRequests('rejected', page, true);
        });
    }

    // Search input event
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            debounce(applyFilters, 300)();
        });
    }

    // Tab change events
    const pendingTab = document.getElementById('pending-tab');
    const approvedTab = document.getElementById('approved-tab');
    const rejectedTab = document.getElementById('rejected-tab');

    // Add direct click handlers to each tab
    if (pendingTab) {
        pendingTab.addEventListener('click', function() {
            loadRequests('pending', 1);
        });
    }

    if (approvedTab) {
        approvedTab.addEventListener('click', function() {
            loadRequests('approved', 1);
        });
    }

    if (rejectedTab) {
        rejectedTab.addEventListener('click', function() {
            loadRequests('rejected', 1);
        });
    }

    // Also keep the bootstrap event listener as a backup
    const requestTabs = document.getElementById('requestTabs');
    if (requestTabs) {
        requestTabs.addEventListener('shown.bs.tab', function(event) {
            const targetId = event.target.getAttribute('data-bs-target')?.replace('#', '') || '';
            if (targetId) {
                loadRequests(targetId, 1);
            }
        });
    }
}

// Add floating button for creating new requests
// function addFloatingButton() {
//     // Check if button already exists
//     if (document.querySelector('.floating-add-button')) {
//         return;
//     }

//     const btn = document.createElement('button');
//     btn.className = 'btn btn-primary btn-lg rounded-circle position-fixed floating-add-button';
//     btn.style.bottom = '30px';
//     btn.style.right = '30px';
//     btn.style.width = '60px';
//     btn.style.height = '60px';
//     btn.style.zIndex = '1000';
//     btn.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
//     btn.innerHTML = '<i class="fas fa-plus"></i>';
//     btn.title = 'Tambah Permintaan Baru';

//     btn.addEventListener('click', function() {
//         const modal = new bootstrap.Modal(document.getElementById('addRequestModal'));
//         if (modal) {
//             modal.show();
//         } else {
//             console.error('Add request modal not found');
//         }
//     });

//     document.body.appendChild(btn);
// }

// Format date for display
function formatDate(dateString) {
    if (!dateString) return 'Tanggal tidak tersedia';

    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return dateString; // Return original string if parsing fails
        }

        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);

        if (diffMins < 1) {
            return 'Baru saja';
        } else if (diffMins < 60) {
            return `${diffMins} menit yang lalu`;
        } else if (diffHours < 24) {
            return `${diffHours} jam yang lalu`;
        } else if (diffDays < 2) {
            return 'Kemarin, ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } else {
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) +
                ', ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    } catch (error) {
        console.error('Error formatting date:', error, dateString);
        return 'Invalid date';
    }
}

// Change page for pagination
function changePage(page) {
    currentPage = page;
    loadRequests('pending', currentPage);
}

// Apply filters
function applyFilters() {
    currentPage = 1; // Reset to first page
    loadRequests('pending', currentPage);

    // Also reload approved and rejected tabs if they're visible
    const activeTab = document.querySelector('#requestTabs .nav-link.active');
    if (activeTab) {
        const targetId = activeTab.getAttribute('data-bs-target').replace('#', '');
        if (targetId !== 'pending') {
            loadRequests(targetId, 1);
        }
    }
}

// Simple debug-free updateDebugInfo function
function updateDebugInfo(message, level = 'info') {
    // Do nothing - function exists for backward compatibility
}

// Prepare modal for approval
function prepareApprove(requestId) {
    const idField = document.getElementById('approve-request-id');
    if (idField) {
        idField.value = requestId;
    }

    const noteField = document.getElementById('approvalNote');
    if (noteField) {
        noteField.value = '';
    }

    const emailCheck = document.getElementById('sendEmailCheck');
    if (emailCheck) {
        emailCheck.checked = true;
    }

    const limitTimeCheck = document.getElementById('limitTimeCheck');
    if (limitTimeCheck) {
        limitTimeCheck.checked = false;
    }
}

// Approve a request
function approveRequest() {
    const requestIdField = document.getElementById('approve-request-id');
    if (!requestIdField) {
        console.error('Request ID field not found');
        return;
    }

    const requestId = requestIdField.value;
    const note = document.getElementById('approvalNote')?.value || '';
    const sendEmail = document.getElementById('sendEmailCheck')?.checked || false;
    const limitTime = document.getElementById('limitTimeCheck')?.checked || false;

    if (!requestId) {
        console.error('No request ID found');
        return;
    }

    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('note', note);
    formData.append('send_email', sendEmail ? '1' : '0');
    formData.append('limit_time', limitTime ? '1' : '0');

    // Tambahkan CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    fetch('/api/approve', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh data
            loadCounts();
            loadRequests('pending', currentPage);
            loadRequests('approved', 1);
            loadActivities();

            // Show notification
            showToast('Permintaan Disetujui', 'Permintaan telah berhasil disetujui dan notifikasi telah dikirim ke pemohon.', 'success');
        } else {
            showToast('Error', data.message || 'Terjadi kesalahan saat menyetujui permintaan', 'error');
        }
    })
    .catch(error => {
        console.error('Error approving request:', error);
        showToast('Error', 'Terjadi kesalahan saat menghubungi server', 'error');
    });

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
    if (modal) modal.hide();
}

// Preview document
function previewDocument(docId, version) {
    if (!docId) {
        console.error('Document ID is required for preview');
        return;
    }

    const previewModal = document.getElementById('documentPreviewModal');
    if (!previewModal) {
        console.error('Preview modal not found');
        showToast('Error', 'Modal preview dokumen tidak ditemukan', 'error');
        return;
    }

    const modal = new bootstrap.Modal(previewModal);
    const previewFrame = document.getElementById('documentPreviewFrame');
    const modalTitle = document.getElementById('documentPreviewModalLabel');

    if (!previewFrame || !modalTitle) {
        console.error('Preview frame or modal title not found');
        return;
    }

    // Set judul loading
    modalTitle.textContent = 'Memuat Dokumen...';

    // Load data dokumen dari API
    fetch(`/api/document/${docId}/preview`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(doc => {
            const title = doc ? `${doc.code || ''} - ${doc.title}` : 'Preview Dokumen';
            modalTitle.textContent = title;

            // In a real implementation, this would point to your document viewer or PDF file
            // For demo we're just showing a placeholder
            previewFrame.src = "about:blank";
            previewFrame.onload = function() {
                const content = `
                    <div style="padding: 50px; text-align: center;">
                        <h2 style="margin-bottom: 30px;">${title}</h2>
                        <div style="border: 1px solid #ddd; padding: 20px; width: 70%; margin: 0 auto; background: #f9f9f9; border-radius: 5px;">
                            <i class="fas fa-file-pdf" style="font-size: 48px; color: #d9534f; margin-bottom: 20px;"></i>
                            <p><strong>Kategori:</strong> ${doc ? doc.category : 'N/A'}</p>
                            <p><strong>Versi:</strong> ${version}.0</p>
                            <p>Ini adalah tampilan preview dokumen. Pada implementasi nyata, ini akan menampilkan dokumen PDF yang sebenarnya.</p>
                        </div>
                    </div>
                `;

                try {
                    previewFrame.contentDocument.open();
                    previewFrame.contentDocument.write(`
                        <html>
                            <head>
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
                                <style>
                                    body { font-family: Arial, sans-serif; }
                                </style>
                            </head>
                            <body>${content}</body>
                        </html>
                    `);
                    previewFrame.contentDocument.close();
                } catch (error) {
                    console.error('Error writing to iframe:', error);
                    previewFrame.src = `data:text/html,${encodeURIComponent(content)}`;
                }
            };
        })
        .catch(error => {
            console.error('Error loading document preview:', error);
            modalTitle.textContent = 'Error Loading Document';

            previewFrame.src = "about:blank";
            previewFrame.onload = function() {
                try {
                    previewFrame.contentDocument.open();
                    previewFrame.contentDocument.write(`
                        <html>
                            <head>
                                <style>
                                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                                    .error-icon { font-size: 48px; color: #d9534f; margin-bottom: 20px; }
                                </style>
                            </head>
                            <body>
                                <div>
                                    <div class="error-icon">⚠️</div>
                                    <h2>Error Loading Document</h2>
                                    <p>Terjadi kesalahan saat memuat dokumen. Silakan coba lagi nanti.</p>
                                    <p class="text-muted small">${error.message}</p>
                                </div>
                            </body>
                        </html>
                    `);
                    previewFrame.contentDocument.close();
                } catch (frameError) {
                    console.error('Error writing to iframe:', frameError);
                    previewFrame.src = `data:text/html,<html><body><div style="text-align:center;padding:50px;">
                        <h2>Error Loading Document</h2>
                        <p>Terjadi kesalahan saat memuat dokumen.</p>
                    </div></body></html>`;
                }
            };
        });

    modal.show();
}

// Show toast notification
function showToast(title, message, type = 'info') {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        // Create toast container if it doesn't exist
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '1050';
        document.body.appendChild(toastContainer);
    }

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

    try {
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });

        bsToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    } catch (error) {
        console.error('Error showing toast:', error);
        // Fallback if Bootstrap Toast is not available
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
}

// Fungsi untuk membuat token acak
function generateRandomToken(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(context, args);
        }, wait);
    };
}

// Submit new request
function submitNewRequest() {
    const userField = document.getElementById('requestUser');
    const departmentField = document.getElementById('requestDepartment');
    const documentField = document.getElementById('requestDocument');
    const reasonTypeField = document.getElementById('requestReason');
    const otherReasonField = document.getElementById('otherReason');
    const notesField = document.getElementById('requestNotes');

    if (!userField || !departmentField || !documentField || !reasonTypeField) {
        console.error('Required fields not found in the form');
        return;
    }

    const user = userField.value;
    const department = departmentField.value;
    const documentId = parseInt(documentField.value);
    const reasonType = reasonTypeField.value;
    const otherReason = otherReasonField ? otherReasonField.value : '';
    const notes = notesField ? notesField.value : '';

    // Validation
    if (!user || !department || !documentId || !reasonType) {
        alert('Mohon isi semua field yang wajib diisi.');
        return;
    }

    // Create reason text
    let reason = reasonType;
    if (reasonType === 'Lainnya' && otherReason) {
        reason = `Lainnya - ${otherReason}`;
    } else if (notes) {
        reason = `${reasonType} - ${notes}`;
    }

    // Buat FormData untuk dikirim ke server
    const formData = new FormData();
    formData.append('document_id', documentId);
    formData.append('user_name', user);
    formData.append('department', department);
    formData.append('reason', reason);
    formData.append('notes', notes);

    // Generate token untuk download
    formData.append('token', generateRandomToken(32));

    // Tambahkan CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    // Kirim request ke server (menggunakan endpoint yang sama dengan form request download)
    fetch('/download-request', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh data
            loadCounts();
            loadRequests('pending', 1);
            loadActivities();

            // Show notification
            showToast('Permintaan Baru', `Permintaan download dokumen dari ${user} telah ditambahkan.`, 'info');

            // Reset form dan tutup modal
            const form = document.getElementById('addRequestForm');
            if (form) {
                form.reset();
            }

            const otherReasonSection = document.getElementById('otherReasonSection');
            if (otherReasonSection) {
                otherReasonSection.style.display = 'none';
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('addRequestModal'));
            if (modal) modal.hide();
        } else {
            showToast('Error', data.message || 'Terjadi kesalahan saat menambah permintaan', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding request:', error);
        showToast('Error', 'Terjadi kesalahan saat menghubungi server', 'error');
    });
}

// Prepare modal for rejection
function prepareReject(requestId) {
    const rejectIdField = document.getElementById('reject-request-id');
    if (rejectIdField) {
        rejectIdField.value = requestId;
    }

    const reasonField = document.getElementById('rejectionReason');
    if (reasonField) {
        reasonField.value = '';
    }

    const noteField = document.getElementById('rejectionNote');
    if (noteField) {
        noteField.value = '';
    }

    const suggestCheck = document.getElementById('suggestAlternativeCheck');
    if (suggestCheck) {
        suggestCheck.checked = false;
    }

    const alternativeSection = document.getElementById('alternativeDocSection');
    if (alternativeSection) {
        alternativeSection.style.display = 'none';
    }

    const alternativeField = document.getElementById('alternativeDoc');
    if (alternativeField) {
        alternativeField.value = '';
    }
}

// Reject a request
function rejectRequest() {
    const requestIdField = document.getElementById('reject-request-id');
    if (!requestIdField) {
        console.error('Reject request ID field not found');
        return;
    }

    const requestId = requestIdField.value;
    const rejectionReason = document.getElementById('rejectionReason')?.value || '';
    const note = document.getElementById('rejectionNote')?.value || '';
    const suggestAlternative = document.getElementById('suggestAlternativeCheck')?.checked || false;
    const alternativeDoc = suggestAlternative ? document.getElementById('alternativeDoc')?.value || null : null;

    // Validation
    if (!requestId || !rejectionReason || !note) {
        alert('Mohon isi semua field yang wajib diisi.');
        return;
    }

    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('rejection_reason', rejectionReason);
    formData.append('note', note);

    if (suggestAlternative && alternativeDoc) {
        formData.append('alternative_doc', alternativeDoc);
    }

    // Tambahkan CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    fetch('/api/reject', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh data
            loadCounts();
            loadRequests('pending', currentPage);
            loadRequests('rejected', 1);
            loadActivities();

            // Show notification
            showToast('Permintaan Ditolak', 'Permintaan telah ditolak dan notifikasi telah dikirim ke pemohon.', 'error');
        } else {
            showToast('Error', data.message || 'Terjadi kesalahan saat menolak permintaan', 'error');
        }
    })
    .catch(error => {
        console.error('Error rejecting request:', error);
        showToast('Error', 'Terjadi kesalahan saat menghubungi server', 'error');
    });

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
    if (modal) modal.hide();
}

// Make functions available globally for onclick handlers
window.previewDocument = previewDocument;
window.prepareApprove = prepareApprove;
window.prepareReject = prepareReject;
window.changePage = changePage;
window.approveRequest = approveRequest;
window.rejectRequest = rejectRequest;
window.submitNewRequest = submitNewRequest;
window.updateDebugInfo = updateDebugInfo;
