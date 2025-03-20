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

// Perbaikan untuk processRequestsData dan loadActivities

// 1. Fix untuk function processRequestsData
function processRequestsData(data, status, page, append) {
    // Cari container yang benar
    let containerSelectors = [
        `#${status}-requests-container`,  // Format utama
        `#${status}`,                     // Format kedua
        `.${status}-container`,           // Opsi lain
        `#tab-${status}`,                 // Mungkin format tab content
        `#${status}-tab-content`          // Format tab content lain
    ];

    // Cari container menggunakan multiple selectors
    let container = null;
    for (const selector of containerSelectors) {
        const found = document.querySelector(selector);
        if (found) {
            container = found;
            console.log(`Found container for ${status} using selector: ${selector}`);
            break;
        }
    }

    // Jika masih tidak menemukan container, coba buat container baru
    if (!container) {
        console.warn(`No predefined container found for status: ${status}. Creating new container.`);

        // Cari tab content parent
        const tabContent = document.querySelector('.tab-content') || document.querySelector('#requestTabsContent');

        if (tabContent) {
            // Buat container baru dalam tab content
            container = document.createElement('div');
            container.id = `${status}-requests-container`;
            container.className = `tab-pane fade ${status === 'pending' ? 'show active' : ''}`;
            container.setAttribute('role', 'tabpanel');
            container.setAttribute('aria-labelledby', `${status}-tab`);

            // Tambahkan ke tab content
            tabContent.appendChild(container);
            console.log(`Created new container for ${status} with ID: ${container.id}`);
        } else {
            // Fallback: tambahkan ke body atau element tertentu
            const fallbackParent = document.querySelector('.main-content') || document.querySelector('main') || document.body;

            container = document.createElement('div');
            container.id = `${status}-requests-container`;
            container.className = `requests-container ${status}-container`;

            fallbackParent.appendChild(container);
            console.log(`Created fallback container for ${status} with ID: ${container.id}`);
        }
    }

    // Gunakan container yang sudah ditemukan/dibuat
    if (!container) {
        console.error(`Still couldn't find or create container for status: ${status}`);
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

// 2. Fix untuk function loadActivities
function loadActivities() {
    makeAuthenticatedRequest('/api/activities', 'GET')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Cari atau buat container activity feed
            let activityFeed = document.getElementById('activity-feed');

            if (!activityFeed) {
                console.warn('Activity feed container not found, searching for alternatives...');

                // Coba cari container dengan kelas yang relevan
                activityFeed = document.querySelector('.activity-feed') ||
                               document.querySelector('.activities-container') ||
                               document.querySelector('.recent-activities');

                // Jika masih tidak ditemukan, buat container baru
                if (!activityFeed) {
                    console.warn('Creating new activity feed container');

                    // Cari parent container yang tepat, seperti sidebar atau panel
                    const sidebar = document.querySelector('.sidebar') ||
                                   document.querySelector('.side-panel') ||
                                   document.querySelector('.activities-panel');

                    // Jika sidebar ada, tambahkan di sana, jika tidak buat di tempat yang sesuai
                    if (sidebar) {
                        activityFeed = document.createElement('div');
                        activityFeed.id = 'activity-feed';
                        activityFeed.className = 'activity-feed';

                        // Tambahkan judul jika belum ada
                        if (!sidebar.querySelector('h5.activities-title')) {
                            const title = document.createElement('h5');
                            title.className = 'activities-title';
                            title.textContent = 'Aktivitas Terbaru';
                            sidebar.appendChild(title);
                        }

                        sidebar.appendChild(activityFeed);
                    } else {
                        // Fallback: cari main-content atau elemen utama lain
                        const mainContent = document.querySelector('.main-content') ||
                                          document.querySelector('main') ||
                                          document.querySelector('.content-wrapper');

                        if (mainContent) {
                            // Buat panel aktivitas
                            const activityPanel = document.createElement('div');
                            activityPanel.className = 'activities-panel card mt-4';

                            // Buat header
                            const header = document.createElement('div');
                            header.className = 'card-header';
                            header.innerHTML = '<h5 class="mb-0">Aktivitas Terbaru</h5>';

                            // Buat body
                            const body = document.createElement('div');
                            body.className = 'card-body p-0';

                            // Buat feed
                            activityFeed = document.createElement('div');
                            activityFeed.id = 'activity-feed';
                            activityFeed.className = 'activity-feed';

                            // Gabungkan semua
                            body.appendChild(activityFeed);
                            activityPanel.appendChild(header);
                            activityPanel.appendChild(body);

                            // Tambahkan ke mainContent
                            mainContent.appendChild(activityPanel);
                        } else {
                            console.error('Could not find suitable container for activity feed');
                            return;
                        }
                    }
                }
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
            <span><i class="fas fa-user me-1"></i> ${userName}</span>
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
    const sendFile = document.getElementById('sendFileCheck')?.checked || false; // New option
    const limitTime = document.getElementById('limitTimeCheck')?.checked || false;

    if (!requestId) {
        console.error('No request ID found');
        return;
    }

    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('note', note);
    formData.append('send_email', sendEmail ? '1' : '0');
    formData.append('send_file', sendFile ? '1' : '0'); // Add new parameter
    formData.append('limit_time', limitTime ? '1' : '0');

    // Tambahkan CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    // Show loading state on button
    const approveBtn = document.querySelector('#approveModal .btn-success');
    const originalText = approveBtn.textContent;
    approveBtn.disabled = true;
    approveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

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
            showToast('Permintaan Disetujui', 'Permintaan telah berhasil disetujui' +
                (sendEmail ? ' dan notifikasi telah dikirim ke pemohon' : '') +
                (sendEmail && sendFile ? ' beserta file yang diminta' : '') + '.', 'success');
        } else {
            showToast('Error', data.message || 'Terjadi kesalahan saat menyetujui permintaan', 'error');
        }

        // Reset button state
        approveBtn.disabled = false;
        approveBtn.textContent = originalText;
    })
    .catch(error => {
        console.error('Error approving request:', error);
        showToast('Error', 'Terjadi kesalahan saat menghubungi server', 'error');

        // Reset button state
        approveBtn.disabled = false;
        approveBtn.textContent = originalText;
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

// Tambahkan kode berikut di akhir file approval-requests.js

/**
 * Fungsi untuk mendapatkan token JWT dari cookie
 * @returns {string|null} JWT token atau null jika tidak ditemukan
 */
function getJwtToken() {
    return getCookie('refresh_token');
}

/**
 * Fungsi yang lebih robust untuk mendapatkan cookie berdasarkan nama
 * @param {string} name Nama cookie
 * @returns {string|null} Nilai cookie atau null jika tidak ditemukan
 */
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

/**
 * Fungsi untuk membuat request dengan token JWT
 * @param {string} url URL endpoint API
 * @param {string} method HTTP method (GET, POST, etc)
 * @param {FormData|Object} data Data yang akan dikirim
 * @returns {Promise} Promise dari fetch
 */
function makeAuthenticatedRequest(url, method, data) {
    // Dapatkan token JWT dari cookie
    const token = getJwtToken();

    // Buat options untuk fetch
    const options = {
        method: method,
        credentials: 'same-origin', // Kirim cookies
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    // Tambahkan data jika ada
    if (data) {
        if (data instanceof FormData) {
            options.body = data;
        } else {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }
    }

    // Tambahkan Authorization header jika token ada
    if (token) {
        options.headers['Authorization'] = `Bearer ${token}`;
    }

    // Tambahkan CSRF token jika ada
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        options.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }

    // Buat request
    return fetch(url, options)
        .then(response => {
            // Log status response untuk debugging
            console.log('Response status:', response.status);

            // Jika unauthorized dan kita punya token, redirect ke login SSO
            if (response.status === 401 && token) {
                // Redirect ke SSO login
                const currentUrl = window.location.href;
                const encodedUrl = btoa(currentUrl);
                const loginUrl = (window.appConfig && window.appConfig.ssoUrl)
                    ? window.appConfig.ssoUrl + '/login'
                    : '/login';
                const redirectUrl = `${loginUrl}?redirect=${encodedUrl}`;

                // Tampilkan pesan
                showToast('Sesi Berakhir', 'Sesi Anda telah berakhir. Akan dialihkan ke halaman login.', 'warning');

                // Redirect setelah delay singkat
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 2000);

                throw new Error('Session expired');
            }

            return response;
        });
}

// Perbaikan fungsi rejectRequest untuk menangani case khusus 500 tapi sukses update database
function rejectRequest() {
    const requestIdField = document.getElementById('reject-request-id');
    if (!requestIdField) {
        console.error('Reject request ID field not found');
        showToast('Error', 'Field untuk ID permintaan tidak ditemukan', 'error');
        return;
    }

    const requestId = requestIdField.value;
    const rejectionReason = document.getElementById('rejectionReason')?.value || '';
    const note = document.getElementById('rejectionNote')?.value || '';
    const suggestAlternative = document.getElementById('suggestAlternativeCheck')?.checked || false;
    const alternativeDoc = suggestAlternative ? document.getElementById('alternativeDoc')?.value || null : null;

    // Validation
    if (!requestId || !rejectionReason) {
        showToast('Validasi Gagal', 'Mohon isi alasan penolakan yang wajib diisi.', 'error');
        return;
    }

    // Show loading state on button
    const rejectBtn = document.querySelector('#rejectRequestBtn');
    const originalText = rejectBtn.textContent;
    rejectBtn.disabled = true;
    rejectBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

    // Prepare data
    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('rejection_reason', rejectionReason);

    // Only append non-empty note
    if (note) {
        formData.append('note', note);
    }

    // Only append alternative doc if option is checked and value exists
    if (suggestAlternative && alternativeDoc) {
        formData.append('alternative_doc', alternativeDoc);
    }

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }

    // Debug: Log request payload
    console.log('Rejecting request:', requestId);
    console.log('Rejection reason:', rejectionReason);

    // Use the authenticated request function
    makeAuthenticatedRequest('/api/reject', 'POST', formData)
        .then(response => {
            // Log response status
            console.log('Response status:', response.status);

            // Spesial case: Jika status 500 tapi data mungkin sudah tersimpan di database,
            // kita anggap operasi berhasil dan refresh data untuk mengkonfirmasi
            if (response.status === 500) {
                // Refresh data untuk melihat jika status berubah di database
                loadCounts();
                const pendingItems = document.querySelectorAll(`#pending-requests-container .request-item[data-id="${requestId}"]`);

                if (pendingItems.length === 0) {
                    // Jika item tidak lagi ada di pending, kemungkinan sudah berhasil diupdate
                    // meskipun API mengembalikan error 500
                    loadRequests('pending', currentPage);
                    loadRequests('rejected', 1);
                    loadActivities();

                    // Show notification with warning
                    showToast('Permintaan Mungkin Ditolak', 'Server mengembalikan error, tapi permintaan mungkin sudah diproses. Data telah direfresh.', 'warning');

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                    if (modal) modal.hide();

                    // Reset button state
                    rejectBtn.disabled = false;
                    rejectBtn.innerHTML = originalText;
                    return;
                }
            }

            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        throw new Error(json.message || `Server error: ${response.status}`);
                    } catch (e) {
                        throw new Error(text || `Server error: ${response.status}`);
                    }
                });
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Refresh data
                loadCounts();
                loadRequests('pending', currentPage);
                loadRequests('rejected', 1);
                loadActivities();

                // Show notification
                showToast('Permintaan Ditolak', 'Permintaan telah ditolak dan notifikasi telah dikirim ke pemohon.', 'error');

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                if (modal) modal.hide();
            } else {
                showToast('Error', data.message || 'Terjadi kesalahan saat menolak permintaan', 'error');
            }
        })
        .catch(error => {
            console.error('Error rejecting request:', error);

            // Don't show error if it's due to session expiry (already handled)
            if (error.message !== 'Session expired') {
                // Display more helpful error message
                let errorMessage = 'Terjadi kesalahan saat menghubungi server';
                if (error.message) {
                    errorMessage += `: ${error.message}`;
                }

                // Special handling for server error that might have succeeded
                if (error.message && error.message.includes('Terjadi kesalahan saat menolak permintaan')) {
                    // Refresh data to check if it was actually successful
                    loadCounts();
                    loadRequests('pending', currentPage);
                    loadRequests('rejected', 1);
                    loadActivities();

                    errorMessage += ". Data telah direfresh untuk mengecek status permintaan.";
                }

                showToast('Error', errorMessage, 'error');
            }
        })
        .finally(() => {
            // Always reset button state
            rejectBtn.disabled = false;
            rejectBtn.innerHTML = originalText;
        });
}

