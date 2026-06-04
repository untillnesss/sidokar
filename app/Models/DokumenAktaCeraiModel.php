<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenAktaCeraiModel extends Model
{
    protected $table = 'dokumen_akta_cerai';
    protected $primaryKey = 'id_dokumen';

    protected $allowedFields = [
        'id_permohonan',
        'jenis_dokumen',
        'file_path'
    ];
}