<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\BankSoalModel;

class Soal extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SoalModel();
    }

    private function checkOwnership($bankSoalId): ?array
    {
        $bankSoal = (new BankSoalModel())->find($bankSoalId);
        if (!$bankSoal || $bankSoal['guru_id'] != session()->get('user_id')) {
            return null;
        }
        return $bankSoal;
    }

    public function index($bankSoalId)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Bank soal tidak ditemukan.');
        }
        $data = [
            'pageTitle' => 'Kelola Soal: ' . $bankSoal['nama_bank'],
            'bankSoal'  => $bankSoal,
            'soals'     => $this->model->getByBankSoal($bankSoalId),
        ];
        return view('guru/soal', $data);
    }

    public function store($bankSoalId)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $this->model->insert([
            'bank_soal_id'      => $bankSoalId,
            'pertanyaan'        => $this->request->getPost('pertanyaan'),
            'tipe_soal'         => $this->request->getPost('tipe_soal'),
            'pilihan_a'         => $this->request->getPost('pilihan_a'),
            'pilihan_b'         => $this->request->getPost('pilihan_b'),
            'pilihan_c'         => $this->request->getPost('pilihan_c'),
            'pilihan_d'         => $this->request->getPost('pilihan_d'),
            'pilihan_e'         => $this->request->getPost('pilihan_e'),
            'jawaban_benar'     => $this->request->getPost('jawaban_benar'),
            'kunci_menjodohkan' => $this->request->getPost('kunci_menjodohkan'),
            'bobot'             => $this->request->getPost('bobot') ?: 1,
            'urutan'            => $this->request->getPost('urutan') ?: $this->model->where('bank_soal_id', $bankSoalId)->countAllResults() + 1,
        ]);
        return redirect()->to("/guru/soal/$bankSoalId")->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update($bankSoalId, $id)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $this->model->update($id, [
            'pertanyaan'        => $this->request->getPost('pertanyaan'),
            'tipe_soal'         => $this->request->getPost('tipe_soal'),
            'pilihan_a'         => $this->request->getPost('pilihan_a'),
            'pilihan_b'         => $this->request->getPost('pilihan_b'),
            'pilihan_c'         => $this->request->getPost('pilihan_c'),
            'pilihan_d'         => $this->request->getPost('pilihan_d'),
            'pilihan_e'         => $this->request->getPost('pilihan_e'),
            'jawaban_benar'     => $this->request->getPost('jawaban_benar'),
            'kunci_menjodohkan' => $this->request->getPost('kunci_menjodohkan'),
            'bobot'             => $this->request->getPost('bobot') ?: 1,
        ]);
        return redirect()->to("/guru/soal/$bankSoalId")->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy($bankSoalId, $id)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $this->model->delete($id);
        return redirect()->to("/guru/soal/$bankSoalId")->with('success', 'Soal berhasil dihapus.');
    }

    public function bulkDelete($bankSoalId)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to("/guru/soal/$bankSoalId")->with('success', 'Soal berhasil dihapus.');
    }

    public function importExcel($bankSoalId)
    {
        $bankSoal = $this->checkOwnership($bankSoalId);
        if (!$bankSoal) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->to("/guru/soal/$bankSoalId")->with('error', 'File tidak valid.');
        }
        $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
        $spreadsheet = $reader->load($file->getTempName());
        $rows        = $spreadsheet->getActiveSheet()->toArray();
        $urutan      = $this->model->where('bank_soal_id', $bankSoalId)->countAllResults() + 1;
        $imported    = 0;
        foreach ($rows as $i => $row) {
            if ($i === 0 || empty($row[0])) continue;
            $this->model->insert([
                'bank_soal_id'  => $bankSoalId,
                'pertanyaan'    => $row[0],
                'tipe_soal'     => $row[1] ?? 'PG',
                'pilihan_a'     => $row[2] ?? null,
                'pilihan_b'     => $row[3] ?? null,
                'pilihan_c'     => $row[4] ?? null,
                'pilihan_d'     => $row[5] ?? null,
                'pilihan_e'     => $row[6] ?? null,
                'jawaban_benar' => $row[7] ?? 'A',
                'bobot'         => $row[8] ?? 1,
                'urutan'        => $urutan++,
            ]);
            $imported++;
        }
        return redirect()->to("/guru/soal/$bankSoalId")->with('success', "$imported soal berhasil diimport.");
    }

    public function importTemplate($bankSoalId)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal');
        $sheet->fromArray(['Pertanyaan', 'Tipe (PG/Essay/BS/Menjodohkan)', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Pilihan E', 'Jawaban Benar', 'Bobot'], null, 'A1');
        $sheet->fromArray(['Contoh pertanyaan?', 'PG', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', '', 'A', '1'], null, 'A2');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_soal.xlsx"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
