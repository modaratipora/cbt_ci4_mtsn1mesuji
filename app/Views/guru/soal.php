<?= $this->extend('layout/main_guru') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="/guru/bank-soal" class="hover:text-emerald-600"><i class="fas fa-file-lines mr-1"></i>Bank Soal</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-800 font-medium"><?= esc($bankSoal['nama_bank']) ?></span>
</nav>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openSoalModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Soal
    </button>
</div>

<!-- Bulk Actions Bar -->
<div id="bulkBar" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
    <span id="selectedCount" class="text-sm font-medium text-yellow-800">0 dipilih</span>
    <form method="POST" action="/guru/soal/<?= $bankSoal['id'] ?>/bulk-delete" onsubmit="return confirm('Hapus semua yang dipilih?')">
        <?= csrf_field() ?>
        <div id="bulkIds"></div>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm">
            <i class="fas fa-trash"></i> Hapus Terpilih
        </button>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow p-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Tampilkan</label>
            <select id="tableSoal_perpage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600">entri</span>
        </div>
        <input id="tableSoal_search" type="text" placeholder="Cari soal..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-emerald-400">
    </div>

    <div class="overflow-x-auto">
        <table id="tableSoal" class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 w-10"><input type="checkbox" id="checkAll" class="rounded"></th>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Pertanyaan</th>
                    <th class="p-3 text-center">Tipe</th>
                    <th class="p-3 text-center">Jawaban</th>
                    <th class="p-3 text-center">Bobot</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($soals)): ?>
                <tr><td colspan="7" class="text-center py-8 text-gray-400">Belum ada soal.</td></tr>
                <?php else: ?>
                <?php foreach ($soals as $i => $s): ?>
                <?php
                    $tipeClass = match($s['tipe_soal']) {
                        'PG'          => 'bg-blue-100 text-blue-700',
                        'Essay'       => 'bg-green-100 text-green-700',
                        'BS'          => 'bg-yellow-100 text-yellow-700',
                        'Menjodohkan' => 'bg-purple-100 text-purple-700',
                        default       => 'bg-gray-100 text-gray-700',
                    };
                    $pertanyaan = strip_tags($s['pertanyaan'] ?? '');
                    $pertanyaan = mb_strlen($pertanyaan) > 80 ? mb_substr($pertanyaan, 0, 80) . '…' : $pertanyaan;
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><input type="checkbox" class="row-check rounded" value="<?= $s['id'] ?>"></td>
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 max-w-xs"><?= esc($pertanyaan) ?></td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium <?= $tipeClass ?>"><?= esc($s['tipe_soal']) ?></span>
                    </td>
                    <td class="p-3 text-center font-mono text-xs font-bold"><?= esc($s['jawaban_benar'] ?? '-') ?></td>
                    <td class="p-3 text-center"><?= $s['bobot'] ?? 1 ?></td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick='openEditSoalModal(<?= json_encode($s) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="/guru/soal/<?= $bankSoal['id'] ?>/destroy/<?= $s['id'] ?>" class="inline" onsubmit="return confirm('Hapus soal ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 gap-2">
        <span id="tableSoal_info" class="text-sm text-gray-500"></span>
        <div id="tableSoal_pagination" class="flex gap-1 flex-wrap"></div>
    </div>
</div>

<!-- Modal Tambah/Edit Soal -->
<div id="modalSoal" class="modal-overlay hidden">
    <div class="modal-card" style="max-width:720px;">
        <div class="flex items-center justify-between mb-4">
            <h3 id="soalModalTitle" class="text-lg font-semibold">Tambah Soal</h3>
            <button onclick="closeSoalModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formSoal" action="/guru/soal/<?= $bankSoal['id'] ?>/store">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <!-- Tipe Soal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                    <select name="tipe_soal" id="tipeSoal" onchange="onTipeChange(this.value)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="PG">Pilihan Ganda (PG)</option>
                        <option value="Essay">Essay</option>
                        <option value="BS">Benar-Salah (BS)</option>
                        <option value="Menjodohkan">Menjodohkan</option>
                    </select>
                </div>
                <!-- Pertanyaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                    <textarea name="pertanyaan" id="pertanyaanField" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" rows="3" required></textarea>
                </div>
                <!-- PG Options -->
                <div id="pgOptions">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                    <div class="space-y-2">
                        <?php foreach (['a','b','c','d','e'] as $opt): ?>
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-center font-bold text-gray-600 uppercase"><?= $opt ?></span>
                            <input type="text" name="pilihan_<?= $opt ?>" id="pilihan_<?= $opt ?>" placeholder="Pilihan <?= strtoupper($opt) ?>" class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Menjodohkan -->
                <div id="menjodohkanOptions" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Menjodohkan</label>
                    <p class="text-xs text-gray-400 mb-2">Format: Kiri|Kanan (satu pasang per baris). Contoh: <code>Ibu Kota Indonesia|Jakarta</code></p>
                    <textarea name="kunci_menjodohkan" id="kunciMenjodohkanField" rows="5"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        placeholder="Pertanyaan 1|Jawaban 1&#10;Pertanyaan 2|Jawaban 2"></textarea>
                </div>
                <!-- Jawaban Benar -->
                <div id="jawabanBenarWrapper">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                    <select name="jawaban_benar" id="jawabanBenarSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                    <input type="hidden" name="jawaban_benar" id="jawabanBenarHidden" class="hidden">
                </div>
                <!-- Bobot -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bobot Nilai</label>
                    <input type="number" name="bobot" id="bobotField" value="1" min="1" class="w-24 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeSoalModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const bankSoalId = <?= (int)$bankSoal['id'] ?>;

