<?php
$data = getSheetData($spreadsheet_id, $sheets['OOS_OOT_OOE']);

renderTablePage([
    'title'              => 'OOS OOT OOE Log',
    'search_id'          => 'ooeSearch',
    'search_placeholder' => 'Cari Data...',
    'table_id'           => 'ooeTable',
    'no_result_id'       => 'noDataOoe',
    'accent'             => '#f39c12',
    'accent_dark'        => '#e67e22',
    'data'               => $data,
]);
