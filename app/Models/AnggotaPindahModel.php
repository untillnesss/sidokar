<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaPindahModel extends Model
{
    protected $table = 'anggota_pindah';
    protected $primaryKey = 'id_anggota';

    protected $allowedFields = [
        'id_pengajuan',
        'nama_anggota',
        'nik_anggota'
    ];
}