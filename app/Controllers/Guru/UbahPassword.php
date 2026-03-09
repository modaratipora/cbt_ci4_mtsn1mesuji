<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\GuruModel;

class UbahPassword extends BaseController
{
    public function index()
    {
        return view('guru/ubah_password', ['pageTitle' => 'Ubah Password']);
    }

    public function update()
    {
        $model = new GuruModel();
        $guru  = $model->find(session()->get('user_id'));

        $oldPw  = $this->request->getPost('password_lama');
        $newPw  = $this->request->getPost('password_baru');
        $confPw = $this->request->getPost('password_konfirmasi');

        if (!password_verify($oldPw, $guru['password'])) {
            return redirect()->to('/guru/ubah-password')->with('error', 'Password lama tidak sesuai!');
        }
        if ($newPw !== $confPw) {
            return redirect()->to('/guru/ubah-password')->with('error', 'Konfirmasi password tidak sesuai!');
        }

        $model->update($guru['id'], ['password' => password_hash($newPw, PASSWORD_BCRYPT)]);
        return redirect()->to('/guru/ubah-password')->with('success', 'Password berhasil diubah.');
    }
}
