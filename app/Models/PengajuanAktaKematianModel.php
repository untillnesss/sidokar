<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanAktaKematianModel extends Model
{
    protected $table = 'pengajuan_akta_kematian';
    protected $primaryKey = 'id_permohonan';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'no_permohonan',

        'nik_jenazah',
        'nama_jenazah',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',

        'tanggal_kematian',
        'jam_kematian',
        'tempat_kematian',
        'sebab_kematian',

        'nama_pelapor',
        'nik_pelapor',
        'hubungan_pelapor',

        'nama_saksi_1',
        'nik_saksi_1',
        'nama_saksi_2',
        'nik_saksi_2',

        'status',
        'catatan_revisi',
        'kode_desa',

        'created_at',
        'updated_at',

        'catatan_pengembalian',
        'catatan_penolakan'
    ];
}