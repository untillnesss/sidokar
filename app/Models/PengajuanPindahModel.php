<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanPindahModel extends Model
{
    protected $table      = 'pengajuan_pindah';
    protected $primaryKey = 'id_pengajuan';
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'tanggal_pengajuan';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'id_user',
        'nama_pemohon',
        'jenis_pindah',
        'kategori_pindah',
        'jenis_tujuan',
        'alamat_asal',
        'alamat_tujuan',
        'alasan',
        'status',
        'catatan_revisi',
        'hasil_file',
        'tanggal_pengajuan',
        'kode_desa',
        'notif_read',
        'catatan_penolakan',
        'catatan_pengembalian',
        'updated_at'
    ];
}