<?php
/**
 * Render halaman tabel data generik.
 *
 * Dulu deviation.php, IRB.php, material_deviation.php, dan OOS_OOT_OOE.php
 * masing-masing 440 baris yang 99% identik (hanya beda judul & gid).
 * Sekarang keempatnya (plus stabtest_valpro & rekomendasi_PQR) tinggal
 * memanggil renderTablePage() dengan konfigurasi singkat.
 *
 * Konfigurasi yang diterima ($cfg):
 *   title            (string) Judul di header tabel
 *   icon             (string) Emoji/ikon sebelum judul, opsional
 *   search_id        (string) id unik untuk input pencarian
 *   search_placeholder (string) placeholder input pencarian
 *   table_id         (string) id unik untuk <table>
 *   no_result_id     (string) id unik untuk elemen "data tidak ditemukan"
 *   modal_id         (string) id unik untuk modal (default: 'detailModal')
 *   modal_text_id    (string) id unik untuk teks modal (default: 'modalText')
 *   accent           (string) warna aksen utama, hex (default '#f39c12')
 *   accent_dark      (string) warna aksen hover, hex (default '#e67e22')
 *   show_row_number  (bool)   tampilkan kolom "No" (default true)
 *   truncate_length  (int)    panjang teks sebelum dipotong (default 120)
 *   data             (array)  hasil getSheetData() — baris pertama = header
 */
function renderTablePage(array $cfg) {
    $title            = $cfg['title'] ?? 'Data';
    $icon             = $cfg['icon'] ?? '';
    $searchId         = $cfg['search_id'] ?? 'tblSearch';
    $searchPlaceholder = $cfg['search_placeholder'] ?? 'Cari data...';
    $tableId          = $cfg['table_id'] ?? 'dataTable';
    $noResultId       = $cfg['no_result_id'] ?? 'noResult';
    $modalId          = $cfg['modal_id'] ?? 'detailModal';
    $modalTextId      = $cfg['modal_text_id'] ?? 'modalText';
    $accent           = $cfg['accent'] ?? '#f39c12';
    $accentDark       = $cfg['accent_dark'] ?? '#e67e22';
    $showRowNumber    = $cfg['show_row_number'] ?? true;
    $truncateLength   = $cfg['truncate_length'] ?? 120;
    $data             = $cfg['data'] ?? [];

    $headerRow = $data[0] ?? [];
    $bodyRows  = array_slice($data, 1);

    // Buang baris kosong sekali di awal, dipakai bareng untuk render tabel
    // maupun data export — supaya index barisnya selalu sinkron antara
    // yang tampil di layar dan yang di-export.
    $visibleRows = array_values(array_filter($bodyRows, function ($row) {
        return !empty(array_filter($row));
    }));
    ?>
    <div class="page-wrapper" style="--accent:<?= htmlspecialchars($accent) ?>;--accent-dark:<?= htmlspecialchars($accentDark) ?>;">

        <div class="table-header-section">
            <h2><?= $icon ? htmlspecialchars($icon) . ' ' : '' ?><?= htmlspecialchars($title) ?></h2>
            <div class="search-box">
                <input type="text" id="<?= htmlspecialchars($searchId) ?>" placeholder="<?= htmlspecialchars($searchPlaceholder) ?>">
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table" id="<?= htmlspecialchars($tableId) ?>">
                <thead>
                    <tr>
                        <?php if ($showRowNumber): ?><th>No</th><?php endif; ?>
                        <?php foreach ($headerRow as $header): ?>
                            <th><?= htmlspecialchars(trim($header)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visibleRows as $i => $row): ?>
                        <tr data-row-index="<?= $i ?>">
                            <?php if ($showRowNumber): ?><td><?= $i + 1 ?></td><?php endif; ?>
                            <?php foreach ($row as $col): ?>
                                <td><?= renderCellValue($col, $truncateLength) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="<?= htmlspecialchars($noResultId) ?>" class="no-result">Data tidak ditemukan</div>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="<?= htmlspecialchars($modalId) ?>" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Detail Informasi</h3>
            <div id="<?= htmlspecialchars($modalTextId) ?>"></div>
        </div>
    </div>

    <script>
        pqrInitTableSearch(<?= json_encode($searchId) ?>, <?= json_encode($tableId) ?>, <?= json_encode($noResultId) ?>);
        pqrInitDetailModal(<?= json_encode($modalId) ?>, <?= json_encode($modalTextId) ?>);
    </script>

    <!-- Data lengkap (tidak terpotong) untuk fitur "Export Tabel" di sidebar.
         Dipakai oleh pqrExportCurrentTable() di assets/js/app.js, yang akan
         mencocokkan data-row-index di atas dengan baris yang sedang tampil
         (lolos filter pencarian) sebelum diexport. -->
    <script type="application/json" data-pqr-export>
        <?= json_encode([
            'tableId'   => $tableId,
            'sheetName' => substr($title, 0, 31), // batas nama sheet Excel = 31 karakter
            'filename'  => preg_replace('/[^a-zA-Z0-9_-]+/', '_', $title),
            'header'    => array_map('strval', $showRowNumber ? array_merge(['No'], $headerRow) : $headerRow),
            'rows'      => buildExportRows($visibleRows, $showRowNumber),
        ], JSON_UNESCAPED_UNICODE) ?>
    </script>
    <?php
}

/**
 * Susun data lengkap (header + isi asli tanpa dipotong) untuk diexport ke Excel.
 * Beda dengan tampilan di tabel yang teksnya bisa dipotong + tombol Detail,
 * sel URL yang jadi tombol "Dokumen" di layar, dsb — hasil export selalu
 * berisi data mentah apa adanya. Urutan array ini sengaja dibuat sejajar
 * dengan data-row-index di tabel (index 0 = baris pertama, dst).
 */
function buildExportRows($visibleRows, $showRowNumber) {
    $rows = [];
    foreach ($visibleRows as $i => $row) {
        $cleanRow = array_map(function ($cell) {
            return trim((string) $cell);
        }, $row);
        $rows[] = $showRowNumber ? array_merge([$i + 1], $cleanRow) : $cleanRow;
    }
    return $rows;
}

/**
 * Format satu sel data: deteksi URL (jadi tombol), teks panjang (jadi
 * potongan + tombol Detail), atau teks biasa.
 */
function renderCellValue($rawValue, $truncateLength = 120) {
    $clean = trim(preg_replace('/\s+/', ' ', (string) $rawValue));

    if ($clean !== '' && filter_var($clean, FILTER_VALIDATE_URL)) {
        return '<a href="' . htmlspecialchars($clean, ENT_QUOTES) . '" target="_blank" rel="noopener" class="link-action">Dokumen</a>';
    }

    if (strlen($clean) > $truncateLength) {
        $short = substr($clean, 0, $truncateLength);
        $modalText = nl2br(htmlspecialchars(trim((string) $rawValue), ENT_QUOTES));
        return '<span class="truncate-text">' . htmlspecialchars($short) . '...</span>'
             . '<button class="btn-detail" data-text="' . $modalText . '">Detail</button>';
    }

    return htmlspecialchars($clean);
}
