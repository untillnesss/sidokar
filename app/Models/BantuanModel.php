<?php

namespace App\Models;

use CodeIgniter\Model;

class BantuanModel extends Model
{
    protected $table = 'bantuan_faq';
    protected $primaryKey = 'id';

    protected $allowedFields = ['pertanyaan', 'jawaban'];
}