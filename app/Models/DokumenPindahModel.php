<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenPindahModel extends Model
{
    protected $table = 'dokumen_pindah';
    protected $primaryKey = 'id_dokumen';

    protected $allowedFields = [
        'id_pengajuan',
        'jenis_dokumen',
        'nama_file',
        'tanggal_upload'
    ];
}