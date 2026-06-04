<?php

namespace App\Models;

use CodeIgniter\Model;

class BantuanKontenModel extends Model
{
    protected $table = 'bantuan_konten';
    protected $primaryKey = 'id';

    protected $allowedFields = ['panduan', 'telepon', 'email', 'alamat'];
}