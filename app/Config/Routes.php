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
    $routes->get('kelas',              'Admin\Kelas::index');
    $routes->post('kelas',             'Admin\Kelas::store');
    $routes->post('kelas/update/(:num)', 'Admin\Kelas::update/$1');
    $routes->post('kelas/delete/(:num)', 'Admin\Kelas::destroy/$1');

    // Mapel
    $routes->get('mapel',               'Admin\Mapel::index');
    $routes->post('mapel',              'Admin\Mapel::store');
    $routes->post('mapel/update/(:num)', 'Admin\Mapel::update/$1');
    $routes->post('mapel/delete/(:num)', 'Admin\Mapel::destroy/$1');

    // Relasi Guru
    $routes->get('relasi-guru',               'Admin\RelasiGuru::index');
    $routes->post('relasi-guru',              'Admin\RelasiGuru::store');
    $routes->post('relasi-guru/delete/(:num)', 'Admin\RelasiGuru::destroy/$1');

    // Guru
    $routes->get('guru',                        'Admin\Guru::index');
    $routes->post('guru',                       'Admin\Guru::store');
    $routes->post('guru/update/(:num)',          'Admin\Guru::update/$1');
    $routes->post('guru/delete/(:num)',          'Admin\Guru::destroy/$1');
    $routes->post('guru/reset-password/(:num)',  'Admin\Guru::resetPassword/$1');
    $routes->get('guru/import-template',         'Admin\Guru::importTemplate');
    $routes->post('guru/import',                 'Admin\Guru::importTemplate');

    // Siswa
    $routes->get('siswa',                        'Admin\Siswa::index');
    $routes->post('siswa',                       'Admin\Siswa::store');
    $routes->post('siswa/update/(:num)',          'Admin\Siswa::update/$1');
    $routes->post('siswa/delete/(:num)',          'Admin\Siswa::destroy/$1');
    $routes->post('siswa/reset-password/(:num)', 'Admin\Siswa::resetPassword/$1');
    $routes->get('siswa/import-template',        'Admin\Siswa::importTemplate');
    $routes->post('siswa/import',                'Admin\Siswa::importTemplate');

    // Bank Soal
    $routes->get('bank-soal',               'Admin\BankSoal::index');
    $routes->post('bank-soal',              'Admin\BankSoal::store');
    $routes->post('bank-soal/update/(:num)', 'Admin\BankSoal::update/$1');
    $routes->post('bank-soal/delete/(:num)', 'Admin\BankSoal::destroy/$1');

    // Soal
    $routes->get('soal',               'Admin\Soal::index');
    $routes->post('soal',              'Admin\Soal::store');
    $routes->post('soal/update/(:num)', 'Admin\Soal::update/$1');
    $routes->post('soal/delete/(:num)', 'Admin\Soal::destroy/$1');
    $routes->post('soal/import',        'Admin\Soal::import');

    // Ruang Ujian
    $routes->get('ruang-ujian',                     'Admin\RuangUjian::index');
    $routes->post('ruang-ujian',                    'Admin\RuangUjian::store');
    $routes->post('ruang-ujian/update/(:num)',       'Admin\RuangUjian::update/$1');
    $routes->post('ruang-ujian/delete/(:num)',       'Admin\RuangUjian::destroy/$1');
    $routes->get('ruang-ujian/monitoring/(:num)',    'Admin\RuangUjian::monitoring/$1');

    // Pengumuman
    $routes->get('pengumuman',               'Admin\Pengumuman::index');
    $routes->post('pengumuman',              'Admin\Pengumuman::store');
    $routes->post('pengumuman/update/(:num)', 'Admin\Pengumuman::update/$1');
    $routes->post('pengumuman/delete/(:num)', 'Admin\Pengumuman::destroy/$1');

    // Administrator (manage admin accounts)
    $routes->get('administrator',               'Admin\Administrator::index');
    $routes->post('administrator',              'Admin\Administrator::store');
    $routes->post('administrator/update/(:num)', 'Admin\Administrator::update/$1');
    $routes->post('administrator/delete/(:num)', 'Admin\Administrator::destroy/$1');

    // Settings
    $routes->get('settings',        'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');

    // Ubah Password
    $routes->get('ubah-password',        'Admin\UbahPassword::index');
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
