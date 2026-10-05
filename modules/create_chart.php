<style>
/* Layout .page-wrapper dan .table-header-section sudah didefinisikan
   secara global di assets/css/style.css. Di bawah ini hanya style
   yang spesifik untuk halaman Create Trend Chart. */

/* ===== CARD ===== */

.card{
    border-radius:12px;
}

.card-header{
    font-size:15px;
    letter-spacing:.3px;
}

/* ===== FORM ===== */

.form-label{
    font-size:13px;
    color:#555;
}

.form-control{
    border-radius:6px;
}

textarea.form-control{
    resize:none;
    font-family:monospace;
    font-size:13px;
}

/* ===== INPUT GROUP ===== */

.input-group-text{
    background:#f8f9fa;
    font-weight:600;
}

/* ===== BUTTON ===== */

.btn{
    border-radius:6px;
    font-weight:500;
}

.btn-primary{
    background:#3b82f6;
    border:none;
}

.btn-primary:hover{
    background:#2563eb;
}

.btn-outline-success:hover{
    background:#16a34a;
}

/* ===== CHART AREA ===== */

#captureArea{
    background:white;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
    padding:25px;
}

/* ===== CHART TITLE ===== */

#chartTitleHeader{
    font-weight:600;
    letter-spacing:.5px;
}

/* ===== STAT TABLE ===== */

.table{
    background:white;
}

.table th{
    font-size:13px;
}

.table td{
    font-size:14px;
}

/* ===== STAT COLOR ===== */

#statMin{
    color:#3498db;
}

#statMax{
    color:#e74c3c;
}

#statAvg{
    color:#78ab46;
}

/* ===== TEXTAREA EXCEL STYLE ===== */

#pasteArea{
    background:#fafafa;
    border:1px dashed #ccc;
}

#pasteArea:focus{
    border-color:#3b82f6;
    background:white;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

