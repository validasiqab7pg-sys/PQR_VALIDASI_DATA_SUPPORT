<style>
  /* Layout sidebar sudah ditangani secara global oleh assets/css/style.css.
     Di sini cukup style yang spesifik untuk halaman ini. */
  .content-wrapper {
    padding: 20px;
  }
</style>
<div class="content-wrapper">
    <div class="header-section">
        <h2>🔄 Data Format Converter</h2>
        <p>Gunakan alat ini untuk menyeragamkan format desimal (Titik vs Koma).</p>
    </div>

    <div class="control-panel" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px;">
        <strong>Konversi Desimal Ke:</strong><br>
        <div style="margin-top: 10px;">
            <input type="radio" id="toDot" name="targetFormat" value="." checked>
            <label for="toDot"><b>Titik (.)</b> Contoh: 1250.50</label>
            
            <input type="radio" id="toComma" name="targetFormat" value="," style="margin-left: 20px;">
            <label for="toComma"><b>Koma (,)</b> Contoh: 1250,50</label>
        </div>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <label>Raw Data (Input):</label>
            <textarea id="inputData" rows="12" style="width: 100%; margin-top: 5px; padding: 10px;" placeholder="Paste data dari Excel di sini..."></textarea>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <label>Clean Data (Output):</label>
            <textarea id="outputData" rows="12" style="width: 100%; margin-top: 5px; padding: 10px; background: #fffde7;" readonly></textarea>
        </div>
    </div>

    <div style="margin-top: 15px;">
        <button onclick="convertData()" style="padding: 10px 25px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Konversi Sekarang
        </button>
        
        <button onclick="copyResult()" style="padding: 10px 25px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Copy Hasil
        </button>

        <button onclick="clearAll()" style="padding: 10px 25px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Reset
        </button>
    </div>
</div>

<div class="content-wrapper">
    <h2>📊 AHU Trend Comparison (CW1 - CW3)</h2>
    <div style="margin-left: 20px;">
        <label><b>Jenis Trend:</b></label>
        <input type="text" id="judulTrend" placeholder="LUX / Particle / Suhu / etc" style="padding: 8px; width: 120px; margin-left: 5px;">
    </div>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;">
        <label><b>Target AHU:</b></label>
        <input type="text" id="filterAHU" placeholder="Contoh: 101" style="padding: 8px; width: 150px; margin-left: 10px;">
        
        <span style="margin-left: 20px;"><b>Format Desimal:</b></span>
        <input type="radio" name="dec" value="." checked> Titik (.) 
        <input type="radio" name="dec" value="," style="margin-left:5px;"> Koma (,)

        <button onclick="generateWeeklyTrend()" style="margin-left: 20px; padding: 10px 25px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Generate Comparison Chart
        </button>
        <button onclick="downloadChart()" style="margin-left: 10px; padding: 10px 20px; background: #34495e; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            📥 Download Chart as PNG
        </button>
    </div>
    <div id="previewContainer" style="display:none; margin-top: 20px; background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
        <h4 style="margin-top:0;">🔍 Preview Data Terdeteksi (Target AHU: <span id="previewAHU"></span>)</h4>
        <div style="max-height: 200px; overflow-y: auto;">
            <table id="tablePreview" style="width:100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: #eee; text-align: left;">
                        <th style="padding: 8px; border: 1px solid #ddd;">Ruangan</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Titik</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">CW 1</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">CW 2</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">CW 3</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <label><b>Ambil Data:</b></label>
    <select id="columnOffset" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
        <option value="1">Kolom 1 (Particle 0.5 / LUX)</option>
        <option value="2">Kolom 2 (Misal: Particle 5.0)</option>
        <option value="3">Kolom 3 (Lainnya)</option>
    </select>
    <div style="display: flex; gap: 15px; align-items: center;">
        <div>
            <label><b>Min (Syarat):</b></label>
            <input type="number" id="minLimit" placeholder="300" style="padding: 8px; width: 70px;">
        </div>
        <div>
            <label><b>Max (Syarat):</b></label>
            <input type="number" id="maxLimit" placeholder="500" style="padding: 8px; width: 70px;">
        </div>
    </div>

    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
        <div style="flex: 1;">
            <label><b>Data CW 1:</b></label>
            <textarea id="dataCW1" rows="8" style="width:100%; margin-top:5px; font-size: 11px;" placeholder="Paste data CW1..."></textarea>
        </div>
        <div style="flex: 1;">
            <label><b>Data CW 2:</b></label>
            <textarea id="dataCW2" rows="8" style="width:100%; margin-top:5px; font-size: 11px;" placeholder="Paste data CW2..."></textarea>
        </div>
        <div style="flex: 1;">
            <label><b>Data CW 3:</b></label>
            <textarea id="dataCW3" rows="8" style="width:100%; margin-top:5px; font-size: 11px;" placeholder="Paste data CW3..."></textarea>
        </div>
    </div>

    <canvas id="multiTrendChart" style="max-height: 400px; background: #fff; border: 1px solid #eee;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
