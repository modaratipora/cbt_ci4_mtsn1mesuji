<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\RuangUjianModel;
use App\Models\SoalModel;
use App\Models\HasilUjianModel;

class Ujian extends BaseController
{
    public function verifyToken()
    {
        $ruangId = (int)$this->request->getPost('ruang_id');
        $token   = strtoupper(trim($this->request->getPost('token')));

        $ruang = (new RuangUjianModel())->find($ruangId);

        if (!$ruang || $ruang['token'] !== $token) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token tidak valid!']);
        }
        if ($ruang['status'] !== 'aktif') {
            return $this->response->setJSON(['success' => false, 'message' => 'Ujian tidak aktif!']);
        }

        return $this->response->setJSON(['success' => true, 'redirect' => '/siswa/ujian/' . $ruangId]);
    }

    public function start($ruangId)
    {
        $siswaId = session()->get('user_id');
        $kelasId = (int)session()->get('kelas_id');

        $ruangModel = new RuangUjianModel();
        $ruang      = $ruangModel->find($ruangId);

        if (!$ruang || (int)$ruang['kelas_id'] !== $kelasId || $ruang['status'] !== 'aktif') {
            return redirect()->to('/siswa/ruang-ujian')->with('error', 'Ujian tidak tersedia.');
        }

        $hasilModel = new HasilUjianModel();
        $hasil      = $hasilModel->getBySiswaRuang($siswaId, $ruangId);

        if ($hasil && $hasil['status'] === 'selesai') {
            return redirect()->to('/siswa/ruang-ujian')->with('info', 'Anda sudah menyelesaikan ujian ini.');
        }

        if ($hasil && (int)$ruang['max_login'] > 0 && $hasil['login_count'] >= (int)$ruang['max_login']) {
            return redirect()->to('/siswa/ruang-ujian')->with('error', 'Batas login ujian telah tercapai.');
        }

        // Load soal
        $soalModel = new SoalModel();
        $soalList  = $soalModel->getByBankSoal($ruang['bank_soal_id']);

        if ($ruang['acak_soal']) {
            shuffle($soalList);
        }

        // Create or resume hasil_ujian
        if (!$hasil) {
            $newId = $hasilModel->insert([
                'siswa_id'       => $siswaId,
                'ruang_ujian_id' => $ruangId,
                'jawaban'        => json_encode([]),
                'waktu_mulai'    => date('Y-m-d H:i:s'),
                'sisa_waktu'     => (int)$ruang['durasi'] * 60,
                'status'         => 'mulai',
                'login_count'    => 1,
                'keluar_count'   => 0,
            ]);
            $hasil = $hasilModel->find($newId);
        } else {
            $hasilModel->update($hasil['id'], ['login_count' => (int)$hasil['login_count'] + 1]);
            $hasil['login_count']++;
        }

        $jawaban = json_decode($hasil['jawaban'] ?? '{}', true) ?: [];

        return view('siswa/ujian', [
            'pageTitle'  => esc($ruang['nama_ujian']),
            'ruang'      => $ruang,
            'soal_list'  => $soalList,
            'hasil'      => $hasil,
            'jawaban'    => $jawaban,
            'sisa_waktu' => (int)$hasil['sisa_waktu'],
        ]);
    }

    public function saveAnswer()
    {
        $siswaId = session()->get('user_id');
        $input   = $this->request->getJSON(true) ?: $this->request->getPost();

        $ruangId   = (int)($input['ruang_id']   ?? 0);
        $soalId    = (int)($input['soal_id']    ?? 0);
        $jawaban   = $input['jawaban']           ?? null;
        $ragu      = !empty($input['ragu']);
        $sisaWaktu = (int)($input['sisa_waktu'] ?? 0);

        $hasilModel = new HasilUjianModel();
        $hasil      = $hasilModel->getBySiswaRuang($siswaId, $ruangId);

        if (!$hasil || $hasil['status'] === 'selesai') {
            return $this->response->setJSON(['success' => false, 'message' => 'Ujian tidak valid.']);
        }

        $jawabanArr           = json_decode($hasil['jawaban'] ?? '{}', true) ?: [];
        $jawabanArr[$soalId]  = ['jawaban' => $jawaban, 'ragu' => $ragu];

        $hasilModel->update($hasil['id'], [
            'jawaban'    => json_encode($jawabanArr),
            'sisa_waktu' => $sisaWaktu,
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function submit()
    {
        $siswaId = session()->get('user_id');
        $input   = $this->request->getJSON(true) ?: $this->request->getPost();
        $ruangId = (int)($input['ruang_id'] ?? 0);

        $hasilModel = new HasilUjianModel();
        $ruangModel = new RuangUjianModel();
        $soalModel  = new SoalModel();

        $hasil = $hasilModel->getBySiswaRuang($siswaId, $ruangId);
        $ruang = $ruangModel->find($ruangId);

        if (!$hasil || $hasil['status'] === 'selesai') {
            return $this->response->setJSON(['success' => false, 'message' => 'Ujian tidak valid.']);
        }

        $soalList   = $soalModel->getByBankSoal($ruang['bank_soal_id']);
        $jawabanArr = json_decode($hasil['jawaban'] ?? '{}', true) ?: [];

        $jmlBenar  = 0;
        $jmlSalah  = 0;
        $jmlRagu   = 0;
        $totalBobot = 0;
        $bobotBenar = 0;

        foreach ($soalList as $soal) {
            $totalBobot += (int)$soal['bobot'];
            $js = $jawabanArr[$soal['id']] ?? null;
            if ($js && !empty($js['ragu'])) {
                $jmlRagu++;
            }
            if ($soal['tipe_soal'] === 'essay') {
                continue;
            }
            if ($js && strtolower(trim($js['jawaban'])) === strtolower(trim($soal['jawaban_benar']))) {
                $jmlBenar++;
                $bobotBenar += (int)$soal['bobot'];
            } else {
                $jmlSalah++;
            }
        }

        $nilai = $totalBobot > 0 ? round(($bobotBenar / $totalBobot) * 100, 2) : 0;

        $hasilModel->update($hasil['id'], [
            'nilai'         => $nilai,
            'jml_benar'     => $jmlBenar,
            'jml_salah'     => $jmlSalah,
            'jml_ragu'      => $jmlRagu,
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'status'        => 'selesai',
        ]);

        return $this->response->setJSON([
            'success'   => true,
            'nilai'     => $nilai,
            'jml_benar' => $jmlBenar,
            'redirect'  => '/siswa/ruang-ujian',
        ]);
    }
}
