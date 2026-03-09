<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------
// Public routes
// ---------------------------------------------------------------
$routes->get('/',       static function () {
    return redirect()->to('/login');
});
$routes->get('/login',  'Auth::loginForm');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// ---------------------------------------------------------------
// Admin routes
// ---------------------------------------------------------------
$routes->group('/admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Kelas
    $routes->get('kelas',                    'Admin\Kelas::index');
    $routes->post('kelas/store',             'Admin\Kelas::store');
    $routes->post('kelas/update/(:num)',     'Admin\Kelas::update/$1');
    $routes->post('kelas/destroy/(:num)',    'Admin\Kelas::destroy/$1');
    $routes->post('kelas/bulk-delete',       'Admin\Kelas::bulkDelete');

    // Mapel
    $routes->get('mapel',                    'Admin\Mapel::index');
    $routes->post('mapel/store',             'Admin\Mapel::store');
    $routes->post('mapel/update/(:num)',     'Admin\Mapel::update/$1');
    $routes->post('mapel/destroy/(:num)',    'Admin\Mapel::destroy/$1');
    $routes->post('mapel/bulk-delete',       'Admin\Mapel::bulkDelete');

    // Relasi Guru
    $routes->get('relasi-guru',              'Admin\RelasiGuru::index');
    $routes->post('relasi-guru/store',       'Admin\RelasiGuru::store');
    $routes->post('relasi-guru/destroy/(:num)', 'Admin\RelasiGuru::destroy/$1');
    $routes->post('relasi-guru/bulk-delete', 'Admin\RelasiGuru::bulkDelete');

    // Guru
    $routes->get('guru',                          'Admin\Guru::index');
    $routes->post('guru/store',                   'Admin\Guru::store');
    $routes->post('guru/update/(:num)',            'Admin\Guru::update/$1');
    $routes->post('guru/destroy/(:num)',           'Admin\Guru::destroy/$1');
    $routes->post('guru/bulk-delete',             'Admin\Guru::bulkDelete');
    $routes->post('guru/reset-password/(:num)',   'Admin\Guru::resetPassword/$1');
    $routes->post('guru/bulk-reset-password',     'Admin\Guru::bulkResetPassword');
    $routes->get('guru/import-template',          'Admin\Guru::importTemplate');
    $routes->post('guru/import-excel',            'Admin\Guru::importExcel');

    // Siswa
    $routes->get('siswa',                         'Admin\Siswa::index');
    $routes->post('siswa/store',                  'Admin\Siswa::store');
    $routes->post('siswa/update/(:num)',           'Admin\Siswa::update/$1');
    $routes->post('siswa/destroy/(:num)',          'Admin\Siswa::destroy/$1');
    $routes->post('siswa/bulk-delete',            'Admin\Siswa::bulkDelete');
    $routes->post('siswa/bulk-reset-password',    'Admin\Siswa::bulkResetPassword');
    $routes->get('siswa/import-template',         'Admin\Siswa::importTemplate');
    $routes->post('siswa/import-excel',           'Admin\Siswa::importExcel');

    // Bank Soal
    $routes->get('bank-soal',                    'Admin\BankSoal::index');
    $routes->post('bank-soal/store',             'Admin\BankSoal::store');
    $routes->post('bank-soal/update/(:num)',     'Admin\BankSoal::update/$1');
    $routes->post('bank-soal/destroy/(:num)',    'Admin\BankSoal::destroy/$1');
    $routes->post('bank-soal/bulk-delete',       'Admin\BankSoal::bulkDelete');

    // Soal (bank_soal_id scoped)
    $routes->get('soal/(:num)',                        'Admin\Soal::index/$1');
    $routes->post('soal/(:num)/store',                 'Admin\Soal::store/$1');
    $routes->post('soal/(:num)/update/(:num)',         'Admin\Soal::update/$1/$2');
    $routes->post('soal/(:num)/destroy/(:num)',        'Admin\Soal::destroy/$1/$2');
    $routes->post('soal/(:num)/bulk-delete',           'Admin\Soal::bulkDelete/$1');
    $routes->post('soal/(:num)/import-excel',          'Admin\Soal::importExcel/$1');
    $routes->get('soal/(:num)/import-template',        'Admin\Soal::importTemplate/$1');

    // Ruang Ujian
    $routes->get('ruang-ujian',                        'Admin\RuangUjian::index');
    $routes->post('ruang-ujian/store',                 'Admin\RuangUjian::store');
    $routes->post('ruang-ujian/update/(:num)',         'Admin\RuangUjian::update/$1');
    $routes->post('ruang-ujian/destroy/(:num)',        'Admin\RuangUjian::destroy/$1');
    $routes->post('ruang-ujian/bulk-delete',           'Admin\RuangUjian::bulkDelete');
    $routes->get('ruang-ujian/monitoring/(:num)',      'Admin\RuangUjian::monitoring/$1');
    $routes->post('ruang-ujian/regenerate-token/(:num)', 'Admin\RuangUjian::regenerateToken/$1');
    $routes->post('ruang-ujian/reset-ujian/(:num)',    'Admin\RuangUjian::resetUjian/$1');
    $routes->post('ruang-ujian/bulk-reset-ujian',      'Admin\RuangUjian::bulkResetUjian');
    $routes->get('ruang-ujian/export-excel/(:num)',    'Admin\RuangUjian::exportExcel/$1');
    $routes->get('ruang-ujian/export-pdf/(:num)',      'Admin\RuangUjian::exportPDF/$1');

    // Pengumuman
    $routes->get('pengumuman',                   'Admin\Pengumuman::index');
    $routes->post('pengumuman/store',            'Admin\Pengumuman::store');
    $routes->post('pengumuman/update/(:num)',    'Admin\Pengumuman::update/$1');
    $routes->post('pengumuman/destroy/(:num)',   'Admin\Pengumuman::destroy/$1');

    // Administrator
    $routes->get('administrator',                'Admin\Administrator::index');
    $routes->post('administrator/store',         'Admin\Administrator::store');
    $routes->post('administrator/update/(:num)', 'Admin\Administrator::update/$1');
    $routes->post('administrator/destroy/(:num)', 'Admin\Administrator::destroy/$1');

    // Settings
    $routes->get('settings',         'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');

    // Ubah Password
    $routes->get('ubah-password',         'Admin\UbahPassword::index');
    $routes->post('ubah-password/update', 'Admin\UbahPassword::update');
});

