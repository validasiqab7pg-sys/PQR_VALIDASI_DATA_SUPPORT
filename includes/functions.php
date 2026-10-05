<?php
/**
 * Ambil data dari Google Sheets (published CSV) dengan caching lokal.
 *
 * Sebelumnya setiap load halaman melakukan HTTP request langsung ke Google
 * (via fopen, tanpa timeout) setiap kali user membuka halaman. Ini lambat
 * dan rawan gagal jika Google lambat merespons / network bermasalah.
 *
 * Sekarang: hasil fetch disimpan sebagai cache file selama $ttl detik.
 * Selama cache masih segar, tidak ada request ke Google sama sekali.
 * Jika fetch gagal (network error / timeout) dan ada cache lama, cache lama
 * dipakai sebagai fallback daripada menampilkan error ke user.
 *
 * @param string $spreadsheet_id  (disimpan agar signature tetap kompatibel)
 * @param string|int $gid         GID sheet tujuan
 * @param int $ttl                Umur cache dalam detik (default 5 menit)
 * @return array                  Array baris CSV (baris pertama = header)
 */
function getSheetData($spreadsheet_id, $gid, $ttl = 300) {

    $baseUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vQk4h1pRUie6Yz2GiWz51N3-2xgnPvyWBX75l67YYBd3pPuFDb3KWbqqk9faVs2CDk_cUVsesBpTRuz/pub?output=csv";
    $url = "https://docs.google.com/spreadsheets/d/$spreadsheet_id/export?format=csv&gid=" . urlencode($gid);

    $cacheDir = __DIR__ . '/../cache';
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0775, true);
    }
    $cacheFile = $cacheDir . '/sheet_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $gid) . '.csv';

    // 1. Cache masih segar -> langsung pakai, tidak usah hit Google.
    if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return parseCsvFile($cacheFile);
    }

    // 2. Ambil data baru dari Google (pakai cURL supaya ada timeout & error handling).
    $raw = fetchUrlWithTimeout($url, 8);

    if ($raw !== false && trim($raw) !== '') {
        // Simpan ke cache untuk request berikutnya, lalu parse dari file
        // (fgetcsv menangani field ber-quote yang mengandung newline dengan benar,
        // beda dengan split per baris yang naif).
        @file_put_contents($cacheFile, $raw);
        return parseCsvFile($cacheFile);
    }

    // 3. Fetch gagal -> pakai cache lama walau sudah kedaluwarsa (lebih baik
    //    data agak basi daripada halaman error total).
    if (is_file($cacheFile)) {
        return parseCsvFile($cacheFile);
    }

    // 4. Tidak ada data sama sekali.
    return [["Error: Gagal mengambil data dari Google Sheets. Silakan coba lagi."]];
}

/**
 * Fetch URL dengan cURL, timeout singkat, tanpa memblokir halaman terlalu lama.
 * Fallback ke file_get_contents dengan stream context jika cURL tidak tersedia.
 */
function fetchUrlWithTimeout($url, $timeoutSeconds = 8) {

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $timeoutSeconds,
            CURLOPT_TIMEOUT        => $timeoutSeconds,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_errno($ch);


        if ($error === 0 && $httpCode >= 200 && $httpCode < 300) {
            return $result;
        }
        return false;
    }

    // Fallback jika ekstensi cURL tidak ada di server.
    $context = stream_context_create([
        'http' => ['timeout' => $timeoutSeconds],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);
    $result = @file_get_contents($url, false, $context);
    return $result === false ? false : $result;
}

function parseCsvFile($path) {
    $data = [];
    if (($handle = fopen($path, "r")) !== false) {
        while (($row = fgetcsv($handle, 0, ",", '"', '')) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}
