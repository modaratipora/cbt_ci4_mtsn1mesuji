<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table      = 'gurus';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nik', 'nama', 'email', 'password', 'foto'];
    protected $useTimestamps = true;

    public function findByNIK($nik)
    {
        return $this->where('nik', $nik)->first();
    }
}