function convertData() {
    const input = document.getElementById('inputData').value;
    const target = document.querySelector('input[name="targetFormat"]:checked').value;
    
    // 1. Deteksi data: Apakah dipisah baris baru (\n), Tab (\t), atau Spasi ( )
    // Ini menangani data yang dicopy menyamping dari Excel
    let rawElements = [];
    if (input.includes('\t')) {
        rawElements = input.split('\t'); // Split jika dari kolom Excel
    } else if (input.includes(' ')) {
        rawElements = input.split(/\s+/); // Split jika dipisah spasi
    } else {
        rawElements = input.split('\n'); // Split jika sudah baris demi baris
    }
    
    const processedRows = rawElements.map(item => {
        let val = item.trim();
        if (val === "") return null;

        // Pembersihan format (Titik/Koma)
        if (val.includes('.') && val.includes(',')) {
            val = val.replace(/\./g, '').replace(',', '.');
        } else if (val.includes(',')) {
            val = val.replace(',', '.');
        }

        let num = parseFloat(val);
        if (isNaN(num)) return val; // Jika teks, biarkan teks

        // Ubah ke target format
        return target === "," ? num.toString().replace('.', ',') : num.toString();
    }).filter(v => v !== null);

    // 2. Output selalu dalam bentuk KOLOM (Baris Baru)
    document.getElementById('outputData').value = processedRows.join('\n');
}

function copyResult() {
    const output = document.getElementById('outputData');
    output.select();
    document.execCommand('copy');
    alert('Data berhasil disalin ke clipboard!');
}

function clearAll() {
    document.getElementById('inputData').value = '';
    document.getElementById('outputData').value = '';
}
</script>
<script>
let multiChart;

/**
 * Fungsi Parsing: Sekarang dilengkapi dengan pembersih Line Break (Pindah Baris)
 */
function parseWeeklyData(text, targetAHU) {
    const rows = text.split('\n');
    let dataMap = {}; 
    let lastAHU = "", lastRuangan = "";
    
    // Ambil nilai offset dari dropdown (Default 1)
    const offset = parseInt(document.getElementById('columnOffset').value) || 1;

    rows.forEach(row => {
        // Pisahkan kolom berdasarkan Tab (khas hasil copy dari Sheets)
        const cols = row.split('\t').map(c => c.trim());
        
        // 1. Deteksi AHU
        let foundAHU = cols.find(c => /^\d{3}$/.test(c));
        if (foundAHU) lastAHU = foundAHU;

        // 2. Deteksi Nama Ruangan
        let foundRuangan = cols.find(c => c.length > 5 && !/^[XY]\d+$/i.test(c) && !/^\d+$/.test(c));
        if (foundRuangan) lastRuangan = foundRuangan;

        // 3. Deteksi Titik Sampling (X1, Y1, dll)
        let titikIndex = cols.findIndex(c => /^[XY]\d+$/i.test(c));

        // 4. EKSEKUSI: Ambil angka berdasarkan jarak dari Titik Sampling
        if (lastAHU === targetAHU && titikIndex !== -1) {
            let foundTitik = cols[titikIndex];
            
            // Ambil sel target (Titik + Offset)
            // Jika offset=1, ambil tepat di kanan X1 (biasanya Particle 0.5)
            let targetCell = cols[titikIndex + offset]; 

            if (targetCell) {
                // Pembersihan format desimal/pecahan
                if (targetCell.includes('/')) targetCell = targetCell.split('/')[0];
                let cleanVal = targetCell.replace(/\./g, '').replace(',', '.');
                let parsed = parseFloat(cleanVal);

                if (!isNaN(parsed)) {
                    let labelKey = `${lastRuangan} - ${foundTitik.toUpperCase()}`;
                    dataMap[labelKey] = parsed;
                }
            }
        }
    });
    return dataMap;
}

/**
 * Fungsi Sinkronisasi: Memastikan urutan titik sampling sama di CW1, CW2, dan CW3
 */
function generateWeeklyTrend() {
    const targetAHU = document.getElementById('filterAHU').value.trim();
    const judul = document.getElementById('judulTrend').value.trim() || "Data";
    const minVal = parseFloat(document.getElementById('minLimit').value) || null;
    const maxVal = parseFloat(document.getElementById('maxLimit').value) || null;
    const decFormat = document.querySelector('input[name="dec"]:checked').value;

    if (!targetAHU) return alert("Masukkan nomor AHU!");

    const cw1Map = parseWeeklyData(document.getElementById('dataCW1').value, targetAHU);
    const cw2Map = parseWeeklyData(document.getElementById('dataCW2').value, targetAHU);
    const cw3Map = parseWeeklyData(document.getElementById('dataCW3').value, targetAHU);

    const allLabels = Array.from(new Set([...Object.keys(cw1Map), ...Object.keys(cw2Map), ...Object.keys(cw3Map)])).sort();

    updatePreviewTable(allLabels, cw1Map, cw2Map, cw3Map, targetAHU);

    const syncV1 = allLabels.map(l => cw1Map[l] || 0);
    const syncV2 = allLabels.map(l => cw2Map[l] || 0);
    const syncV3 = allLabels.map(l => cw3Map[l] || 0);
    
    renderMultiChart(allLabels, syncV1, syncV2, syncV3, 
                     minVal ? allLabels.map(() => minVal) : [], 
                     maxVal ? allLabels.map(() => maxVal) : [], 
                     decFormat, targetAHU, judul);
}