function openSoalModal() {
    document.getElementById('soalModalTitle').textContent = 'Tambah Soal';
    document.getElementById('formSoal').action = '/guru/soal/' + bankSoalId + '/store';
    document.getElementById('pertanyaanField').value = '';
    document.getElementById('tipeSoal').value = 'PG';
    document.getElementById('bobotField').value = 1;
    ['a','b','c','d','e'].forEach(o => {
        const el = document.getElementById('pilihan_' + o);
        if (el) el.value = '';
    });
    document.getElementById('kunciMenjodohkanField').value = '';
    document.getElementById('jawabanBenarSelect').value = 'A';
    onTipeChange('PG');
    document.getElementById('modalSoal').classList.remove('hidden');
}

function openEditSoalModal(data) {
    document.getElementById('soalModalTitle').textContent = 'Edit Soal';
    document.getElementById('formSoal').action = '/guru/soal/' + bankSoalId + '/update/' + data.id;
    document.getElementById('pertanyaanField').value = data.pertanyaan ?? '';
    document.getElementById('tipeSoal').value = data.tipe_soal ?? 'PG';
    document.getElementById('bobotField').value = data.bobot ?? 1;
    ['a','b','c','d','e'].forEach(o => {
        const el = document.getElementById('pilihan_' + o);
        if (el) el.value = data['pilihan_' + o] ?? '';
    });
    document.getElementById('kunciMenjodohkanField').value = data.kunci_menjodohkan ?? '';
    document.getElementById('jawabanBenarSelect').value = data.jawaban_benar ?? 'A';
    onTipeChange(data.tipe_soal ?? 'PG');
    document.getElementById('modalSoal').classList.remove('hidden');
}

function closeSoalModal() {
    document.getElementById('modalSoal').classList.add('hidden');
}

function onTipeChange(tipe) {
    const pgOpts    = document.getElementById('pgOptions');
    const jodohOpts = document.getElementById('menjodohkanOptions');
    const jwbWrapper = document.getElementById('jawabanBenarWrapper');
    const jwbSelect  = document.getElementById('jawabanBenarSelect');

    pgOpts.classList.add('hidden');
    jodohOpts.classList.add('hidden');
    jwbWrapper.classList.remove('hidden');
    jwbSelect.name = 'jawaban_benar';

    if (tipe === 'PG') {
        pgOpts.classList.remove('hidden');
        jwbSelect.innerHTML = '<option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option>';
    } else if (tipe === 'BS') {
        jwbSelect.innerHTML = '<option value="Benar">Benar</option><option value="Salah">Salah</option>';
    } else if (tipe === 'Essay') {
        jwbWrapper.classList.add('hidden');
        jwbSelect.name = '';
    } else if (tipe === 'Menjodohkan') {
        jodohOpts.classList.remove('hidden');
        jwbWrapper.classList.add('hidden');
        jwbSelect.name = '';
    }
}

// Bulk logic
const checkAll = document.getElementById('checkAll');
const bulkBar  = document.getElementById('bulkBar');
const bulkIds  = document.getElementById('bulkIds');
const selCount = document.getElementById('selectedCount');

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked');
    if (checked.length > 0) {
        bulkBar.classList.remove('hidden');
        selCount.textContent = checked.length + ' dipilih';
        bulkIds.innerHTML = '';
        checked.forEach(c => {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = c.value;
            bulkIds.appendChild(inp);
        });
    } else {
        bulkBar.classList.add('hidden');
    }
}
checkAll?.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked);
    updateBulkBar();
});
document.querySelectorAll('.row-check').forEach(c => c.addEventListener('change', updateBulkBar));

// DataTable
(function() {
    const tableId = 'tableSoal';
    const table   = document.getElementById(tableId);
    const tbody   = table.querySelector('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    let filtered  = [...allRows];
    let perPage   = 10;
    let page      = 1;

    function render() {
        allRows.forEach(r => r.style.display = 'none');
        const start = (page - 1) * perPage;
        const end   = start + perPage;
        filtered.slice(start, end).forEach(r => r.style.display = '');
        const info = document.getElementById(tableId + '_info');
        if (info) info.textContent = filtered.length === 0 ? 'Tidak ada data' :
            `Menampilkan ${start + 1}–${Math.min(end, filtered.length)} dari ${filtered.length} data`;
        renderPagination();
    }

    function renderPagination() {
        const el = document.getElementById(tableId + '_pagination');
        if (!el) return;
        const total = Math.ceil(filtered.length / perPage);
        el.innerHTML = '';
        if (total <= 1) return;
        const btn = (label, p, disabled, active) => {
            const b = document.createElement('button');
            b.textContent = label;
            b.disabled = disabled;
            b.className = `px-3 py-1 rounded text-sm border ${active ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'} ${disabled ? 'opacity-40 cursor-not-allowed' : ''}`;
            if (!disabled) b.addEventListener('click', () => { page = p; render(); });
            return b;
        };
        el.appendChild(btn('«', 1, page === 1, false));
        el.appendChild(btn('‹', page - 1, page === 1, false));
        for (let i = Math.max(1, page - 2); i <= Math.min(total, page + 2); i++) {
            el.appendChild(btn(i, i, false, i === page));
        }
        el.appendChild(btn('›', page + 1, page === total, false));
        el.appendChild(btn('»', total, page === total, false));
    }

    document.getElementById(tableId + '_search')?.addEventListener('input', function() {
        const q = this.value.toLowerCase(); page = 1;
        filtered = allRows.filter(r => r.textContent.toLowerCase().includes(q));
        render();
    });
    document.getElementById(tableId + '_perpage')?.addEventListener('change', function() {
        perPage = +this.value; page = 1; render();
    });
    render();
})();
</script>

<?= $this->endSection() ?>

