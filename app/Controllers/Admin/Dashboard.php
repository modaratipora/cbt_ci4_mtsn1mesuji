<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\BankSoalModel;
use App\Models\RuangUjianModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'pageTitle'        => 'Dashboard Admin',
            'total_siswa'      => (new SiswaModel())->countAll(),
            'total_guru'       => (new GuruModel())->countAll(),
            'total_kelas'      => (new KelasModel())->countAll(),
            'total_mapel'      => (new MapelModel())->countAll(),
            'total_bank_soal'  => (new BankSoalModel())->countAll(),
            'total_ruang_ujian'=> (new RuangUjianModel())->countAll(),
        ];
        return view('admin/dashboard', $data);
    }
}
