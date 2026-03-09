<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\RuangUjianModel;
use App\Models\HasilUjianModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\BankSoalModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RuangUjian extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new RuangUjianModel();
    }

    public function index()
    {
        $guruId = session()->get('user_id');
        $data = [
            'pageTitle'    => 'Ruang Ujian',
            'ruang_list'   => $this->model->getWithRelasi($guruId),
            'kelas_list'   => (new KelasModel())->orderBy('nama_kelas')->findAll(),
            'mapel_list'   => (new MapelModel())->orderBy('nama_mapel')->findAll(),
            'bank_list'    => (new BankSoalModel())->where('guru_id', $guruId)->findAll(),
        ];
        return view('guru/ruang_ujian', $data);
    }

    public function store()
    {
        $guruId = session()->get('user_id');
        $token  = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        $this->model->insert([
            'guru_id'        => $guruId,
            'nama_ujian'     => $this->request->getPost('nama_ujian'),
            'bank_soal_id'   => $this->request->getPost('bank_soal_id'),
            'kelas_id'       => $this->request->getPost('kelas_id'),
            'mapel_id'       => $this->request->getPost('mapel_id'),
            'tanggal_mulai'  => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'=> $this->request->getPost('tanggal_selesai'),
            'durasi'         => (int)$this->request->getPost('durasi'),
            'token'          => $token,
            'status'         => $this->request->getPost('status') ?? 'draft',
            'acak_soal'      => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_jawaban'   => $this->request->getPost('acak_jawaban') ? 1 : 0,
            'max_login'      => (int)($this->request->getPost('max_login') ?? 1),
            'batas_keluar'   => (int)($this->request->getPost('batas_keluar') ?? 3),
        ]);
        return redirect()->to('/guru/ruang-ujian')->with('success', 'Ruang ujian berhasil ditambahkan.');
    }

    public function update($id)
    {
        $guruId = session()->get('user_id');
        $ruang  = $this->model->find($id);
        if (!$ruang || $ruang['guru_id'] != $guruId) {
            return redirect()->to('/guru/ruang-ujian')->with('error', 'Akses ditolak.');
        }
        $this->model->update($id, [
            'nama_ujian'     => $this->request->getPost('nama_ujian'),
            'bank_soal_id'   => $this->request->getPost('bank_soal_id'),
            'kelas_id'       => $this->request->getPost('kelas_id'),
            'mapel_id'       => $this->request->getPost('mapel_id'),
            'tanggal_mulai'  => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'=> $this->request->getPost('tanggal_selesai'),
            'durasi'         => (int)$this->request->getPost('durasi'),
            'status'         => $this->request->getPost('status'),
            'acak_soal'      => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_jawaban'   => $this->request->getPost('acak_jawaban') ? 1 : 0,
            'max_login'      => (int)($this->request->getPost('max_login') ?? 1),
            'batas_keluar'   => (int)($this->request->getPost('batas_keluar') ?? 3),
        ]);
        return redirect()->to('/guru/ruang-ujian')->with('success', 'Ruang ujian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guruId = session()->get('user_id');
        $ruang  = $this->model->find($id);
        if (!$ruang || $ruang['guru_id'] != $guruId) {
            return redirect()->to('/guru/ruang-ujian')->with('error', 'Akses ditolak.');
        }
        $this->model->delete($id);
        return redirect()->to('/guru/ruang-ujian')->with('success', 'Ruang ujian berhasil dihapus.');
    }

    public function monitoring($id)
    {
        $guruId = session()->get('user_id');
        $ruang  = $this->model->find($id);
        if (!$ruang || $ruang['guru_id'] != $guruId) {
            return redirect()->to('/guru/ruang-ujian')->with('error', 'Akses ditolak.');
        }
        $hasilModel = new HasilUjianModel();
        $data = [
            'pageTitle' => 'Monitoring: ' . esc($ruang['nama_ujian']),
            'ruang'     => $ruang,
            'hasil'     => $hasilModel->getMonitoring($id),
        ];
        return view('guru/ruang_ujian_monitoring', $data);
    }

    public function resetUjian($hasilId)
    {
        $hasilModel = new HasilUjianModel();
        $hasil = $hasilModel->find($hasilId);
        if (!$hasil) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        $ruang = $this->model->find($hasil['ruang_ujian_id']);
        $hasilModel->update($hasilId, [
            'jawaban'      => json_encode([]),
            'nilai'        => 0,
            'jml_benar'    => 0,
            'jml_salah'    => 0,
            'waktu_selesai'=> null,
            'sisa_waktu'   => $ruang ? $ruang['durasi'] * 60 : 0,
            'status'       => 'mulai',
            'login_count'  => 0,
            'keluar_count' => 0,
        ]);
        return redirect()->back()->with('success', 'Ujian siswa berhasil direset.');
    }

    public function exportExcel($id)
    {
        $ruang = $this->model->find($id);
        $hasilModel = new HasilUjianModel();
        $hasil = $hasilModel->getMonitoring($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['No','NISN','Nama','Kelas','Waktu Mulai','Waktu Selesai','Jml Benar','Jml Salah','Nilai'], null, 'A1');
        foreach ($hasil as $i => $row) {
            $sheet->fromArray([
                $i + 1,
                $row['nisn'],
                $row['nama_siswa'],
                $row['nama_kelas'],
                $row['waktu_mulai'],
                $row['waktu_selesai'],
                $row['jml_benar'],
                $row['jml_salah'],
                $row['nilai'],
            ], null, 'A' . ($i + 2));
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="hasil_ujian_' . $id . '.xlsx"');
        header('Cache-Control: max-age=0');
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
