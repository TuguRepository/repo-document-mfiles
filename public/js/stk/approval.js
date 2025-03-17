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
    const sendFile = document.getElementById('sendFileCheck')?.checked || false; // Opsi baru
    const limitTime = document.getElementById('limitTimeCheck')?.checked || false;

    if (!requestId) {
        console.error('No request ID found');
        return;
    }

    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('note', note);
    formData.append('send_email', sendEmail ? '1' : '0');
    formData.append('send_file', sendFile ? '1' : '0'); // Parameter baru
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
            let message = 'Permintaan telah berhasil disetujui';
            if (sendEmail) {
                message += ' dan notifikasi telah dikirim ke pemohon';
                if (sendFile) {
                    message += ' beserta file dokumen yang diminta';
                }
            }
            message += '.';

            showToast('Permintaan Disetujui', message, 'success');
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

// Function untuk menampilkan modal approve
function showApproveModal(requestId) {
    // Reset form
    document.getElementById('approveForm').reset();

    // Set request ID
    document.getElementById('approve-request-id').value = requestId;

    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}
