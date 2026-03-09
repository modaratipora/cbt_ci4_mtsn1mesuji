<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class Mapel extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new MapelModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Master Mapel',
            'mapels'    => $this->model->orderBy('nama_mapel', 'ASC')->findAll(),
        ];
        return view('admin/mapel', $data);
    }

    public function store()
    {
        $namaMapel = $this->request->getPost('nama_mapel');
        if (is_array($namaMapel)) {
            foreach ($namaMapel as $nama) {
                if (!empty(trim($nama))) {
                    $this->model->insert(['nama_mapel' => trim($nama)]);
                }
            }
        } else {
            if (!empty(trim($namaMapel))) {
                $this->model->insert(['nama_mapel' => trim($namaMapel)]);
            }
        }
        return redirect()->to('/admin/mapel')->with('success', 'Mapel berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->model->update($id, ['nama_mapel' => $this->request->getPost('nama_mapel')]);
        return redirect()->to('/admin/mapel')->with('success', 'Mapel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/mapel')->with('success', 'Mapel berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/mapel')->with('success', 'Mapel berhasil dihapus.');
    }
}