.col-lg-4{
    margin-bottom:20px;
}

}
</style>
<div class="page-wrapper" style="--accent:#3b82f6;--accent-dark:#2563eb;">
    <div class="table-header-section mb-3">
        <h2 class="fw-bold text-dark">📈 Instant Trend Chart Generator</h2>
    </div>

    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        ⚙ Chart Configuration
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Chart</label>
                            <input type="text" id="chartTitleInput" class="form-control" placeholder="Nama Produk/Parameter">
                        </div>
                        
                        <label class="form-label fw-semibold">Konfigurasi Parameter</label>
                        <div class="row mb-2">
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-danger fw-bold">USL</span>
                                    <input type="number" id="maxLimit" class="form-control" placeholder="Max" step="0.0001">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-primary fw-bold">LSL</span>
                                    <input type="number" id="minLimit" class="form-control" placeholder="Min" step="0.0001">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" style="font-size: 11px;" onclick="applyAutoLimit()">
                                ✨ Auto-Limit (UCL/LCL)
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearLimits()">
                                <i class="bi bi-trash"></i> Reset
                            </button>
                        </div>

                        <label class="form-label fw-semibold">Satuan Sumbu Y</label>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="unitType" id="unitNone" value="none" checked onchange="processData()">
                                <label class="form-check-label" for="unitNone">Angka Polos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="unitType" id="unitPercent" value="percent" onchange="processData()">
                                <label class="form-check-label" for="unitPercent">Persen (%)</label>
                            </div>
                        </div>

                        <label class="form-label fw-semibold">Satuan Performance</label>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="perfUnit" id="perfPercent" value="percent" checked onchange="processData()">
                                <label class="form-check-label" style="font-size: 13px;" for="perfPercent">Persen (%)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="perfUnit" id="perfPpm" value="ppm" onchange="processData()">
                                <label class="form-check-label" style="font-size: 13px;" for="perfPpm">PPM</label>
                            </div>
                        </div>

                        <label class="form-label fw-semibold">Opsi Statistik (Show/Hide)</label>
                        <div class="mb-3 px-2">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="toggleBasic" checked disabled>
                                <label class="form-check-label" style="font-size: 13px;">Basic (Min, Max, Avg)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="toggleControl" onchange="toggleRows()">
                                <label class="form-check-label" style="font-size: 13px;">Control Limits (Std Dev, UCL, LCL)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggleCapability" onchange="toggleRows()">
                                <label class="form-check-label" style="font-size: 13px;">Capability Analysis (Cp, Cpk)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="togglePvalue" onchange="toggleRows()">
                                <label class="form-check-label" style="font-size: 13px;">Trend Significance (p-value)</label>
                            </div>
                        </div>

                        <div class="p-3 mb-3 border rounded bg-light">
                            <label class="form-label fw-bold text-dark" style="font-size: 13px;">🧪 Advanced Analysis (Non-Normal)</label>
                            <select id="distMethod" class="form-select form-select-sm mb-2" onchange="processData()">
                                <option value="normal">Normal Distribution</option>
                                <option value="boxcox">Box-Cox Transformation</option>
                                <option value="weibull">Weibull Analysis</option>
                            </select>
                            <div id="lambdaDisplay" class="text-muted d-none" style="font-size: 11px;">
                                Calculated λ: <span id="lambdaVal" class="fw-bold">1.00</span>
                            </div>
                        </div>

                        <label class="form-label fw-semibold">Paste Data Excel</label>
                        <textarea id="pasteArea" class="form-control" rows="8" placeholder="Paste kolom angka di sini..."></textarea>
                        
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-primary" onclick="processData()">Generate Chart</button>
                            <button id="downloadBtn" class="btn btn-outline-success" disabled onclick="downloadChart()">Download PNG</button>
                            <button class="btn btn-outline-secondary" onclick="resetForm()">Reset Data</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <h5 class="mb-3 text-secondary">3. Preview Chart & Statistics</h5>
                <div id="captureArea" style="background: white; padding: 30px; border-radius: 8px; display: flex; flex-direction: column; align-items: center; width: 100%; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);">
                    
                    <div id="chartTitleHeader" style="width: 100%; text-align: center; font-weight: bold; font-size: 22px; color: #2d3748; margin-bottom: 20px; min-height: 30px;"></div>

                    <div style="height:400px; width:100%; margin-bottom: 30px; position: relative;">
                        <canvas id="trendChart"></canvas>
                    </div>

                    <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                        
                        <table class="table table-bordered text-center" style="width: 400px; font-size: 13px; border: 1px solid #dee2e6; margin-bottom: 15px;">
                            <thead class="table-light">
                                <tr style="background-color: #f8f9fa;">
                                    <th style="width: 55%;">Parameter</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Min</td><td id="statMin" style="font-weight: bold;">-</td></tr>
                                <tr><td>Max</td><td id="statMax" style="font-weight: bold;">-</td></tr>
                                <tr><td>Average</td><td id="statAvg" style="font-weight: bold; color: #78ab46;">-</td></tr>
                                <tr id="row-normality"><td>Normality (p-value)</td><td id="statNormality">-</td></tr>
                                <tr id="row-normality-note"><td>Data Distribution</td><td id="statNormalityNote" style="font-size: 11px;">-</td></tr>
                                <tr class="row-control d-none"><td>Std Dev</td><td id="statStd">-</td></tr>
                                <tr class="row-pvalue d-none"><td>Trend p-value</td><td id="statPvalue" style="font-weight: bold;">-</td></tr>
                                <tr class="row-pvalue d-none"><td>Interpretation</td><td id="statPNote" style="font-size: 11px;">-</td></tr>
                                <tr class="row-control d-none"><td>UCL (3σ)</td><td id="statUCL">-</td></tr>
                                <tr class="row-control d-none"><td>LCL (3σ)</td><td id="statLCL">-</td></tr>
                                <tr class="row-capability d-none"><td>Cp</td><td id="statCp">-</td></tr>
                                <tr class="row-capability d-none"><td>Cpk</td><td id="statCpk" style="font-weight: bold;">-</td></tr>
                                <tr class="row-capability d-none"><td>Ppk</td><td id="statPpk">-</td></tr>
                            </tbody>
                        </table>

                        <div class="row-capability d-none" style="width: 400px;">
                            <div class="text-center mb-1 fw-bold text-secondary" style="font-size: 12px;">Performance Analysis (<span id="perfUnitLabel">%</span>)</div>
                            <table class="table table-sm table-bordered text-center" style="font-size: 12px; border: 1px solid #dee2e6;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 34%;">Condition</th>
                                        <th style="width: 33%;">Observed</th>
                                        <th>Expected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="text-start ps-2"> < LSL </td><td id="perfObsLsl">-</td><td id="perfExpLsl">-</td></tr>
                                    <tr><td class="text-start ps-2"> > USL </td><td id="perfObsUsl">-</td><td id="perfExpUsl">-</td></tr>
                                    <tr class="table-warning fw-bold"><td>Total</td><td id="perfObsTotal">-</td><td id="perfExpTotal">-</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="aiInterpretation" style="width: 100%; max-width: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<script>
    function applyAutoLimit() {
    const rawData = document.getElementById('pasteArea').value;
    
    // Parsing data yang ada di textarea
    const dataValues = rawData.split(/\r?\n/)
        .map(line => line.trim().replace(',', '.'))
        .filter(line => line !== "" && !isNaN(line))
        .map(Number);

    if (dataValues.length < 2) {
        alert("Silakan paste data minimal 2 baris terlebih dahulu!");
        return;
    }

    // Hitung Statistik Dasar
    const sum = dataValues.reduce((a, b) => a + b, 0);
    const avg = sum / dataValues.length;
    const variance = dataValues.reduce((a, b) => a + Math.pow(b - avg, 2), 0) / dataValues.length;
    const std = Math.sqrt(variance);

    // Hitung UCL & LCL (3 Sigma)
    const ucl = avg + (3 * std);
    const lcl = avg - (3 * std);

    // Masukkan ke input box dengan presisi 4 desimal
    document.getElementById("maxLimit").value = ucl.toFixed(4);
    document.getElementById("minLimit").value = lcl.toFixed(4);

    // Jalankan fungsi utama untuk update chart & tabel secara otomatis
    processData();
}

