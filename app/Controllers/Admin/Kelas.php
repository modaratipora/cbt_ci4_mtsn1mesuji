<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;

class Kelas extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new KelasModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Master Kelas',
            'kelas'     => $this->model->orderBy('nama_kelas', 'ASC')->findAll(),
        ];
        return view('admin/kelas', $data);
    }

    public function store()
    {
        $namaKelas = $this->request->getPost('nama_kelas');
        if (is_array($namaKelas)) {
            foreach ($namaKelas as $nama) {
                if (!empty(trim($nama))) {
                    $this->model->insert(['nama_kelas' => trim($nama)]);
                }
            }
        } else {
            if (!empty(trim($namaKelas))) {
                $this->model->insert(['nama_kelas' => trim($namaKelas)]);
            }
        }
        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->model->update($id, ['nama_kelas' => $this->request->getPost('nama_kelas')]);
        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/kelas')->with('success', 'Kelas berhasil dihapus.');
    }
}
