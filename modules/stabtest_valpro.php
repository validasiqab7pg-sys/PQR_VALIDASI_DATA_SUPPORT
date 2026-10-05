<?php
$data = getSheetData($spreadsheet_id, $sheets['stabtest_valpro']);

renderTablePage([
    'title'              => 'Stabtest Validasi Proses Database',
    'icon'               => '🧪',
    'search_id'          => 'globalSearch',
    'search_placeholder' => 'Cari data...',
    'table_id'           => 'dataTable',
    'no_result_id'       => 'noData',
    'accent'             => '#3498db',
    'accent_dark'        => '#2980b9',
    'truncate_length'    => 100,
    'data'               => $data,
]);
