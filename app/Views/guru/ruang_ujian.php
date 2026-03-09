<?= $this->extend('layout/main_guru') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openModal('modalTambah')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Buat Ruang Ujian
    </button>
</div>

<!-- Bulk Actions Bar -->
<div id="bulkBar" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
    <span id="selectedCount" class="text-sm font-medium text-yellow-800">0 dipilih</span>
    <form method="POST" action="/guru/ruang-ujian/bulk-delete" onsubmit="return confirm('Hapus semua yang dipilih?')">
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
            <select id="tableRuang_perpage" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="text-sm text-gray-600">entri</span>
        </div>
        <input id="tableRuang_search" type="text" placeholder="Cari ujian..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-emerald-400">
    </div>

    <div class="overflow-x-auto">
        <table id="tableRuang" class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 w-10"><input type="checkbox" id="checkAll" class="rounded"></th>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama Ujian</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-left">Mapel</th>
                    <th class="p-3 text-center">Mulai</th>
                    <th class="p-3 text-center">Selesai</th>
                    <th class="p-3 text-center">Durasi</th>
                    <th class="p-3 text-center">Token</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ruang_list)): ?>
                <tr><td colspan="11" class="text-center py-8 text-gray-400">Belum ada ruang ujian.</td></tr>
                <?php else: ?>
                <?php foreach ($ruang_list as $i => $r): ?>
                <?php
                    $statusClass = match($r['status']) {
                        'aktif'   => 'bg-green-100 text-green-700',
                        'selesai' => 'bg-red-100 text-red-700',
                        default   => 'bg-gray-100 text-gray-600',
                    };
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><input type="checkbox" class="row-check rounded" value="<?= $r['id'] ?>"></td>
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-medium"><?= esc($r['nama_ujian']) ?></td>
                    <td class="p-3 text-gray-600 text-xs"><?= esc($r['nama_kelas'] ?? '-') ?></td>
                    <td class="p-3 text-gray-600 text-xs"><?= esc($r['nama_mapel'] ?? '-') ?></td>
                    <td class="p-3 text-center text-xs"><?= $r['tanggal_mulai'] ? date('d/m/y H:i', strtotime($r['tanggal_mulai'])) : '-' ?></td>
                    <td class="p-3 text-center text-xs"><?= $r['tanggal_selesai'] ? date('d/m/y H:i', strtotime($r['tanggal_selesai'])) : '-' ?></td>
                    <td class="p-3 text-center text-xs"><?= $r['durasi'] ?> mnt</td>
                    <td class="p-3 text-center">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded font-bold tracking-widest"><?= esc($r['token']) ?></span>
                    </td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium <?= $statusClass ?>"><?= ucfirst($r['status']) ?></span>
                    </td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <a href="/guru/ruang-ujian/monitoring/<?= $r['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs mr-1" title="Monitoring">
                            <i class="fas fa-chart-bar"></i>
                        </a>
                        <a href="/guru/ruang-ujian/export-excel/<?= $r['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs mr-1" title="Export Excel">
                            <i class="fas fa-file-excel"></i>
                        </a>
                        <button onclick='openEditModal(<?= json_encode($r) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="/guru/ruang-ujian/destroy/<?= $r['id'] ?>" class="inline" onsubmit="return confirm('Hapus ruang ujian ini?')">
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
        <span id="tableRuang_info" class="text-sm text-gray-500"></span>
        <div id="tableRuang_pagination" class="flex gap-1 flex-wrap"></div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="modal-overlay hidden">
    <div class="modal-card" style="max-width:640px;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Buat Ruang Ujian</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/guru/ruang-ujian/store">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ujian</label>
                    <input type="text" name="nama_ujian" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal</label>
                    <select name="bank_soal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                        <option value="">-- Pilih Bank Soal --</option>
                        <?php foreach ($bank_list as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= esc($b['nama_bank']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= esc($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($mapel_list as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= esc($m['nama_mapel']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="draft">Draft</option>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit)</label>
                    <input type="number" name="durasi" value="60" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Login</label>
                    <input type="number" name="max_login" value="1" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Keluar</label>
                    <input type="number" name="batas_keluar" value="3" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div class="sm:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="acak_soal" value="1" class="rounded">
                        Acak Soal
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="acak_jawaban" value="1" class="rounded">
                        Acak Jawaban
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="modal-overlay hidden">
    <div class="modal-card" style="max-width:640px;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Ruang Ujian</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formEdit" action="">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ujian</label>
                    <input type="text" name="nama_ujian" id="editNamaUjian" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal</label>
                    <select name="bank_soal_id" id="editBankSoalId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="">-- Pilih Bank Soal --</option>
                        <?php foreach ($bank_list as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= esc($b['nama_bank']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas_id" id="editKelasId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelas_list as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= esc($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" id="editMapelId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="">-- Pilih Mapel --</option>
                        <?php foreach ($mapel_list as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= esc($m['nama_mapel']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="editStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="draft">Draft</option>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" id="editTanggalMulai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" id="editTanggalSelesai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit)</label>
                    <input type="number" name="durasi" id="editDurasi" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Login</label>
                    <input type="number" name="max_login" id="editMaxLogin" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Keluar</label>
                    <input type="number" name="batas_keluar" id="editBatasKeluar" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                </div>
                <div class="sm:col-span-2 flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="acak_soal" id="editAcakSoal" value="1" class="rounded">
                        Acak Soal
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="acak_jawaban" id="editAcakJawaban" value="1" class="rounded">
                        Acak Jawaban
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function toLocalDatetime(str) {
    if (!str) return '';
    const d = new Date(str.replace(' ', 'T'));
    return d.toISOString().slice(0, 16);
}

function openEditModal(data) {
    document.getElementById('editNamaUjian').value    = data.nama_ujian;
    document.getElementById('editBankSoalId').value   = data.bank_soal_id ?? '';
    document.getElementById('editKelasId').value      = data.kelas_id ?? '';
    document.getElementById('editMapelId').value      = data.mapel_id ?? '';
    document.getElementById('editStatus').value       = data.status ?? 'draft';
    document.getElementById('editTanggalMulai').value = toLocalDatetime(data.tanggal_mulai);
    document.getElementById('editTanggalSelesai').value = toLocalDatetime(data.tanggal_selesai);
    document.getElementById('editDurasi').value       = data.durasi ?? 60;
    document.getElementById('editMaxLogin').value     = data.max_login ?? 1;
    document.getElementById('editBatasKeluar').value  = data.batas_keluar ?? 3;
    document.getElementById('editAcakSoal').checked   = !!data.acak_soal;
    document.getElementById('editAcakJawaban').checked = !!data.acak_jawaban;
    document.getElementById('formEdit').action        = '/guru/ruang-ujian/update/' + data.id;
    openModal('modalEdit');
}

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
    const tableId = 'tableRuang';
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
