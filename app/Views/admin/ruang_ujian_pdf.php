<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($pageTitle) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .info { margin-bottom: 16px; color: #555; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #4a5568; color: white; padding: 6px 8px; text-align: left; font-size: 11px; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) td { background: #f7fafc; }
        .status-selesai { color: #276749; font-weight: bold; }
        .status-sedang  { color: #2b6cb0; font-weight: bold; }
        .status-belum   { color: #718096; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <button onclick="window.print()" style="margin-bottom:12px;padding:6px 16px;background:#3182ce;color:white;border:none;border-radius:4px;cursor:pointer;">
        🖨 Cetak
    </button>

    <h1>Hasil Ujian: <?= esc($ruang['nama_ujian']) ?></h1>
    <div class="info">
        Tanggal dicetak: <?= date('d/m/Y H:i') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Nilai</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($hasil)): ?>
            <tr><td colspan="10" style="text-align:center;padding:16px;color:#718096;">Belum ada data.</td></tr>
            <?php else: ?>
            <?php foreach ($hasil as $i => $h): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($h['nisn']) ?></td>
                <td><?= esc($h['nama_siswa']) ?></td>
                <td><?= esc($h['nama_kelas'] ?? '-') ?></td>
                <td class="status-<?= $h['status'] ?>"><?= ucfirst($h['status']) ?></td>
                <td><?= $h['nilai'] ?? '-' ?></td>
                <td><?= $h['jml_benar'] ?? 0 ?></td>
                <td><?= $h['jml_salah'] ?? 0 ?></td>
                <td><?= $h['waktu_mulai'] ?? '-' ?></td>
                <td><?= $h['waktu_selesai'] ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
