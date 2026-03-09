<?= $this->extend('layout/main_guru') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <a href="/guru/ruang-ujian" class="inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-800">
        <i class="fas fa-arrow-left"></i> Kembali ke Ruang Ujian
    </a>
</div>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-gray-800"><?= esc($pageTitle) ?></h1>
    <div class="flex gap-2">
        <a href="/guru/ruang-ujian/export-excel/<?= $ruang['id'] ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 no-print">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 no-print">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Ruang Info Card -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <div class="text-gray-500 text-xs mb-1">Nama Ujian</div>
            <div class="font-semibold"><?= esc($ruang['nama_ujian']) ?></div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Mata Pelajaran</div>
            <div class="font-semibold"><?= esc($ruang['nama_mapel'] ?? '-') ?></div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Kelas</div>
            <div class="font-semibold"><?= esc($ruang['nama_kelas'] ?? '-') ?></div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Durasi</div>
            <div class="font-semibold"><?= $ruang['durasi'] ?> menit</div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Tanggal Mulai</div>
            <div class="font-semibold"><?= $ruang['tanggal_mulai'] ? date('d/m/Y H:i', strtotime($ruang['tanggal_mulai'])) : '-' ?></div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Tanggal Selesai</div>
            <div class="font-semibold"><?= $ruang['tanggal_selesai'] ? date('d/m/Y H:i', strtotime($ruang['tanggal_selesai'])) : '-' ?></div>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Status</div>
            <?php
                $statusClass = match($ruang['status']) {
                    'aktif'   => 'bg-green-100 text-green-700',
                    'selesai' => 'bg-red-100 text-red-700',
                    default   => 'bg-gray-100 text-gray-600',
                };
            ?>
            <span class="px-2 py-0.5 rounded text-xs font-medium <?= $statusClass ?>"><?= ucfirst($ruang['status']) ?></span>
        </div>
        <div>
            <div class="text-gray-500 text-xs mb-1">Token</div>
            <span class="font-mono text-sm font-bold tracking-widest bg-gray-100 px-2 py-0.5 rounded"><?= esc($ruang['token']) ?></span>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow p-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <h2 class="text-base font-semibold text-gray-700">Hasil Peserta</h2>
        <input id="tableMonitor_search" type="text" placeholder="Cari siswa..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-emerald-400 no-print">
    </div>
    <div class="overflow-x-auto">
        <table id="tableMonitor" class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">NISN</th>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Nilai</th>
                    <th class="p-3 text-center">Benar</th>
                    <th class="p-3 text-center">Salah</th>
                    <th class="p-3 text-center">Waktu Mulai</th>
                    <th class="p-3 text-center">Waktu Selesai</th>
                    <th class="p-3 text-center no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hasil)): ?>
                <tr><td colspan="11" class="text-center py-8 text-gray-400">Belum ada peserta ujian.</td></tr>
                <?php else: ?>
                <?php foreach ($hasil as $i => $h): ?>
                <?php
                    $statusClass = match($h['status']) {
                        'selesai' => 'bg-green-100 text-green-700',
                        'mulai'   => 'bg-blue-100 text-blue-700',
                        default   => 'bg-gray-100 text-gray-600',
                    };
                ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3"><?= $i + 1 ?></td>
                    <td class="p-3 font-mono text-xs"><?= esc($h['nisn'] ?? '-') ?></td>
                    <td class="p-3 font-medium"><?= esc($h['nama_siswa']) ?></td>
                    <td class="p-3 text-xs"><?= esc($h['nama_kelas'] ?? '-') ?></td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-medium <?= $statusClass ?>"><?= ucfirst($h['status']) ?></span>
                    </td>
                    <td class="p-3 text-center font-bold"><?= $h['nilai'] ?? '-' ?></td>
                    <td class="p-3 text-center text-green-600"><?= $h['jml_benar'] ?? 0 ?></td>
                    <td class="p-3 text-center text-red-600"><?= $h['jml_salah'] ?? 0 ?></td>
                    <td class="p-3 text-center text-xs"><?= $h['waktu_mulai'] ? date('d/m/y H:i', strtotime($h['waktu_mulai'])) : '-' ?></td>
                    <td class="p-3 text-center text-xs"><?= $h['waktu_selesai'] ? date('d/m/y H:i', strtotime($h['waktu_selesai'])) : '-' ?></td>
                    <td class="p-3 text-center no-print">
                        <form method="POST" action="/guru/ruang-ujian/reset-ujian/<?= $h['id'] ?>" class="inline" onsubmit="return confirm('Reset ujian siswa ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-4 gap-2 no-print">
        <span id="tableMonitor_info" class="text-sm text-gray-500"></span>
        <div id="tableMonitor_pagination" class="flex gap-1 flex-wrap"></div>
    </div>
</div>

<script>
(function() {
    const tableId = 'tableMonitor';
    const table   = document.getElementById(tableId);
    const tbody   = table.querySelector('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    let filtered  = [...allRows];
    let perPage   = 25;
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
    render();
})();
</script>

<?= $this->endSection() ?>
