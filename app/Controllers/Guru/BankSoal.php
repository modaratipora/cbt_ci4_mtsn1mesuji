<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\BankSoalModel;
use App\Models\MapelModel;
use App\Models\KelasModel;

class BankSoal extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new BankSoalModel();
    }

    public function index()
    {
        $guruId = session()->get('user_id');
        $data   = [
            'pageTitle' => 'Bank Soal',
            'bank_soal' => $this->model->getWithRelasi($guruId),
            'mapels'    => (new MapelModel())->orderBy('nama_mapel', 'ASC')->findAll(),
            'kelas'     => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
        ];
        return view('guru/bank_soal', $data);
    }

    public function store()
    {
        $guruId = session()->get('user_id');
        $this->model->insert([
            'nama_bank' => $this->request->getPost('nama_bank'),
            'mapel_id'  => $this->request->getPost('mapel_id'),
            'kelas_id'  => $this->request->getPost('kelas_id'),
            'guru_id'   => $guruId,
        ]);
        return redirect()->to('/guru/bank-soal')->with('success', 'Bank soal berhasil ditambahkan.');
    }

    public function update($id)
    {
        $guruId = session()->get('user_id');
        $bank   = $this->model->find($id);
        if (!$bank || $bank['guru_id'] != $guruId) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $this->model->update($id, [
            'nama_bank' => $this->request->getPost('nama_bank'),
            'mapel_id'  => $this->request->getPost('mapel_id'),
            'kelas_id'  => $this->request->getPost('kelas_id'),
        ]);
        return redirect()->to('/guru/bank-soal')->with('success', 'Bank soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guruId = session()->get('user_id');
        $bank   = $this->model->find($id);
        if (!$bank || $bank['guru_id'] != $guruId) {
            return redirect()->to('/guru/bank-soal')->with('error', 'Akses ditolak.');
        }
        $this->model->delete($id);
        return redirect()->to('/guru/bank-soal')->with('success', 'Bank soal berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $guruId = session()->get('user_id');
        $ids    = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->where('guru_id', $guruId)->delete();
        }
        return redirect()->to('/guru/bank-soal')->with('success', 'Bank soal berhasil dihapus.');
    }
}
