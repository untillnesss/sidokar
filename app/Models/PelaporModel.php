<?php

namespace App\Models;

use CodeIgniter\Model;

class PelaporModel extends Model
{
    protected $table = 'pelapor';
    protected $primaryKey = 'id_pelapor';
    protected $allowedFields = [
        'nama_pelapor',
        'nik_pelapor',
        'alamat_pelapor',
        'id_permohonan'
    ];
}