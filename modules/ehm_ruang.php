<?php
$data = getSheetData($spreadsheet_id, $sheets['ehm_ruang']);

$headers = [];
$rows = [];

if (!empty($data)) {
    $headers = $data[0];
    $rows = array_slice($data, 1);
}

// URL langsung ke spreadsheet sheet "ehm_ruang"
$spreadsheet_url = "https://docs.google.com/spreadsheets/d/$spreadsheet_id/edit#gid=" . $sheets['ehm_ruang'];
?>

<div class="page-wrapper">
    <div class="table-header-section">
        <h2>📅 EHM Ruang</h2>

        <div class="action-buttons" style="display: flex; gap: 10px;">
            <a href="<?= htmlspecialchars($spreadsheet_url) ?>" 
               target="_blank" 
               class="btn btn-primary"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; text-decoration: none; border-radius: 4px; background-color: #f39c12; color: white; font-weight: bold;">
                ✏️ Edit di Spreadsheet
            </a>
        </div>
    </div>

    <?php if (!empty($data) && count($rows) > 0): ?>
        <div class="table-responsive">
            <table class="custom-table" id="ehmRuangTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <?php foreach ($headers as $header): ?>
                            <th><?= htmlspecialchars(trim($header)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $index => $row): ?>
                        <?php 
                        // Skip baris kosong
                        if (empty(array_filter($row))) continue;
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <?php foreach ($row as $cell): ?>
                                <td>
                                    <?php 
                                    $cellValue = trim((string)$cell);
                                    // Jika berupa URL, tampilkan sebagai link
                                    if (filter_var($cellValue, FILTER_VALIDATE_URL)) {
                                        echo '<a href="' . htmlspecialchars($cellValue) . '" target="_blank" class="link-action">Buka Link</a>';
                                    } else {
                                        echo htmlspecialchars($cellValue);
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <p>Belum ada data. Klik tombol <strong>"Edit di Spreadsheet"</strong> untuk menambahkan data.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #f39c12;
    color: white;
}

.btn-primary:hover {
    background-color: #e67e22;
    text-decoration: none;
}

.link-action {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.link-action:hover {
    text-decoration: underline;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin: 15px 0;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}
</style>