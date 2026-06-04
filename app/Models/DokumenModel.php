<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    protected $allowedFields = [
        'id_permohonan',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'ukuran_file',
        'tipe_file',
        'uploaded_at'
    ];
}