function clearLimits() {
    document.getElementById("maxLimit").value = "";
    document.getElementById("minLimit").value = "";
    // Jalankan ulang agar garis limit di chart hilang
    processData();
}

function toggleRows() {
    // 1. Ambil status checkbox (dengan fallback false jika elemen tidak ditemukan)
    const showControl = document.getElementById('toggleControl')?.checked || false;
    const showCapability = document.getElementById('toggleCapability')?.checked || false;
    const showPvalue = document.getElementById('togglePvalue')?.checked || false;

    // 2. Sembunyikan/Tampilkan baris berdasarkan Class CSS
    // Menggunakan classList.toggle(namaClass, status) jauh lebih ringkas
    document.querySelectorAll('.row-control').forEach(row => {
        row.classList.toggle('d-none', !showControl);
    });

    document.querySelectorAll('.row-capability').forEach(row => {
        row.classList.toggle('d-none', !showCapability);
    });

    document.querySelectorAll('.row-pvalue').forEach(row => {
        row.classList.toggle('d-none', !showPvalue);
    });

    // 3. Jalankan ulang pemrosesan data jika ada isinya
    // Ini penting agar garis UCL/LCL di Chart ikut muncul/hilang secara real-time
    const rawData = document.getElementById('pasteArea').value;
    if (rawData && rawData.trim() !== "") {
        // Gunakan try-catch agar jika ada error di processData tidak menghentikan UI
        try {
            processData();
        } catch (err) {
            console.warn("Gagal memproses ulang data:", err);
        }
    }
}

function calculateNormality(data) {
    const n = data.length;
    if (n < 5) return null; // Statistik butuh minimal 5 data agar akurat

    const avg = data.reduce((a, b) => a + b, 0) / n;
    let s2 = 0, s3 = 0, s4 = 0;

    for (let i = 0; i < n; i++) {
        let diff = data[i] - avg;
        s2 += Math.pow(diff, 2);
        s3 += Math.pow(diff, 3);
        s4 += Math.pow(diff, 4);
    }

    const variance = s2 / n;
    const skewness = (s3 / n) / Math.pow(variance, 1.5);
    const kurtosis = (s4 / n) / Math.pow(variance, 2);

    // Jarque-Bera Test Statistic
    const jb = (n / 6) * (Math.pow(skewness, 2) + 0.25 * Math.pow(kurtosis - 3, 2));

    // Menghitung p-value dari Chi-Square distribution (df=2)
    const pValue = Math.exp(-0.5 * jb);
    return pValue;
}

