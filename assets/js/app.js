/* =====================================================================
   PQR VALIDASI DATA SUPPORT — shared JS
   Sebelumnya logic search-tabel dan modal-detail disalin ulang di 6 file
   modul berbeda. Sekarang cukup satu fungsi generik dipanggil per halaman.
   ===================================================================== */

/**
 * Aktifkan live-search sederhana pada sebuah tabel.
 * @param {string} searchInputId  id elemen <input>
 * @param {string} tableId        id elemen <table>
 * @param {string} noResultId     id elemen "data tidak ditemukan"
 */
function pqrInitTableSearch(searchInputId, tableId, noResultId) {
    const input = document.getElementById(searchInputId);
    const table = document.getElementById(tableId);
    const noData = document.getElementById(noResultId);
    if (!input || !table) return;

    const rows = table.querySelectorAll('tbody tr');

    input.addEventListener('keyup', function () {
        const filter = this.value.toUpperCase();
        let found = false;

        rows.forEach(row => {
            const match = row.textContent.toUpperCase().indexOf(filter) > -1;
            row.style.display = match ? '' : 'none';
            if (match) found = true;
        });

        if (noData) noData.style.display = found ? 'none' : 'block';
    });
}

/**
 * Aktifkan modal "Detail" generik: klik tombol .btn-detail (dengan
 * data-text) menampilkan isi lengkapnya di dalam modal.
 * @param {string} modalId
 * @param {string} textContainerId
 */
function pqrInitDetailModal(modalId, textContainerId) {
    const modal = document.getElementById(modalId);
    const textEl = document.getElementById(textContainerId);
    if (!modal || !textEl) return;

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-detail');
        if (btn && modal.contains(btn) === false) {
            textEl.innerHTML = btn.getAttribute('data-text') || '';
            modal.style.display = 'flex';
            return;
        }
        if (e.target.classList.contains('close-modal') || e.target === modal) {
            modal.style.display = 'none';
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') modal.style.display = 'none';
    });
}

/**
 * Highlight menu sidebar sesuai halaman aktif (?page=...).
 */
function pqrHighlightActiveMenu() {
    const page = new URLSearchParams(window.location.search).get('page') || 'rekomendasi_PQR';
    document.querySelectorAll('.menu-item').forEach(link => {
        if (link.href.includes('page=' + page)) {
            link.classList.add('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', pqrHighlightActiveMenu);

/* ===== EXPORT TABEL (menu sidebar, tersedia di semua halaman) ===== */

let _pqrXlsxLoadPromise = null;

/**
 * Muat library SheetJS hanya saat dibutuhkan (saat user klik "Export Tabel"),
 * bukan di setiap page load — supaya halaman yang tidak dipakai untuk export
 * tetap ringan.
 */
function pqrLoadXlsxLib() {
    if (window.XLSX) return Promise.resolve();
    if (_pqrXlsxLoadPromise) return _pqrXlsxLoadPromise;

    _pqrXlsxLoadPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Gagal memuat library Excel. Cek koneksi internet.'));
        document.head.appendChild(script);
    });

    return _pqrXlsxLoadPromise;
}

/**
 * Export tabel yang sedang aktif di halaman ini ke file Excel (.xlsx).
 * Dipanggil dari menu "📥 Export Tabel" di sidebar. Kalau halaman yang
 * sedang dibuka tidak punya tabel data (mis. Generate Data / Create Chart),
 * menu ini memberi tahu user bahwa fitur tidak tersedia.
 */
function pqrExportCurrentTable() {
    const dataEl = document.querySelector('script[data-pqr-export]');
    if (!dataEl) {
        alert('Halaman ini tidak memiliki tabel untuk diexport.');
        return;
    }

    let payload;
    try {
        payload = JSON.parse(dataEl.textContent);
    } catch (e) {
        alert('Gagal membaca data tabel untuk diexport.');
        return;
    }

    // Tentukan baris mana yang sedang tampil (lolos filter pencarian saat ini).
    // Kalau tabelnya tidak ditemukan di DOM (mestinya tidak terjadi), fallback
    // ke seluruh data supaya export tetap jalan.
    const table = payload.tableId ? document.getElementById(payload.tableId) : null;
    let bodyRows = payload.rows;

    if (table) {
        const visibleRows = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return; // disembunyikan oleh search
            const idx = parseInt(tr.getAttribute('data-row-index'), 10);
            if (!isNaN(idx) && payload.rows[idx]) {
                visibleRows.push(payload.rows[idx]);
            }
        });
        bodyRows = visibleRows;
    }

    if (bodyRows.length === 0) {
        alert('Tidak ada data (sesuai hasil pencarian saat ini) untuk diexport.');
        return;
    }

    const sheetRows = [payload.header, ...bodyRows];

    pqrLoadXlsxLib().then(() => {
        const worksheet = XLSX.utils.aoa_to_sheet(sheetRows);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, payload.sheetName || 'Data');

        const today = new Date().toISOString().slice(0, 10);
        const filename = (payload.filename || 'export') + '_' + today + '.xlsx';
        XLSX.writeFile(workbook, filename);
    }).catch(err => alert(err.message));
}
