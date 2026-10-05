<?php
$data = getSheetData($spreadsheet_id, $sheets['deviation']);

renderTablePage([
    'title'              => 'Deviation Log',
    'search_id'          => 'devSearch',
    'search_placeholder' => 'Cari deviation...',
    'table_id'           => 'devTable',
    'no_result_id'       => 'noDataDev',
    'accent'             => '#f39c12',
    'accent_dark'        => '#e67e22',
    'data'               => $data,
]);
