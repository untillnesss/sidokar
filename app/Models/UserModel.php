<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array'; // WAJIB TAMBAH INI

    protected $allowedFields = [
        'nama_lengkap',
        'username',
        'password',
        'email',
        'kode_desa',
        'role',
        'is_master',
        'avatar',
        'otp_code',
        'otp_expired',
        'reset_token',
        'token_expired',
        'created_at'
    ];
}