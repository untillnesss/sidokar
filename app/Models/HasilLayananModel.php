<?php

namespace App\Models;
use CodeIgniter\Model;

class HasilLayananModel extends Model
{
    protected $table = 'hasil_layanan';
    protected $primaryKey = 'id_hasil';

    protected $allowedFields = [
        'jenis_layanan',
        'id_ref',
        'file_hasil',
        'nama_file_asli',
        'uploaded_by',
        'created_at'
    ];

    protected $useTimestamps = false;
}