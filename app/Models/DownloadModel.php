<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model
{
    protected $table = 'download_files';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_file', 'judul', 'deskripsi', 'path_file', 'uploaded_by', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}