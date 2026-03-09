<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RuangUjianModel;
use App\Models\BankSoalModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GuruModel;
use App\Models\HasilUjianModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RuangUjian extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new RuangUjianModel();
    }

    private function generateToken(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $token;
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Ruang Ujian',
            'ruang'     => $this->model->getWithRelasi(),
            'mapels'    => (new MapelModel())->orderBy('nama_mapel', 'ASC')->findAll(),
            'kelas'     => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
            'gurus'     => (new GuruModel())->orderBy('nama', 'ASC')->findAll(),
            'bank_soal' => (new BankSoalModel())->orderBy('nama_bank', 'ASC')->findAll(),
        ];
        return view('admin/ruang_ujian', $data);
    }

    public function store()
    {
        $this->model->insert([
            'nama_ujian'      => $this->request->getPost('nama_ujian'),
            'bank_soal_id'    => $this->request->getPost('bank_soal_id'),
            'mapel_id'        => $this->request->getPost('mapel_id'),
            'kelas_id'        => $this->request->getPost('kelas_id'),
            'guru_id'         => $this->request->getPost('guru_id') ?: session()->get('user_id'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'durasi'          => $this->request->getPost('durasi'),
            'token'           => $this->generateToken(),
            'status'          => $this->request->getPost('status') ?: 'draft',
            'acak_soal'       => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_jawaban'    => $this->request->getPost('acak_jawaban') ? 1 : 0,
            'max_login'       => $this->request->getPost('max_login') ?: 1,
            'batas_keluar'    => $this->request->getPost('batas_keluar') ?: 3,
        ]);
        return redirect()->to('/admin/ruang-ujian')->with('success', 'Ruang ujian berhasil dibuat.');
    }

    public function update($id)
    {
        $this->model->update($id, [
            'nama_ujian'      => $this->request->getPost('nama_ujian'),
            'bank_soal_id'    => $this->request->getPost('bank_soal_id'),
            'mapel_id'        => $this->request->getPost('mapel_id'),
            'kelas_id'        => $this->request->getPost('kelas_id'),
            'guru_id'         => $this->request->getPost('guru_id'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'durasi'          => $this->request->getPost('durasi'),
            'status'          => $this->request->getPost('status'),
            'acak_soal'       => $this->request->getPost('acak_soal') ? 1 : 0,
            'acak_jawaban'    => $this->request->getPost('acak_jawaban') ? 1 : 0,
            'max_login'       => $this->request->getPost('max_login') ?: 1,
            'batas_keluar'    => $this->request->getPost('batas_keluar') ?: 3,
        ]);
        return redirect()->to('/admin/ruang-ujian')->with('success', 'Ruang ujian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/ruang-ujian')->with('success', 'Ruang ujian berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/ruang-ujian')->with('success', 'Ruang ujian berhasil dihapus.');
    }

    public function regenerateToken($id)
    {
        $this->model->update($id, ['token' => $this->generateToken()]);
        return redirect()->to('/admin/ruang-ujian')->with('success', 'Token berhasil diperbarui.');
    }

    public function monitoring($id)
    {
        $ruang     = $this->model->getWithRelasi();
        $ruangData = null;
        foreach ($ruang as $r) {
            if ($r['id'] == $id) {
                $ruangData = $r;
                break;
            }
        }
        if (!$ruangData) {
            return redirect()->to('/admin/ruang-ujian')->with('error', 'Ruang ujian tidak ditemukan.');
        }
        $data = [
            'pageTitle' => 'Monitoring: ' . $ruangData['nama_ujian'],
            'ruang'     => $ruangData,
            'hasil'     => (new HasilUjianModel())->getMonitoring($id),
            'kelas'     => (new KelasModel())->findAll(),
        ];
        return view('admin/ruang_ujian_monitoring', $data);
    }

    public function resetUjian($hasilId)
    {
        $hasil = (new HasilUjianModel())->find($hasilId);
        if ($hasil) {
            (new HasilUjianModel())->delete($hasilId);
            return redirect()->to("/admin/ruang-ujian/monitoring/{$hasil['ruang_ujian_id']}")->with('success', 'Ujian siswa berhasil direset.');
        }
        return redirect()->to('/admin/ruang-ujian')->with('error', 'Data tidak ditemukan.');
    }

    public function bulkResetUjian()
    {
        $ids     = $this->request->getPost('ids');
        $ruangId = $this->request->getPost('ruang_ujian_id');
        if ($ids) {
            (new HasilUjianModel())->whereIn('id', $ids)->delete();
        }
        return redirect()->to("/admin/ruang-ujian/monitoring/$ruangId")->with('success', 'Ujian siswa berhasil direset.');
    }

    public function exportExcel($id)
    {
        $hasil = (new HasilUjianModel())->getMonitoring($id);
        $ruang = $this->model->find($id);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Ujian');
        $sheet->fromArray(['No', 'NISN', 'Nama', 'Kelas', 'Status', 'Nilai', 'Benar', 'Salah', 'Waktu Mulai', 'Waktu Selesai'], null, 'A1');

        foreach ($hasil as $i => $row) {
            $sheet->fromArray([
                $i + 1,
                $row['nisn'],
                $row['nama_siswa'],
                $row['nama_kelas'] ?? '',
                $row['status'],
                $row['nilai'] ?? 0,
                $row['jml_benar'] ?? 0,
                $row['jml_salah'] ?? 0,
                $row['waktu_mulai'] ?? '',
                $row['waktu_selesai'] ?? '',
            ], null, 'A' . ($i + 2));
        }

        $namaFile = preg_replace('/[^A-Za-z0-9_\-]/', '_', $ruang['nama_ujian']);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="hasil_ujian_' . $namaFile . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPDF($id)
    {
        $hasil = (new HasilUjianModel())->getMonitoring($id);
        $ruang = $this->model->find($id);
        $data  = [
            'pageTitle' => 'Export PDF - ' . $ruang['nama_ujian'],
            'hasil'     => $hasil,
            'ruang'     => $ruang,
        ];
        return view('admin/ruang_ujian_pdf', $data);
    }
}
