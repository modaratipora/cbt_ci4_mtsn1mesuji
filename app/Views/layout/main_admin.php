<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? esc($pageTitle) . ' - CBT MTsN 1 Mesuji' : 'CBT MTsN 1 Mesuji' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
    <!-- Summernote -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar { transition: transform 0.3s ease; }
        .main-content { margin-left: 256px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay { display: block; }
            .main-content { margin-left: 0; }
        }

        /* Toast */
        .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 9999; }
        .toast { padding: 0.75rem 1.25rem; border-radius: 0.5rem; color: white; min-width: 220px;
                 margin-bottom: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                 animation: toastIn 0.3s ease; display: flex; align-items: center; gap: 0.5rem; }
        .toast-success { background: #16a34a; }
        .toast-error   { background: #dc2626; }
        .toast-info    { background: #2563eb; }
        .toast-warning { background: #d97706; }
        @keyframes toastIn { from { transform: translateX(110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000;
                         display: flex; align-items: center; justify-content: center; }
        .modal-card { background: white; border-radius: 0.75rem; padding: 1.5rem;
                      max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }

        /* Print */
        @media print {
            .sidebar, .main-content > header, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Mobile overlay -->
<div class="sidebar-overlay hidden fixed inset-0 bg-black/50 z-40" id="sidebarOverlay"></div>

<?= view('layout/sidebar_admin') ?>

<div class="main-content min-h-screen flex flex-col">

    <!-- Top Header -->
    <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-40 no-print">
        <div class="flex items-center gap-3">
            <!-- Mobile hamburger -->
            <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 md:hidden focus:outline-none" aria-label="Toggle sidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
                <h2 class="text-base font-semibold text-gray-800"><?= isset($pageTitle) ? esc($pageTitle) : 'Dashboard' ?></h2>
                <?php if (isset($breadcrumb)): ?>
                <nav class="text-xs text-gray-400 mt-0.5"><?= $breadcrumb ?></nav>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-sm text-gray-500 hidden sm:block">
            <i class="fas fa-calendar-day mr-1"></i>
            <?= date('d F Y, H:i') ?>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="flash-msg mx-6 mt-4 p-3 bg-green-50 border border-green-300 text-green-700 rounded-lg flex items-center justify-between gap-4">
        <span class="flex items-center gap-2"><i class="fas fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></span>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 shrink-0"><i class="fas fa-xmark"></i></button>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="flash-msg mx-6 mt-4 p-3 bg-red-50 border border-red-300 text-red-700 rounded-lg flex items-center justify-between gap-4">
        <span class="flex items-center gap-2"><i class="fas fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?></span>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 shrink-0"><i class="fas fa-xmark"></i></button>
    </div>
    <?php endif; ?>

    <!-- Page Content -->
    <main class="p-6 flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="text-center text-xs text-gray-400 py-4 no-print">
        &copy; <?= date('Y') ?> CBT MTsN 1 Mesuji &mdash; Sistem Ujian Online
    </footer>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer" aria-live="polite"></div>

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js" crossorigin="anonymous"></script>

<script>
// Sidebar toggle (mobile)
const sidebarToggle  = document.getElementById('sidebarToggle');
const sidebar        = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

sidebarToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    sidebarOverlay.classList.toggle('hidden');
});
sidebarOverlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    sidebarOverlay.classList.add('hidden');
});

// Toast utility
function showToast(message, type = 'success') {
    const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info', warning: 'fa-triangle-exclamation' };
    const container = document.getElementById('toastContainer');
    const toast     = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas ${icons[type] ?? icons.info}"></i><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// Auto-dismiss flash messages after 5 s
setTimeout(() => { document.querySelectorAll('.flash-msg').forEach(el => el.remove()); }, 5000);

// CSRF helpers
const CSRF_TOKEN = '<?= csrf_hash() ?>';
const CSRF_NAME  = '<?= csrf_token() ?>';

async function fetchWithCSRF(url, data = {}) {
    data[CSRF_NAME] = CSRF_TOKEN;
    const res = await fetch(url, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body:    JSON.stringify(data),
    });
    return res.json();
}
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
