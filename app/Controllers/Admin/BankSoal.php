<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BankSoalModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\GuruModel;

class BankSoal extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new BankSoalModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Bank Soal',
            'bank_soal' => $this->model->getWithRelasi(),
            'mapels'    => (new MapelModel())->orderBy('nama_mapel', 'ASC')->findAll(),
            'kelas'     => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
            'gurus'     => (new GuruModel())->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('admin/bank_soal', $data);
    }

    public function store()
    {
        $this->model->insert([
            'nama_bank' => $this->request->getPost('nama_bank'),
            'mapel_id'  => $this->request->getPost('mapel_id'),
            'kelas_id'  => $this->request->getPost('kelas_id'),
            'guru_id'   => $this->request->getPost('guru_id') ?: session()->get('user_id'),
        ]);
        return redirect()->to('/admin/bank-soal')->with('success', 'Bank soal berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->model->update($id, [
            'nama_bank' => $this->request->getPost('nama_bank'),
            'mapel_id'  => $this->request->getPost('mapel_id'),
            'kelas_id'  => $this->request->getPost('kelas_id'),
            'guru_id'   => $this->request->getPost('guru_id'),
        ]);
        return redirect()->to('/admin/bank-soal')->with('success', 'Bank soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/bank-soal')->with('success', 'Bank soal berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/bank-soal')->with('success', 'Bank soal berhasil dihapus.');
    }
}
