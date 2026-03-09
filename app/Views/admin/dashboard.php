<?= $this->extend('layout/main_admin') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, <?= esc(session()->get('user_nama')) ?>!</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <!-- Total Siswa -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_siswa ?></div>
            <div class="text-blue-100 text-sm mt-1">Total Siswa</div>
        </div>
        <div class="text-5xl text-blue-300 opacity-80"><i class="fas fa-user-graduate"></i></div>
    </div>
    <!-- Total Guru -->
    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_guru ?></div>
            <div class="text-green-100 text-sm mt-1">Total Guru</div>
        </div>
        <div class="text-5xl text-green-300 opacity-80"><i class="fas fa-chalkboard-user"></i></div>
    </div>
    <!-- Total Kelas -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_kelas ?></div>
            <div class="text-purple-100 text-sm mt-1">Total Kelas</div>
        </div>
        <div class="text-5xl text-purple-300 opacity-80"><i class="fas fa-school"></i></div>
    </div>
    <!-- Total Mapel -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_mapel ?></div>
            <div class="text-orange-100 text-sm mt-1">Total Mapel</div>
        </div>
        <div class="text-5xl text-orange-300 opacity-80"><i class="fas fa-book-open"></i></div>
    </div>
    <!-- Total Bank Soal -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_bank_soal ?></div>
            <div class="text-cyan-100 text-sm mt-1">Total Bank Soal</div>
        </div>
        <div class="text-5xl text-cyan-300 opacity-80"><i class="fas fa-file-lines"></i></div>
    </div>
    <!-- Total Ruang Ujian -->
    <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-xl p-5 text-white shadow flex items-center justify-between">
        <div>
            <div class="text-4xl font-bold"><?= $total_ruang_ujian ?></div>
            <div class="text-red-100 text-sm mt-1">Total Ruang Ujian</div>
        </div>
        <div class="text-5xl text-red-300 opacity-80"><i class="fas fa-desktop"></i></div>
    </div>
</div>

<?= $this->endSection() ?>
