<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table      = 'siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nisn', 'nama', 'kelas_id', 'password', 'foto'];
    protected $useTimestamps = true;

    public function findByNISN($nisn)
    {
        return $this->where('nisn', $nisn)->first();
    }

    public function getSiswaWithKelas()
    {
        return $this->db->table('siswa s')
            ->select('s.*, k.nama_kelas')
            ->join('kelas k', 'k.id = s.kelas_id', 'left')
            ->get()->getResultArray();
    }
}
