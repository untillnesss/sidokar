<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangtuaModel extends Model
{
    protected $table = 'orangtua';
    protected $primaryKey = 'id_orangtua';
    protected $allowedFields = [
        'nama_ayah',
        'nik_ayah',
        'nama_ibu',
        'nik_ibu',
        'id_permohonan'
    ];
}