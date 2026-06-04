<?php

namespace App\Models;

use CodeIgniter\Model;

class AnakModel extends Model
{
    protected $table = 'anak';
    protected $primaryKey = 'id_anak';
    protected $allowedFields = [
        'nama_anak',
        'anak_ke',
        'jk_anak',
        'tempat_lahir',
        'tgl_lahir',
        'jam_lahir',
        'berat_bayi',
        'panjang_bayi',
        'id_permohonan'
    ];
}