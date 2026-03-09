<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'password'];
    protected $useTimestamps = true;

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