// Perbaiki juga fungsi approveRequest menggunakan makeAuthenticatedRequest
function approveRequest() {
    const requestIdField = document.getElementById('approve-request-id');
    if (!requestIdField) {
        console.error('Request ID field not found');
        return;
    }

    const requestId = requestIdField.value;
    const note = document.getElementById('approvalNote')?.value || '';
    const sendEmail = document.getElementById('sendEmailCheck')?.checked || false;
    const sendFile = document.getElementById('sendFileCheck')?.checked || false;
    const limitTime = document.getElementById('limitTimeCheck')?.checked || false;

    if (!requestId) {
        console.error('No request ID found');
        return;
    }

    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('note', note);
    formData.append('send_email', sendEmail ? '1' : '0');
    formData.append('send_file', sendFile ? '1' : '0');
    formData.append('limit_time', limitTime ? '1' : '0');

    // Show loading state on button
    const approveBtn = document.querySelector('#approveModal .btn-success');
    const originalText = approveBtn.textContent;
    approveBtn.disabled = true;
    approveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

    // Use the authenticated request function
    makeAuthenticatedRequest('/api/approve', 'POST', formData)
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        throw new Error(json.message || `Server error: ${response.status}`);
                    } catch (e) {
                        throw new Error(text || `Server error: ${response.status}`);
                    }
                });
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Refresh data
                loadCounts();
                loadRequests('pending', currentPage);
                loadRequests('approved', 1);
                loadActivities();

                // Show notification
                showToast('Permintaan Disetujui', 'Permintaan telah berhasil disetujui' +
                    (sendEmail ? ' dan notifikasi telah dikirim ke pemohon' : '') +
                    (sendEmail && sendFile ? ' beserta file yang diminta' : '') + '.', 'success');

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal'));
                if (modal) modal.hide();
            } else {
                showToast('Error', data.message || 'Terjadi kesalahan saat menyetujui permintaan', 'error');
            }
        })
        .catch(error => {
            console.error('Error approving request:', error);

            // Don't show error if it's due to session expiry (already handled)
            if (error.message !== 'Session expired') {
                // Display more helpful error message
                let errorMessage = 'Terjadi kesalahan saat menghubungi server';
                if (error.message) {
                    errorMessage += `: ${error.message}`;
                }

                showToast('Error', errorMessage, 'error');
            }
        })
        .finally(() => {
            // Always reset button state
            approveBtn.disabled = false;
            approveBtn.textContent = originalText;
        });
}

