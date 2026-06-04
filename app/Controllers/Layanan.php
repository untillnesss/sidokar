<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Layanan extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        if ($role == 'admin') {
            return view('layanan_admin');
        } elseif ($role == 'desa') {
            return view('layanan_desa');
        }

        return redirect()->to('/login');
    }
}