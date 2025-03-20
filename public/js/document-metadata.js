
// Aktifkan mode debug dengan menambahkan ?debug=metadata ke URL
const urlParams = new URLSearchParams(window.location.search);
const debugMode = urlParams.get('debug') === 'metadata';

if (debugMode) {
    console.log('MODE DEBUG METADATA AKTIF');

    // Tambahkan info debug ke halaman
    const debugInfo = document.createElement('div');
    debugInfo.className = 'metadata-debug-info';
    debugInfo.textContent = 'Debug Mode Aktif\nURL: ' + window.location.href;
    document.body.prepend(debugInfo);
}

/**
 * File: document-metadata.js
 * Fungsi untuk menampilkan metadata dokumen dalam kartu dokumen
 */

function tingkatkanKartuDokumen() {
    // Target semua kartu dokumen
    const kartuDokumen = document.querySelectorAll('.document-card');
    console.log('Menemukan kartu dokumen:', kartuDokumen.length);

    // Cek apakah kartu dokumen memiliki atribut data-id
    const kartuDenganId = Array.from(kartuDokumen).filter(k => k.hasAttribute('data-id'));
    console.log('Kartu dengan data-id:', kartuDenganId.length);

    if (kartuDenganId.length === 0) {
        console.error('PERHATIAN: Tidak ada kartu dokumen dengan atribut data-id! Coba periksa HTML struktur kartu dokumen.');

        // Cetak contoh HTML untuk analisis
        if (kartuDokumen.length > 0) {
            console.log('Contoh HTML kartu dokumen pertama:', kartuDokumen[0].outerHTML);
        }

        // Mencoba mencari ID dengan cara alternatif
        console.log('Mencoba menemukan ID dokumen dengan cara alternatif...');

        // Jika kartu dokumen tidak memiliki data-id, mungkin menggunakan id lain atau kelas khusus
        kartuDokumen.forEach((kartu, index) => {
            // Mungkin ID disimpan dalam atribut lain
            const alternativeId =
                kartu.getAttribute('id') ||
                kartu.getAttribute('document-id') ||
                kartu.getAttribute('doc-id');

            if (alternativeId) {
                console.log(`Kartu #${index} memiliki ID alternatif:`, alternativeId);
                // Tambahkan data-id yang benar
                kartu.setAttribute('data-id', alternativeId);
            } else {
                // Coba ekstrak ID dari href tombol
                const tombolLihat = kartu.querySelector('a[href*="document"]') || kartu.querySelector('button[data-id]');
                if (tombolLihat) {
                    const hrefMatch = tombolLihat.getAttribute('href')?.match(/document\/(\d+)/);
                    const buttonId = tombolLihat.getAttribute('data-id');

                    if (hrefMatch && hrefMatch[1]) {
                        console.log(`Kartu #${index} memiliki ID dari href:`, hrefMatch[1]);
                        kartu.setAttribute('data-id', hrefMatch[1]);
                    } else if (buttonId) {
                        console.log(`Kartu #${index} memiliki ID dari tombol:`, buttonId);
                        kartu.setAttribute('data-id', buttonId);
                    }
                }

                // Coba ekstrak dari HTML jika mengandung pattern seperti numerik
                const htmlContent = kartu.innerHTML;
                const docIdMatch = htmlContent.match(/dokumen-(\d+)/) || htmlContent.match(/document-id="(\d+)"/);
                if (docIdMatch && docIdMatch[1]) {
                    console.log(`Kartu #${index} memiliki ID dari konten HTML:`, docIdMatch[1]);
                    kartu.setAttribute('data-id', docIdMatch[1]);
                }
            }
        });
    }

    // Proses setiap kartu dokumen
    kartuDokumen.forEach(kartu => {
        // Lewati jika sudah diproses
        if (kartu.hasAttribute('data-ditingkatkan')) {
            console.log('Kartu sudah ditingkatkan, melewati');
            return;
        }

        // Tandai sebagai sudah diproses
        kartu.setAttribute('data-ditingkatkan', 'true');

        // Dapatkan ID dokumen dan versi
        const idDokumen = kartu.getAttribute('data-id');
        const versiDokumen = kartu.getAttribute('data-version') || 'latest';

        // Jika tidak ada ID dokumen, tambahkan tombol alternatif
        if (!idDokumen) {
            console.log('Kartu dokumen tidak memiliki ID, menambahkan tombol info alternatif');

            // Buat tombol info
            const tombolInfo = document.createElement('button');
            tombolInfo.className = 'metadata-info-btn';
            tombolInfo.innerHTML = '<i class="fas fa-info-circle"></i>';
            tombolInfo.title = 'Informasi dokumen';

            // Tambahkan gaya tombol
            tombolInfo.style.position = 'absolute';
            tombolInfo.style.right = '10px';
            tombolInfo.style.top = '10px';
            tombolInfo.style.background = 'transparent';
            tombolInfo.style.border = 'none';
            tombolInfo.style.color = '#00205b';
            tombolInfo.style.cursor = 'pointer';
            tombolInfo.style.zIndex = '10';

            // Temukan kontainer yang cocok untuk tombol info
            const kontainerUntukToggle = kartu.querySelector('.document-content') || kartu;
            if (getComputedStyle(kontainerUntukToggle).position === 'static') {
                kontainerUntukToggle.style.position = 'relative';
            }
            kontainerUntukToggle.appendChild(tombolInfo);

            // Tambahkan event handler klik untuk tombol info
            tombolInfo.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Tampilkan informasi dokumen sederhana dalam alert
                const title = kartu.querySelector('.document-title')?.textContent || 'Dokumen';
                const desc = kartu.querySelector('.document-desc')?.textContent || '';
                const meta = kartu.querySelector('.document-meta')?.textContent || '';

                alert(`Informasi Dokumen\n\nJudul: ${title}\nDeskripsi: ${desc}\n${meta}\n\nID dokumen tidak tersedia, metadata tidak dapat dimuat.`);
            });

            return; // Lewati proses metadata
        }

        // Buat kontainer metadata jika belum ada
        let kontainerMetadata = kartu.querySelector('.document-metadata');
        if (!kontainerMetadata) {
            kontainerMetadata = document.createElement('div');
            kontainerMetadata.className = 'document-metadata';
            kontainerMetadata.style.display = 'none';
            kontainerMetadata.innerHTML = '<div class="metadata-loading">Memuat metadata...</div>';

            // Temukan tempat yang tepat untuk menambahkannya
            const elementKonten = kartu.querySelector('.document-content');
            if (elementKonten) {
                elementKonten.appendChild(kontainerMetadata);
            } else {
                kartu.appendChild(kontainerMetadata);
            }
        }

        // Buat tombol untuk membuka/menutup
        const tombolToggle = document.createElement('button');
        tombolToggle.className = 'metadata-toggle';
        tombolToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
        tombolToggle.title = 'Tampilkan metadata';

        // Tambahkan gaya tombol
        tombolToggle.style.position = 'absolute';
        tombolToggle.style.right = '10px';
        tombolToggle.style.top = '10px';
        tombolToggle.style.background = 'transparent';
        tombolToggle.style.border = 'none';
        tombolToggle.style.color = '#00205b';
        tombolToggle.style.cursor = 'pointer';
        tombolToggle.style.zIndex = '10';
        tombolToggle.style.transition = 'transform 0.3s ease';

        // Temukan kontainer yang cocok untuk tombol toggle
        const kontainerUntukToggle = kartu.querySelector('.document-content') || kartu;
        if (getComputedStyle(kontainerUntukToggle).position === 'static') {
            kontainerUntukToggle.style.position = 'relative';
        }
        kontainerUntukToggle.appendChild(tombolToggle);

        // Tambahkan event handler klik untuk tombol toggle
        tombolToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const sudahDibuka = kontainerMetadata.style.display !== 'none';

            if (sudahDibuka) {
                // Tutup/collapse
                kontainerMetadata.style.display = 'none';
                tombolToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
                tombolToggle.title = 'Tampilkan metadata';
                tombolToggle.style.transform = 'rotate(0deg)';
            } else {
                // Buka/expand
                kontainerMetadata.style.display = 'block';
                tombolToggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
                tombolToggle.title = 'Sembunyikan metadata';
                tombolToggle.style.transform = 'rotate(180deg)';

                // Muat metadata jika belum dimuat
                if (kontainerMetadata.querySelector('.metadata-loading')) {
                    ambilDanTampilkanMetadata(idDokumen, versiDokumen, kontainerMetadata);
                }
            }
        });
    });
}

