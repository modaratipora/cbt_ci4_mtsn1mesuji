<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;

class UbahPassword extends BaseController
{
    public function index()
    {
        return view('siswa/ubah_password', ['pageTitle' => 'Ubah Password']);
    }

    public function update()
    {
        $model  = new SiswaModel();
        $siswa  = $model->find(session()->get('user_id'));

        $oldPw  = $this->request->getPost('password_lama');
        $newPw  = $this->request->getPost('password_baru');
        $confPw = $this->request->getPost('password_konfirmasi');

        if (!password_verify($oldPw, $siswa['password'])) {
            return redirect()->to('/siswa/ubah-password')->with('error', 'Password lama tidak sesuai!');
        }
        if ($newPw !== $confPw) {
            return redirect()->to('/siswa/ubah-password')->with('error', 'Konfirmasi password tidak sesuai!');
        }

        $model->update($siswa['id'], ['password' => password_hash($newPw, PASSWORD_BCRYPT)]);
        return redirect()->to('/siswa/ubah-password')->with('success', 'Password berhasil diubah.');
    }
}
