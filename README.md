# CBT CI4 MTSN 1 Mesuji

Aplikasi **Computer Based Test (CBT)** berbasis **CodeIgniter 4** dan **MySQL** untuk MTSN 1 Mesuji. Aplikasi ini digunakan untuk pelaksanaan ujian berbasis komputer bagi siswa/i MTSN 1 Mesuji.

---

## Daftar Isi

- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
  - [1. Clone Repository](#1-clone-repository)
  - [2. Instal Dependensi (Composer)](#2-instal-dependensi-composer)
  - [3. Konfigurasi File Environment (.env)](#3-konfigurasi-file-environment-env)
  - [4. Konfigurasi Database](#4-konfigurasi-database)
  - [5. Migrasi & Seed Database](#5-migrasi--seed-database)
  - [6. Konfigurasi Web Server](#6-konfigurasi-web-server)
  - [7. Menjalankan Aplikasi](#7-menjalankan-aplikasi)
- [Struktur Folder](#struktur-folder)
- [Akun Default](#akun-default)
- [Troubleshooting](#troubleshooting)

---

## Persyaratan Sistem

Sebelum menginstal, pastikan perangkat Anda telah memenuhi persyaratan berikut:

| Komponen | Versi Minimum |
|---|---|
| PHP | 7.4 atau lebih baru (disarankan PHP 8.1) |
| Composer | 2.x |
| MySQL / MariaDB | 5.7 / 10.3 atau lebih baru |
| Web Server | Apache 2.4 / Nginx (atau gunakan XAMPP / Laragon) |
| Ekstensi PHP | `intl`, `mbstring`, `json`, `mysqlnd`, `xml`, `curl` |

> **Catatan:** Untuk kemudahan pengembangan lokal, disarankan menggunakan **XAMPP**, **Laragon**, atau **WAMP**.

---

## Instalasi

### 1. Clone Repository

Buka terminal / command prompt, lalu jalankan perintah berikut:

```bash
git clone https://github.com/modaratipora/cbt_ci4_mtsn1mesuji.git
cd cbt_ci4_mtsn1mesuji
```

Atau unduh sebagai ZIP dari halaman GitHub, kemudian ekstrak ke direktori web server Anda (misalnya `C:\xampp\htdocs\` untuk XAMPP di Windows).

---

### 2. Instal Dependensi (Composer)

Pastikan **Composer** sudah terinstal di sistem Anda. Cek dengan perintah:

```bash
composer --version
```

Jika belum terinstal, unduh dari [https://getcomposer.org/download/](https://getcomposer.org/download/).

Kemudian instal semua dependensi proyek:

```bash
composer install
```

Tunggu hingga proses selesai. Folder `vendor/` akan dibuat secara otomatis.

---

### 3. Konfigurasi File Environment (.env)

Salin file `.env.example` menjadi `.env`:

```bash
# Linux / macOS
cp env .env

# Windows (Command Prompt)
copy env .env
```

> **Catatan:** CodeIgniter 4 menyertakan file bernama `env` (tanpa titik) sebagai template. Salin dan ubah namanya menjadi `.env`.

Buka file `.env` dengan editor teks, lalu ubah baris berikut sesuai kebutuhan:

```env
# Ubah mode menjadi development saat pengembangan, production saat produksi
CI_ENVIRONMENT = development

# URL dasar aplikasi (sesuaikan dengan URL lokal Anda)
# Gunakan HTTPS di lingkungan produksi: app.baseURL = 'https://domain-anda.com/'
app.baseURL = 'http://localhost/cbt_ci4_mtsn1mesuji/public/'

# Konfigurasi database
database.default.hostname = localhost
database.default.database = cbt_mtsn1mesuji
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

---

### 4. Konfigurasi Database

#### a. Buat Database Baru

Masuk ke **phpMyAdmin** (http://localhost/phpmyadmin) atau gunakan MySQL CLI:

```sql
CREATE DATABASE cbt_mtsn1mesuji CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### b. Import Struktur Database (Jika Tersedia File SQL)

Jika terdapat file SQL di folder `database/` atau `sql/`, import file tersebut:

```bash
# Menggunakan MySQL CLI
mysql -u root -p cbt_mtsn1mesuji < database/cbt_mtsn1mesuji.sql
```

Atau import melalui phpMyAdmin:
1. Buka phpMyAdmin → pilih database `cbt_mtsn1mesuji`
2. Klik tab **Import**
3. Pilih file `.sql` → klik **Go**

---

### 5. Migrasi & Seed Database

Jika proyek menggunakan fitur **Migration** dan **Seeder** CodeIgniter 4, jalankan perintah berikut dari direktori root proyek:

```bash
# Jalankan semua migrasi (membuat tabel)
php spark migrate

# Jalankan seeder (mengisi data awal)
php spark db:seed DatabaseSeeder
```

---

### 6. Konfigurasi Web Server

#### Menggunakan XAMPP (Windows)

1. Salin folder proyek ke `C:\xampp\htdocs\cbt_ci4_mtsn1mesuji`
2. Pastikan Apache dan MySQL sudah berjalan di XAMPP Control Panel
3. Akses aplikasi di browser: `http://localhost/cbt_ci4_mtsn1mesuji/public/`

#### Menggunakan Laragon (Windows)

1. Salin folder proyek ke `C:\laragon\www\cbt_ci4_mtsn1mesuji`
2. Jalankan Laragon, klik **Start All**
3. Akses aplikasi di: `http://cbt_ci4_mtsn1mesuji.test/` (Laragon membuat virtual host otomatis)

#### Menggunakan Apache Virtual Host (Linux/macOS)

Tambahkan konfigurasi berikut di file virtual host Apache:

```apache
<VirtualHost *:80>
    ServerName cbt.local
    DocumentRoot /var/www/html/cbt_ci4_mtsn1mesuji/public

    <Directory /var/www/html/cbt_ci4_mtsn1mesuji/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/cbt_error.log
    CustomLog ${APACHE_LOG_DIR}/cbt_access.log combined
</VirtualHost>
```

Aktifkan mod_rewrite Apache:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

### 7. Menjalankan Aplikasi

#### Cara 1: Menggunakan Built-in Server PHP (Development)

Dari direktori root proyek, jalankan:

```bash
php spark serve
```

Kemudian buka browser dan akses: `http://localhost:8080`

#### Cara 2: Menggunakan Web Server (XAMPP/Apache)

Buka browser dan akses sesuai konfigurasi web server Anda, misalnya:
`http://localhost/cbt_ci4_mtsn1mesuji/public/`

---

## Struktur Folder

```
cbt_ci4_mtsn1mesuji/
├── app/                    # Kode utama aplikasi (MVC)
│   ├── Config/             # File konfigurasi (Database, Routes, dll.)
│   ├── Controllers/        # Controller aplikasi
│   ├── Models/             # Model (interaksi database)
│   ├── Views/              # Tampilan (HTML/Template)
│   └── Libraries/          # Library tambahan
├── public/                 # Document root (index.php, assets)
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── writable/               # Cache, log, session (harus dapat ditulis)
├── vendor/                 # Dependensi Composer (jangan diedit manual)
├── database/               # File SQL / Migration / Seeder
├── .env                    # Konfigurasi environment (buat dari file `env`)
├── env                     # Template .env (disediakan CI4)
├── composer.json           # Definisi dependensi Composer
└── spark                   # CLI tool CodeIgniter 4
```

---

## Akun Default

Setelah database berhasil diimport atau seeder dijalankan, akun awal akan dibuat secara otomatis. Informasi akun default disimpan di file seeder (`database/seeds/`) dan **tidak dipublikasikan** di README ini demi keamanan.

> **Penting (Keamanan Produksi):**
> - Segera ubah semua password default setelah pertama kali login.
> - Nonaktifkan atau hapus akun-akun default yang tidak digunakan di lingkungan produksi.
> - Pastikan setiap pengguna memiliki akun dan password yang unik.

---

## Troubleshooting

### ❌ Error: "The page you are looking for cannot be found"

- Pastikan `mod_rewrite` Apache sudah aktif.
- Periksa file `.htaccess` ada di folder `public/`.
- Pastikan `AllowOverride All` sudah dikonfigurasi di web server.

### ❌ Error: "Unable to connect to the database"

- Periksa konfigurasi database di file `.env`.
- Pastikan MySQL/MariaDB sudah berjalan.
- Pastikan nama database, username, dan password sudah benar.

### ❌ Error: "Writable directory is not writable"

Berikan izin tulis pada folder `writable/`:

```bash
# Linux / macOS — berikan izin ke user web server (lebih aman)
sudo chown -R www-data:www-data writable/
chmod -R 755 writable/
```

### ❌ Error saat `composer install`: "Your PHP version does not satisfy..."

- Perbarui versi PHP Anda sesuai persyaratan (minimal PHP 7.4).
- Periksa versi PHP aktif: `php --version`

### ❌ Gambar / CSS tidak tampil

- Pastikan `app.baseURL` di file `.env` sudah diisi dengan benar dan diakhiri `/`.
- Contoh: `app.baseURL = 'http://localhost/cbt_ci4_mtsn1mesuji/public/'`

---

## Lisensi

Proyek ini dikembangkan untuk keperluan internal **MTSN 1 Mesuji**. Seluruh hak cipta dilindungi.

---

## Kontak & Dukungan

Jika mengalami kendala dalam instalasi atau penggunaan, silakan buat **Issue** di halaman GitHub repositori ini atau hubungi pengembang.