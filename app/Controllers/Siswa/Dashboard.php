<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\PengumumanModel;
use App\Models\RuangUjianModel;
use App\Models\HasilUjianModel;
use App\Models\KelasModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $siswaId = session()->get('user_id');
        $kelasId = (int)session()->get('kelas_id');

        $kelas      = (new KelasModel())->find($kelasId);
        $namaKelas  = $kelas ? $kelas['nama_kelas'] : '';

        $pengumumanModel = new PengumumanModel();
        $ruangModel      = new RuangUjianModel();
        $hasilModel      = new HasilUjianModel();

        $pengumuman  = $pengumumanModel->getForKelas($kelasId, $namaKelas);
        $ruangAktif  = $ruangModel->getAktifForSiswa($kelasId);
        $hasilUjian  = $hasilModel->where('siswa_id', $siswaId)->findAll();

        foreach ($ruangAktif as &$r) {
            $h = $hasilModel->getBySiswaRuang($siswaId, $r['id']);
            $r['hasil']         = $h;
            $r['sudah_selesai'] = $h && $h['status'] === 'selesai';
        }

        return view('siswa/dashboard', [
            'pageTitle'   => 'Dashboard',
            'pengumuman'  => $pengumuman,
            'ruang_aktif' => $ruangAktif,
            'hasil_ujian' => $hasilUjian,
            'nama_kelas'  => $namaKelas,
        ]);
    }
}
