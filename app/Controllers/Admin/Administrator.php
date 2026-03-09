<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Administrator extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Administrator',
            'admins'    => $this->model->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('admin/administrator', $data);
    }

    public function store()
    {
        $password = $this->request->getPost('password') ?: 'Admin@MTsN2026';
        $this->model->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);
        return redirect()->to('/admin/administrator')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update($id)
    {
        $data = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];
        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $this->model->update($id, $data);
        return redirect()->to('/admin/administrator')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/administrator')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $this->model->delete($id);
        return redirect()->to('/admin/administrator')->with('success', 'Admin berhasil dihapus.');
    }
}
