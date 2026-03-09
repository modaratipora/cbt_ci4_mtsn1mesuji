<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Guru extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new GuruModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Data Guru',
            'gurus'     => $this->model->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('admin/guru', $data);
    }

    public function store()
    {
        $password = $this->request->getPost('password') ?: 'Guru@MTsN2026';
        $this->model->insert([
            'nik'      => $this->request->getPost('nik'),
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);
        return redirect()->to('/admin/guru')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function update($id)
    {
        $data = [
            'nik'   => $this->request->getPost('nik'),
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $this->model->update($id, $data);
        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/guru')->with('success', 'Guru berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/guru')->with('success', 'Guru berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $this->model->update($id, ['password' => password_hash('Guru@MTsN2026', PASSWORD_BCRYPT)]);
        return redirect()->to('/admin/guru')->with('success', 'Password guru direset ke default.');
    }

    public function bulkResetPassword()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $hash = password_hash('Guru@MTsN2026', PASSWORD_BCRYPT);
            foreach ($ids as $id) {
                $this->model->update($id, ['password' => $hash]);
            }
        }
        return redirect()->to('/admin/guru')->with('success', 'Password guru berhasil direset.');
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Guru');
        $sheet->fromArray(['NIK', 'Nama Lengkap', 'Email', 'Password'], null, 'A1');
        $sheet->fromArray(['1234567890123456', 'Contoh Guru, S.Pd', 'guru@mtsn1.sch.id', 'Guru@MTsN2026'], null, 'A2');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_guru.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/guru')->with('error', 'File tidak valid.');
        }

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
            $this->model->insert([
                'nik'      => $row[0],
                'nama'     => $row[1],
                'email'    => $row[2] ?? '',
                'password' => password_hash($row[3] ?? 'Guru@MTsN2026', PASSWORD_BCRYPT),
            ]);
            $imported++;
        }
        return redirect()->to('/admin/guru')->with('success', "$imported guru berhasil diimport.");
    }
}
