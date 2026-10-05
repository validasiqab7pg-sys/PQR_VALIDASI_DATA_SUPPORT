<?php
$data = getSheetData($spreadsheet_id, $sheets['material_deviation']);

renderTablePage([
    'title'              => 'Material Deviation Log',
    'search_id'          => 'matDevSearch',
    'search_placeholder' => 'Cari deviation...',
    'table_id'           => 'matDevTable',
    'no_result_id'       => 'noDataMatDev',
    'accent'             => '#f39c12',
    'accent_dark'        => '#e67e22',
    'data'               => $data,
]);
