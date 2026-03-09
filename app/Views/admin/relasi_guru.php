<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Relasi
    </button>
</div>

<!-- Bulk Actions Bar -->
<div id="bulkBar" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
    <span id="selectedCount" class="text-sm font-medium text-yellow-800">0 dipilih</span>
    <form method="POST" action="/admin/relasi-guru/bulk-delete" onsubmit="return confirm('Hapus semua yang dipilih?')">
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
            <select id="tableRelasi_perpage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600">entri</span>
        </div>
        <input id="tableRelasi_search" type="text" placeholder="Cari relasi..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div class="overflow-x-auto">
        <table id="tableRelasi" class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 w-10"><input type="checkbox" id="checkAll" class="rounded"></th>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Guru</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-left">Mapel</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($relasi)): ?>
                <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada relasi guru.</td></tr>
                <?php else: ?>
                <?php foreach ($relasi as $i => $r): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><input type="checkbox" class="row-check rounded" value="<?= $r['id'] ?>"></td>
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-medium"><?= esc($r['nama_guru'] ?? '-') ?></td>
                    <td class="p-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs"><?= esc($r['nama_kelas'] ?? '-') ?></span>
                    </td>
                    <td class="p-3">
                        <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs"><?= esc($r['nama_mapel'] ?? '-') ?></span>
                    </td>
                    <td class="p-3 text-center">
                        <form method="POST" action="/admin/relasi-guru/destroy/<?= $r['id'] ?>" class="inline" onsubmit="return confirm('Hapus relasi ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-trash"></i> Hapus
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
        <span id="tableRelasi_info" class="text-sm text-gray-500"></span>
        <div id="tableRelasi_pagination" class="flex gap-1 flex-wrap"></div>
    </div>
</div>

<!-- Modal Tambah Relasi -->
<div id="modalTambah" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tambah Relasi Guru</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/admin/relasi-guru/store">
            <?= csrf_field() ?>
            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                    <select name="guru_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach ($gurus as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-gray-400 font-normal text-xs">(bisa pilih banyak)</span></label>
                    <select name="kelas_id[]" multiple class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 h-32" required>
                        <?php foreach ($kelas as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= esc($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mapel <span class="text-gray-400 font-normal text-xs">(bisa pilih banyak)</span></label>
                    <select name="mapel_id[]" multiple class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 h-32" required>
                        <?php foreach ($mapels as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= esc($m['nama_mapel']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="text-xs text-gray-400"><i class="fas fa-info-circle"></i> Tahan Ctrl/Cmd untuk memilih lebih dari satu.</p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

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

(function() {
    const tableId = 'tableRelasi';
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