// ---------------------------------------------------------------
// Guru routes
// ---------------------------------------------------------------
$routes->group('/guru', ['filter' => 'auth:guru'], static function ($routes) {
    $routes->get('dashboard', 'Guru\Dashboard::index');

    // Bank Soal
    $routes->get('bank-soal',               'Guru\BankSoal::index');
    $routes->post('bank-soal',              'Guru\BankSoal::store');
    $routes->post('bank-soal/update/(:num)', 'Guru\BankSoal::update/$1');
    $routes->post('bank-soal/delete/(:num)', 'Guru\BankSoal::destroy/$1');

    // Soal
    $routes->get('soal',               'Guru\Soal::index');
    $routes->post('soal',              'Guru\Soal::store');
    $routes->post('soal/update/(:num)', 'Guru\Soal::update/$1');
    $routes->post('soal/delete/(:num)', 'Guru\Soal::destroy/$1');

    // Ruang Ujian
    $routes->get('ruang-ujian',                  'Guru\RuangUjian::index');
    $routes->post('ruang-ujian',                 'Guru\RuangUjian::store');
    $routes->post('ruang-ujian/update/(:num)',    'Guru\RuangUjian::update/$1');
    $routes->post('ruang-ujian/delete/(:num)',    'Guru\RuangUjian::destroy/$1');
    $routes->get('ruang-ujian/monitoring/(:num)', 'Guru\RuangUjian::monitoring/$1');

    // Ubah Password
    $routes->get('ubah-password',         'Guru\UbahPassword::index');
    $routes->post('ubah-password/update',  'Guru\UbahPassword::update');
});

// ---------------------------------------------------------------
// Siswa routes
// ---------------------------------------------------------------
$routes->group('/siswa', ['filter' => 'auth:siswa'], static function ($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');

    // Ruang Ujian
    $routes->get('ruang-ujian', 'Siswa\RuangUjian::index');

    // Ujian
    $routes->get('ujian/(:num)',        'Siswa\Ujian::start/$1');
    $routes->post('ujian/save-answer',  'Siswa\Ujian::saveAnswer');
    $routes->post('ujian/submit',       'Siswa\Ujian::submit');

    // Ubah Password
    $routes->get('ubah-password',        'Siswa\UbahPassword::index');
    $routes->post('ubah-password/update', 'Siswa\UbahPassword::update');
});
