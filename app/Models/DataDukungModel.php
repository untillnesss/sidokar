<?php

namespace App\Models;

use CodeIgniter\Model;

class DataDukungModel extends Model
{
    protected $table = 'data_dukung';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kategori',
        'judul',
        'isi',
        'catatan'
    ];
}