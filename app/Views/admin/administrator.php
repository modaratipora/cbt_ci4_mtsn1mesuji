<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Admin
    </button>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow p-4">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admins)): ?>
                <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada data administrator.</td></tr>
                <?php else: ?>
                <?php foreach ($admins as $i => $a): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-medium">
                        <?= esc($a['nama']) ?>
                        <?php if ($a['id'] == session()->get('user_id')): ?>
                        <span class="ml-1 bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-xs">Anda</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-3 text-gray-500"><?= esc($a['email']) ?></td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick='openEditModal(<?= json_encode($a) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <?php if ($a['id'] != session()->get('user_id')): ?>
                        <form method="POST" action="/admin/administrator/destroy/<?= $a['id'] ?>" class="inline" onsubmit="return confirm('Hapus admin ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-gray-300 text-xs px-2 py-1">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tambah Administrator</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/admin/administrator/store">
            <?= csrf_field() ?>
            <div class="space-y-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 font-normal">(kosong = default: Admin@MTsN2026)</span></label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Administrator</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formEdit" action="">
            <?= csrf_field() ?>
            <div class="space-y-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="nama" id="editNama" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 font-normal">(kosong = tidak berubah)</span></label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
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
    document.getElementById('editNama').value  = data.nama;
    document.getElementById('editEmail').value = data.email;
    document.getElementById('formEdit').action = '/admin/administrator/update/' + data.id;
    openModal('modalEdit');
}
</script>

<?= $this->endSection() ?>
