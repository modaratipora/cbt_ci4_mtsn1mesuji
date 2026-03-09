<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengumumanModel;
use App\Models\KelasModel;

class Pengumuman extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PengumumanModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'   => 'Pengumuman',
            'pengumumans' => $this->model->orderBy('created_at', 'DESC')->findAll(),
            'kelas'       => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
        ];
        return view('admin/pengumuman', $data);
    }

    public function store()
    {
        $targetKelas = $this->request->getPost('target_kelas');
        if (is_array($targetKelas)) {
            $targetKelas = json_encode($targetKelas);
        }
        $this->model->insert([
            'judul'        => $this->request->getPost('judul'),
            'konten'       => $this->request->getPost('konten'),
            'target_kelas' => $targetKelas,
        ]);
        return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update($id)
    {
        $targetKelas = $this->request->getPost('target_kelas');
        if (is_array($targetKelas)) {
            $targetKelas = json_encode($targetKelas);
        }
        $this->model->update($id, [
            'judul'        => $this->request->getPost('judul'),
            'konten'       => $this->request->getPost('konten'),
            'target_kelas' => $targetKelas,
        ]);
        return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