// Update loadCounts untuk menggunakan makeAuthenticatedRequest
function loadCounts() {
    makeAuthenticatedRequest('/api/counts', 'GET')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
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

// Update loadRequests untuk menggunakan makeAuthenticatedRequest
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
    makeAuthenticatedRequest(`/api/requests?${params.toString()}`, 'GET')
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

// Update loadActivities untuk menggunakan makeAuthenticatedRequest
function loadActivities() {
    makeAuthenticatedRequest('/api/activities', 'GET')
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
// Fungsi untuk menyiapkan modal reject
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

    // Fix Bootstrap modal focus management
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        // Remove aria-hidden if it's been incorrectly set
        rejectModal.addEventListener('shown.bs.modal', function () {
            if (rejectModal.getAttribute('aria-hidden') === 'true') {
                rejectModal.setAttribute('aria-hidden', 'false');
            }

            // Focus pada field alasan
            if (reasonField) {
                setTimeout(() => reasonField.focus(), 150);
            }
        }, { once: true });
    }
}

// Fungsi untuk menyiapkan modal approve
function prepareApprove(requestId) {
    const idField = document.getElementById('approve-request-id');
    if (idField) {
        idField.value = requestId;
    }

    const noteField = document.getElementById('approvalNote');
    if (noteField) {
        noteField.value = '';
        // Defer focus until modal is fully shown
        setTimeout(() => noteField.focus(), 150);
    }

    const emailCheck = document.getElementById('sendEmailCheck');
    if (emailCheck) {
        emailCheck.checked = true;
    }

    const sendFileCheck = document.getElementById('sendFileCheck');
    if (sendFileCheck) {
        sendFileCheck.checked = false;
    }

    const limitTimeCheck = document.getElementById('limitTimeCheck');
    if (limitTimeCheck) {
        limitTimeCheck.checked = false;
    }

    // Fix Bootstrap modal focus management
    const approveModal = document.getElementById('approveModal');
    if (approveModal) {
        // Remove aria-hidden if it's been incorrectly set
        approveModal.addEventListener('shown.bs.modal', function () {
            if (approveModal.getAttribute('aria-hidden') === 'true') {
                approveModal.setAttribute('aria-hidden', 'false');
            }
        }, { once: true });
    }
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
window.applyFilters = applyFilters;
