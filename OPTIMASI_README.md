# Ringkasan Optimasi — PQR Validasi Data Support

## 1. Performa: caching data Google Sheets
**Sebelum:** setiap load halaman melakukan HTTP request langsung ke Google Sheets
(pakai `fopen`, tanpa timeout). Jika Google lambat/network bermasalah, halaman ikut lambat
atau bahkan gagal total.

**Sesudah** (`includes/functions.php`):
- Hasil fetch disimpan sebagai cache file di folder `cache/` selama 5 menit (bisa diubah via
  parameter `$ttl` di `getSheetData()`).
- Selama cache masih segar, **tidak ada** request ke Google — halaman langsung load dari disk.
- Fetch sekarang pakai cURL dengan timeout 8 detik (sebelumnya tanpa timeout sama sekali).
- Kalau fetch gagal tapi ada cache lama, cache lama tetap dipakai sebagai fallback (lebih baik
  data agak basi daripada halaman error).
- Folder `cache/` diberi `.htaccess` + `index.php` supaya tidak bisa diakses langsung dari browser.

## 2. Hilangkan duplikasi kode besar-besaran
**Sebelum:** modul `deviation.php`, `IRB.php`, `material_deviation.php`, `OOS_OOT_OOE.php`
masing-masing 440 baris yang 99% identik (hanya beda judul & gid sheet). `stabtest_valpro.php`
dan `rekomendasi_PQR.php` juga punya struktur yang sangat mirip dengan variasi warna/style.

**Sesudah:**
- `includes/table_template.php` — satu fungsi `renderTablePage()` yang menghasilkan seluruh
  halaman tabel (header, search box, tabel, modal detail) dari sebuah array konfigurasi.
- Keenam modul di atas sekarang masing-masing hanya **~12 baris** berisi konfigurasi (judul,
  warna aksen, placeholder pencarian) — total baris modul turun dari ~2600 baris menjadi ~90 baris.
- Kalau ke depannya perlu ubah tampilan tabel (misal ganti style tombol Detail), cukup edit
  satu file, otomatis berlaku ke semua halaman.

## 3. CSS & JS dari inline (berulang) jadi shared assets
**Sebelum:** setiap modul punya `<style>` ~150–250 baris dan `<script>` ~50–70 baris yang
disalin-tempel, di-download & di-parse ulang browser setiap kali pindah halaman.

**Sesudah:**
- `assets/css/style.css` — satu stylesheet untuk layout, sidebar, tabel, modal, dll. Dimuat
  sekali di `<head>` dan di-cache browser antar-halaman.
- `assets/js/app.js` — fungsi generik `pqrInitTableSearch()`, `pqrInitDetailModal()`,
  `pqrHighlightActiveMenu()` dipakai ulang oleh semua halaman, menggantikan script yang
  disalin-tempel di tiap modul.
- Warna aksen per-halaman tetap bisa beda-beda lewat CSS variable `--accent` yang di-set
  langsung di `<div class="page-wrapper" style="--accent:...">`.

## 4. Bug: halaman "Create Trend Chart" render tanpa styling
Modul `create_chart.php` memakai banyak class Bootstrap (`card`, `row`, `col-lg-4`,
`form-control`, `btn btn-primary`, dll) tapi **Bootstrap CSS tidak pernah dimuat** di manapun
dalam aplikasi. Sekarang Bootstrap 5 (CSS only, tanpa JS bundle karena tidak dipakai) dimuat
dari CDN di `includes/header.php`.

## 5. Bug: `chart.js` dimuat 2x di halaman Generate Data
`modules/generate_data.php` memuat `<script src="...chart.js">` dua kali (baris 145 & 196),
memboroskan bandwidth dan waktu parse. Sekarang hanya dimuat sekali, beserta plugin
`chartjs-plugin-datalabels` yang sebelumnya dipakai (`Chart.register(ChartDataLabels)`) tanpa
pernah di-load sama sekali di bagian atas.

## 6. Bug: CSS sidebar konflik/duplikat
`modules/generate_data.php` mendefinisikan ulang `.sidebar { position:fixed; width:250px; }`
yang tumpang tindih dan tidak konsisten (beda 10-20px) dengan definisi sidebar global.
Sekarang layout sidebar konsisten di semua halaman, didefinisikan sekali di
`assets/css/style.css`.

## 7. Hardening kecil
- Link `href` hasil deteksi URL sekarang di-`htmlspecialchars()` juga (sebelumnya URL mentah
  langsung ditaruh di atribut `href`), mencegah HTML/atribut jadi rusak kalau ada karakter
  aneh di data sheet.
