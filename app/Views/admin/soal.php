<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="/admin/bank-soal" class="hover:text-blue-600"><i class="fas fa-file-lines mr-1"></i>Bank Soal</a>
    <i class="fas fa-chevron-right text-xs"></i>
    <span class="text-gray-800 font-medium"><?= esc($bankSoal['nama_bank']) ?></span>
</nav>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <div class="flex gap-2">
        <a href="/admin/soal/<?= $bankSoal['id'] ?>/import-template" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Template
        </a>
        <button onclick="openModal('modalImport')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-file-import"></i> Import
        </button>
        <button onclick="openSoalModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Soal
        </button>
    </div>
</div>

<!-- Bulk Actions Bar -->
<div id="bulkBar" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
    <span id="selectedCount" class="text-sm font-medium text-yellow-800">0 dipilih</span>
    <form method="POST" action="/admin/soal/<?= $bankSoal['id'] ?>/bulk-delete" onsubmit="return confirm('Hapus semua yang dipilih?')">
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
        <input id="tableSoal_search" type="text" placeholder="Cari soal..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
                    $pertanyaan = strip_tags($s['pertanyaan']);
                    $pertanyaan = mb_strlen($pertanyaan) > 80 ? mb_substr($pertanyaan, 0, 80) . '…' : $pertanyaan;
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><input type="checkbox" class="row-check rounded" value="<?= $s['id'] ?>"></td>
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 max-w-xs"><?= esc($pertanyaan) ?></td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium <?= $tipeClass ?>"><?= esc($s['tipe_soal']) ?></span>
                    </td>
                    <td class="p-3 text-center font-mono text-xs font-bold"><?= esc($s['jawaban_benar']) ?></td>
                    <td class="p-3 text-center"><?= $s['bobot'] ?></td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick='openEditSoalModal(<?= json_encode($s) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="/admin/soal/<?= $bankSoal['id'] ?>/destroy/<?= $s['id'] ?>" class="inline" onsubmit="return confirm('Hapus soal ini?')">
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
<div id="modalSoal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-8 pb-8 overflow-y-auto">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" id="modalSoalTitle">Tambah Soal</h3>
            <button onclick="closeModal('modalSoal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formSoal" action="/admin/soal/<?= $bankSoal['id'] ?>/store">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <!-- Pertanyaan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                    <textarea name="pertanyaan" id="soalPertanyaan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <!-- Tipe Soal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                    <select name="tipe_soal" id="soalTipe" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" onchange="toggleTipeFields()">
                        <option value="PG">Pilihan Ganda (PG)</option>
                        <option value="Essay">Essay</option>
                        <option value="BS">Benar/Salah (BS)</option>
                        <option value="Menjodohkan">Menjodohkan</option>
                    </select>
                </div>

                <!-- Fields PG -->
                <div id="fieldsPG">
                    <div class="grid grid-cols-1 gap-2">
                        <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt): ?>
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold w-8 text-center"><?= strtoupper($opt) ?></span>
                            <input type="text" name="pilihan_<?= $opt ?>" id="soalPilihan<?= strtoupper($opt) ?>" placeholder="Pilihan <?= strtoupper($opt) ?>..." class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                        <select name="jawaban_benar" id="soalJawabanPG" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                </div>

                <!-- Fields BS (Benar/Salah) -->
                <div id="fieldsBS" class="hidden">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Pilihan A</label>
                            <input type="text" value="Benar" readonly class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Pilihan B</label>
                            <input type="text" value="Salah" readonly class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-gray-50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                        <select name="jawaban_benar" id="soalJawabanBS" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="Benar">Benar</option>
                            <option value="Salah">Salah</option>
                        </select>
                    </div>
                </div>

                <!-- Fields Menjodohkan -->
                <div id="fieldsMenjodohkan" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Menjodohkan</label>
                    <p class="text-xs text-gray-400 mb-2">Format: Kiri|Kanan (satu pasang per baris). Contoh: <code>Ibu Kota Indonesia|Jakarta</code></p>
                    <textarea name="kunci_menjodohkan" id="soalKunciMenjodohkan" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Pertanyaan 1|Jawaban 1&#10;Pertanyaan 2|Jawaban 2"></textarea>
                </div>

                <!-- Fields Essay — no options -->
                <div id="fieldsEssay" class="hidden">
                    <p class="text-sm text-gray-400 italic">Soal Essay tidak memerlukan pilihan jawaban. Nilai akan dinilai manual oleh guru.</p>
                    <input type="hidden" name="jawaban_benar" value="">
                </div>

                <!-- Bobot -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bobot</label>
                        <input type="number" name="bobot" id="soalBobot" value="1" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" name="urutan" id="soalUrutan" value="" min="1" placeholder="Auto" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('modalSoal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div id="modalImport" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Import Soal (Excel)</h3>
            <button onclick="closeModal('modalImport')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/admin/soal/<?= $bankSoal['id'] ?>/import-excel" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">File Excel (.xlsx)</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <p class="text-xs text-gray-400 mt-1">Kolom: Pertanyaan, Tipe, A, B, C, D, E, Jawaban Benar, Bobot.</p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalImport')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Import</button>
            </div>
        </form>
    </div>
