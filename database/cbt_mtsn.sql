-- ============================================================
-- CBT MTsN 1 Mesuji - Database Schema & Seed Data
-- ============================================================

CREATE DATABASE IF NOT EXISTS `cbt_mtsn`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `cbt_mtsn`;

-- ============================================================
-- TABLE: admins
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nama`       VARCHAR(100)     NOT NULL,
    `email`      VARCHAR(100)     NOT NULL UNIQUE,
    `password`   VARCHAR(255)     NOT NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: gurus
-- ============================================================
CREATE TABLE IF NOT EXISTS `gurus` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nik`        VARCHAR(20)      NOT NULL UNIQUE,
    `nama`       VARCHAR(100)     NOT NULL,
    `email`      VARCHAR(100)     NOT NULL UNIQUE,
    `password`   VARCHAR(255)     NOT NULL,
    `foto`       VARCHAR(255)     NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: kelas
-- ============================================================
CREATE TABLE IF NOT EXISTS `kelas` (
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nama_kelas`  VARCHAR(50)      NOT NULL,
    `created_at`  DATETIME         NULL,
    `updated_at`  DATETIME         NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: mapel
-- ============================================================
CREATE TABLE IF NOT EXISTS `mapel` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nama_mapel` VARCHAR(100)     NOT NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: relasi_guru
-- ============================================================
CREATE TABLE IF NOT EXISTS `relasi_guru` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `guru_id`    INT UNSIGNED     NOT NULL,
    `kelas_id`   INT UNSIGNED     NOT NULL,
    `mapel_id`   INT UNSIGNED     NOT NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`),
    KEY `fk_relasi_guru_guru`  (`guru_id`),
    KEY `fk_relasi_guru_kelas` (`kelas_id`),
    KEY `fk_relasi_guru_mapel` (`mapel_id`),
    CONSTRAINT `fk_relasi_guru_guru`  FOREIGN KEY (`guru_id`)  REFERENCES `gurus`  (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_relasi_guru_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas`  (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_relasi_guru_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel`  (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: siswa
-- ============================================================
CREATE TABLE IF NOT EXISTS `siswa` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nisn`       VARCHAR(20)      NOT NULL UNIQUE,
    `nama`       VARCHAR(100)     NOT NULL,
    `kelas_id`   INT UNSIGNED     NOT NULL,
    `password`   VARCHAR(255)     NOT NULL,
    `foto`       VARCHAR(255)     NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`),
    KEY `fk_siswa_kelas` (`kelas_id`),
    CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: bank_soal
-- ============================================================
CREATE TABLE IF NOT EXISTS `bank_soal` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `guru_id`    INT UNSIGNED     NOT NULL,
    `nama_bank`  VARCHAR(150)     NOT NULL,
    `mapel_id`   INT UNSIGNED     NOT NULL,
    `kelas_id`   INT UNSIGNED     NOT NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`),
    KEY `fk_bank_soal_guru`  (`guru_id`),
    KEY `fk_bank_soal_mapel` (`mapel_id`),
    KEY `fk_bank_soal_kelas` (`kelas_id`),
    CONSTRAINT `fk_bank_soal_guru`  FOREIGN KEY (`guru_id`)  REFERENCES `gurus`  (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bank_soal_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel`  (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_bank_soal_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas`  (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: soal
-- ============================================================
CREATE TABLE IF NOT EXISTS `soal` (
    `id`               INT UNSIGNED                                            NOT NULL AUTO_INCREMENT,
    `bank_soal_id`     INT UNSIGNED                                            NOT NULL,
    `pertanyaan`       TEXT                                                    NOT NULL,
    `tipe_soal`        ENUM('pg','essay','benar_salah','menjodohkan')          NOT NULL DEFAULT 'pg',
    `pilihan_a`        TEXT                                                    NULL,
    `pilihan_b`        TEXT                                                    NULL,
    `pilihan_c`        TEXT                                                    NULL,
    `pilihan_d`        TEXT                                                    NULL,
    `pilihan_e`        TEXT                                                    NULL,
    `jawaban_benar`    VARCHAR(10)                                             NULL,
    `kunci_menjodohkan` TEXT                                                   NULL,
    `bobot`            INT                                                     NOT NULL DEFAULT 1,
    `urutan`           INT                                                     NULL,
    `created_at`       DATETIME                                                NULL,
    `updated_at`       DATETIME                                                NULL,
    PRIMARY KEY (`id`),
    KEY `fk_soal_bank_soal` (`bank_soal_id`),
    CONSTRAINT `fk_soal_bank_soal` FOREIGN KEY (`bank_soal_id`) REFERENCES `bank_soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: ruang_ujian
-- ============================================================
CREATE TABLE IF NOT EXISTS `ruang_ujian` (
    `id`              INT UNSIGNED                               NOT NULL AUTO_INCREMENT,
    `guru_id`         INT UNSIGNED                               NOT NULL,
    `nama_ujian`      VARCHAR(150)                               NOT NULL,
    `bank_soal_id`    INT UNSIGNED                               NOT NULL,
    `kelas_id`        INT UNSIGNED                               NOT NULL,
    `mapel_id`        INT UNSIGNED                               NOT NULL,
    `tanggal_mulai`   DATETIME                                   NULL,
    `tanggal_selesai` DATETIME                                   NULL,
    `durasi`          INT                                        NOT NULL DEFAULT 60,
    `token`           VARCHAR(10)                                NOT NULL,
    `status`          ENUM('draft','aktif','selesai')            NOT NULL DEFAULT 'draft',
    `acak_soal`       TINYINT                                    NOT NULL DEFAULT 0,
    `acak_jawaban`    TINYINT                                    NOT NULL DEFAULT 0,
    `max_login`       INT                                        NOT NULL DEFAULT 1,
    `batas_keluar`    INT                                        NOT NULL DEFAULT 3,
    `created_at`      DATETIME                                   NULL,
    `updated_at`      DATETIME                                   NULL,
    PRIMARY KEY (`id`),
    KEY `fk_ruang_ujian_guru`      (`guru_id`),
    KEY `fk_ruang_ujian_bank_soal` (`bank_soal_id`),
    KEY `fk_ruang_ujian_kelas`     (`kelas_id`),
    KEY `fk_ruang_ujian_mapel`     (`mapel_id`),
    CONSTRAINT `fk_ruang_ujian_guru`      FOREIGN KEY (`guru_id`)      REFERENCES `gurus`     (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ruang_ujian_bank_soal` FOREIGN KEY (`bank_soal_id`) REFERENCES `bank_soal` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_ruang_ujian_kelas`     FOREIGN KEY (`kelas_id`)     REFERENCES `kelas`     (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_ruang_ujian_mapel`     FOREIGN KEY (`mapel_id`)     REFERENCES `mapel`     (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: hasil_ujian
-- ============================================================
CREATE TABLE IF NOT EXISTS `hasil_ujian` (
    `id`             INT UNSIGNED                          NOT NULL AUTO_INCREMENT,
    `siswa_id`       INT UNSIGNED                          NOT NULL,
    `ruang_ujian_id` INT UNSIGNED                          NOT NULL,
    `jawaban`        TEXT                                  NULL COMMENT 'JSON: {soal_id: jawaban}',
    `nilai`          DECIMAL(5,2)                          NULL,
    `jml_benar`      INT                                   NOT NULL DEFAULT 0,
    `jml_salah`      INT                                   NOT NULL DEFAULT 0,
    `jml_ragu`       INT                                   NOT NULL DEFAULT 0,
    `waktu_mulai`    DATETIME                              NULL,
    `waktu_selesai`  DATETIME                              NULL,
    `sisa_waktu`     INT                                   NULL,
    `status`         ENUM('mulai','selesai')               NOT NULL DEFAULT 'mulai',
    `login_count`    INT                                   NOT NULL DEFAULT 0,
    `keluar_count`   INT                                   NOT NULL DEFAULT 0,
    `created_at`     DATETIME                              NULL,
    `updated_at`     DATETIME                              NULL,
    PRIMARY KEY (`id`),
    KEY `fk_hasil_ujian_siswa`       (`siswa_id`),
    KEY `fk_hasil_ujian_ruang_ujian` (`ruang_ujian_id`),
    CONSTRAINT `fk_hasil_ujian_siswa`       FOREIGN KEY (`siswa_id`)       REFERENCES `siswa`       (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_hasil_ujian_ruang_ujian` FOREIGN KEY (`ruang_ujian_id`) REFERENCES `ruang_ujian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: pengumuman
-- ============================================================
CREATE TABLE IF NOT EXISTS `pengumuman` (
    `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `judul`         VARCHAR(200)     NOT NULL,
    `konten`        TEXT             NOT NULL,
    `target_kelas`  VARCHAR(255)     NULL COMMENT 'CSV of kelas ids or "all"',
    `created_at`    DATETIME         NULL,
    `updated_at`    DATETIME         NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `settings` (
    `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `key_name`   VARCHAR(100)     NOT NULL,
    `value`      TEXT             NULL,
    `created_at` DATETIME         NULL,
    `updated_at` DATETIME         NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_settings_key_name` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SEED DATA
-- ============================================================

-- Admins
-- password: Admin@MTsN2026
INSERT INTO `admins` (`id`, `nama`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@mtsn1.sch.id', '$2y$10$546/CfS8beFGOMhqdskkQOWJOH9liOV5NxS7Bp5EmSVzDHd14TkPi', NOW(), NOW());

-- Gurus
-- password: Guru@MTsN2026
INSERT INTO `gurus` (`id`, `nik`, `nama`, `email`, `password`, `foto`, `created_at`, `updated_at`) VALUES
(1, '1234567890123456', 'Budi Santoso, S.Pd', 'budi@mtsn1.sch.id', '$2y$10$Fa7mEcLAK6XJtbo5fmbg.Ojbi4rbsXjo4Z3OFimac5LTi929I8WYi', NULL, NOW(), NOW());

-- Kelas
INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1,  '7A', NOW(), NOW()),
(2,  '7B', NOW(), NOW()),
(3,  '7C', NOW(), NOW()),
(4,  '8A', NOW(), NOW()),
(5,  '8B', NOW(), NOW()),
(6,  '8C', NOW(), NOW()),
(7,  '9A', NOW(), NOW()),
(8,  '9B', NOW(), NOW()),
(9,  '9C', NOW(), NOW());

-- Mapel
INSERT INTO `mapel` (`id`, `nama_mapel`, `created_at`, `updated_at`) VALUES
(1, 'Matematika',        NOW(), NOW()),
(2, 'Bahasa Indonesia',  NOW(), NOW()),
(3, 'Bahasa Inggris',    NOW(), NOW()),
(4, 'IPA',               NOW(), NOW()),
(5, 'IPS',               NOW(), NOW()),
(6, 'PKN',               NOW(), NOW()),
(7, 'PAI',               NOW(), NOW()),
(8, 'Prakarya',          NOW(), NOW());

-- Siswa
-- password: Siswa@MTsN2026
INSERT INTO `siswa` (`id`, `nisn`, `nama`, `kelas_id`, `password`, `foto`, `created_at`, `updated_at`) VALUES
(1, '1234567890', 'Ahmad Dahlan', 1, '$2y$10$NNoOUhnZSNBdvo6GF1HrPeiXhy1GcX0ippDAhut41y3MJ6xgs9TCS', NULL, NOW(), NOW());

-- Bank Soal
INSERT INTO `bank_soal` (`id`, `guru_id`, `nama_bank`, `mapel_id`, `kelas_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bank Soal Matematika Kelas 7A', 1, 1, NOW(), NOW());

-- Soal (5 sample PG questions)
INSERT INTO `soal` (`id`, `bank_soal_id`, `pertanyaan`, `tipe_soal`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_e`, `jawaban_benar`, `kunci_menjodohkan`, `bobot`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hasil dari 5 + 3 × 2 adalah ...', 'pg', '10', '11', '16', '13', NULL, 'b', NULL, 1, 1, NOW(), NOW()),
(2, 1, 'Nilai dari 2³ adalah ...', 'pg', '5', '6', '7', '8', NULL, 'd', NULL, 1, 2, NOW(), NOW()),
(3, 1, 'Bentuk sederhana dari pecahan 6/8 adalah ...', 'pg', '1/2', '2/3', '3/4', '4/5', NULL, 'c', NULL, 1, 3, NOW(), NOW()),
(4, 1, 'Luas persegi dengan sisi 7 cm adalah ...', 'pg', '14 cm²', '28 cm²', '42 cm²', '49 cm²', NULL, 'd', NULL, 1, 4, NOW(), NOW()),
(5, 1, 'Bilangan prima antara 10 dan 20 adalah ...', 'pg', '11, 13, 17, 19', '11, 13, 15, 19', '11, 15, 17, 19', '13, 15, 17, 19', NULL, 'a', NULL, 1, 5, NOW(), NOW());

-- Ruang Ujian (1 sample active room)
INSERT INTO `ruang_ujian` (`id`, `guru_id`, `nama_ujian`, `bank_soal_id`, `kelas_id`, `mapel_id`, `tanggal_mulai`, `tanggal_selesai`, `durasi`, `token`, `status`, `acak_soal`, `acak_jawaban`, `max_login`, `batas_keluar`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ujian Harian Matematika - Kelas 7A', 1, 1, 1, NOW(), DATE_ADD(NOW(), INTERVAL 2 HOUR), 60, 'MTsN7A001', 'aktif', 0, 0, 1, 3, NOW(), NOW());

-- Settings
INSERT INTO `settings` (`id`, `key_name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'examBrowserOnly', '0', NOW(), NOW());
