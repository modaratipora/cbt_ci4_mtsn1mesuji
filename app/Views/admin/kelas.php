<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Kelas
    </button>
</div>

<!-- Bulk Actions Bar -->
<div id="bulkBar" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
    <span id="selectedCount" class="text-sm font-medium text-yellow-800">0 dipilih</span>
    <form method="POST" action="/admin/kelas/bulk-delete" onsubmit="return confirm('Hapus semua yang dipilih?')">
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
            <select id="tableKelas_perpage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600">entri</span>
        </div>
        <input id="tableKelas_search" type="text" placeholder="Cari kelas..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <div class="overflow-x-auto">
        <table id="tableKelas" class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 w-10"><input type="checkbox" id="checkAll" class="rounded"></th>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama Kelas</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelas)): ?>
                <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada data kelas.</td></tr>
                <?php else: ?>
                <?php foreach ($kelas as $i => $k): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><input type="checkbox" class="row-check rounded" value="<?= $k['id'] ?>"></td>
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-medium"><?= esc($k['nama_kelas']) ?></td>
                    <td class="p-3 text-center">
                        <button onclick='openEditModal(<?= json_encode($k) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="/admin/kelas/destroy/<?= $k['id'] ?>" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
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
        <span id="tableKelas_info" class="text-sm text-gray-500"></span>
        <div id="tableKelas_pagination" class="flex gap-1"></div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div id="modalTambah" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tambah Kelas</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/admin/kelas/store">
            <?= csrf_field() ?>
            <div id="kelasInputs">
                <div class="flex gap-2 mb-2">
                    <input type="text" name="nama_kelas[]" placeholder="Nama kelas..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-minus-circle"></i></button>
                </div>
                <div class="flex gap-2 mb-2">
                    <input type="text" name="nama_kelas[]" placeholder="Nama kelas..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-minus-circle"></i></button>
                </div>
                <div class="flex gap-2 mb-2">
                    <input type="text" name="nama_kelas[]" placeholder="Nama kelas..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-minus-circle"></i></button>
                </div>
            </div>
            <button type="button" onclick="addKelasRow()" class="text-blue-600 hover:text-blue-800 text-sm mb-4 flex items-center gap-1">
                <i class="fas fa-plus-circle"></i> Tambah Baris
            </button>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modalEdit" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Kelas</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formEdit" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="editNamaKelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openEditModal(data) {
    document.getElementById('editNamaKelas').value = data.nama_kelas;
    document.getElementById('formEdit').action = '/admin/kelas/update/' + data.id;
    openModal('modalEdit');
}

function addKelasRow() {
    const container = document.getElementById('kelasInputs');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `<input type="text" name="nama_kelas[]" placeholder="Nama kelas..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-minus-circle"></i></button>`;
    container.appendChild(div);
}

function removeRow(btn) {
    const rows = document.querySelectorAll('#kelasInputs .flex');
    if (rows.length > 1) btn.closest('.flex').remove();
}

// Checkbox & bulk actions
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

// Simple DataTable
(function() {
    const tableId  = 'tableKelas';
    const table    = document.getElementById(tableId);
    const tbody    = table.querySelector('tbody');
    const allRows  = Array.from(tbody.querySelectorAll('tr'));
    let filtered   = [...allRows];
    let perPage    = 10;
    let page       = 1;

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
        const q = this.value.toLowerCase();
        page    = 1;
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
