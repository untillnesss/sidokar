<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new UserModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            $username = $session->get('username');
            $user = $this->user->where('username', $username)->first();

            if ($user) {
                $session->set('user_id', $user['id']);
                $userId = $user['id'];
            }
        }

        $user = $this->user->find($userId);

        if (!$user) {
            $user = [
                'nama_lengkap' => $session->get('nama_lengkap'),
                'avatar'       => $session->get('avatar') ?? 'default.png'
            ];
        }

        return view('profile/index', ['user' => $user]);
    }

    public function update()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            $username = $session->get('username');
            $user = $this->user->where('username', $username)->first();

            if ($user) {
                $userId = $user['id'];
                $session->set('user_id', $userId);
            } else {
                return redirect()->to('/login');
            }
        }

        $data = [];

        // update nama
        $nama = $this->request->getPost('nama_lengkap');
        if (!empty($nama)) {
            $data['nama_lengkap'] = $nama;
            $session->set('nama_lengkap', $nama);
        }

        // =====================
        // CROPPER (BASE64)
        // =====================
        $avatarCrop = $this->request->getPost('avatar_crop');

        if (!empty($avatarCrop)) {

            if (!is_dir(FCPATH . 'uploads/avatar')) {
                mkdir(FCPATH . 'uploads/avatar', 0777, true);
            }

            $image_parts = explode(";base64,", $avatarCrop);

            if (count($image_parts) == 2) {

                $image_base64 = base64_decode($image_parts[1]);

                $fileName = uniqid() . '.jpg';
                $filePath = FCPATH . 'uploads/avatar/' . $fileName;

                file_put_contents($filePath, $image_base64);

                $data['avatar'] = $fileName;
                $session->set('avatar', $fileName);
            }
        }

        // =====================
        // SAVE
        // =====================
        if (!empty($data)) {
            $this->user->update($userId, $data);
        }

        return redirect()->to('/profile')->with('success', 'Profil berhasil diupdate');
    }

    // 🔥 HAPUS FOTO PROFIL
    public function deleteAvatar()
    {
        $session = session();
        $userId = $session->get('user_id');

        $user = $this->user->find($userId);

        if ($user && !empty($user['avatar']) && $user['avatar'] != 'default.png') {

            $path = FCPATH . 'uploads/avatar/' . $user['avatar'];

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->user->update($userId, ['avatar' => 'default.png']);
        $session->set('avatar', 'default.png');

        return redirect()->to('/profile')->with('success', 'Foto profil dihapus');
    }
}