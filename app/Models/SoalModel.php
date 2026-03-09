<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table      = 'soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['bank_soal_id', 'pertanyaan', 'tipe_soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'jawaban_benar', 'kunci_menjodohkan', 'bobot', 'urutan'];
    protected $useTimestamps = true;

    public function getByBankSoal($bankSoalId)
    {
        return $this->where('bank_soal_id', $bankSoalId)->orderBy('urutan', 'ASC')->findAll();
    }
}
