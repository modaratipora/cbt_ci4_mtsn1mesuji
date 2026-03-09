<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; CBT MTsN 1 Mesuji</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* Gradient background */
        .login-bg {
            background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 50%, #0f172a 100%);
            min-height: 100vh;
        }

        /* Animated floating shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            animation: float 8s ease-in-out infinite;
        }
        .shape-1 { width: 300px; height: 300px; background: #60a5fa; top: -80px; right: -60px; animation-delay: 0s; }
        .shape-2 { width: 200px; height: 200px; background: #818cf8; bottom: -40px; left: -60px; animation-delay: 2s; }
        .shape-3 { width: 150px; height: 150px; background: #34d399; top: 40%; left: 5%; animation-delay: 4s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50%       { transform: translateY(-20px) rotate(5deg); }
        }

        /* Tab indicator */
        .tab-btn { transition: all 0.25s; }
        .tab-btn.active { border-bottom: 3px solid #3b82f6; color: #3b82f6; font-weight: 600; }

        /* Input focus */
        .form-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }

        /* Token section animation */
        #tokenSection { transition: max-height 0.4s ease, opacity 0.3s ease; overflow: hidden; }
        #tokenSection.hidden-section { max-height: 0; opacity: 0; pointer-events: none; }
        #tokenSection.visible-section { max-height: 300px; opacity: 1; }

        /* Submit button loading state */
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        /* Password toggle */
        .pw-wrap { position: relative; }
        .pw-toggle { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af; }
        .pw-toggle:hover { color: #6b7280; }
    </style>
</head>
<body class="login-bg flex items-center justify-center p-4">

    <!-- Decorative shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="relative z-10 w-full max-w-md">

        <!-- Logo / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur rounded-2xl mb-4 shadow-lg">
                <i class="fas fa-graduation-cap text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">CBT MTsN 1 Mesuji</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem Computer Based Test</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
            <div class="mx-6 mt-5 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3 text-sm">
                <i class="fas fa-circle-exclamation mt-0.5 shrink-0"></i>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
            <div class="mx-6 mt-5 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-start gap-3 text-sm">
                <i class="fas fa-circle-check mt-0.5 shrink-0"></i>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
            <?php endif; ?>

            <!-- Tabs -->
            <?php $activeTab = session()->getFlashdata('tab') ?? 'admin'; ?>
            <div class="flex border-b border-gray-200 mt-4 px-2">
                <button type="button" data-tab="admin"
                        class="tab-btn flex-1 py-3 text-sm text-gray-500 hover:text-blue-500 <?= $activeTab === 'admin' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield mr-1.5"></i>Admin
                </button>
                <button type="button" data-tab="guru"
                        class="tab-btn flex-1 py-3 text-sm text-gray-500 hover:text-blue-500 <?= $activeTab === 'guru' ? 'active' : '' ?>">
                    <i class="fas fa-chalkboard-user mr-1.5"></i>Guru
                </button>
                <button type="button" data-tab="siswa"
                        class="tab-btn flex-1 py-3 text-sm text-gray-500 hover:text-blue-500 <?= $activeTab === 'siswa' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate mr-1.5"></i>Siswa
                </button>
            </div>

            <div class="p-6 pt-5">

                <!-- ===== ADMIN FORM ===== -->
                <div id="tab-admin" class="tab-content <?= $activeTab !== 'admin' ? 'hidden' : '' ?>">
                    <form method="POST" action="/login" id="formAdmin">
                        <?= csrf_field() ?>
                        <input type="hidden" name="login_type" value="admin">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="admin_email">
                                <i class="fas fa-envelope mr-1 text-gray-400"></i>Email
                            </label>
                            <input type="email" id="admin_email" name="email" required
                                   placeholder="admin@mtsn1mesuji.sch.id"
                                   value="<?= esc($this->request->getOldInput('email') ?? '') ?>"
                                   class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="admin_password">
                                <i class="fas fa-lock mr-1 text-gray-400"></i>Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="admin_password" name="password" required
                                       placeholder="••••••••"
                                       class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 pr-10 text-sm bg-gray-50 focus:bg-white transition">
                                <span class="pw-toggle" onclick="togglePassword('admin_password', this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-right-to-bracket"></i>
                            Masuk sebagai Admin
                        </button>
                    </form>
                </div>

                <!-- ===== GURU FORM ===== -->
                <div id="tab-guru" class="tab-content <?= $activeTab !== 'guru' ? 'hidden' : '' ?>">
                    <form method="POST" action="/login" id="formGuru">
                        <?= csrf_field() ?>
                        <input type="hidden" name="login_type" value="guru">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="guru_nik">
                                <i class="fas fa-id-card mr-1 text-gray-400"></i>NIK (Nomor Induk Karyawan)
                            </label>
                            <input type="text" id="guru_nik" name="nik" required
                                   placeholder="Masukkan NIK"
                                   class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="guru_password">
                                <i class="fas fa-lock mr-1 text-gray-400"></i>Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="guru_password" name="password" required
                                       placeholder="••••••••"
                                       class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 pr-10 text-sm bg-gray-50 focus:bg-white transition">
                                <span class="pw-toggle" onclick="togglePassword('guru_password', this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-right-to-bracket"></i>
                            Masuk sebagai Guru
                        </button>
                    </form>
                </div>

                <!-- ===== SISWA FORM ===== -->
                <div id="tab-siswa" class="tab-content <?= $activeTab !== 'siswa' ? 'hidden' : '' ?>">
                    <form method="POST" action="/login" id="formSiswa">
                        <?= csrf_field() ?>
                        <input type="hidden" name="login_type" value="siswa">
                        <input type="hidden" name="ruang_id" id="ruang_id_input" value="">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="siswa_nisn">
                                <i class="fas fa-id-badge mr-1 text-gray-400"></i>NISN
                            </label>
                            <input type="text" id="siswa_nisn" name="nisn" required
                                   placeholder="Masukkan NISN"
                                   class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5" for="siswa_password">
                                <i class="fas fa-lock mr-1 text-gray-400"></i>Password
                            </label>
                            <div class="pw-wrap">
                                <input type="password" id="siswa_password" name="password" required
                                       placeholder="••••••••"
                                       class="form-input w-full border border-gray-300 rounded-xl px-4 py-2.5 pr-10 text-sm bg-gray-50 focus:bg-white transition">
                                <span class="pw-toggle" onclick="togglePassword('siswa_password', this)">
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Token toggle -->
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 select-none">
                                <input type="checkbox" id="useTokenCheckbox" class="w-4 h-4 text-blue-600 rounded accent-blue-600">
                                <span><i class="fas fa-key mr-1 text-gray-400"></i>Masuk dengan token ujian</span>
                            </label>
                        </div>

                        <!-- Token fields (hidden by default) -->
                        <div id="tokenSection" class="hidden-section">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 space-y-3">
                                <p class="text-xs text-amber-700 font-medium flex items-center gap-1">
                                    <i class="fas fa-triangle-exclamation"></i>
                                    Minta token kepada guru pengawas sebelum memulai ujian.
                                </p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="token_input">
                                        Token Ujian
                                    </label>
                                    <input type="text" id="token_input" name="token"
                                           placeholder="Contoh: A1B2C3"
                                           maxlength="10"
                                           oninput="this.value = this.value.toUpperCase()"
                                           class="form-input w-full border border-amber-300 rounded-xl px-4 py-2.5 text-sm bg-white font-mono tracking-widest text-center text-lg transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="ruang_id_visible">
                                        ID Ruang Ujian
                                    </label>
                                    <input type="number" id="ruang_id_visible"
                                           placeholder="Contoh: 5"
                                           min="1"
                                           oninput="document.getElementById('ruang_id_input').value = this.value"
                                           class="form-input w-full border border-amber-300 rounded-xl px-4 py-2.5 text-sm bg-white transition">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit w-full bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white font-semibold py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-right-to-bracket"></i>
                            Masuk sebagai Siswa
                        </button>
                    </form>
                </div>

            </div><!-- /p-6 -->

            <!-- Footer help text -->
            <div class="px-6 pb-6 text-center text-xs text-gray-400">
                Hubungi administrator jika mengalami kesulitan login.
            </div>

        </div><!-- /card -->

        <!-- Bottom branding -->
        <p class="text-center text-blue-300/70 text-xs mt-6">
            &copy; <?= date('Y') ?> MTsN 1 Mesuji &mdash; v1.0
        </p>

    </div><!-- /z-10 container -->

<script>
// ── Tab switching ──────────────────────────────────────────────────────────────
const tabBtns    = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;

        tabBtns.forEach(b => b.classList.remove('active'));
        tabContents.forEach(c => c.classList.add('hidden'));

        btn.classList.add('active');
        document.getElementById('tab-' + target)?.classList.remove('hidden');
    });
});

