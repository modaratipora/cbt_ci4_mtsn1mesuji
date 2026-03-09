<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RelasiGuruModel;
use App\Models\GuruModel;
use App\Models\KelasModel;
use App\Models\MapelModel;

class RelasiGuru extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new RelasiGuruModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Relasi Guru',
            'relasi'    => $this->model->getAll(),
            'gurus'     => (new GuruModel())->orderBy('nama', 'ASC')->findAll(),
            'kelas'     => (new KelasModel())->orderBy('nama_kelas', 'ASC')->findAll(),
            'mapels'    => (new MapelModel())->orderBy('nama_mapel', 'ASC')->findAll(),
        ];
        return view('admin/relasi_guru', $data);
    }

    public function store()
    {
        $guruId   = $this->request->getPost('guru_id');
        $kelasIds = $this->request->getPost('kelas_id');
        $mapelIds = $this->request->getPost('mapel_id');

        if (!is_array($kelasIds)) {
            $kelasIds = [$kelasIds];
        }
        if (!is_array($mapelIds)) {
            $mapelIds = [$mapelIds];
        }

        foreach ($kelasIds as $kelasId) {
            foreach ($mapelIds as $mapelId) {
                $existing = $this->model
                    ->where('guru_id', $guruId)
                    ->where('kelas_id', $kelasId)
                    ->where('mapel_id', $mapelId)
                    ->first();
                if (!$existing) {
                    $this->model->insert([
                        'guru_id'  => $guruId,
                        'kelas_id' => $kelasId,
                        'mapel_id' => $mapelId,
                    ]);
                }
            }
        }
        return redirect()->to('/admin/relasi-guru')->with('success', 'Relasi guru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/relasi-guru')->with('success', 'Relasi guru berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if ($ids) {
            $this->model->whereIn('id', $ids)->delete();
        }
        return redirect()->to('/admin/relasi-guru')->with('success', 'Relasi guru berhasil dihapus.');
    }
}
