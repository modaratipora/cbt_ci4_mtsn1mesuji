<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table      = 'pengumuman';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'konten', 'target_kelas'];
    protected $useTimestamps = true;

    public function getForKelas($kelasId, $namaKelas)
    {
        $kelasId   = (int) $kelasId;
        $namaKelas = $this->db->escapeString($namaKelas);
        return $this->db->table('pengumuman')
            ->where("(target_kelas = 'all' OR FIND_IN_SET({$kelasId}, target_kelas) OR FIND_IN_SET('{$namaKelas}', target_kelas))")
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }
}
