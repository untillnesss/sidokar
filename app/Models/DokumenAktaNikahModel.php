<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenAktaNikahModel extends Model
{
    protected $table = 'dokumen_akta_nikah';
    protected $primaryKey = 'id_dokumen';

    protected $allowedFields = [
        'id_permohonan',
        'jenis_dokumen',
        'file'
    ];
}