<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenAktaKematianModel extends Model
{
    protected $table = 'dokumen_akta_kematian';
    protected $primaryKey = 'id_dokumen';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'id_permohonan',
        'jenis_dokumen',
        'file_dokumen',
        'created_at'
    ];
}