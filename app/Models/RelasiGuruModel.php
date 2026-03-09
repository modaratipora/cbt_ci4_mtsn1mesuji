<?php

namespace App\Models;

use CodeIgniter\Model;

class RelasiGuruModel extends Model
{
    protected $table      = 'relasi_guru';
    protected $primaryKey = 'id';
    protected $allowedFields = ['guru_id', 'kelas_id', 'mapel_id'];
    protected $useTimestamps = true;

    public function getByGuru($guruId)
    {
        return $this->db->table('relasi_guru rg')
            ->select('rg.*, k.nama_kelas, m.nama_mapel, g.nama as nama_guru')
            ->join('kelas k', 'k.id = rg.kelas_id', 'left')
            ->join('mapel m', 'm.id = rg.mapel_id', 'left')
            ->join('gurus g', 'g.id = rg.guru_id', 'left')
            ->where('rg.guru_id', $guruId)
            ->get()->getResultArray();
    }

    public function getAll()
    {
        return $this->db->table('relasi_guru rg')
            ->select('rg.*, k.nama_kelas, m.nama_mapel, g.nama as nama_guru')
            ->join('kelas k', 'k.id = rg.kelas_id', 'left')
            ->join('mapel m', 'm.id = rg.mapel_id', 'left')
            ->join('gurus g', 'g.id = rg.guru_id', 'left')
            ->get()->getResultArray();
    }
}
