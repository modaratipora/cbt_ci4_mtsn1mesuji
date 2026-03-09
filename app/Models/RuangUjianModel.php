<?php

namespace App\Models;

use CodeIgniter\Model;

class RuangUjianModel extends Model
{
    protected $table      = 'ruang_ujian';
    protected $primaryKey = 'id';
    protected $allowedFields = ['guru_id', 'nama_ujian', 'bank_soal_id', 'kelas_id', 'mapel_id', 'tanggal_mulai', 'tanggal_selesai', 'durasi', 'token', 'status', 'acak_soal', 'acak_jawaban', 'max_login', 'batas_keluar'];
    protected $useTimestamps = true;

    public function getWithRelasi($guruId = null)
    {
        $builder = $this->db->table('ruang_ujian ru')
            ->select('ru.*, m.nama_mapel, k.nama_kelas, g.nama as nama_guru, bs.nama_bank')
            ->join('mapel m', 'm.id = ru.mapel_id', 'left')
            ->join('kelas k', 'k.id = ru.kelas_id', 'left')
            ->join('gurus g', 'g.id = ru.guru_id', 'left')
            ->join('bank_soal bs', 'bs.id = ru.bank_soal_id', 'left');

        if ($guruId) {
            $builder->where('ru.guru_id', $guruId);
        }

        return $builder->get()->getResultArray();
    }

    public function getAktifForSiswa($kelasId)
    {
        return $this->db->table('ruang_ujian ru')
            ->select('ru.*, m.nama_mapel, g.nama as nama_guru')
            ->join('mapel m', 'm.id = ru.mapel_id', 'left')
            ->join('gurus g', 'g.id = ru.guru_id', 'left')
            ->where('ru.kelas_id', $kelasId)
            ->where('ru.status', 'aktif')
            ->where('ru.tanggal_mulai <=', date('Y-m-d H:i:s'))
            ->where('ru.tanggal_selesai >=', date('Y-m-d H:i:s'))
            ->get()->getResultArray();
    }
}
