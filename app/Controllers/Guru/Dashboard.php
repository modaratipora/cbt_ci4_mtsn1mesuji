<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\BankSoalModel;
use App\Models\RuangUjianModel;
use App\Models\RelasiGuruModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $guruId      = session()->get('user_id');
        $bankSoalModel = new BankSoalModel();
        $ruangModel  = new RuangUjianModel();
        $relasiModel = new RelasiGuruModel();

        $relasi    = $relasiModel->getByGuru($guruId);
        $kelasIds  = array_unique(array_column($relasi, 'kelas_id'));
        $mapelIds  = array_unique(array_column($relasi, 'mapel_id'));

        $data = [
            'pageTitle'         => 'Dashboard Guru',
            'total_bank_soal'   => $bankSoalModel->where('guru_id', $guruId)->countAllResults(),
            'total_ruang_ujian' => $ruangModel->where('guru_id', $guruId)->countAllResults(),
            'total_kelas'       => count($kelasIds),
            'total_mapel'       => count($mapelIds),
        ];
        return view('guru/dashboard', $data);
    }
}
