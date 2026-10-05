<?php
$data = getSheetData($spreadsheet_id, $sheets['rekomendasi_PQR']);

renderTablePage([
    'title'              => 'Monitoring Rekomendasi PQR',
    'search_id'          => 'pqrSearch',
    'search_placeholder' => 'Cari data PQR...',
    'table_id'           => 'pqrTable',
    'no_result_id'       => 'noDataPQR',
    'accent'             => '#2ecc71',
    'accent_dark'        => '#27ae60',
    'show_row_number'    => false,
    'data'               => $data,
]);
