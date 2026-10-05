<?php
$data = getSheetData($spreadsheet_id, $sheets['ehm_ruang']);

echo '<pre>';
var_dump($data);
echo '</pre>';