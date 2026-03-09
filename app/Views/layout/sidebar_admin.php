<?php
$currentUrl = current_url();
$menuItems  = [
    ['url' => '/admin/dashboard',    'icon' => 'fa-house',          'label' => 'Dashboard'],
    ['url' => '/admin/kelas',        'icon' => 'fa-school',         'label' => 'Master Kelas'],
    ['url' => '/admin/mapel',        'icon' => 'fa-book-open',      'label' => 'Master Mapel'],
    ['url' => '/admin/relasi-guru',  'icon' => 'fa-link',           'label' => 'Relasi Guru'],
    ['url' => '/admin/guru',         'icon' => 'fa-chalkboard-user','label' => 'Data Guru'],
    ['url' => '/admin/siswa',        'icon' => 'fa-user-graduate',  'label' => 'Data Siswa'],
    ['url' => '/admin/bank-soal',    'icon' => 'fa-file-lines',     'label' => 'Bank Soal'],
    ['url' => '/admin/ruang-ujian',  'icon' => 'fa-desktop',        'label' => 'Ruang Ujian'],
    ['url' => '/admin/pengumuman',   'icon' => 'fa-bullhorn',       'label' => 'Pengumuman'],
    ['url' => '/admin/administrator','icon' => 'fa-user-shield',    'label' => 'Administrator'],
    ['url' => '/admin/settings',     'icon' => 'fa-gear',           'label' => 'Settings'],
    ['url' => '/admin/ubah-password','icon' => 'fa-lock',           'label' => 'Ubah Password'],
];
?>
<aside class="sidebar bg-gray-900 text-white w-64 min-h-screen flex flex-col fixed left-0 top-0 z-50" id="sidebar">
    <!-- Brand -->
    <div class="p-5 border-b border-gray-700 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-lg font-bold shrink-0">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
            <h1 class="text-base font-bold text-white leading-tight">CBT MTsN 1</h1>
            <p class="text-xs text-blue-400 font-medium">Panel Admin</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-3 space-y-0.5 px-2" role="navigation" aria-label="Admin menu">
        <?php foreach ($menuItems as $item): ?>
        <?php $isActive = strpos($currentUrl, $item['url']) !== false; ?>
        <a href="<?= $item['url'] ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  <?= $isActive
                      ? 'bg-blue-600 text-white shadow-md'
                      : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas <?= $item['icon'] ?> w-4 text-center text-base"></i>
            <span><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-4 border-t border-gray-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-sm font-bold shrink-0">
                <?= esc(strtoupper(substr(session()->get('user_nama') ?? 'A', 0, 1))) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate"><?= esc(session()->get('user_nama')) ?></p>
                <p class="text-xs text-gray-400">Administrator</p>
            </div>
        </div>
        <a href="/logout"
           class="flex items-center justify-center gap-2 w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-right-from-bracket"></i>
            Logout
        </a>
    </div>
</aside>