- Link eksternal (`target="_blank"`) ditambahkan `rel="noopener"` untuk keamanan standar.

## 8. Fitur baru: Export Tabel (menu di sidebar)
Ditambahkan menu **"📥 Export Tabel"** di sidebar (`includes/sidebar.php`) — karena sidebar
dipakai bersama di semua halaman, menu ini otomatis muncul di setiap halaman.

- Saat diklik, tabel yang sedang aktif di halaman itu langsung diexport ke file **Excel
  (.xlsx)**, memakai data **lengkap/asli** (bukan versi terpotong yang tampil di layar dengan
  tombol "Detail").
- Library Excel (SheetJS) di-*lazy-load* — hanya diunduh browser saat tombol diklik pertama
  kali, supaya tidak memperlambat load halaman yang tidak butuh fitur ini.
- Di halaman tanpa tabel data (Generate Data, Create Chart), klik menu ini akan menampilkan
  pesan bahwa fitur tidak tersedia di halaman tersebut, bukan error.
- Nama file otomatis mengikuti judul halaman + tanggal hari ini, contoh:
  `Deviation_Log_2026-07-29.xlsx`.

## Fix: Export sekarang mengikuti hasil filter pencarian
Sebelumnya tombol "Export Tabel" selalu mengambil **seluruh** data, mengabaikan pencarian
yang sedang aktif di halaman. Sekarang:
- Setiap baris `<tr>` di tabel diberi `data-row-index` yang sejajar dengan data lengkap
  di JSON export.
- Saat export diklik, JS mengecek baris mana yang sedang **tampil** di tabel (tidak
  disembunyikan oleh pencarian) lewat `data-row-index`, lalu hanya baris itu yang
  dimasukkan ke file Excel.
- Kalau tidak ada pencarian aktif, semua baris tampil → export tetap berisi semua data
  (perilaku sama seperti sebelumnya).
- Kalau hasil pencarian kosong, muncul pesan "Tidak ada data (sesuai hasil pencarian saat
  ini) untuk diexport" alih-alih mengexport file kosong atau seluruh data.

## Fix bug tambahan (setelah rewrite pertama)
- **Tombol "Detail" tidak berfungsi**: `app.js` sebelumnya dimuat di footer (bawah halaman),
  padahal script di dalam modul yang memanggil `pqrInitDetailModal()` posisinya lebih atas —
  jadi fungsi belum ada saat dipanggil. Sekarang `app.js` dimuat di `<head>` sehingga semua
  fungsi sudah siap sebelum dipanggil di manapun pada halaman.
- **`mb_substr()` undefined function**: dihindari karena extension `mbstring` tidak selalu
  terpasang di semua hosting; diganti `substr()` biasa (aman karena judul halaman hanya teks
  ASCII pendek).

## Struktur file baru
```
assets/css/style.css       ← stylesheet bersama (baru)
assets/js/app.js           ← javascript bersama (baru)
includes/table_template.php ← template tabel generik (baru)
includes/functions.php     ← + caching & timeout (diperbarui)
includes/header.php        ← + bootstrap, link ke assets (diperbarui)
includes/sidebar.php       ← markup saja, CSS/JS dipindah (diperbarui)
includes/footer.php        ← load app.js (diperbarui)
modules/deviation.php            ← ringkas jadi ~12 baris (dari 440)
modules/IRB.php                  ← ringkas jadi ~12 baris (dari 440)
modules/material_deviation.php   ← ringkas jadi ~12 baris (dari 440)
modules/OOS_OOT_OOE.php          ← ringkas jadi ~12 baris (dari 440)
modules/stabtest_valpro.php      ← ringkas jadi ~16 baris (dari 412)
modules/rekomendasi_PQR.php      ← ringkas jadi ~12 baris (dari 153)
modules/generate_data.php        ← dibersihkan (CSS/script duplikat dihapus)
modules/create_chart.php         ← dibersihkan (CSS duplikat dihapus)
cache/                           ← folder cache baru (auto-dibuat), diproteksi
```

## Yang TIDAK diubah
Logic bisnis / perhitungan JS di `generate_data.php` dan `create_chart.php` (parsing data
Excel, kalkulasi statistik Cp/Cpk, rendering chart) dibiarkan sama persis — hanya bagian
struktural (CSS/duplikat script) yang dirapikan, supaya risiko regresi minimal.

## Cara pakai
Upload semua file (termasuk folder `cache/` — akan otomatis dibuat kalau belum ada, asal
folder `PQR_VALIDASI_DATA_SUPPORT` writable oleh web server) ke server PHP seperti biasa,
tidak perlu langkah instalasi tambahan.