// Fungsi untuk mengambil dan menampilkan metadata dokumen
function ambilDanTampilkanMetadata(idDokumen, versi, kontainer) {
    console.log('Memulai pengambilan metadata untuk dokumen:', idDokumen, 'versi:', versi);

    // Tambahkan indikator loading yang lebih terlihat
    kontainer.innerHTML = `
        <div class="metadata-loading">
            <div class="loading-spinner"></div>
            <div>Memuat metadata dokumen...</div>
        </div>
    `;

    // Buat permintaan API untuk mendapatkan metadata
    const url = `/stk/api/document/${idDokumen}/${versi}/metadata`;
    console.log('Mengakses URL API:', url);

    fetch(url)
        .then(response => {
            console.log('Respons diterima:', response.status, response.statusText);

            // Tambahkan penanganan kesalahan yang lebih baik
            if (!response.ok) {
                if (response.status === 500) {
                    console.error('Server error 500 - mungkin ada bug di controller');
                } else if (response.status === 404) {
                    console.error('Endpoint tidak ditemukan - periksa route');
                } else if (response.status === 401) {
                    console.error('Tidak terautentikasi - token mungkin tidak valid');
                }

                throw new Error('Gagal mengambil metadata: ' + response.status + ' ' + response.statusText);
            }

            return response.json();
        })
        .then(data => {
            console.log('Data JSON diterima:', data);

            if (!data) {
                throw new Error('Respons kosong dari server');
            }

            if (data.success && data.metadata) {
                const metadataCount = Object.keys(data.metadata).length;
                console.log('Metadata tersedia:', metadataCount, 'properti');

                if (metadataCount === 0) {
                    kontainer.innerHTML = `
                        <div class="metadata-info">
                            <i class="fas fa-info-circle"></i> Dokumen ini tidak memiliki metadata tambahan.
                        </div>
                    `;
                } else {
                    renderTabelMetadata(kontainer, data.metadata);
                }
            } else {
                console.error('Data tidak berisi metadata yang valid:', data);
                kontainer.innerHTML = `
                    <div class="metadata-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        Gagal memuat metadata: ${data.message || 'Tidak ada data metadata valid'}
                        <br><small>Coba refresh halaman atau hubungi administrator jika masalah berlanjut.</small>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error dalam fetch metadata:', error);
            kontainer.innerHTML = `
                <div class="metadata-error">
                    <i class="fas fa-exclamation-circle"></i>
                    Error saat memuat metadata: ${error.message}
                    <br><small>URL: ${url}</small>
                    <br><small>Coba refresh halaman atau hubungi administrator jika masalah berlanjut.</small>
                </div>
            `;
        });
}
// Fungsi untuk menampilkan metadata dalam bentuk tabel
// Fungsi untuk menampilkan metadata dalam bentuk tabel
function renderTabelMetadata(kontainer, metadata) {
    // Buat elemen tabel
    const tabel = document.createElement('table');
    tabel.className = 'metadata-table';
    tabel.style.width = '100%';
    tabel.style.borderCollapse = 'collapse';
    tabel.style.marginTop = '10px';
    tabel.style.fontSize = '14px';

    // Tambahkan baris header
    const thead = document.createElement('thead');
    const barisHeader = document.createElement('tr');
    barisHeader.innerHTML = `
        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; background-color: #024996; color: white; font-weight: bold;">Properti</th>
        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; background-color: #024996; color: white; font-weight: bold;">Deskripsi</th>
    `;
    thead.appendChild(barisHeader);
    tabel.appendChild(thead);

    // Tambahkan body dengan properti metadata
    const tbody = document.createElement('tbody');

    // Urutkan kunci metadata untuk tampilan yang konsisten
    const kunciTerurut = Object.keys(metadata).sort((a, b) => {
        // Coba urutkan berdasarkan display_order jika tersedia
        const urutanA = metadata[a].display_order ?? 999;
        const urutanB = metadata[b].display_order ?? 999;
        return urutanA - urutanB;
    });

    // Filter properti yang ingin ditampilkan
    // Hapus baris ini jika ingin menampilkan semua properti
    const propertiUntukTampil = kunciTerurut.filter(key => {
        // Tambahkan filter jika diperlukan, misalnya:
        // return !['some_hidden_prop_id'].includes(key);
        return true; // Tampilkan semua properti
    });

    // Buat kategori untuk properti-properti tertentu
    const kategoriProperti = {
        'Umum': ['title', 'id', '1870'],
        'Tanggal': ['20', '21', '25'],
        'Detail Dokumen': ['1853', '1854', '1855', '1856', '1857', '1859'],
        'Lainnya': []  // Semua properti lain masuk sini
    };

    // Kelompokkan properti berdasarkan kategori
    const propertiByKategori = {};

    // Inisialisasi kategori
    Object.keys(kategoriProperti).forEach(kategori => {
        propertiByKategori[kategori] = [];
    });

    // Kelompokkan properti
    propertiUntukTampil.forEach(key => {
        let dikelompokkan = false;

        for (const [kategori, propertiList] of Object.entries(kategoriProperti)) {
            if (propertiList.includes(key)) {
                propertiByKategori[kategori].push(key);
                dikelompokkan = true;
                break;
            }
        }

        if (!dikelompokkan) {
            propertiByKategori['Lainnya'].push(key);
        }
    });

    // Render metadata berdasarkan kategori
    for (const [kategori, propertiList] of Object.entries(propertiByKategori)) {
        if (propertiList.length === 0) continue;

        // Tambahkan header kategori
        const headerKategori = document.createElement('tr');
        headerKategori.innerHTML = `
            <td colspan="2" style="background-color: #e9ecef; padding: 8px; font-weight: 600; border-bottom: 1px solid #ddd;">
                ${kategori}
            </td>
        `;
        tbody.appendChild(headerKategori);

        // Tambahkan properti dalam kategori ini
        propertiList.forEach(key => {
            const meta = metadata[key];

            // Lewati jika tidak ada nama atau nilai null & kategori bukan Umum
            if (!meta.name && kategori !== 'Umum') return;

            const baris = document.createElement('tr');
            baris.style.borderBottom = '1px solid #eee';

            // Tambahkan indikator wajib jika diperlukan
            const gayaWajib = meta.required ? 'font-weight: 600;' : '';

            const nilai = meta.value !== null && meta.value !== undefined ? meta.value :
                '<span style="color: #999; font-style: italic;">Tidak diatur</span>';

            baris.innerHTML = `
                <td style="padding: 8px; ${gayaWajib}">${meta.name}</td>
                <td style="padding: 8px;">${nilai}</td>
            `;

            tbody.appendChild(baris);
        });
    }

    tabel.appendChild(tbody);

    // Tambahkan tombol untuk menyalin semua metadata ke clipboard
    const salinButton = document.createElement('button');
    salinButton.textContent = 'Salin Semua Metadata';
    salinButton.className = 'metadata-copy-btn';
    salinButton.style.marginTop = '10px';
    salinButton.style.padding = '5px 10px';
    salinButton.style.backgroundColor = '#f8f9fa';
    salinButton.style.border = '1px solid #ddd';
    salinButton.style.borderRadius = '4px';
    salinButton.style.cursor = 'pointer';

    salinButton.addEventListener('click', function() {
        let teksMetadata = '';

        for (const key of kunciTerurut) {
            const meta = metadata[key];
            const nilai = meta.value !== null && meta.value !== undefined ? meta.value : 'Tidak diatur';
            teksMetadata += `${meta.name}: ${nilai}\n`;
        }

        navigator.clipboard.writeText(teksMetadata).then(() => {
            salinButton.textContent = 'Disalin!';
            setTimeout(() => {
                salinButton.textContent = 'Salin Semua Metadata';
            }, 2000);
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
            alert('Tidak dapat menyalin teks ke clipboard.');
        });
    });

    // Bersihkan kontainer dan tambahkan tabel dan tombol
    kontainer.innerHTML = '';
    kontainer.appendChild(tabel);
    kontainer.appendChild(salinButton);
}
// Tambahkan gaya CSS untuk komponen metadata
function tambahkanGayaMetadata() {
    const elemenGaya = document.createElement('style');
    elemenGaya.textContent = `
        .document-metadata {
            padding: 20px 30px;
            border-top: 1px dashed #ddd;
            background-color: #ffffff;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }

        .metadata-loading,
        .metadata-error {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        .metadata-error {
            color: #dc3545;
        }

        .metadata-table tr:hover {
            background-color: #f1f3f5;
        }

        .metadata-toggle {
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .metadata-toggle:hover {
            opacity: 1;
        }

        @keyframes putar {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 32, 91, 0.1);
            border-radius: 50%;
            border-top-color: #00205b;
            animation: putar 1s ease-in-out infinite;
            margin-right: 8px;
        }
    `;
    document.head.appendChild(elemenGaya);
}

// Inisialisasi peningkatan kartu dokumen saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan gaya metadata
    tambahkanGayaMetadata();

    // Tingkatkan kartu dokumen
    tingkatkanKartuDokumen();

    // Juga amati kartu dokumen baru yang mungkin ditambahkan secara dinamis
    const pengamat = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                tingkatkanKartuDokumen();
            }
        });
    });

    // Amati body dokumen untuk kartu baru
    pengamat.observe(document.body, { childList: true, subtree: true });
});