// Fungsi untuk menghitung p-value (Linear Regression Trend)
function getTrendPValue(data) {
    const n = data.length;
    if (n < 2) return 1;

    let sumX = 0, sumY = 0, sumXY = 0, sumX2 = 0;
    for (let i = 0; i < n; i++) {
        sumX += i;
        sumY += data[i];
        sumXY += i * data[i];
        sumX2 += i * i;
    }

    const slope = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
    const intercept = (sumY - slope * sumX) / n;

    let ssE = 0;
    let ssX = sumX2 - (sumX * sumX) / n;
    for (let i = 0; i < n; i++) {
        let prediction = intercept + slope * i;
        ssE += Math.pow(data[i] - prediction, 2);
    }
    
    if (ssX === 0 || n <= 2) return 1;

    const seSlope = Math.sqrt((ssE / (n - 2)) / ssX);
    const tStat = Math.abs(slope / seSlope);

    // Pendekatan Distribusi Normal (Cukup akurat untuk deteksi tren)
    const p = 0.2316419, b1 = 0.319381530, b2 = -0.356563782, b3 = 1.781477937, b4 = -1.821255978, b5 = 1.330274429;
    const t = 1.0 / (1.0 + p * tStat);
    const sig = 1.0 - (1.0/Math.sqrt(2*Math.PI)) * Math.exp(-tStat*tStat/2.0) * t * (t * (t * (t * (t * b5 + b4) + b3) + b2) + b1);
    return 2 * (1 - sig);
}

// Fungsi pembantu untuk probabilitas normal
function cumulativeStdNormal(x) {
    const b1 = 0.319381530, b2 = -0.356563782, b3 = 1.781477937, b4 = -1.821255978, b5 = 1.330274429;
    const p = 0.2316419, c = 0.39894228;
    const t = 1.0 / (1.0 + p * Math.abs(x));
    const sig = 1.0 - c * Math.exp(-x * x / 2.0) * t * (t * (t * (t * (t * b5 + b4) + b3) + b2) + b1);
    return x >= 0 ? sig : 1.0 - sig;
}

function resetForm() {
    // 1. Bersihkan Input
    document.getElementById('chartTitleInput').value = '';
    document.getElementById('pasteArea').value = '';
    document.getElementById('maxLimit').value = '102';
    document.getElementById('minLimit').value = '98';
    
    // 2. Bersihkan Preview
    document.getElementById('chartTitleHeader').innerText = '';
    document.getElementById('statMin').innerText = '-';
    document.getElementById('statMax').innerText = '-';
    document.getElementById('statAvg').innerText = '-';
    document.getElementById('statStd').innerText = '-';
    document.getElementById('statUCL').innerText = '-';
    document.getElementById('statLCL').innerText = '-';
    document.getElementById('statCp').innerText = '-';
    document.getElementById('statCpk').innerText = '-';
    document.getElementById('statPpk').innerText = '-';
    document.getElementById('statPvalue').innerText = '-';
    document.getElementById('statPNote').innerText = '-';
    document.getElementById('statNormality').innerText = '-';
    document.getElementById('statNormalityNote').innerText = '-';
    document.getElementById('perfObsLsl').innerText = '-';
    document.getElementById('perfObsUsl').innerText = '-';
    document.getElementById('perfObsTotal').innerText = '-';
    
    // 3. Hapus Chart
    if (myChart) {
        myChart.destroy();
    }
    
    // 4. Matikan tombol download
    document.getElementById('downloadBtn').disabled = true;

    // Optional: Fokuskan kembali ke input judul
    document.getElementById('chartTitleInput').focus();
}
</script>
<script>
let myChart;

function processData() {
    // 1. Ambil Input Dasar
    const rawArea = document.getElementById('pasteArea').value;
    const chartTitleInput = document.getElementById('chartTitleInput').value.trim() || 'Trend Chart';
    const unitType = document.querySelector('input[name="unitType"]:checked').value;
    const method = document.getElementById('distMethod').value; 

    // 2. Parsing Data
    const dataValues = rawArea.split(/\r?\n/)
        .map(line => line.trim().replace(',', '.'))
        .filter(line => line !== "" && !isNaN(line))
        .map(Number);

    if (dataValues.length === 0) {
        alert("Data tidak valid! Masukkan kolom angka dari Excel.");
        return;
    }

    // 3. Logika Transformasi
    let processedValues = [...dataValues];
    let transformationNote = "";
    const lambdaDisplay = document.getElementById('lambdaDisplay');
    const lambdaVal = document.getElementById('lambdaVal');

    if (method === 'boxcox') {
        const result = applyBoxCox(dataValues); 
        processedValues = result.transformedData;
        if(lambdaDisplay) lambdaDisplay.classList.remove('d-none');
        if(lambdaVal) lambdaVal.innerText = result.lambda;
        transformationNote = `Box-Cox λ: ${result.lambda}`;
    } else {
        if(lambdaDisplay) lambdaDisplay.classList.add('d-none');
    }

    // 4. Update Statistik
    // UpdateStats harus menerima unitType dan method untuk perhitungan Cpk yang tepat
    const stats = updateStats(processedValues, unitType, method);

    // 5. Render Chart
    // URUTAN PARAMETER HARUS SAMA DENGAN DEFINISI DI RENDERCHART
    renderChart(dataValues, processedValues, chartTitleInput, unitType, stats, transformationNote);

    document.getElementById('downloadBtn').disabled = false;
}

