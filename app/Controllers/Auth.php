<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\RuangUjianModel;
use App\Models\HasilUjianModel;

class Auth extends BaseController
{
    public function loginForm()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole();
        }
        return view('auth/login');
    }

    public function login()
    {
        $loginType = $this->request->getPost('login_type'); // admin, guru, siswa

        if ($loginType === 'admin') {
            return $this->loginAdmin();
        } elseif ($loginType === 'guru') {
            return $this->loginGuru();
        } elseif ($loginType === 'siswa') {
            return $this->loginSiswa();
        }

        return redirect()->to('/login')->with('error', 'Tipe login tidak valid.');
    }

    private function loginAdmin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new AdminModel();
        $admin = $model->findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            return redirect()->to('/login')->with('error', 'Email atau Password salah!')->with('tab', 'admin');
        }

        session()->set([
            'logged_in'  => true,
            'role'       => 'admin',
            'user_id'    => $admin['id'],
            'user_nama'  => $admin['nama'],
            'user_email' => $admin['email'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    private function loginGuru()
    {
        $nik      = $this->request->getPost('nik');
        $password = $this->request->getPost('password');

        $model = new GuruModel();
        $guru  = $model->findByNIK($nik);

        if (!$guru || !password_verify($password, $guru['password'])) {
            return redirect()->to('/login')->with('error', 'NIK atau Password salah!')->with('tab', 'guru');
        }

        session()->set([
            'logged_in' => true,
            'role'      => 'guru',
            'user_id'   => $guru['id'],
            'user_nama' => $guru['nama'],
            'user_nik'  => $guru['nik'],
        ]);

        return redirect()->to('/guru/dashboard');
    }

    private function loginSiswa()
    {
        $nisn     = $this->request->getPost('nisn');
        $password = $this->request->getPost('password');
        $token    = $this->request->getPost('token');
        $ruangId  = (int) $this->request->getPost('ruang_id'); // cast to int to prevent open-redirect

        $siswaModel = new SiswaModel();
        $siswa      = $siswaModel->findByNISN($nisn);

        if (!$siswa || !password_verify($password, $siswa['password'])) {
            return redirect()->to('/login')->with('error', 'NISN atau Password salah!')->with('tab', 'siswa');
        }

        // If token provided, verify and auto-start exam
        if ($token && $ruangId) {
            $ruangModel = new RuangUjianModel();
            $ruang      = $ruangModel->find($ruangId);

            if (!$ruang || $ruang['token'] !== strtoupper($token)) {
                return redirect()->to('/login')->with('error', 'Token tidak valid!')->with('tab', 'siswa');
            }

            if ($ruang['status'] !== 'aktif') {
                return redirect()->to('/login')->with('error', 'Ujian tidak aktif!')->with('tab', 'siswa');
            }
        }

        session()->set([
            'logged_in'  => true,
            'role'       => 'siswa',
            'user_id'    => $siswa['id'],
            'user_nama'  => $siswa['nama'],
            'user_nisn'  => $siswa['nisn'],
            'kelas_id'   => $siswa['kelas_id'],
        ]);

        if ($token && $ruangId) {
            return redirect()->to('/siswa/ujian/' . $ruangId);
        }

        return redirect()->to('/siswa/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }

    private function redirectByRole()
    {
        $role = session()->get('role');
        if ($role === 'admin') return redirect()->to('/admin/dashboard');
        if ($role === 'guru')  return redirect()->to('/guru/dashboard');
        if ($role === 'siswa') return redirect()->to('/siswa/dashboard');
        return redirect()->to('/login');
    }
}
