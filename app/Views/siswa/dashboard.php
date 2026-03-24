<?= $this->extend('layout/main_siswa') ?>
<?= $this->section('content') ?>

<!-- Welcome Header -->
<div class="mb-6 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl p-6 text-white">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold">
            <?= esc(strtoupper(substr(session()->get('user_nama') ?? 'S', 0, 1))) ?>
        </div>
        <div>
            <h1 class="text-xl font-bold">Selamat Datang, <?= esc(session()->get('user_nama')) ?>!</h1>
            <p class="text-emerald-100 text-sm mt-0.5">Kelas: <?= esc($nama_kelas ?: '-') ?></p>
        </div>
    </div>
</div>

<!-- Active Exams Section -->
<?php if (!empty($ruang_aktif)): ?>
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-desktop text-emerald-600"></i> Ujian Aktif
    </h2>
    <div class="grid md:grid-cols-2 gap-4">
        <?php foreach ($ruang_aktif as $r): ?>
        <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div>
                    <h3 class="font-semibold text-gray-800"><?= esc($r['nama_ujian']) ?></h3>
                    <p class="text-xs text-gray-500 mt-0.5"><?= esc($r['nama_mapel'] ?? '-') ?></p>
                </div>
                <?php if ($r['sudah_selesai']): ?>
                <span class="shrink-0 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Selesai
                </span>
                <?php else: ?>
                <span class="shrink-0 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                    <i class="fas fa-clock mr-1"></i>Aktif
                </span>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-4">
                <span><i class="fas fa-stopwatch mr-1"></i><?= $r['durasi'] ?> menit</span>
                <?php if ($r['tanggal_selesai']): ?>
                <span><i class="fas fa-calendar-xmark mr-1"></i>Berakhir: <?= date('d/m/Y H:i', strtotime($r['tanggal_selesai'])) ?></span>
                <?php endif; ?>
            </div>
            <?php if ($r['sudah_selesai']): ?>
            <div class="flex items-center gap-3">
                <button disabled class="flex-1 bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                    <i class="fas fa-check mr-1"></i> Selesai
                </button>
                <?php if (isset($r['hasil']['nilai'])): ?>
                <span class="text-sm font-bold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg">
                    Nilai: <?= $r['hasil']['nilai'] ?>
                </span>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <button onclick="openTokenModal(<?= (int)$r['id'] ?>, '<?= esc($r['nama_ujian']) ?>')"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition">
                <i class="fas fa-play-circle"></i> Mulai Ujian
            </button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Announcements Section -->
<?php if (!empty($pengumuman)): ?>
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-bullhorn text-emerald-600"></i> Pengumuman
    </h2>
    <div class="space-y-3">
        <?php foreach ($pengumuman as $p): ?>
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-gray-800"><?= esc($p['judul']) ?></h3>
                <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($p['created_at'] ?? $p['tanggal'] ?? 'now')) ?></span>
            </div>
            <div class="text-sm text-gray-600 prose max-w-none"><?= $p['konten'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (empty($ruang_aktif) && empty($pengumuman)): ?>
<div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
    <i class="fas fa-inbox text-5xl mb-4 opacity-30"></i>
    <p class="text-lg font-medium">Belum ada ujian atau pengumuman aktif.</p>
    <p class="text-sm mt-1">Pantau terus halaman ini untuk informasi terbaru.</p>
</div>
<?php endif; ?>

<!-- Token Modal -->
<div id="modalToken" class="modal-overlay hidden">
    <div class="modal-card" style="max-width:400px;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Masukkan Token Ujian</h3>
            <button onclick="closeTokenModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <p id="tokenModalUjianName" class="text-sm text-gray-600 mb-4"></p>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Token</label>
            <input type="text" id="tokenInput" placeholder="Masukkan token (6 karakter)" maxlength="6"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase tracking-widest font-mono text-center text-lg focus:outline-none focus:ring-2 focus:ring-emerald-400"
                oninput="this.value = this.value.toUpperCase()">
            <p id="tokenError" class="text-red-600 text-xs mt-1 hidden"></p>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeTokenModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Batal</button>
            <button type="button" onclick="submitToken()" id="tokenSubmitBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-arrow-right"></i> Mulai
            </button>
        </div>
    </div>
</div>

<script>
let currentRuangId = null;

function openTokenModal(ruangId, ujianName) {
    currentRuangId = ruangId;
    document.getElementById('tokenModalUjianName').textContent = 'Ujian: ' + ujianName;
    document.getElementById('tokenInput').value = '';
    document.getElementById('tokenError').classList.add('hidden');
    document.getElementById('modalToken').classList.remove('hidden');
    setTimeout(() => document.getElementById('tokenInput').focus(), 100);
}

function closeTokenModal() {
    document.getElementById('modalToken').classList.add('hidden');
    currentRuangId = null;
}

async function submitToken() {
    const token = document.getElementById('tokenInput').value.trim().toUpperCase();
    const errEl = document.getElementById('tokenError');
    const btn   = document.getElementById('tokenSubmitBtn');

    if (!token || token.length < 4) {
        errEl.textContent = 'Masukkan token yang valid.';
        errEl.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
    errEl.classList.add('hidden');

    try {
        const res = await fetch('/siswa/ujian/verify-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                [CSRF_NAME]: CSRF_TOKEN,
                ruang_id: currentRuangId,
                token: token,
            }),
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            errEl.textContent = data.message ?? 'Token tidak valid!';
            errEl.classList.remove('hidden');
        }
    } catch (e) {
        errEl.textContent = 'Terjadi kesalahan jaringan.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-arrow-right"></i> Mulai';
    }
}

document.getElementById('tokenInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') submitToken();
});
</script>

<?= $this->endSection() ?>
