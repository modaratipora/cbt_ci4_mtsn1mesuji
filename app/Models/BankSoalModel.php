<?php

namespace App\Models;

use CodeIgniter\Model;

class BankSoalModel extends Model
{
    protected $table      = 'bank_soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['guru_id', 'nama_bank', 'mapel_id', 'kelas_id'];
    protected $useTimestamps = true;

    public function getWithRelasi($guruId = null)
    {
        $builder = $this->db->table('bank_soal bs')
            ->select('bs.*, m.nama_mapel, k.nama_kelas, g.nama as nama_guru, COUNT(s.id) as jml_soal')
            ->join('mapel m', 'm.id = bs.mapel_id', 'left')
            ->join('kelas k', 'k.id = bs.kelas_id', 'left')
            ->join('gurus g', 'g.id = bs.guru_id', 'left')
            ->join('soal s', 's.bank_soal_id = bs.id', 'left')
            ->groupBy('bs.id');

        if ($guruId) {
            $builder->where('bs.guru_id', $guruId);
        }

        return $builder->get()->getResultArray();
    }
}
