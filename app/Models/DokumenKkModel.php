<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenKkModel extends Model
{
    protected $table = 'dokumen_kk';
    protected $primaryKey = 'id_dokumen';

    protected $allowedFields = [
        'id_pengajuan',
        'jenis_dokumen',
        'nama_file'
    ];
}