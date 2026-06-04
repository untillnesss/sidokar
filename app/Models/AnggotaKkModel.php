<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaKkModel extends Model
{
    protected $table = 'anggota_kk';
    protected $primaryKey = 'id_anggota';

    protected $allowedFields = [
        'id_pengajuan',
        'nama',
        'nik',
        'shdk',
        'field_diubah',
        'nilai_lama',
        'nilai_baru',
        'dasar_perubahan',
        'file_dokumen'
    ];
}