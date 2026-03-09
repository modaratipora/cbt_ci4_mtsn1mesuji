# Panduan Instalasi CBT MTsN 1 Mesuji — VPS aaPanel

## Persyaratan
- PHP 8.1+ dengan ekstensi: mbstring, intl, json, mysqlnd, curl, gd, xml, zip
- MySQL 8.0+ atau MariaDB 10.6+
- Apache 2.4+ dengan mod_rewrite
- Composer 2.x

## Langkah Instalasi

### 1. Upload File
Upload seluruh isi repository ke `/www/wwwroot/cbt.mtsn1mesuji.sch.id/`

### 2. Buat Database
Di aaPanel → Database → Tambah database:
- Nama DB: `cbt_mtsn`
- User: `cbt_mtsn`
- Password: `cbt_mtsn`

Import file `database/cbt_mtsn.sql` melalui phpMyAdmin atau:
```
mysql -u cbt_mtsn -p cbt_mtsn < database/cbt_mtsn.sql
```

### 3. Install Dependencies
```
composer install --no-dev
```

### 4. Konfigurasi .env
Salin `.env.example` menjadi `.env` dan sesuaikan jika perlu.

### 5. Konfigurasi aaPanel Website
- Document Root: `/www/wwwroot/cbt.mtsn1mesuji.sch.id/public`
- PHP Version: 8.1+
- Enable mod_rewrite

### 6. Permission Writable
```
chmod -R 755 writable/
chown -R www-data:www-data writable/
```

### 7. Akun Default Login
> **⚠️ PERINGATAN KEAMANAN**: Segera ubah semua password default setelah login pertama! Jangan gunakan akun default di lingkungan produksi.

| Role  | Kredensial |
|-------|-----------|
| Admin | Email: admin@mtsn1.sch.id / Password: Admin@MTsN2026 |
| Guru  | NIK: 1234567890123456 / Password: Guru@MTsN2026 |
| Siswa | NISN: 1234567890 / Password: Siswa@MTsN2026 |

## Troubleshooting
- **404 semua halaman**: Pastikan mod_rewrite aktif dan .htaccess diizinkan (AllowOverride All)
- **500 error**: Cek permission writable/ dan pastikan .env ada
- **Blank page**: Aktifkan display_errors sementara di .env: `CI_ENVIRONMENT = development`