function applyBoxCox(data) {
    // 1. Validasi: Box-Cox butuh data > 0. Jika ada 0/negatif, beri offset.
    const minData = Math.min(...data);
    const offset = minData <= 0 ? Math.abs(minData) + 1 : 0;
    const shiftedData = data.map(v => v + offset);

    // 2. Cari Lambda (λ) optimal menggunakan Log-Likelihood sederhana
    let bestLambda = 1;
    let minStdDev = Infinity;

    // Iterasi mencari λ dari -2 sampai 2
    for (let l = -2; l <= 2; l += 0.1) {
        const transformed = shiftedData.map(v => {
            if (Math.abs(l) < 0.01) return Math.log(v);
            return (Math.pow(v, l) - 1) / l;
        });

        // Hitung standar deviasi hasil transformasi
        const avg = transformed.reduce((a, b) => a + b) / transformed.length;
        const sd = Math.sqrt(transformed.reduce((a, b) => a + Math.pow(b - avg, 2), 0) / transformed.length);
        
        if (sd < minStdDev) {
            minStdDev = sd;
            bestLambda = l;
        }
    }

    // 3. Transformasi Akhir
    let transformedData = shiftedData.map(v => {
        if (Math.abs(bestLambda) < 0.01) return Math.log(v);
        return (Math.pow(v, bestLambda) - 1) / bestLambda;
    });

    // 4. NORMALISASI (PENTING!) 
    // Agar data tidak menjadi angka kecil (seperti 4.522), kita kembalikan ke skala aslinya
    // Ini menjaga grafik tetap sinkron dengan USL/LSL Anda.
    const origAvg = data.reduce((a, b) => a + b) / data.length;
    const transAvg = transformedData.reduce((a, b) => a + b) / transformedData.length;
    const origSD = Math.sqrt(data.reduce((a, b) => a + Math.pow(b - origAvg, 2), 0) / data.length);
    const transSD = Math.sqrt(transformedData.reduce((a, b) => a + Math.pow(b - transAvg, 2), 0) / transformedData.length);

    const finalData = transformedData.map(v => {
        return ((v - transAvg) / transSD) * origSD + origAvg;
    });

    return { 
        transformedData: finalData, 
        lambda: bestLambda.toFixed(2) 
    };
}

function calculateWeibullStats(data, usl, lsl) {
    // Pengurutan data untuk estimasi parameter
    const sorted = [...data].sort((a, b) => a - b);
    const n = sorted.length;
    
    // Estimasi sederhana parameter Weibull (Metode Least Squares pada Probability Plot)
    // ln(-ln(1-F(x))) = k ln(x) - k ln(lambda)
    // Ini memerlukan regresi linear pada log-log data.
    
    // ... (Logika regresi linear untuk mencari k dan lambda) ...

    // Setelah dapat k dan lambda, hitung Expected:
    // F(x) = 1 - exp(-(x/lambda)^k)
    // Expected < LSL = F(lsl)
    // Expected > USL = 1 - F(usl)
}

