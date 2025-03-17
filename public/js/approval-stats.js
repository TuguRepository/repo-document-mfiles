// public/js/approval-stats.js

/**
 * Approval Statistics and Counter Management
 *
 * This script handles the real-time updating of request counters
 * and statistics for the document approval system.
 */

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up counters
    initializeCounters();

    // Set up Echo listeners for real-time updates
    setupEchoListeners();

    // Initialize charts if we're on the dashboard
    if (document.getElementById('request-trend-chart')) {
        initializeCharts();
    }

    // Set up tab switching behavior
    initializeTabs();
});

/**
 * Initialize counters and set up refresh intervals
 */
function initializeCounters() {
    // Update all counters initially
    updateAllCounters();

    // Set up refresh interval (every 30 seconds)
    setInterval(updateAllCounters, 30000);

    // Listen for refresh button click
    const refreshButton = document.getElementById('refresh-stats');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            updateAllCounters();

            // Add spinning animation to refresh icon
            const icon = this.querySelector('svg');
            if (icon) {
                icon.classList.add('animate-spin');

                // Remove animation after 1 second
                setTimeout(() => {
                    icon.classList.remove('animate-spin');
                    showNotification('Data berhasil diperbarui', 'success');
                }, 1000);
            }
        });
    }
}

/**
 * Set up Echo listeners for real-time updates
 */
function setupEchoListeners() {
    // Check if Echo is defined (Laravel Echo)
    if (typeof window.Echo !== 'undefined') {
        // Listen for new download requests
        window.Echo.private('stk.approvals')
            .listen('.download.request.new', (e) => {
                // Update the pending count when a new request comes in
                updateStatusCount('pending');

                // Show notification
                showNotification('Permintaan download baru telah diterima', 'info');

                // Reload the pending requests list if we're on the dashboard
                if (document.getElementById('pending-tab') && !document.getElementById('pending-tab').classList.contains('hidden')) {
                    reloadRequestList('pending');
                }
            });

        // Listen for request status changes
        window.Echo.private('stk.approvals')
            .listen('.download.request.status.changed', (e) => {
                // Update all counters when a status changes
                updateAllCounters();

                // Show notification
                const statusText = e.status === 'approved' ? 'disetujui' : 'ditolak';
                const message = `Permintaan untuk dokumen "${e.document_title}" telah ${statusText}`;
                showNotification(message, 'success');

                // Reload the relevant request lists if we're on the dashboard
                if (document.getElementById('pending-tab')) {
                    reloadRequestList('pending');
                    reloadRequestList(e.status); // Either 'approved' or 'rejected'
                }
            });
    }
}

/**
 * Initialize charts
 */
function initializeCharts() {
    fetch('/stk/approval/dashboard-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTrendChart(data.weekly_trend);
                renderApprovalRateChart(data.approval_stats);
            }
        })
        .catch(error => console.error('Error loading chart data:', error));
}

/**
 * Initialize tab switching
 */
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-tab-target');

            // Deactivate all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300', 'dark:hover:border-gray-600');
            });

            tabPanes.forEach(pane => {
                pane.classList.add('hidden');
            });

            // Activate target tab
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300', 'dark:hover:border-gray-600');
            this.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');

            document.getElementById(`${target}-tab`).classList.remove('hidden');

            // Update the specific count
            updateStatusCount(target);
        });
    });
}

/**
 * Update all counters
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
 * Reload a tab's request list via AJAX
 */
function reloadRequestList(status) {
    const tabContentElement = document.getElementById(`${status}-tab`);

    if (tabContentElement) {
        // Add loading state
        tabContentElement.classList.add('opacity-50');

        // Get the current URL and add refresh parameter
        const url = new URL(window.location.href);
        url.searchParams.set('refresh', status);

        // Fetch updated content
        fetch(url.toString())
            .then(response => response.text())
            .then(html => {
                // Create a temporary element to parse the HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Find the new tab content
                const newTabContent = doc.getElementById(`${status}-tab`);

                if (newTabContent) {
                    // Replace the content
                    tabContentElement.innerHTML = newTabContent.innerHTML;
                }

                // Remove loading state
                tabContentElement.classList.remove('opacity-50');
            })
            .catch(error => {
                console.error(`Error reloading ${status} list:`, error);
                tabContentElement.classList.remove('opacity-50');
            });
    }
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

    // Set appropriate styles based on type
    let bgColor, iconHtml;
    if (type === 'success') {
        bgColor = 'bg-green-100 border-green-400 text-green-700';
        iconHtml = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
    } else if (type === 'error') {
        bgColor = 'bg-red-100 border-red-400 text-red-700';
        iconHtml = '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
    } else {
        bgColor = 'bg-blue-100 border-blue-400 text-blue-700';
        iconHtml = '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
    }

    notification.className = `notification mb-2 p-3 rounded shadow-md border ${bgColor} transition-all duration-300 transform translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0 mr-2">
                ${iconHtml}
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
        notification.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

/**
 * Render trend chart
 */
function renderTrendChart(data) {
    const ctx = document.getElementById('request-trend-chart');
    if (!ctx) return;

    const context = ctx.getContext('2d');

    // Format dates for display
    const labels = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });

    // Extract data series
    const pendingData = data.map(item => item.pending);
    const approvedData = data.map(item => item.approved);
    const rejectedData = data.map(item => item.rejected);

    new Chart(context, {
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
    const ctx = document.getElementById('approval-rate-chart');
    if (!ctx) return;

    const context = ctx.getContext('2d');

    new Chart(context, {
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
