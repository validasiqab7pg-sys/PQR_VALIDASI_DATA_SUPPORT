<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PQR-VALIDASI DATA SUPPORT</title>

    <!-- Bootstrap dipakai oleh modul Create Trend Chart (card, row, form-control, dll).
         Sebelumnya class Bootstrap dipakai tanpa Bootstrap pernah di-load,
         sehingga halaman itu tampil tanpa styling grid/komponen sama sekali. -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Stylesheet bersama untuk seluruh halaman (dulu di-copy ulang di tiap modul) -->
    <link rel="stylesheet" href="assets/css/style.css?v=2">

    <!-- Dimuat di <head>, BUKAN di footer: modul tabel memanggil pqrInitTableSearch()/
         pqrInitDetailModal() langsung lewat inline <script> di tengah konten halaman.
         Kalau app.js baru dimuat di footer (setelah konten), pemanggilan fungsi itu akan
         gagal karena fungsinya belum didefinisikan saat dipanggil. -->
    <script src="assets/js/app.js?v=3"></script>
</head>
<body>
    <div class="layout">
