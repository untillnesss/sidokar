<?php

namespace App\Controllers;

use App\Models\UserModel;

class Otp extends BaseController
{
    public function index()
    {
        return view('auth/verifikasi_otp');
    }

    public function verify()
    {
        $session = session();
        $model   = new UserModel();

        $otpInput = trim($this->request->getPost('otp'));
        $userId   = $session->get('temp_user_id');

        if (!$userId) {
            return redirect()->to('/login')
                ->with('error', 'Session OTP tidak ditemukan. Silakan login ulang.');
        }

        // Ambil user berdasarkan ID
        $user = $model->find($userId);

        if (!$user) {
            return redirect()->to('/login')
                ->with('error', 'User tidak ditemukan.');
        }

        // Validasi OTP
        if (
            $user['otp_code'] !== $otpInput ||
            strtotime($user['otp_expired']) < time()
        ) {
            return redirect()->back()
                ->with('error', 'OTP salah atau sudah expired!');
        }

        // Hapus OTP setelah berhasil
        $model->update($user['id'], [
            'otp_code'    => null,
            'otp_expired' => null
        ]);

        // Set session lengkap
        $session->set([
            'id'        => $user['id'],
            'nama'      => $user['nama_lengkap'] ?? '',
            'username'  => $user['username'],
            'role'      => $user['role'],
            'kode_desa' => $user['kode_desa'] ?? null,
            'is_master' => (int) ($user['is_master'] ?? 0),
            'logged_in' => true
        ]);

        // Hapus session sementara OTP
        $session->remove(['temp_user_id', 'otp_email']);

        return redirect()->to('/dashboard');
    }
}