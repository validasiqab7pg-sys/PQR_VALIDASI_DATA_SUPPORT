<?php
include 'config/sheets.php';
include 'includes/functions.php';
include 'includes/table_template.php';

$page = $_GET['page'] ?? 'rekomendasi_PQR';
$allowed = array_keys($sheets);

if(!in_array($page, $allowed)){
    $page = 'rekomendasi_PQR';
}

include 'includes/header.php';
include 'includes/sidebar.php';

// Bungkus modul di dalam div content di sini
echo '<div class="content">';
    include "modules/$page.php";
echo '</div>';

include 'includes/footer.php';
?>