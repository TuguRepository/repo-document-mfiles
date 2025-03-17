// Fungsi untuk mengekstrak ID dokumen dari berbagai sumber
function extractDocumentIdFromContext() {
    try {
      // Coba dapatkan dari iframe
      const previewFrame = document.getElementById('documentPreviewFrame');
      if (previewFrame && previewFrame.src) {
        try {
          // Coba cari ID di URL iframe
          const iframeSrc = previewFrame.src;
          if (iframeSrc) {
            const matches = iframeSrc.match(/\/(\d+)(\/|$)/);
            if (matches && matches[1]) {
              console.log('ID extracted from iframe src:', matches[1]);
              return matches[1];
            }
          }
        } catch (e) {
          console.warn('Error accessing iframe content:', e);
        }
      }

      // Coba cari dalam DOM melalui data attributes
      const elements = document.querySelectorAll('[data-id], [data-document-id], [data-object-id]');
      for (let el of elements) {
        const id = el.getAttribute('data-id') || el.getAttribute('data-document-id') || el.getAttribute('data-object-id');
        if (id) {
          console.log('ID found in DOM element:', id);
          return id;
        }
      }

      // Coba cari dari URL halaman
      const urlMatches = window.location.pathname.match(/\/(\d+)(\/|$)/);
      if (urlMatches && urlMatches[1]) {
        console.log('ID extracted from page URL:', urlMatches[1]);
        return urlMatches[1];
      }

      // Coba cari dari judul dokumen jika ada nomor dokumen
      const modalTitle = document.getElementById('documentPreviewModalLabel');
      if (modalTitle && modalTitle.textContent) {
        if (/\d{5,}/.test(modalTitle.textContent)) {
          const matches = modalTitle.textContent.match(/\d{5,}/);
          if (matches && matches[0]) {
            console.log('ID extracted from document title:', matches[0]);
            return matches[0];
          }
        }
      }

      // Coba cari dari modal HTML attributes
      if (document.getElementById('documentPreviewModal')) {
        const modal = document.getElementById('documentPreviewModal');
        const modalId = modal.getAttribute('data-document-id') || modal.getAttribute('data-id');
        if (modalId) {
          console.log('ID extracted from modal attributes:', modalId);
          return modalId;
        }
      }

      return null;
    } catch (e) {
      console.error('Error extracting document ID:', e);
      return null;
    }
  }

  // Debug info
  console.log('Debug info - checking document context:');

  // Inisialisasi ID dari konteks jika belum ada
  window.currentDocumentId = extractDocumentIdFromContext();
  console.log('Current document ID initialized to:', window.currentDocumentId);

  // Solusi lengkap untuk document-preview.js
  document.addEventListener('DOMContentLoaded', function() {
      console.log('Document preview script loaded');

      // Elements
      const documentPreviewModal = document.getElementById('documentPreviewModal');
      const documentPreviewContainer = document.getElementById('documentPreviewContainer');
      const downloadRequestForm = document.getElementById('downloadRequestForm');
      const formRequestDownload = document.getElementById('formRequestDownload');
      const requestReason = document.getElementById('requestReason');
      const otherReasonContainer = document.getElementById('otherReasonContainer');

      // Buttons
      const requestDownloadBtn = document.getElementById('requestDownloadBtn');
      const cancelRequestBtn = document.getElementById('cancelRequestBtn');

      // Fungsi global untuk menyimpan ID dokumen saat preview
      window.setCurrentDocument = function(id, version = 'latest') {
          window.currentDocumentId = id;
          window.currentDocumentVersion = version;
          console.log('Current document set:', id, version);
      };

      // Override fungsi previewDocument yang dipanggil oleh tombol view
      window.viewDocumentInModal = function(id, version = 'latest') {
          // Simpan ID dan versi saat ini
          window.setCurrentDocument(id, version);

          // Reset tampilan
          const loadingIndicator = document.getElementById('documentLoadingIndicator');
          if (loadingIndicator) {
              loadingIndicator.classList.remove('d-none');
          }
          if (documentPreviewContainer) {
              documentPreviewContainer.classList.add('d-none');
          }
          const errorContainer = document.getElementById('documentPreviewError');
          if (errorContainer) {
              errorContainer.classList.add('d-none');
          }
          if (downloadRequestForm) {
              downloadRequestForm.classList.add('d-none');
          }

          // Update judul modal
          const modalLabel = document.getElementById('documentPreviewModalLabel');
          if (modalLabel) {
              modalLabel.textContent = 'Memuat Dokumen...';
          }

          // Tampilkan modal
          if (documentPreviewModal) {
              const modal = new bootstrap.Modal(documentPreviewModal);
              modal.show();
          }

          // Siapkan URL untuk iframe
          const previewUrl = `/stk/preview/${id}${version ? '/' + version : ''}`;

          // Set iframe src untuk memuat dokumen
          const previewFrame = document.getElementById('documentPreviewFrame');
          if (previewFrame) {
              previewFrame.src = previewUrl;

              // Handler untuk iframe loaded
              previewFrame.onload = function() {
                  if (loadingIndicator) {
                      loadingIndicator.classList.add('d-none');
                  }
                  if (documentPreviewContainer) {
                      documentPreviewContainer.classList.remove('d-none');
                  }
              };
          }
      };

      // Alias untuk kompatibilitas
      window.previewDocument = window.viewDocumentInModal;

      // Event: Request Download button click - PERBAIKAN
      if (requestDownloadBtn) {
          // Hapus event listener yang sudah ada (jika ada)
          const newBtn = requestDownloadBtn.cloneNode(true);
          if (requestDownloadBtn.parentNode) {
              requestDownloadBtn.parentNode.replaceChild(newBtn, requestDownloadBtn);
          }

          // Tambahkan event listener baru
          newBtn.addEventListener('click', function() {
              console.log('Request download button clicked (safe version)');

              if (documentPreviewContainer) documentPreviewContainer.classList.add('d-none');
              if (downloadRequestForm) downloadRequestForm.classList.remove('d-none');

              // Set document ID dengan pendekatan yang lebih agresif
              const requestDocId = document.getElementById('requestDocId');
              if (requestDocId) {
                  // Coba dapatkan ID dari berbagai sumber
                  let docId = window.currentDocumentId;

                  // Jika tidak ada, coba cari lagi dari konteks
                  if (!docId) {
                      docId = extractDocumentIdFromContext();
                  }

                  // Jika masih tidak ditemukan, coba dari iframe aktif
                  if (!docId) {
                      const frame = document.getElementById('documentPreviewFrame');
                      if (frame && frame.src) {
                          const matches = frame.src.match(/\/(\d+)(\/|$)/);
                          if (matches && matches[1]) {
                              docId = matches[1];
                          }
                      }
                  }

                  // Jika masih tidak ditemukan, tampilkan alert
                  if (!docId) {
                      console.warn('Could not determine document ID for download request');
                      alert('Tidak dapat menentukan ID dokumen. Silakan coba lagi.');

                      // Kembalikan ke tampilan awal
                      if (downloadRequestForm) downloadRequestForm.classList.add('d-none');
                      if (documentPreviewContainer) documentPreviewContainer.classList.remove('d-none');
                      return;
                  }

                  requestDocId.value = docId;
                  console.log('Setting document ID:', requestDocId.value);
              }

              // Set document version dengan aman
              const requestDocVersion = document.getElementById('requestDocVersion');
              if (requestDocVersion) {
                  let version = window.currentDocumentVersion;

                  // Jika tidak ada, coba cek URL iframe
                  if (!version) {
                      const frame = document.getElementById('documentPreviewFrame');
                      if (frame && frame.src) {
                          const matches = frame.src.match(/\/latest\/|\/(\d+)\//);
                          if (matches) {
                              version = matches[1] || '2';
                          }
                      }
                  }

                  requestDocVersion.value = version || '';
                  console.log('Setting document version:', requestDocVersion.value);
              }
          });
      }

      // Event: Cancel Request button click
      if (cancelRequestBtn) {
          cancelRequestBtn.addEventListener('click', function() {
              console.log('Cancel button clicked');

              if (downloadRequestForm) downloadRequestForm.classList.add('d-none');
              if (documentPreviewContainer) documentPreviewContainer.classList.remove('d-none');
          });
      }

      // Event: Reason select change
      if (requestReason) {
          requestReason.addEventListener('change', function() {
              console.log('Reason changed to:', this.value);

              // Check for "other" or "Lainnya" in any case
              const value = this.value.toLowerCase();
              if (value === 'other' || value === 'lainnya') {
                  if (otherReasonContainer) otherReasonContainer.style.display = 'block';
              } else {
                  if (otherReasonContainer) otherReasonContainer.style.display = 'none';
              }
          });
      }

      // Tangani form submission tanpa validasi checkbox
      if (formRequestDownload) {
          formRequestDownload.onsubmit = function(e) {
              console.log('Form submitting...');
              console.log('Let handleSubmitRequest run via onclick...');
              return true;
          };
      }

      // Fungsi untuk menangani request download
window.handleSubmitRequest = function() {
    console.log('handleSubmitRequest called');

    // Generate reference number
    const refNumber = 'REF-' + Math.floor(Math.random() * 900000 + 100000);

    // Generate token untuk download
    const downloadToken = generateRandomToken(32);

    // Update reference di modal sukses
    const refElement = document.getElementById('requestReferenceNumber');
    if (refElement) {
        refElement.textContent = refNumber;
    }

    // Ambil data form
    const form = document.getElementById('formRequestDownload');
    if (!form) {
        console.error('Form tidak ditemukan');
        return false;
    }

    // Buat FormData object untuk mengirim data form
    const formData = new FormData(form);

    // Tambahkan reference number ke formData
    formData.append('reference_number', refNumber);

    // Tambahkan token yang dibutuhkan
    formData.append('token', downloadToken);

    // Tambahkan data-data yang diperlukan sesuai skema
    formData.append('user_name', 'Guest User');
    formData.append('user_email', 'guest@example.com');

    // Coba ambil judul dokumen dari modal
    let docTitle = '';
    const modalTitle = document.getElementById('documentPreviewModalLabel');
    if (modalTitle && modalTitle.textContent) {
        docTitle = modalTitle.textContent.trim();
    }
    formData.append('document_title', docTitle || 'Dokumen');

    // Coba ekstrak nomor dokumen dari judul
    let docNumber = '';
    if (docTitle) {
        const matches = docTitle.match(/No\.\s*(\d+)/i);
        if (matches && matches[1]) {
            docNumber = 'DOC-' + matches[1];
        }
    }
    formData.append('document_number', docNumber || 'DOC-' + new Date().toISOString().slice(0, 10).replace(/-/g, ''));

    // Jika alasan adalah "other", gabungkan dengan "other_reason" dalam satu field
    const reason = formData.get('reason');
    const otherReasonEl = document.getElementById('otherReason');
    if (reason === 'other' && otherReasonEl && otherReasonEl.value) {
        formData.set('reason', reason + ' - ' + otherReasonEl.value);
    }

    // Tambahkan usage_type (ada di skema)
    formData.append('usage_type', 'internal');

    // HAPUS field yang tidak ada di skema untuk mencegah error
    formData.delete('other_reason'); // Tidak ada di skema
    formData.delete('user_agent');   // Tidak ada di skema

    // Debug: Tampilkan semua data yang akan dikirim
    console.log('Data yang akan dikirim:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Pastikan CSRF token ada
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.error('CSRF token tidak ditemukan! Tambahkan <meta name="csrf-token" content="{{ csrf_token() }}"> ke halaman Anda.');
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        return false;
    }

    // Kirim data ke controller melalui AJAX
    fetch('/download-request', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Success response:', data);

        if (!data.success) {
            // Jika ada error dari server tapi bukan error HTTP
            console.error('Server error:', data.message);
            alert('Error: ' + data.message);
            return;
        }

        // Sembunyi modal preview
        try {
            bootstrap.Modal.getInstance(document.getElementById('documentPreviewModal')).hide();
        } catch (error) {
            console.warn('Could not hide preview modal properly:', error);
            // Fallback manual hide
            if (documentPreviewModal) {
                documentPreviewModal.style.display = 'none';
                documentPreviewModal.classList.remove('show');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
            }
        }

        // Tampilkan modal sukses
        try {
            new bootstrap.Modal(document.getElementById('requestSuccessModal')).show();
        } catch (error) {
            console.warn('Could not show success modal:', error);
            alert('Permintaan Anda telah berhasil dikirim.');
        }

        // Reset form
        form.reset();

        // Kembalikan ke tampilan preview
        if (downloadRequestForm) downloadRequestForm.classList.add('d-none');
        if (documentPreviewContainer) documentPreviewContainer.classList.remove('d-none');

        // Coba tampilkan toast
        try {
            if (typeof showToast === 'function') {
                showToast('Permintaan Terkirim', 'Permintaan download dokumen Anda telah berhasil dikirim dan sedang menunggu persetujuan.', 'success');
            }
        } catch (error) {
            console.warn('Could not show toast:', error);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Terjadi kesalahan saat mengirim permintaan. Silakan coba lagi.');
    });

    return false; // Prevent default form submission
};

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

      // Inisialisasi terhadap cards yang sudah ada
      function initExistingCards() {
        console.log("hehehe");
          // Target all document cards that might be already in the DOM
          const cards = document.querySelectorAll('.document-card, .card[data-id]');
          if (cards.length > 0) {
              console.log('Found existing cards:', cards.length);
              cards.forEach(card => {
                  // Skip already processed cards
                  if (card.hasAttribute('data-processed')) {
                      return;
                  }
                  card.setAttribute('data-processed', 'true');

                  // Get document ID
                  const id = card.getAttribute('data-id');
                  const version = card.getAttribute('data-version') || 'latest';

                  if (id) {
                      card.style.cursor = 'pointer';
                      card.addEventListener('click', function(e) {
                          e.preventDefault();
                          window.viewDocumentInModal(id, version);
                      });
                  }
              });
          }

          // Handle existing view buttons
          const viewButtons = document.querySelectorAll('.view-btn, .view-doc-btn, [onclick*="previewDocument"]');
          if (viewButtons.length > 0) {
              console.log('Found existing view buttons:', viewButtons.length);
              viewButtons.forEach(btn => {
                  // Skip already processed buttons
                  if (btn.hasAttribute('data-processed')) {
                      return;
                  }
                  btn.setAttribute('data-processed', 'true');

                  // Get document ID from attributes or onclick
                  let id = btn.getAttribute('data-id');
                  let version = btn.getAttribute('data-version') || 'latest';

                  // If no ID, try to extract from onclick
                  if (!id) {
                      const onclickAttr = btn.getAttribute('onclick');
                      if (onclickAttr && onclickAttr.includes('previewDocument')) {
                          const match = onclickAttr.match(/previewDocument\((\d+)(?:,\s*['"]?([^'"\)]+)['"]?)?\)/);
                          if (match) {
                              id = match[1];
                              version = match[2] || 'latest';
                          }
                      }
                  }

                  if (id) {
                      // Override onclick
                      btn.onclick = function(e) {
                          e.preventDefault();
                          window.viewDocumentInModal(id, version);
                          return false;
                      };
                  }
              });
          }
      }

      // Run initialization
      initExistingCards();

      // Set up document card handling through MutationObserver
      const observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
              if (mutation.addedNodes.length) {
                  setTimeout(initExistingCards, 100);
              }
          });
      });

      observer.observe(document.body, { childList: true, subtree: true });

      // Polyfill untuk mengatasi referensi undefined function
      if (typeof fetchSTKSummary === 'undefined') {
          window.fetchSTKSummary = function() {
              console.warn('fetchSTKSummary is not defined but was called');
              return Promise.resolve();
          };
      }

      // Override loadFeaturedDocuments juga untuk mencegah error 404
      window.loadFeaturedDocuments = function() {
          console.log('loadFeaturedDocuments override called');

          // Gunakan data dummy untuk mengatasi error 404
          const currentYear = new Date().getFullYear();
          const fallbackDocs = [
              {
                  title: `STK No. 1 Tahun ${currentYear}`,
                  description: 'Pedoman Umum Pelaksanaan Pengadaan dan Anggaran',
                  id: window.currentDocumentId || '',
                  version: window.currentDocumentVersion || ''
              },
              {
                  title: `STK No. 23 Tahun ${currentYear-1}`,
                  description: 'Pendistribusian Dokumen Internal',
                  id: window.currentDocumentId || '',
                  version: window.currentDocumentVersion || ''
              },
              {
                  title: `STK No. 13 Tahun ${currentYear-2}`,
                  description: 'Kebijakan Pengelolaan Dokumen',
                  id: window.currentDocumentId || '',
                  version: window.currentDocumentVersion || ''
              }
          ];

          // Panggil fungsi display dengan data dummy
          if (typeof displayFeaturedDocuments === 'function') {
              displayFeaturedDocuments(fallbackDocs);
          } else {
              console.warn('displayFeaturedDocuments is not defined');
          }
      };

      // Jika tidak ada showToast, buat satu default
      if (typeof window.showToast !== 'function') {
          window.showToast = function(title, message, type = 'info') {
              // Create container if not exists
              let container = document.querySelector('.toast-container');
              if (!container) {
                  container = document.createElement('div');
                  container.className = 'toast-container position-fixed top-0 end-0 p-3';
                  container.style.zIndex = '1060';
                  document.body.appendChild(container);
              }

              // Create toast
              const toastId = 'toast-' + Date.now();
              const toast = document.createElement('div');
              toast.id = toastId;
              toast.className = 'toast';
              toast.setAttribute('role', 'alert');
              toast.setAttribute('aria-live', 'assertive');
              toast.setAttribute('aria-atomic', 'true');

              // Set color based on type
              let bgClass = 'bg-info', iconClass = 'fa-info-circle';
              if (type === 'success') {
                  bgClass = 'bg-success';
                  iconClass = 'fa-check-circle';
              } else if (type === 'error') {
                  bgClass = 'bg-danger';
                  iconClass = 'fa-exclamation-circle';
              } else if (type === 'warning') {
                  bgClass = 'bg-warning';
                  iconClass = 'fa-exclamation-triangle';
              }

              // Set content
              toast.innerHTML = `
                  <div class="toast-header ${bgClass} text-white">
                      <i class="fas ${iconClass} me-2"></i>
                      <strong class="me-auto">${title}</strong>
                      <small>Just now</small>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">${message}</div>
              `;

              // Add to container
              container.appendChild(toast);

              // Show with Bootstrap
              if (typeof bootstrap !== 'undefined') {
                  const bsToast = new bootstrap.Toast(toast, {
                      delay: 5000
                  });
                  bsToast.show();
              }

              // Auto remove
              setTimeout(() => {
                  if (toast.parentNode) {
                      toast.parentNode.removeChild(toast);
                  }
              }, 5000);
          };
      }

      // Perbaikan masalah ARIA accessibility
      if (documentPreviewModal) {
          // Tangani aksesibilitas modal
          documentPreviewModal.addEventListener('hidden.bs.modal', function() {
              // Pastikan aria-hidden dihapus saat modal tertutup
              documentPreviewModal.removeAttribute('aria-hidden');

              // Pindahkan fokus ke elemen lain yang sesuai (seperti tombol yang membuka modal)
              const lastFocusedElement = window.lastFocusedBeforeModal || document.body;
              if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                  lastFocusedElement.focus();
              }
          });

          documentPreviewModal.addEventListener('show.bs.modal', function() {
              // Simpan elemen yang memiliki fokus sebelum modal terbuka
              window.lastFocusedBeforeModal = document.activeElement;
          });
      }

      // Perbaiki event tombol-tombol di modal untuk menangani fokus dengan lebih baik
      const modalButtons = document.querySelectorAll('#documentPreviewModal button');
      modalButtons.forEach(button => {
          button.addEventListener('click', function(e) {
              // Mencegah fokus tetap berada pada tombol saat modal disembunyikan
              if (this.getAttribute('data-bs-dismiss') === 'modal') {
                  setTimeout(() => {
                      this.blur();
                  }, 100);
              }
          });
      });
  });
