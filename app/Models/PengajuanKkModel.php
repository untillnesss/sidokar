<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanKkModel extends Model
{
    protected $table = 'pengajuan_kk';
    protected $primaryKey = 'id_pengajuan';

    protected $allowedFields = [
        'kode_desa',
        'jenis_pengajuan',
        'nama_kepala',
        'nik_kepala',
        'no_kk',
        'alamat',
        'status',
        'catatan_revisi',
        'created_at',
        'updated_at',
        'catatan_penolakan',
        'catatan_pengembalian'
    ];
}