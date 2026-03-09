<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SettingModel();
    }

    public function index()
    {
        $data = [
            'pageTitle'       => 'Settings',
            'examBrowserOnly' => $this->model->getSetting('examBrowserOnly', '0'),
            'namaSekolah'     => $this->model->getSetting('namaSekolah', 'MTsN 1 Mesuji'),
            'tahunAjaran'     => $this->model->getSetting('tahunAjaran', date('Y') . '/' . (date('Y') + 1)),
        ];
        return view('admin/settings', $data);
    }

    public function update()
    {
        $this->model->setSetting('examBrowserOnly', $this->request->getPost('examBrowserOnly') ? '1' : '0');
        $this->model->setSetting('namaSekolah', $this->request->getPost('namaSekolah'));
        $this->model->setSetting('tahunAjaran', $this->request->getPost('tahunAjaran'));
        return redirect()->to('/admin/settings')->with('success', 'Settings berhasil disimpan.');
    }
}
