<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table      = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key_name', 'value'];
    protected $useTimestamps = true;

    public function getSetting($key, $default = null)
    {
        $row = $this->where('key_name', $key)->first();
        return $row ? $row['value'] : $default;
    }

    public function setSetting($key, $value)
    {
        $existing = $this->where('key_name', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        }
        return $this->insert(['key_name' => $key, 'value' => $value]);
    }
}
