<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Siswa extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SiswaModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Data Siswa',
            'siswas'    => $this->model->getSiswaWithKelas(),
            'kelas'     => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
        ];
        return view('admin/siswa', $data);
    }

    public function store()
    {
        $password = $this->request->getPost('password') ?: 'Siswa@MTsN2026';
        $this->model->insert([
            'nisn'     => $this->request->getPost('nisn'),
            'nama'     => $this->request->getPost('nama'),
            'kelas_id' => $this->request->getPost('kelas_id'),
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);
        return redirect()->to('/admin/siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update($id)
    {
        $data = [
            'nisn'     => $this->request->getPost('nisn'),
            'nama'     => $this->request->getPost('nama'),
            'kelas_id' => $this->request->getPost('kelas_id'),
        ];
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $this->model->update($id, $data);
        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/siswa')->with('success', 'Siswa berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/siswa')->with('success', 'Siswa berhasil dihapus.');
    }

    public function bulkResetPassword()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $hash = password_hash('Siswa@MTsN2026', PASSWORD_BCRYPT);
            foreach ($ids as $id) {
                $this->model->update($id, ['password' => $hash]);
            }
        }
        return redirect()->to('/admin/siswa')->with('success', 'Password siswa berhasil direset.');
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');
        $sheet->fromArray(['NISN', 'Nama Lengkap', 'Kelas', 'Password'], null, 'A1');
        $sheet->fromArray(['1234567890', 'Contoh Siswa', 'VII A', 'Siswa@MTsN2026'], null, 'A2');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_siswa.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/siswa')->with('error', 'File tidak valid.');
        }

        $kelasModel  = new KelasModel();
        $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
        $spreadsheet = $reader->load($file->getTempName());
        $rows        = $spreadsheet->getActiveSheet()->toArray();

        $imported = 0;
        foreach ($rows as $i => $row) {
            if ($i === 0) {
                continue;
            }
            if (empty($row[0])) {
                continue;
            }
            $kelas    = $kelasModel->where('nama_kelas', $row[2])->first();
            $kelasId  = $kelas ? $kelas['id'] : null;
            $this->model->insert([
                'nisn'     => $row[0],
                'nama'     => $row[1],
                'kelas_id' => $kelasId,
                'password' => password_hash($row[3] ?? 'Siswa@MTsN2026', PASSWORD_BCRYPT),
            ]);
            $imported++;
        }
        return redirect()->to('/admin/siswa')->with('success', "$imported siswa berhasil diimport.");
    }
}
