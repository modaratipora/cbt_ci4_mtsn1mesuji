<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> - CBT MTsN 1 Mesuji</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <style>
        body { font-family: 'Inter', sans-serif; user-select: none; }
        .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 9999; }
        .toast { padding: 0.75rem 1.25rem; border-radius: 0.5rem; color: white; min-width: 220px;
                 margin-bottom: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                 animation: toastIn 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .toast-success { background: #16a34a; }
        .toast-error   { background: #dc2626; }
        .toast-info    { background: #2563eb; }
        .toast-warning { background: #d97706; }
        @keyframes toastIn { from { transform: translateX(110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .nav-btn { width: 36px; height: 36px; border-radius: 6px; font-size: 12px; font-weight: 600;
                   display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid transparent; transition: all 0.15s; }
        .nav-btn-unanswered { background: #f3f4f6; color: #374151; border-color: #d1d5db; }
        .nav-btn-answered   { background: #16a34a; color: white; border-color: #15803d; }
        .nav-btn-ragu       { background: #f59e0b; color: white; border-color: #d97706; }
        .nav-btn-current    { outline: 3px solid #2563eb; outline-offset: 2px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000;
                         display: flex; align-items: center; justify-content: center; }
        .modal-card { background: white; border-radius: 0.75rem; padding: 1.5rem;
                      max-width: 480px; width: 90%; }
        .prose img { max-width: 100%; height: auto; }
    </style>
</head>
<body class="bg-gray-100">

<!-- Fixed Top Bar -->
<header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50 px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shrink-0">
            <i class="fas fa-pencil"></i>
        </div>
        <div>
            <h1 class="text-sm font-bold text-gray-800 leading-tight"><?= esc($pageTitle) ?></h1>
            <p class="text-xs text-gray-500"><?= esc(session()->get('user_nama')) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <!-- Timer -->
        <div id="timerDisplay" class="text-xl font-mono font-bold text-gray-800 bg-gray-100 px-3 py-1 rounded-lg min-w-[70px] text-center"></div>
        <!-- Submit button -->
        <button onclick="confirmSubmit()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-paper-plane"></i>
            <span class="hidden sm:inline">Submit Ujian</span>
        </button>
    </div>
</header>

<!-- Main Content -->
<div class="pt-16 flex h-screen overflow-hidden">

    <!-- Question Panel (left, ~70%) -->
    <main class="flex-1 overflow-y-auto p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Question Card -->
            <div id="questionCard" class="bg-white rounded-xl shadow p-6 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-500">
                        Soal <span id="qNumber">1</span> dari <?= count($soal_list) ?>
                    </span>
                    <span id="tipeBadge" class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">PG</span>
                </div>

                <!-- Question Text -->
                <div id="questionText" class="text-gray-800 mb-6 prose max-w-none text-sm md:text-base leading-relaxed"></div>

                <!-- Answer Area -->
                <div id="answerArea"></div>

                <!-- Ragu-ragu -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <label class="flex items-center gap-2 text-sm text-yellow-700 cursor-pointer select-none">
                        <input type="checkbox" id="raguCheck" onchange="onRaguChange()" class="rounded accent-yellow-500 w-4 h-4">
                        <i class="fas fa-question-circle text-yellow-500"></i>
                        Tandai Ragu-ragu
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between gap-3">
                <button onclick="prevQuestion()" id="btnPrev"
                    class="flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="fas fa-arrow-left"></i> Sebelumnya
                </button>
                <button onclick="nextQuestion()" id="btnNext"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Berikutnya <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </main>

    <!-- Navigation Panel (right, ~30%) -->
    <aside class="hidden md:flex flex-col w-72 bg-white shadow-l border-l border-gray-200 overflow-y-auto">
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Navigasi Soal</h2>
            <!-- Legend -->
            <div class="grid grid-cols-2 gap-1 text-xs mb-2">
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-gray-200 inline-block"></span>Belum dijawab</div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-green-500 inline-block"></span>Sudah dijawab</div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-yellow-400 inline-block"></span>Ragu-ragu</div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-blue-200 border-2 border-blue-600 inline-block"></span>Soal saat ini</div>
            </div>
        </div>
        <div class="p-4 flex-1">
            <div id="navGrid" class="flex flex-wrap gap-2"></div>
        </div>
        <!-- Summary -->
        <div class="p-4 border-t border-gray-100 text-xs text-gray-500 space-y-1">
            <div>Total soal: <strong class="text-gray-800"><?= count($soal_list) ?></strong></div>
            <div>Dijawab: <strong id="countAnswered" class="text-green-700">0</strong></div>
            <div>Ragu-ragu: <strong id="countRagu" class="text-yellow-700">0</strong></div>
            <div>Belum dijawab: <strong id="countUnanswered" class="text-red-700"><?= count($soal_list) ?></strong></div>
        </div>
    </aside>
</div>

<!-- Submit Confirmation Modal -->
<div id="modalSubmit" class="modal-overlay hidden">
    <div class="modal-card">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                <i class="fas fa-paper-plane text-emerald-600"></i>
            </div>
            <h3 class="text-lg font-semibold">Konfirmasi Submit Ujian</h3>
        </div>
        <div id="submitWarning" class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800 hidden">
            <i class="fas fa-triangle-exclamation mr-1"></i>
            <span id="submitWarningText"></span>
        </div>
        <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin submit ujian? Setelah submit, Anda tidak dapat mengubah jawaban lagi.</p>
        <div class="flex justify-end gap-3">
            <button onclick="document.getElementById('modalSubmit').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Kembali</button>
            <button onclick="doSubmit()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Ya, Submit
            </button>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div id="modalResult" class="modal-overlay hidden">
    <div class="modal-card text-center">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-trophy text-emerald-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Ujian Selesai!</h3>
        <p class="text-gray-500 text-sm mb-4">Hasil ujian Anda:</p>
        <div class="text-5xl font-bold text-emerald-600 mb-2" id="resultNilai">-</div>
        <p class="text-xs text-gray-400 mb-1">Benar: <span id="resultBenar">0</span></p>
        <p class="text-sm text-gray-500 mb-6">Anda akan diarahkan ke halaman ujian dalam <span id="redirectCount">5</span> detik...</p>
        <a href="/siswa/ruang-ujian" class="inline-block px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">
            Kembali ke Ruang Ujian
        </a>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" aria-live="polite"></div>

<script>
// ─── Data from PHP ─────────────────────────────────────────────────────────────
const SOAL_LIST  = <?= json_encode(array_values($soal_list)) ?>;
const RUANG      = <?= json_encode($ruang) ?>;
const HASIL      = <?= json_encode($hasil) ?>;
const CSRF_TOKEN = '<?= csrf_hash() ?>';
const CSRF_NAME  = '<?= csrf_token() ?>';
const BATAS_KELUAR = <?= (int)($ruang['batas_keluar'] ?? 3) ?>;

// ─── State ─────────────────────────────────────────────────────────────────────
let currentQ   = 0;
let sisa_waktu = <?= (int)$sisa_waktu ?>;
let jawaban    = <?= json_encode((object)$jawaban) ?>;
let keluarCount = 0;
let timerInterval;

// ─── Toast ─────────────────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info', warning: 'fa-triangle-exclamation' };
    const container = document.getElementById('toastContainer');
    const toast     = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas ${icons[type] ?? icons.info}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// ─── Timer ─────────────────────────────────────────────────────────────────────
function formatTime(secs) {
    const m = String(Math.floor(secs / 60)).padStart(2, '0');
    const s = String(secs % 60).padStart(2, '0');
    return `${m}:${s}`;
}

function startTimer() {
    const display = document.getElementById('timerDisplay');
    timerInterval = setInterval(() => {
        sisa_waktu--;
        display.textContent = formatTime(sisa_waktu);
        if (sisa_waktu <= 60) {
            display.classList.add('text-red-600');
            display.classList.remove('text-gray-800');
        }
        if (sisa_waktu <= 0) {
            clearInterval(timerInterval);
            showToast('Waktu habis! Ujian akan disubmit otomatis.', 'warning');
            setTimeout(() => doSubmit(true), 1500);
        }
    }, 1000);
    display.textContent = formatTime(sisa_waktu);
}

// ─── Navigation Grid ─────────────────────────────────────────────────────────
function buildNavGrid() {
    const grid = document.getElementById('navGrid');
    grid.innerHTML = '';
    SOAL_LIST.forEach((soal, idx) => {
        const btn = document.createElement('button');
        btn.id        = 'nav-' + idx;
        btn.textContent = idx + 1;
        btn.className = 'nav-btn';
        btn.onclick   = () => goToQuestion(idx);
        grid.appendChild(btn);
    });
    updateNavGrid();
}

function updateNavGrid() {
    let answered = 0, raguCount = 0;
    SOAL_LIST.forEach((soal, idx) => {
        const btn    = document.getElementById('nav-' + idx);
        const jw     = jawaban[soal.id];
        const hasJaw = jw && jw.jawaban !== null && jw.jawaban !== '' && jw.jawaban !== undefined;
        const isRagu = jw && jw.ragu;

        btn.className = 'nav-btn';
        if (idx === currentQ) btn.classList.add('nav-btn-current');
        if (isRagu)           { btn.classList.add('nav-btn-ragu'); raguCount++; }
        else if (hasJaw)      { btn.classList.add('nav-btn-answered'); answered++; }
        else                  { btn.classList.add('nav-btn-unanswered'); }
    });
    document.getElementById('countAnswered').textContent  = answered;
    document.getElementById('countRagu').textContent      = raguCount;
    document.getElementById('countUnanswered').textContent = SOAL_LIST.length - answered - raguCount;
}

// ─── Question Rendering ───────────────────────────────────────────────────────
function renderQuestion(idx) {
    const soal   = SOAL_LIST[idx];
    const jw     = jawaban[soal.id] ?? {};

    document.getElementById('qNumber').textContent  = idx + 1;
    document.getElementById('questionText').innerHTML = soal.pertanyaan ?? '';
    document.getElementById('btnPrev').disabled = (idx === 0);
    document.getElementById('btnNext').disabled = (idx === SOAL_LIST.length - 1);
    document.getElementById('raguCheck').checked = !!(jw.ragu);

    // Tipe badge
    const tipeColors = { PG: 'bg-blue-100 text-blue-700', Essay: 'bg-green-100 text-green-700', BS: 'bg-yellow-100 text-yellow-700', Menjodohkan: 'bg-purple-100 text-purple-700' };
    const badge = document.getElementById('tipeBadge');
    badge.textContent = soal.tipe_soal;
    badge.className   = `px-2 py-0.5 rounded text-xs font-medium ${tipeColors[soal.tipe_soal] ?? 'bg-gray-100 text-gray-700'}`;

    // Answer area
    const area = document.getElementById('answerArea');
    area.innerHTML = '';

    if (soal.tipe_soal === 'PG') {
        ['a','b','c','d','e'].forEach(opt => {
            const val = soal['pilihan_' + opt];
            if (!val) return;
            const label = document.createElement('label');
            label.className = 'flex items-start gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 mb-2 transition';
            const checked = jw.jawaban === opt.toUpperCase() ? 'checked' : '';
            label.innerHTML = `
                <input type="radio" name="jawaban" value="${opt.toUpperCase()}" ${checked} onchange="saveAnswer('${opt.toUpperCase()}')"
                    class="mt-0.5 accent-emerald-600 w-4 h-4 shrink-0">
                <span class="text-sm"><strong class="mr-1">${opt.toUpperCase()}.</strong><span class="prose max-w-none inline">${val}</span></span>`;
            if (checked) label.classList.add('border-emerald-400', 'bg-emerald-50');
            area.appendChild(label);
        });
    } else if (soal.tipe_soal === 'Essay') {
        const ta = document.createElement('textarea');
        ta.name = 'jawaban';
        ta.rows = 5;
        ta.placeholder = 'Tuliskan jawaban Anda di sini...';
        ta.className = 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 resize-y';
        ta.value = jw.jawaban ?? '';
        ta.addEventListener('input', () => saveAnswer(ta.value));
        area.appendChild(ta);
    } else if (soal.tipe_soal === 'BS') {
        ['Benar','Salah'].forEach(val => {
            const label = document.createElement('label');
            label.className = 'flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 mb-2 transition';
            const checked = jw.jawaban === val ? 'checked' : '';
            label.innerHTML = `
                <input type="radio" name="jawaban" value="${val}" ${checked} onchange="saveAnswer('${val}')"
                    class="accent-emerald-600 w-4 h-4">
                <span class="text-sm font-medium">${val}</span>`;
            if (checked) label.classList.add('border-emerald-400', 'bg-emerald-50');
            area.appendChild(label);
        });
    } else if (soal.tipe_soal === 'Menjodohkan') {
        const desc = document.createElement('p');
        desc.className = 'text-xs text-gray-500 mb-3';
        desc.textContent = 'Pilih pasangan yang sesuai untuk setiap pernyataan.';
        area.appendChild(desc);
        const rightOptions = ['a','b','c','d','e'].map(o => soal['pilihan_' + o]).filter(Boolean);
        ['a','b','c','d','e'].forEach((opt, i) => {
            const left = soal['pilihan_' + opt];
            if (!left) return;
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 mb-3';
            const savedVal = (() => {
                try {
                    const kunci = JSON.parse(jw.jawaban ?? '{}');
                    return kunci[opt] ?? '';
                } catch { return ''; }
            })();
            row.innerHTML = `
                <span class="flex-1 text-sm">${left}</span>
                <select onchange="saveMenjodohkan('${opt}', this.value)"
                    class="w-40 border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="">-- Pilih --</option>
                    ${rightOptions.map(o => `<option value="${o}" ${savedVal === o ? 'selected' : ''}>${o}</option>`).join('')}
                </select>`;
            area.appendChild(row);
        });
    }
}

// ─── Answer Saving ─────────────────────────────────────────────────────────────
let saveTimeout = null;
function saveAnswer(value) {
    const soal = SOAL_LIST[currentQ];
    if (!jawaban[soal.id]) jawaban[soal.id] = {};
    jawaban[soal.id].jawaban = value;
    jawaban[soal.id].ragu    = document.getElementById('raguCheck').checked;
    updateNavGrid();
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => persistAnswer(soal.id, value, jawaban[soal.id].ragu), 300);
}

function saveMenjodohkan(opt, value) {
    const soal = SOAL_LIST[currentQ];
    if (!jawaban[soal.id]) jawaban[soal.id] = {};
    let kunci = {};
    try { kunci = JSON.parse(jawaban[soal.id].jawaban ?? '{}'); } catch {}
    kunci[opt] = value;
    jawaban[soal.id].jawaban = JSON.stringify(kunci);
    jawaban[soal.id].ragu    = document.getElementById('raguCheck').checked;
    updateNavGrid();
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => persistAnswer(soal.id, jawaban[soal.id].jawaban, jawaban[soal.id].ragu), 300);
}

function onRaguChange() {
    const soal = SOAL_LIST[currentQ];
    if (!jawaban[soal.id]) jawaban[soal.id] = {};
    jawaban[soal.id].ragu = document.getElementById('raguCheck').checked;
    updateNavGrid();
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => persistAnswer(soal.id, jawaban[soal.id].jawaban ?? null, jawaban[soal.id].ragu), 300);
}

async function persistAnswer(soalId, jawabanVal, ragu) {
    try {
        await fetch('/siswa/ujian/save-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                [CSRF_NAME]: CSRF_TOKEN,
                ruang_id:   RUANG.id,
                soal_id:    soalId,
                jawaban:    jawabanVal,
                ragu:       ragu ? 1 : 0,
                sisa_waktu: sisa_waktu,
            }),
        });
    } catch {}
}

// ─── Navigation ────────────────────────────────────────────────────────────────
function goToQuestion(idx) {
    currentQ = idx;
    renderQuestion(idx);
    updateNavGrid();
    document.getElementById('questionCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function prevQuestion() {
    if (currentQ > 0) goToQuestion(currentQ - 1);
}

function nextQuestion() {
    if (currentQ < SOAL_LIST.length - 1) goToQuestion(currentQ + 1);
}

// ─── Submit ─────────────────────────────────────────────────────────────────────
function confirmSubmit() {
    const unanswered = SOAL_LIST.filter(s => !jawaban[s.id]?.jawaban && jawaban[s.id]?.jawaban !== 0).length;
    const warn = document.getElementById('submitWarning');
    const warnText = document.getElementById('submitWarningText');
    if (unanswered > 0) {
        warn.classList.remove('hidden');
        warnText.textContent = `${unanswered} soal belum dijawab. Soal yang belum dijawab akan dihitung salah.`;
    } else {
        warn.classList.add('hidden');
    }
    document.getElementById('modalSubmit').classList.remove('hidden');
}

async function doSubmit(autoSubmit = false) {
    clearInterval(timerInterval);
    document.getElementById('modalSubmit').classList.add('hidden');

    try {
        const res = await fetch('/siswa/ujian/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                [CSRF_NAME]: CSRF_TOKEN,
                ruang_id: RUANG.id,
            }),
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('resultNilai').textContent = data.nilai;
            document.getElementById('resultBenar').textContent = data.jml_benar;
            document.getElementById('modalResult').classList.remove('hidden');
            let count = 5;
            const countEl = document.getElementById('redirectCount');
            const ri = setInterval(() => {
                count--;
                countEl.textContent = count;
                if (count <= 0) { clearInterval(ri); window.location.href = data.redirect ?? '/siswa/ruang-ujian'; }
            }, 1000);
        } else {
            showToast(data.message ?? 'Gagal submit ujian.', 'error');
            startTimer();
        }
    } catch (e) {
        showToast('Terjadi kesalahan jaringan. Mencoba lagi...', 'error');
        startTimer();
    }
}

// ─── Tab/Focus Detection ────────────────────────────────────────────────────────
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        keluarCount++;
        showToast(`Peringatan: Jangan berpindah halaman! (${keluarCount}/${BATAS_KELUAR})`, 'warning');
        if (BATAS_KELUAR > 0 && keluarCount >= BATAS_KELUAR) {
            showToast('Batas perpindahan halaman tercapai. Ujian disubmit otomatis.', 'error');
            setTimeout(() => doSubmit(true), 1500);
        }
    }
});

window.addEventListener('blur', () => {
    if (!document.hidden) {
        keluarCount++;
        showToast(`Peringatan: Jangan berpindah jendela! (${keluarCount}/${BATAS_KELUAR})`, 'warning');
        if (BATAS_KELUAR > 0 && keluarCount >= BATAS_KELUAR) {
            showToast('Batas perpindahan jendela tercapai. Ujian disubmit otomatis.', 'error');
            setTimeout(() => doSubmit(true), 1500);
        }
    }
});

// ─── Anti Back-button ─────────────────────────────────────────────────────────
history.pushState(null, '', location.href);
window.addEventListener('popstate', () => {
    history.pushState(null, '', location.href);
    showToast('Tidak dapat kembali selama ujian berlangsung.', 'warning');
});

// ─── Init ─────────────────────────────────────────────────────────────────────
buildNavGrid();
renderQuestion(0);
startTimer();
</script>
</body>
</html>
