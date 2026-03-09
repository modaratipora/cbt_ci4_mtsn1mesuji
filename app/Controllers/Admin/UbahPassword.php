<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class UbahPassword extends BaseController
{
    public function index()
    {
        return view('admin/ubah_password', ['pageTitle' => 'Ubah Password']);
    }

    public function update()
    {
        $model   = new AdminModel();
        $admin   = $model->find(session()->get('user_id'));
        $oldPw   = $this->request->getPost('password_lama');
        $newPw   = $this->request->getPost('password_baru');
        $confirm = $this->request->getPost('konfirmasi_password');

        if (!password_verify($oldPw, $admin['password'])) {
            return redirect()->to('/admin/ubah-password')->with('error', 'Password lama tidak sesuai.');
        }
        if ($newPw !== $confirm) {
            return redirect()->to('/admin/ubah-password')->with('error', 'Konfirmasi password tidak cocok.');
        }
        if (strlen($newPw) < 6) {
            return redirect()->to('/admin/ubah-password')->with('error', 'Password minimal 6 karakter.');
        }
        $model->update($admin['id'], ['password' => password_hash($newPw, PASSWORD_BCRYPT)]);
        return redirect()->to('/admin/ubah-password')->with('success', 'Password berhasil diubah.');
    }
}
