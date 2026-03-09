<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\RuangUjianModel;
use App\Models\HasilUjianModel;

class RuangUjian extends BaseController
{
    public function index()
    {
        $siswaId = session()->get('user_id');
        $kelasId = (int)session()->get('kelas_id');

        $ruangModel = new RuangUjianModel();
        $hasilModel = new HasilUjianModel();

        $ruangList = $ruangModel->getAktifForSiswa($kelasId);

        foreach ($ruangList as &$r) {
            $h = $hasilModel->getBySiswaRuang($siswaId, $r['id']);
            $r['hasil']         = $h;
            $r['sudah_selesai'] = $h && $h['status'] === 'selesai';
            $r['sedang_jalan']  = $h && $h['status'] === 'mulai';
        }

        return view('siswa/ruang_ujian', [
            'pageTitle'  => 'Ruang Ujian',
            'ruang_list' => $ruangList,
        ]);
    }
}
