<?= $this->extend('layout/main_siswa') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-500 text-sm mt-1">Daftar ujian yang tersedia untuk Anda.</p>
</div>

<?php if (empty($ruang_list)): ?>
<div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
    <i class="fas fa-desktop text-5xl mb-4 opacity-30"></i>
    <p class="text-lg font-medium">Belum ada ujian aktif.</p>
    <p class="text-sm mt-1">Hubungi guru Anda jika ada ujian yang seharusnya tersedia.</p>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($ruang_list as $r): ?>
    <div class="bg-white rounded-xl shadow p-5 border border-gray-100 flex flex-col">
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800"><?= esc($r['nama_ujian']) ?></h3>
                <p class="text-xs text-gray-500 mt-0.5"><?= esc($r['nama_mapel'] ?? '-') ?></p>
            </div>
            <?php
                $statusClass = match(true) {
                    ($r['sudah_selesai'] ?? false) => 'bg-green-100 text-green-700',
                    ($r['sedang_jalan']  ?? false) => 'bg-blue-100 text-blue-700',
                    default                        => 'bg-yellow-100 text-yellow-700',
                };
                $statusLabel = match(true) {
                    ($r['sudah_selesai'] ?? false) => 'Selesai',
                    ($r['sedang_jalan']  ?? false) => 'Sedang Berjalan',
                    default                        => 'Belum Mulai',
                };
            ?>
            <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>"><?= $statusLabel ?></span>
        </div>

        <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-4 flex-1">
            <span><i class="fas fa-stopwatch mr-1"></i><?= $r['durasi'] ?> menit</span>
            <?php if ($r['tanggal_mulai']): ?>
            <span><i class="fas fa-calendar-day mr-1"></i>Mulai: <?= date('d/m/Y H:i', strtotime($r['tanggal_mulai'])) ?></span>
            <?php endif; ?>
            <?php if ($r['tanggal_selesai']): ?>
            <span><i class="fas fa-calendar-xmark mr-1"></i>Berakhir: <?= date('d/m/Y H:i', strtotime($r['tanggal_selesai'])) ?></span>
            <?php endif; ?>
        </div>

        <div class="mt-auto">
            <?php if ($r['sudah_selesai'] ?? false): ?>
            <div class="flex items-center gap-2">
                <button disabled class="flex-1 bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                    <i class="fas fa-check mr-1"></i> Selesai ✓
                </button>
                <?php if (!empty($r['hasil']['nilai'])): ?>
                <span class="text-sm font-bold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg whitespace-nowrap">
                    Nilai: <?= $r['hasil']['nilai'] ?>
                </span>
                <?php endif; ?>
            </div>
            <?php elseif ($r['sedang_jalan'] ?? false): ?>
            <a href="/siswa/ujian/<?= $r['id'] ?>"
                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-rotate-right mr-1"></i> Lanjutkan Ujian
            </a>
            <?php else: ?>
            <button onclick="openTokenModal(<?= (int)$r['id'] ?>, '<?= esc($r['nama_ujian']) ?>')"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition">
                <i class="fas fa-play-circle"></i> Mulai Ujian
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
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