function updatePreviewTable(labels, m1, m2, m3, ahu) {
    const tbody = document.querySelector('#tablePreview tbody');
    document.getElementById('previewAHU').innerText = ahu;
    tbody.innerHTML = labels.map(label => {
        const p = label.split(' - ');
        return `<tr>
            <td style="padding:8px; border:1px solid #ddd;">${p[0]}</td>
            <td style="padding:8px; border:1px solid #ddd;"><b>${p[1]}</b></td>
            <td style="padding:8px; border:1px solid #ddd; color:#3498db;">${m1[label] || '-'}</td>
            <td style="padding:8px; border:1px solid #ddd; color:#e67e22;">${m2[label] || '-'}</td>
            <td style="padding:8px; border:1px solid #ddd; color:#2ecc71;">${m3[label] || '-'}</td>
        </tr>`;
    }).join('');
    document.getElementById('previewContainer').style.display = 'block';
}
Chart.register(ChartDataLabels); 

// Tambahkan parameter 'judul' di akhir baris ini
function renderMultiChart(labels, v1, v2, v3, minLine, maxLine, decFormat, ahu, judul) {
    const ctx = document.getElementById('multiTrendChart').getContext('2d');
    if (window.multiChart) window.multiChart.destroy();

    // Fungsi pembantu untuk format ribuan (e.g., 352000 -> 352.000)
    const formatNumber = (num) => {
        if (num === 0 || num === null) return '';
        let formatted = num.toString();
        if (num >= 1000) {
            formatted = new Intl.NumberFormat('id-ID').format(num);
        }
        return decFormat === "," ? formatted.replace('.', ',') : formatted;
    };

    const datasets = [
        { 
            label: 'CW 1', data: v1, borderColor: '#3498db', borderWidth: 2, tension: 0.1, 
            datalabels: { align: 'start', anchor: 'start', offset: 4 } 
        },
        { 
            label: 'CW 2', data: v2, borderColor: '#e67e22', borderWidth: 2, tension: 0.1, 
            datalabels: { align: 'end', anchor: 'end', offset: 4 } 
        },
        { 
            label: 'CW 3', data: v3, borderColor: '#2ecc71', borderWidth: 2, tension: 0.1, 
            datalabels: { align: 'top', offset: 10 } 
        }
    ];

    if (minLine.length > 0) {
        datasets.push({ 
            label: 'Batas Minimal', data: minLine, borderColor: '#e74c3c', 
            borderDash: [5, 5], pointRadius: 0, fill: false, datalabels: { display: false } 
        });
    }
    if (maxLine.length > 0) {
        datasets.push({ 
            label: 'Batas Maksimal', data: maxLine, borderColor: '#f1c40f', 
            borderDash: [5, 5], pointRadius: 0, fill: false, datalabels: { display: false } 
        });
    }

    window.multiChart = new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            scales: {
                // Di dalam objek options: { scales: { y: { ... } } }
                y: { 
                    beginAtZero: true,
                    grace: '5%', // Memberikan ruang kosong 5% di atas titik tertinggi agar label tidak terpotong
                    title: { display: true, text: `Nilai ${judul}` },
                    ticks: {
                        callback: function(value) { return formatNumber(value); }
                    }
                }
            },
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: `Trend ${judul} AHU ${ahu}` },
                datalabels: {
                    color: '#444',
                    font: { size: 10, weight: 'bold' },
                    formatter: (value) => formatNumber(value),
                    padding: 4
                }
            }
        }
    });
}
function downloadChart() {
    // 1. Cek apakah chart sudah dibuat
    if (!window.multiChart) {
        return alert("Silakan generate chart terlebih dahulu!");
    }

    // 2. Ambil elemen canvas
    const canvas = document.getElementById('multiTrendChart');
    const targetAHU = document.getElementById('filterAHU').value || 'Data';

    // 3. Buat link download sementara
    const link = document.createElement('a');
    link.id = 'download-link';
    
    // 4. Konversi canvas ke URL Gambar (PNG)
    // Kita berikan background putih agar gambar tidak transparan saat disimpan
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    const ctx = tempCanvas.getContext('2d');
    
    // Isi background putih
    ctx.fillStyle = "#FFFFFF";
    ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
    
    // Gambar chart di atas background putih
    ctx.drawImage(canvas, 0, 0);

    // 5. Eksekusi Download
    link.href = tempCanvas.toDataURL('image/png');
    link.download = `Trend_AHU_${targetAHU}_${new Date().toLocaleDateString()}.png`;
    link.click();
}
</script>