<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanKiaModel extends Model
{
    protected $table = 'pengajuan_kia';
    protected $primaryKey = 'id_kia';

    protected $allowedFields = [
        'nama_ayah',
        'nik_ayah',
        'alamat_desa',
        'nama_anak',
        'nik_anak',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'foto_anak',
        'file_kk',
        'file_akta',
        'file_f102',
        'status',
        'catatan',
        'hasil_pdf',
        'kode_desa',
        'notif_read',
        'jenis_pengajuan',
        'catatan_pengembalian',
        'catatan_penolakan'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'tanggal_pengajuan';
    protected $updatedField = 'updated_at';
}