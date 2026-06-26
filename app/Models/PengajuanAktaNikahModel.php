<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanAktaNikahModel extends Model
{
    protected $table = 'pengajuan_akta_nikah';
    protected $primaryKey = 'id_permohonan';

    protected $allowedFields = [
        'id_user',
        'nama_laki_laki',
        'nik_laki_laki',
        'nama_perempuan',
        'nik_perempuan',
        'agama',
        'tempat_pernikahan',
        'tanggal_perkawinan',
        'nama_pemuka_agama',
        'nama_saksi_1',
        'nama_saksi_2',
        'status',
        'catatan',
        'kode_desa',
        'hasil_layanan',
        'catatan_pengembalian',
        'catatan_penolakan',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $updatedField  = 'updated_at';
}