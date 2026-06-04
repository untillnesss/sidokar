<?php

namespace App\Models;

use CodeIgniter\Model;

class DesaModel extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id_desa';
    protected $allowedFields = ['kode_desa','nama_desa','kode_kecamatan'];
}