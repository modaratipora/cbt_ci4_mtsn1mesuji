<?= $this->extend('layout/main_guru') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, <?= esc(session()->get('user_nama')) ?>!</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Bank Soal -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_bank_soal ?></div>
            <div class="text-emerald-100 text-sm mt-1">Bank Soal</div>
        </div>
        <div class="text-5xl text-emerald-300 opacity-80"><i class="fas fa-file-lines"></i></div>
    </div>
    <!-- Ruang Ujian -->
    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_ruang_ujian ?></div>
            <div class="text-green-100 text-sm mt-1">Ruang Ujian</div>
        </div>
        <div class="text-5xl text-green-300 opacity-80"><i class="fas fa-desktop"></i></div>
    </div>
    <!-- Kelas -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_kelas ?></div>
            <div class="text-teal-100 text-sm mt-1">Kelas Diampu</div>
        </div>
        <div class="text-5xl text-teal-300 opacity-80"><i class="fas fa-school"></i></div>
    </div>
    <!-- Mapel -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_mapel ?></div>
            <div class="text-cyan-100 text-sm mt-1">Mata Pelajaran</div>
        </div>
        <div class="text-5xl text-cyan-300 opacity-80"><i class="fas fa-book-open"></i></div>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <i class="fas fa-bolt text-emerald-500"></i> Akses Cepat
        </h2>
        <div class="space-y-2">
            <a href="/guru/bank-soal" class="flex items-center gap-3 p-3 rounded-lg hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition">
                <i class="fas fa-file-lines text-emerald-600 w-5"></i>
                <span class="text-sm font-medium text-gray-700">Kelola Bank Soal</span>
            </a>
            <a href="/guru/ruang-ujian" class="flex items-center gap-3 p-3 rounded-lg hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition">
                <i class="fas fa-desktop text-emerald-600 w-5"></i>
                <span class="text-sm font-medium text-gray-700">Kelola Ruang Ujian</span>
            </a>
            <a href="/guru/ubah-password" class="flex items-center gap-3 p-3 rounded-lg hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition">
                <i class="fas fa-lock text-emerald-600 w-5"></i>
                <span class="text-sm font-medium text-gray-700">Ubah Password</span>
            </a>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <i class="fas fa-circle-info text-emerald-500"></i> Informasi
        </h2>
        <p class="text-sm text-gray-500">
            Selamat datang di Panel Guru CBT MTsN 1 Mesuji. Gunakan menu di sebelah kiri untuk mengelola bank soal dan ruang ujian Anda.
        </p>
    </div>
</div>

<?= $this->endSection() ?>
