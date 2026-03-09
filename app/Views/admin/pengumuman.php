<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <button onclick="openModal('modalTambah')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Pengumuman
    </button>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow p-4">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Judul</th>
                    <th class="p-3 text-left">Target Kelas</th>
                    <th class="p-3 text-center">Tanggal</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pengumumans)): ?>
                <tr><td colspan="5" class="text-center py-8 text-gray-400">Belum ada pengumuman.</td></tr>
                <?php else: ?>
                <?php foreach ($pengumumans as $i => $p): ?>
                <?php
                    $targetRaw = $p['target_kelas'] ?? '';
                    $targets   = json_decode($targetRaw, true) ?: (array) $targetRaw;
                    if (in_array('all', $targets)) {
                        $targetLabel = '<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Semua Kelas</span>';
                    } else {
                        $kelasNames = [];
                        foreach ($kelas as $k) {
                            if (in_array($k['id'], $targets)) {
                                $kelasNames[] = esc($k['nama_kelas']);
                            }
                        }
                        $targetLabel = implode(', ', $kelasNames) ?: esc($targetRaw);
                    }
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-medium"><?= esc($p['judul']) ?></td>
                    <td class="p-3"><?= $targetLabel ?></td>
                    <td class="p-3 text-center text-gray-500 text-xs"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                    <td class="p-3 text-center whitespace-nowrap">
                        <button onclick='openEditModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)' class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="/admin/pengumuman/destroy/<?= $p['id'] ?>" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
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
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-8 pb-8 overflow-y-auto">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Tambah Pengumuman</h3>
            <button onclick="closeModal('modalTambah')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" action="/admin/pengumuman/store">
            <?= csrf_field() ?>
            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                    <textarea name="konten" id="kontenTambah" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Kelas</label>
                    <label class="flex items-center gap-2 mb-2">
                        <input type="checkbox" name="target_kelas[]" value="all" id="checkAllKelas" class="rounded">
                        <span class="text-sm font-medium text-green-700">Semua Kelas</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2" id="kelasCheckboxes">
                        <?php foreach ($kelas as $k): ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="target_kelas[]" value="<?= $k['id'] ?>" class="kelas-check rounded">
                            <span class="text-sm"><?= esc($k['nama_kelas']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
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
<div id="modalEdit" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-8 pb-8 overflow-y-auto">
    <div class="modal-card bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Pengumuman</h3>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form method="POST" id="formEdit" action="">
            <?= csrf_field() ?>
            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" id="editJudul" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                    <textarea name="konten" id="kontenEdit" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Kelas</label>
                    <label class="flex items-center gap-2 mb-2">
                        <input type="checkbox" name="target_kelas[]" value="all" id="editCheckAllKelas" class="rounded">
                        <span class="text-sm font-medium text-green-700">Semua Kelas</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        <?php foreach ($kelas as $k): ?>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="target_kelas[]" value="<?= $k['id'] ?>" class="edit-kelas-check rounded" data-id="<?= $k['id'] ?>">
                            <span class="text-sm"><?= esc($k['nama_kelas']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
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
    document.getElementById('editJudul').value = data.judul;
    document.getElementById('formEdit').action = '/admin/pengumuman/update/' + data.id;

    // Populate konten
    setTimeout(() => {
        if (typeof $('#kontenEdit').summernote === 'function') {
            $('#kontenEdit').summernote('code', data.konten || '');
        } else {
            document.getElementById('kontenEdit').value = data.konten || '';
        }
    }, 100);

    // Reset checkboxes
    document.querySelectorAll('.edit-kelas-check').forEach(c => c.checked = false);
    document.getElementById('editCheckAllKelas').checked = false;

    // Parse target_kelas
    let targets = [];
    try { targets = JSON.parse(data.target_kelas); } catch(e) { targets = [data.target_kelas]; }
    if (targets.includes('all')) {
        document.getElementById('editCheckAllKelas').checked = true;
    } else {
        document.querySelectorAll('.edit-kelas-check').forEach(c => {
            if (targets.includes(c.dataset.id) || targets.includes(parseInt(c.dataset.id))) {
                c.checked = true;
            }
        });
    }

    openModal('modalEdit');
}

// Semua kelas toggle
document.getElementById('checkAllKelas')?.addEventListener('change', function() {
    document.querySelectorAll('.kelas-check').forEach(c => c.checked = false);
});
document.querySelectorAll('.kelas-check').forEach(c => {
    c.addEventListener('change', function() {
        if (this.checked) document.getElementById('checkAllKelas').checked = false;
    });
});
document.getElementById('editCheckAllKelas')?.addEventListener('change', function() {
    document.querySelectorAll('.edit-kelas-check').forEach(c => c.checked = false);
});
document.querySelectorAll('.edit-kelas-check').forEach(c => {
    c.addEventListener('change', function() {
        if (this.checked) document.getElementById('editCheckAllKelas').checked = false;
    });
});

// Summernote init
$(document).ready(function() {
    if (typeof $.fn.summernote !== 'undefined') {
        ['#kontenTambah', '#kontenEdit'].forEach(sel => {
            $(sel).summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                placeholder: 'Tulis konten pengumuman...',
            });
        });
    }
});
</script>

<?= $this->endSection() ?>
