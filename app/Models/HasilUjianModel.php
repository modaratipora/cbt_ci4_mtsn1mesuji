<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table      = 'hasil_ujian';
    protected $primaryKey = 'id';
    protected $allowedFields = ['siswa_id', 'ruang_ujian_id', 'jawaban', 'nilai', 'jml_benar', 'jml_salah', 'jml_ragu', 'waktu_mulai', 'waktu_selesai', 'sisa_waktu', 'status', 'login_count', 'keluar_count'];
    protected $useTimestamps = true;

    public function getBySiswaRuang($siswaId, $ruangId)
    {
        return $this->where('siswa_id', $siswaId)->where('ruang_ujian_id', $ruangId)->first();
    }

    public function getMonitoring($ruangId)
    {
        return $this->db->table('hasil_ujian hu')
            ->select('hu.*, s.nisn, s.nama as nama_siswa, k.nama_kelas')
            ->join('siswa s', 's.id = hu.siswa_id', 'left')
            ->join('kelas k', 'k.id = s.kelas_id', 'left')
            ->where('hu.ruang_ujian_id', $ruangId)
            ->get()->getResultArray();
    }
}
