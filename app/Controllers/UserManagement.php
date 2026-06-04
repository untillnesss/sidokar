<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
    public function index()
{
    if (!session()->get('logged_in') || session()->get('is_master') != 1) {
        return redirect()->to('/dashboard');
    }

    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder->select('users.*, desa.nama_desa');
    $builder->join('desa', 'desa.kode_desa = users.kode_desa', 'left');

    $role    = $this->request->getGet('role');
    $keyword = $this->request->getGet('keyword');

    if ($role) {
        $builder->where('users.role', $role);
    }

    if ($keyword) {
        $builder->groupStart()
                ->like('users.nama_lengkap', $keyword)
                ->orLike('desa.nama_desa', $keyword)
                ->groupEnd();
    }

    $users = $builder->get()->getResultArray();

    return view('master/user-management', [
        'users'   => $users,
        'role'    => $role,
        'keyword' => $keyword
    ]);
}
    public function update($id)
    {
        if (session()->get('is_master') != 1) {
            return redirect()->to('/dashboard');
        }

        $model = new UserModel();

        $role      = $this->request->getPost('role');
        $is_master = $this->request->getPost('is_master') ? 1 : 0;

        $model->update($id, [
            'role'      => $role,
            'is_master' => $is_master
        ]);

        return redirect()->to('/user-management')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session()->get('is_master') != 1) {
            return redirect()->to('/dashboard');
        }

        // Jangan boleh hapus diri sendiri
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $model = new UserModel();
        $model->delete($id);

        return redirect()->to('/user-management')
            ->with('success', 'User berhasil dihapus.');
    }
}