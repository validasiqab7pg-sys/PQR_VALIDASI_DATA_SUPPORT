<?php
$data = getSheetData($spreadsheet_id, $sheets['IRB']);

renderTablePage([
    'title'              => 'Informasi Recipe Baru',
    'search_id'          => 'irbSearch',
    'search_placeholder' => 'Cari IRB...',
    'table_id'           => 'irbTable',
    'no_result_id'       => 'noDataIrb',
    'accent'             => '#f39c12',
    'accent_dark'        => '#e67e22',
    'data'               => $data,
]);
