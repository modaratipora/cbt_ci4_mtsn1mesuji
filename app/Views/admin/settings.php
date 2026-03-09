<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-500 text-sm mt-1">Konfigurasi aplikasi CBT.</p>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-xl">
    <form method="POST" action="/admin/settings/update">
        <?= csrf_field() ?>
        <div class="space-y-6">
            <!-- Nama Sekolah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
                <input type="text" name="namaSekolah" value="<?= esc($namaSekolah) ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Tahun Ajaran -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahunAjaran" value="<?= esc($tahunAjaran) ?>" placeholder="2025/2026" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Exam Browser Only Toggle -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <div class="text-sm font-medium text-gray-800">Exam Browser Only</div>
                    <div class="text-xs text-gray-500 mt-0.5">Wajibkan penggunaan Safe Exam Browser saat ujian.</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="examBrowserOnly" value="1" <?= $examBrowserOnly === '1' ? 'checked' : '' ?> class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
