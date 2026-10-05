<?php
// DEBUG MODE - Cek koneksi Google Sheets
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h2>🔍 Debug EHM Ruang - Cek Koneksi Google Sheets</h2>';

// 1. Cek variable ada atau tidak
echo '<p><strong>1. Cek Variabel:</strong></p>';
echo 'spreadsheet_id: ' . (isset($spreadsheet_id) ? $spreadsheet_id : 'TIDAK ADA') . '<br>';
echo 'sheets[ehm_ruang]: ' . (isset($sheets['ehm_ruang']) ? $sheets['ehm_ruang'] : 'TIDAK ADA') . '<br>';

// 2. Cek URL yang akan diakses
$url = "https://docs.google.com/spreadsheets/d/$spreadsheet_id/export?format=csv&gid=" . urlencode($sheets['ehm_ruang']);
echo '<p><strong>2. URL CSV yang akan diakses:</strong></p>';
echo '<a href="' . htmlspecialchars($url) . '" target="_blank">' . htmlspecialchars($url) . '</a><br>';

// 3. Cek cURL tersedia
echo '<p><strong>3. Extension cURL:</strong></p>';
echo function_exists('curl_init') ? '✅ cURL tersedia' : '❌ cURL TIDAK tersedia' . '<br>';

// 4. Test cURL langsung
echo '<p><strong>4. Test Fetch URL dengan cURL:</strong></p>';
if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_VERBOSE        => true,
    ]);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_errno($ch);
    $errorMsg = curl_error($ch);
    
    echo "HTTP Code: $httpCode<br>";
    echo "cURL Error Code: $error<br>";
    echo "cURL Error Msg: $errorMsg<br>";
    echo "Data diterima: " . strlen($result ?? '') . " bytes<br>";
    
    if ($result && strlen($result) > 0) {
        echo '<pre style="background:#eee; padding:10px; max-height:200px; overflow:auto;">';
        echo htmlspecialchars(substr($result, 0, 500));
        echo '</pre>';
    }
    curl_close($ch);
} else {
    echo '❌ cURL tidak tersedia, fallback ke file_get_contents<br>';
    $result = @file_get_contents($url);
    echo "Hasil: " . ($result ? strlen($result) . ' bytes' : 'GAGAL');
}

echo '<hr>';
echo '<p><strong>5. Jalankan getSheetData():</strong></p>';

// Sekarang coba panggil fungsi asli
$data = getSheetData($spreadsheet_id, $sheets['ehm_ruang']);

echo '<pre>';
var_dump($data);
echo '</pre>';
?>