</div>

<script>
const bankSoalId = <?= $bankSoal['id'] ?>;

function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openSoalModal() {
    document.getElementById('modalSoalTitle').textContent = 'Tambah Soal';
    document.getElementById('formSoal').action = `/admin/soal/${bankSoalId}/store`;
    document.getElementById('soalPertanyaan').value = '';
    document.getElementById('soalTipe').value = 'PG';
    ['A','B','C','D','E'].forEach(l => { const el = document.getElementById(`soalPilihan${l}`); if(el) el.value = ''; });
    document.getElementById('soalJawabanPG').value = 'A';
    document.getElementById('soalJawabanBS').value = 'Benar';
    document.getElementById('soalKunciMenjodohkan').value = '';
    document.getElementById('soalBobot').value = 1;
    document.getElementById('soalUrutan').value = '';
    toggleTipeFields();
    openModal('modalSoal');
    setTimeout(() => { if (typeof $('#soalPertanyaan').summernote === 'function') $('#soalPertanyaan').summernote('reset'); }, 100);
}

function openEditSoalModal(data) {
    document.getElementById('modalSoalTitle').textContent = 'Edit Soal';
    document.getElementById('formSoal').action = `/admin/soal/${bankSoalId}/update/${data.id}`;
    document.getElementById('soalTipe').value = data.tipe_soal || 'PG';
    document.getElementById('soalBobot').value = data.bobot || 1;
    document.getElementById('soalUrutan').value = data.urutan || '';
    document.getElementById('soalKunciMenjodohkan').value = data.kunci_menjodohkan || '';
    toggleTipeFields();

    ['A','B','C','D','E'].forEach(l => {
        const el = document.getElementById(`soalPilihan${l}`);
        if(el) el.value = data[`pilihan_${l.toLowerCase()}`] || '';
    });

    if (data.tipe_soal === 'BS') {
        document.getElementById('soalJawabanBS').value = data.jawaban_benar || 'Benar';
    } else {
        const pgSel = document.getElementById('soalJawabanPG');
        if (pgSel) pgSel.value = data.jawaban_benar || 'A';
    }

    setTimeout(() => {
        if (typeof $('#soalPertanyaan').summernote === 'function') {
            $('#soalPertanyaan').summernote('code', data.pertanyaan || '');
        } else {
            document.getElementById('soalPertanyaan').value = data.pertanyaan || '';
        }
    }, 100);

    openModal('modalSoal');
}

function toggleTipeFields() {
    const tipe = document.getElementById('soalTipe').value;
    document.getElementById('fieldsPG').classList.toggle('hidden', tipe !== 'PG');
    document.getElementById('fieldsBS').classList.toggle('hidden', tipe !== 'BS');
    document.getElementById('fieldsMenjodohkan').classList.toggle('hidden', tipe !== 'Menjodohkan');
    document.getElementById('fieldsEssay').classList.toggle('hidden', tipe !== 'Essay');
}

// Summernote init
$(document).ready(function() {
    if (typeof $.fn.summernote !== 'undefined') {
        $('#soalPertanyaan').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['font', ['superscript', 'subscript']],
                ['insert', ['picture']],
                ['view', ['fullscreen', 'codeview']],
            ],
            placeholder: 'Tulis pertanyaan di sini...',
        });
    }
});

// Bulk actions
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
            b.className = `px-3 py-1 rounded text-sm border ${active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'} ${disabled ? 'opacity-40 cursor-not-allowed' : ''}`;
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
