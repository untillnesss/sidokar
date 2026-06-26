<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanAktaCeraiModel extends Model
{
    protected $table = 'pengajuan_akta_cerai';
    protected $primaryKey = 'id_permohonan';

    protected $allowedFields = [
        'id_user',
        'kode_desa',
        'nama_perempuan',
        'nik_perempuan',
        'nama_laki',
        'nik_laki',
        'tanggal_cerai',
        'tempat_cerai',
        'nomor_akta_perkawinan',
        'tanggal_perkawinan',
        'status',
        'catatan_revisi',
        'diproses_oleh',
        'updated_by',
        'tanggal_pengajuan',
        'tanggal_proses',
        'tanggal_selesai',
        'catatan_pengembalian',
        'catatan_penolakan',
        'updated_at'
    ];

    protected $useTimestamps = true;
}