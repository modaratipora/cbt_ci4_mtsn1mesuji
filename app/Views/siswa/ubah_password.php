<?= $this->extend('layout/main_siswa') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-500 text-sm mt-1">Ganti password akun siswa Anda.</p>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-md">
    <form method="POST" action="/siswa/ubah-password/update">
        <?= csrf_field() ?>
        <div class="space-y-4">
            <!-- Password Lama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <div class="relative">
                    <input type="password" name="password_lama" id="pwLama" class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                    <button type="button" onclick="togglePw('pwLama', 'eyeLama')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="eyeLama" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_baru" id="pwBaru" class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required minlength="6">
                    <button type="button" onclick="togglePw('pwBaru', 'eyeBaru')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="eyeBaru" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Minimal 6 karakter.</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_konfirmasi" id="pwKonfirm" class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" required>
                    <button type="button" onclick="togglePw('pwKonfirm', 'eyeKonfirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="eyeKonfirm" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-medium w-full">
                <i class="fas fa-lock mr-2"></i>Ubah Password
            </button>
        </div>
    </form>
</div>

<script>
function togglePw(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye   = document.getElementById(eyeId);
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?= $this->endSection() ?>