function updateStats(dataValues, unitType) {
    const suffix = unitType === 'percent' ? '%' : '';
    const sum = dataValues.reduce((a, b) => a + b, 0);
    const avg = sum / dataValues.length;
    const min = Math.min(...dataValues);
    const max = Math.max(...dataValues);

    const variance = dataValues.reduce((a, b) => a + Math.pow(b - avg, 2), 0) / dataValues.length;
    const std = Math.sqrt(variance);

    const ucl = avg + (3 * std);
    const lcl = avg - (3 * std);

    // Ambil nilai Limit dari input HTML
    const uslInput = document.getElementById("maxLimit").value;
    const lslInput = document.getElementById("minLimit").value;
    const hasLimits = uslInput !== "" && lslInput !== "";

    // Helper Presisi Adaptif
    const formatAdaptive = (val) => {
        if (val === 0) return "0.00";
        const absVal = Math.abs(val);
        if (absVal < 0.1) return val.toFixed(4);
        if (absVal < 10) return val.toFixed(3);
        return val.toFixed(2);
    };

    const showControl = document.getElementById('toggleControl').checked;
    const showCapability = document.getElementById('toggleCapability').checked;
    const showPvalue = document.getElementById('togglePvalue').checked;

    // 1. Basic Stats Update
    document.getElementById("statMin").innerText = formatAdaptive(min) + suffix;
    document.getElementById("statMax").innerText = formatAdaptive(max) + suffix;
    document.getElementById("statAvg").innerText = formatAdaptive(avg) + suffix;

    // 2. Control Limits Update
    if (showControl) {
        document.getElementById("statStd").innerText = std.toFixed(5);
        document.getElementById("statUCL").innerText = formatAdaptive(ucl) + suffix;
        document.getElementById("statLCL").innerText = formatAdaptive(lcl) + suffix;
    } else {
        document.getElementById("statStd").innerText = "-";
        document.getElementById("statUCL").innerText = "-";
        document.getElementById("statLCL").innerText = "-";
    }

    // 3. Capability & Performance Analysis
    if (showCapability && hasLimits && std > 0) {
        const usl = parseFloat(uslInput);
        const lsl = parseFloat(lslInput);
        
        // Hitung Cp & Cpk
        const cp = (usl - lsl) / (6 * std);
        const cpu = (usl - avg) / (3 * std);
        const cpl = (avg - lsl) / (3 * std);
        const cpk = Math.max(0, Math.min(cpu, cpl));

        const getStatColor = (val) => {
            if (val >= 1.33) return "#2ecc71";
            if (val >= 1.00) return "#f1c40f";
            return "#e74c3c";
        };

        document.getElementById("statCp").innerText = cp.toFixed(2);
        document.getElementById("statCpk").innerText = cpk.toFixed(2);
        document.getElementById("statCpk").style.color = getStatColor(cpk);
        if (document.getElementById("statPpk")) {
            document.getElementById("statPpk").innerText = cpk.toFixed(2);
            document.getElementById("statPpk").style.color = getStatColor(cpk);
        }

        // --- LOGIKA PERFORMANCE (PPM vs PERCENT) ---
        const perfUnit = document.querySelector('input[name="perfUnit"]:checked').value;
        const perfMultiplier = perfUnit === 'ppm' ? 10000 : 1;
        const perfDecimal = perfUnit === 'ppm' ? 0 : 4;
        document.getElementById("perfUnitLabel").innerText = perfUnit === 'ppm' ? 'PPM' : '%';

        // Observed (Aktual)
        const obsLslVal = (dataValues.filter(v => v < lsl).length / dataValues.length) * 100 * perfMultiplier;
        const obsUslVal = (dataValues.filter(v => v > usl).length / dataValues.length) * 100 * perfMultiplier;

        // Expected (Statistik)
        const normalCDF = (z) => {
            let t = 1 / (1 + 0.2316419 * Math.abs(z));
            let d = 0.3989423 * Math.exp(-z * z / 2);
            let p = d * t * (0.3193815 + t * (-0.3565638 + t * (1.781478 + t * (-1.821256 + t * 1.330274))));
            return z > 0 ? 1 - p : p;
        };

        const expLslVal = normalCDF((lsl - avg) / std) * 100 * perfMultiplier;
        const expUslVal = (1 - normalCDF((usl - avg) / std)) * 100 * perfMultiplier;

        // Update Tabel Performance
        document.getElementById("perfObsLsl").innerText = obsLslVal.toFixed(perfDecimal);
        document.getElementById("perfObsUsl").innerText = obsUslVal.toFixed(perfDecimal);
        document.getElementById("perfObsTotal").innerText = (obsLslVal + obsUslVal).toFixed(perfDecimal);
        document.getElementById("perfExpLsl").innerText = expLslVal.toFixed(perfDecimal);
        document.getElementById("perfExpUsl").innerText = expUslVal.toFixed(perfDecimal);
        document.getElementById("perfExpTotal").innerText = (expLslVal + expUslVal).toFixed(perfDecimal);

    } else {
        // Reset Capability & Performance
        document.getElementById("statCp").innerText = "-";
        document.getElementById("statCpk").innerText = "-";
        ["perfObsLsl", "perfObsUsl", "perfObsTotal", "perfExpLsl", "perfExpUsl", "perfExpTotal"].forEach(id => {
            if(document.getElementById(id)) document.getElementById(id).innerText = "-";
        });
    }

    // 4. Normalitas & Trend (P-Value)
    const normPValue = calculateNormality(dataValues);
    const normElem = document.getElementById("statNormality");
    const normNote = document.getElementById("statNormalityNote");
    let isNormal = true;

    if (normPValue !== null) {
        normElem.innerText = normPValue.toFixed(4);
        if (normPValue < 0.05) {
            normElem.style.color = "#e74c3c";
            normNote.innerText = "Non-Normal";
            normNote.style.color = "#e74c3c";
            isNormal = false;
        } else {
            normElem.style.color = "#2ecc71";
            normNote.innerText = "Normal";
            normNote.style.color = "#2ecc71";
        }
    }

    // AI Insight Message
    const pValueElem = document.getElementById("statPvalue");
    const pNoteElem = document.getElementById("statPNote");
    const aiBox = document.getElementById('aiInterpretation');
    let aiMessage = "";

    if (showPvalue) {
        const pVal = getTrendPValue(dataValues);
        pValueElem.innerText = pVal.toFixed(4);
        if (pVal < 0.05) {
            pValueElem.style.color = "#e74c3c";
            pNoteElem.innerText = "Significant Trend";
            aiMessage += `<li><strong>Trend Alert:</strong> Terdeteksi tren signifikan ($p < 0.05$).</li>`;
        } else {
            pValueElem.style.color = "#2ecc71";
            pNoteElem.innerText = "No Significant Trend";
        }
    }

    if (!isNormal && dataValues.length >= 5) {
        aiMessage += `<li><strong>Peringatan:</strong> Data tidak normal. Gunakan hasil $C_{pk}$ dengan hati-hati.</li>`;
    }

    if (aiBox) {
        aiBox.innerHTML = aiMessage !== "" ? `<div class="alert alert-warning mt-3 border-0 shadow-sm"><strong>💡 AI Insight:</strong><ul class="mb-0 mt-2">${aiMessage}</ul></div>` : "";
    }

    return { avg, std, ucl, lcl };
}