// ── Token section toggle ───────────────────────────────────────────────────────
const useTokenCheckbox = document.getElementById('useTokenCheckbox');
const tokenSection     = document.getElementById('tokenSection');
const tokenInput       = document.getElementById('token_input');
const ruangIdVisible   = document.getElementById('ruang_id_visible');

useTokenCheckbox.addEventListener('change', function () {
    if (this.checked) {
        tokenSection.classList.remove('hidden-section');
        tokenSection.classList.add('visible-section');
        tokenInput.setAttribute('required', 'required');
        ruangIdVisible.setAttribute('required', 'required');
    } else {
        tokenSection.classList.remove('visible-section');
        tokenSection.classList.add('hidden-section');
        tokenInput.removeAttribute('required');
        ruangIdVisible.removeAttribute('required');
        tokenInput.value       = '';
        ruangIdVisible.value   = '';
        document.getElementById('ruang_id_input').value = '';
    }
});

// ── Password toggle ────────────────────────────────────────────────────────────
function togglePassword(inputId, toggleEl) {
    const input = document.getElementById(inputId);
    const icon  = toggleEl.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
}

// ── Submit loading indicator ───────────────────────────────────────────────────
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function () {
        const btn = this.querySelector('.btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        }
    });
});

// ── Auto-uppercase token ───────────────────────────────────────────────────────
document.getElementById('token_input')?.addEventListener('input', function () {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
});
</script>

</body>
</html>
