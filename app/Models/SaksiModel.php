<?php

namespace App\Models;

use CodeIgniter\Model;

class SaksiModel extends Model
{
    protected $table = 'saksi';
    protected $primaryKey = 'id_saksi';
    protected $allowedFields = [
        'nama_saksi',
        'nik_saksi',
        'id_permohonan'
    ];
}