// URUTAN PARAMETER DISESUAIKAN:
// 1. dataValues (Asli)
// 2. processedValues (Hasil Transformasi)
// 3. title (Judul)
// 4. unitType (none/percent)
// 5. stats (Object statistik)
// 6. note (Catatan transformasi)
function renderChart(dataValues, processedValues, title, unitType, stats, note) {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    // Gabungkan judul dengan catatan transformasi (Box-Cox) jika ada
    const fullTitle = note ? `${title} (${note})` : title;
    document.getElementById("chartTitleHeader").innerText = fullTitle;

    if (window.myChart) window.myChart.destroy();

    // 1. AMBIL LIMIT DARI INPUT (Agar pewarnaan titik tetap akurat)
    const usl = parseFloat(document.getElementById('maxLimit').value);
    const lsl = parseFloat(document.getElementById('minLimit').value);

    // 2. HITUNG REGRESI LINIER (Garis Tren)
    const n = dataValues.length;
    let sumX = 0, sumY = 0, sumXY = 0, sumX2 = 0;
    for (let i = 0; i < n; i++) {
        const x = i + 1;
        const y = dataValues[i];
        sumX += x; sumY += y; sumXY += x * y; sumX2 += x * x;
    }
    const slope = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
    const intercept = (sumY - slope * sumX) / n;
    const trendData = dataValues.map((_, i) => slope * (i + 1) + intercept);

    // 3. HELPER FORMAT ANGKA
    const formatChartVal = (val) => {
        const absVal = Math.abs(val);
        if (absVal === 0) return "0.00";
        if (absVal < 0.1) return val.toFixed(4);
        if (absVal < 10) return val.toFixed(3);
        return val.toFixed(2);
    };

    // 4. TENTUKAN WARNA TITIK (Berdasarkan Data Asli vs USL/LSL/UCL/LCL)
    const pointColors = dataValues.map(v => {
        if (!isNaN(usl) && v > usl) return "#e74c3c"; // Merah (Out of Spec)
        if (!isNaN(lsl) && v < lsl) return "#e74c3c"; 
        if (v > stats.ucl || v < stats.lcl) return "#f39c12"; // Oranye (Out of Control)
        return "#78ab46"; // Hijau (Normal)
    });

    const showControl = document.getElementById('toggleControl').checked;

    // 5. SUSUN DATASET
    const datasets = [
        {
            label: 'Result',
            data: dataValues, // Tampilkan data asli di grafik
            borderColor: '#78ab46',
            borderWidth: 2,
            pointRadius: 5,
            pointBackgroundColor: pointColors,
            tension: 0.2
        },
        {
            label: 'Trendline',
            data: trendData,
            borderColor: '#3498db',
            borderWidth: 1.5,
            pointRadius: 0,
            borderDash: [4, 4]
        },
        {
            label: 'Mean',
            data: dataValues.map(() => stats.avg),
            borderColor: '#8e44ad',
            borderDash: [6, 6],
            pointRadius: 0,
            borderWidth: 1.5
        }
    ];

    // Tambahkan UCL & LCL
    if (showControl) {
        datasets.push(
            { label: 'UCL', data: dataValues.map(() => stats.ucl), borderColor: '#f39c12', borderDash: [5, 5], pointRadius: 0, borderWidth: 1 },
            { label: 'LCL', data: dataValues.map(() => stats.lcl), borderColor: '#f39c12', borderDash: [5, 5], pointRadius: 0, borderWidth: 1 }
        );
    }

    // Tambahkan USL & LSL (Gunakan variabel usl/lsl yang sudah di-parse)
    if (!isNaN(usl)) {
        datasets.push({ label: 'USL', data: dataValues.map(() => usl), borderColor: '#e74c3c', borderDash: [2, 2], pointRadius: 0, borderWidth: 2 });
    }
    if (!isNaN(lsl)) {
        datasets.push({ label: 'LSL', data: dataValues.map(() => lsl), borderColor: '#3498db', borderDash: [2, 2], pointRadius: 0, borderWidth: 2 });
    }

    // 6. INISIALISASI CHART
    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dataValues.map((_, i) => i + 1),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label + ': ' + formatChartVal(context.parsed.y);
                            return unitType === "percent" ? label + "%" : label;
                        }
                    }
                }
            },
            scales: {
                x: { title: { display: true, text: 'Data Ke-', font: { weight: 'bold' } } },
                y: {
                    ticks: {
                        callback: (value) => unitType === "percent" ? formatChartVal(value) + "%" : formatChartVal(value)
                    }
                }
            }
        }
    });
}

