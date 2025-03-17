/**
 * Document Preview System for Sistem Tata Kelola - Tugu Insurance
 * This script handles document preview functionality in a modal with download request feature
 */

// Execute after DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up document preview in modal
    setupDocumentPreview();
});

// Function to set up preview document with request download functionality
function setupDocumentPreview() {
    // Find modal elements
    const previewModal = document.getElementById('documentPreviewModal');
    if (!previewModal) {
        console.error('Document preview modal not found');
        return;
    }

    const previewFrame = document.getElementById('documentPreviewFrame');
    const loadingIndicator = document.getElementById('documentLoadingIndicator');
    const previewContainer = document.getElementById('documentPreviewContainer');
    const errorContainer = document.getElementById('documentPreviewError');
    const requestDownloadBtn = document.getElementById('requestDownloadBtn');
    const downloadRequestForm = document.getElementById('downloadRequestForm');
    const cancelRequestBtn = document.getElementById('cancelRequestBtn');
    const requestReason = document.getElementById('requestReason');
    const otherReasonContainer = document.getElementById('otherReasonContainer');
    const formRequestDownload = document.getElementById('formRequestDownload');
    const agreeTerms = document.getElementById('agreeTerms');

    // Initialize variables to store current document
    let currentDocumentId = null;
    let currentDocumentVersion = null;

    // Event listener for request download button
    if (requestDownloadBtn) {
        requestDownloadBtn.addEventListener('click', function() {
            // Hide preview container
            previewContainer.classList.add('d-none');
            // Show download request form
            downloadRequestForm.classList.remove('d-none');

            // Set hidden field values
            document.getElementById('requestDocId').value = currentDocumentId;
            document.getElementById('requestDocVersion').value = currentDocumentVersion;
        });
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

    // Event listener for reason select change
    if (requestReason) {
        requestReason.addEventListener('change', function() {
            if (this.value === 'other') {
                otherReasonContainer.style.display = 'block';
                document.getElementById('otherReason').setAttribute('required', 'required');
            } else {
                otherReasonContainer.style.display = 'none';
                document.getElementById('otherReason').removeAttribute('required');
            }
        });
    }

    // Event listener for submit form request
    if (formRequestDownload) {
        formRequestDownload.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            // Check if terms are agreed
            if (!agreeTerms.checked) {
                alert('Anda harus menyetujui syarat dan ketentuan');
                return;
            }

            // Create FormData to collect form data
            const formData = new FormData(this);

            // Generate random reference number
            const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);

            // Display reference number in success modal
            document.getElementById('requestReferenceNumber').textContent = refNumber;

            // Simulate sending request to server
            // In actual implementation, use fetch to send data to server
            console.log('Sending download request:', Object.fromEntries(formData));

            // Submit form data to server via fetch API
            fetch('/api/stk/request-download', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);
                // Optional: Update reference number from server response
                if (data.reference_number) {
                    document.getElementById('requestReferenceNumber').textContent = data.reference_number;
                }
            })
            .catch(error => {
                console.error('Error sending request:', error);
                // Continue showing success modal even on error for better UX
            })
            .finally(() => {
                // Close preview modal
                const previewModalInstance = bootstrap.Modal.getInstance(previewModal);
                if (previewModalInstance) {
                    previewModalInstance.hide();
                }

                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('requestSuccessModal'));
                successModal.show();

                // Reset form
                this.reset();
                downloadRequestForm.classList.add('d-none');
                previewContainer.classList.remove('d-none');
                otherReasonContainer.style.display = 'none';
            });
        });
    }

    // Function to display document preview in modal
    window.viewDocumentInModal = function(id, version = 'latest') {
        // Store current document ID and version
        currentDocumentId = id;
        currentDocumentVersion = version;

        // Reset display
        loadingIndicator.classList.remove('d-none');
        previewContainer.classList.add('d-none');
        errorContainer.classList.add('d-none');
        downloadRequestForm.classList.add('d-none');

        // Update modal title
        document.getElementById('documentPreviewModalLabel').textContent = 'Memuat Dokumen...';

        // Show modal
        const modal = new bootstrap.Modal(previewModal);
        modal.show();

        // Prepare URL for iframe
        const previewUrl = `/stk/preview/${id}${version ? '/' + version : ''}`;

        // Try to get document info
        fetch(`/api/stk/document-info/${id}${version ? '/' + version : ''}`, {
            headers: {
                'Accept': 'application/json',
                'X-Authentication': sessionStorage.getItem('mfiles_auth_token') || '',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.document) {
                document.getElementById('documentPreviewModalLabel').textContent = data.document.title || 'Preview Dokumen';
            }
        })
        .catch(error => console.error('Error fetching document info:', error));

        // Try to load document in iframe
        previewFrame.onload = function() {
            loadingIndicator.classList.add('d-none');
            previewContainer.classList.remove('d-none');

            // Check if iframe loaded valid content
            try {
                // Try to access contentDocument (will fail if CORS error)
                const frameContent = previewFrame.contentDocument || previewFrame.contentWindow.document;

                // If document exists but content is error JSON
                const frameText = frameContent.body.textContent;
                if (frameText.includes('"success":false') || frameText.includes('error')) {
                    try {
                        const errorData = JSON.parse(frameText);
                        showErrorMessage(errorData.message || 'Terjadi kesalahan saat memuat dokumen');
                    } catch (e) {
                        // If not valid JSON, display content as is
                        if (frameText.trim().length > 0) {
                            // Has text content, may be readable
                        } else {
                            showErrorMessage('Konten dokumen tidak dapat dimuat');
                        }
                    }
                }

                // Add style to prevent download/print
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
                // CORS error usually means PDF loaded correctly
                console.log('Cross-origin frame access - expected for PDFs');
            }
        };

        // Handler for iframe loading error
        previewFrame.onerror = function() {
            showErrorMessage('Gagal memuat dokumen');
        };

        // Set iframe src to load document
        previewFrame.src = previewUrl;

        // Function to display error message
        function showErrorMessage(message) {
            loadingIndicator.classList.add('d-none');
            previewContainer.classList.add('d-none');
            errorContainer.classList.remove('d-none');
            document.getElementById('errorMessage').textContent = message;

            // Set retry button
            document.getElementById('retryLoadButton').onclick = function(e) {
                e.preventDefault();
                previewFrame.src = previewUrl;
                loadingIndicator.classList.remove('d-none');
                errorContainer.classList.add('d-none');
            };
        }
    };

    // Override standard previewDocument function
    window.previewDocument = function(id, version) {
        // Use modal for preview
        viewDocumentInModal(id, version);
        return false; // Prevent default navigation
    };

    // Process all document cards to open in modal
    handleDocumentCards();

    // Add mutation observer for dynamically added cards
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                setTimeout(handleDocumentCards, 100);
            }
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}

