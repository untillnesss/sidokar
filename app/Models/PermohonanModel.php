<?php

namespace App\Models;
use CodeIgniter\Model;

class PermohonanModel extends Model
{
    protected $table = 'permohonan';
    protected $primaryKey = 'id_permohonan';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'id_pelapor',
        'id_anak',
        'id_orangtua',
        'id_saksi1',
        'id_saksi2',
        'kode_desa',
        'status',
        'catatan_revisi',
        'created_at',
        'updated_at',
        'catatan_pengembalian',
        'catatan_penolakan'
    ];
}