function downloadChart() {
    const element = document.getElementById('captureArea');
    const fileNameInput = document.getElementById('chartTitleInput').value.trim();
    
    // Memberikan nama file yang lebih spesifik (Timestamp)
    const dateStr = new Date().toISOString().slice(0, 10);
    const fileName = (fileNameInput || 'Trend_Chart') + '_' + dateStr;

    // Menampilkan loading state sederhana (opsional)
    const btn = document.getElementById('downloadBtn');
    const originalText = btn.innerText;
    btn.innerText = "Processing...";
    btn.disabled = true;

    html2canvas(element, {
        scale: 3, // Ditingkatkan ke 3 agar saat di-zoom di laporan tetap tajam
        backgroundColor: "#ffffff",
        useCORS: true, // Berguna jika ada resource eksternal
        logging: false,
        // Memastikan warna font (Merah/Hijau) dirender dengan tepat
        onclone: (clonedDoc) => {
            // Kita bisa memanipulasi elemen di dalam hasil download tanpa merubah tampilan asli
            const table = clonedDoc.querySelector('table');
            if(table) table.style.margin = "20px auto";
        }
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = fileName + '.png';
        link.href = canvas.toDataURL('image/png', 1.0); // Kualitas maksimal
        link.click();
        
        // Kembalikan tombol ke keadaan semula
        btn.innerText = originalText;
        btn.disabled = false;
    }).catch(err => {
        console.error("Download failed:", err);
        btn.innerText = originalText;
        btn.disabled = false;
    });
}
</script>