// Process document cards to show in modal
function handleDocumentCards() {
    // Target all document cards and cards
    const cards = document.querySelectorAll('.document-card, .card[data-id]');

    cards.forEach(card => {
        // Skip already processed cards
        if (card.hasAttribute('data-modal-handler')) {
            return;
        }

        // Mark as processed
        card.setAttribute('data-modal-handler', 'true');
        card.style.cursor = 'pointer';

        // Get document ID from various sources
        let docId = card.getAttribute('data-id');
        let docVersion = card.getAttribute('data-version') || 'latest';

        // Check onclick attribute
        const onclickAttr = card.getAttribute('onclick');
        if (!docId && onclickAttr && onclickAttr.includes('previewDocument')) {
            const match = onclickAttr.match(/previewDocument\((\d+)(?:,\s*['"]?([^'"\)]+)['"]?)?\)/);
            if (match) {
                docId = match[1];
                docVersion = match[2] || 'latest';

                // Replace onclick with our modal function
                card.setAttribute('onclick', `event.preventDefault(); event.stopPropagation(); viewDocumentInModal(${docId}, '${docVersion}'); return false;`);
            }
        }

        // Add click event listener if no onclick exists
        if (!onclickAttr && docId) {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                viewDocumentInModal(docId, docVersion);
            });
        }
    });

    // Also handle view buttons directly
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
            // Add click event listener
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                viewDocumentInModal(id, version);
            });
        }
    });
}
