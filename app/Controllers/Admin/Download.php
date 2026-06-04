<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DownloadModel;

class Download extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DownloadModel();

        // hanya admin master
        if (
            session()->get('role') != 'admin' ||
            session()->get('is_master') != 1
        ) {
            exit('Akses ditolak. Hanya Admin Master.');
        }
    }

    public function index()
    {
        $data['files'] = $this->model->findAll();
        return view('admin/download/index', $data);
    }

    public function create()
    {
        return view('admin/download/create');
    }

    public function store()
    {
        $file = $this->request->getFile('file_upload');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH.'uploads/downloads', $newName);

            $this->model->save([
                'nama_file' => $file->getClientName(),
                'judul'     => $this->request->getPost('judul'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'path_file' => 'uploads/downloads/'.$newName,
                'uploaded_by' => session()->get('id')
            ]);

            return redirect()->to('/admin/download')->with('success', 'File berhasil diupload.');
        } else {
            return redirect()->back()->with('error', 'File tidak valid.');
        }
    }

    public function delete($id)
    {
        $file = $this->model->find($id);
        if ($file) {
            if(file_exists(WRITEPATH.$file['path_file'])){
                unlink(WRITEPATH.$file['path_file']);
            }
            $this->model->delete($id);
        }
        return redirect()->to('/admin/download')->with('success', 'File berhasil dihapus.');
    }

    public function edit($id)
    {
        $file = $this->model->find($id);

        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }
        return view('admin/download/edit', ['file' => $file]);
    }

    public function update($id)
{
    $fileData = $this->model->find($id);

    if (!$fileData) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
    }

    $updateData = [
        'judul'     => $this->request->getPost('judul'),
        'deskripsi' => $this->request->getPost('deskripsi')
    ];

    $file = $this->request->getFile('file_upload');

    if ($file && $file->isValid() && !$file->hasMoved()) {

        // hapus file lama
        if (file_exists(WRITEPATH . $fileData['path_file'])) {
            unlink(WRITEPATH . $fileData['path_file']);
        }

        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/downloads', $newName);

        $updateData['nama_file'] = $file->getClientName();
        $updateData['path_file'] = 'uploads/downloads/' . $newName;
    }

    $this->model->update($id, $updateData);

    return redirect()->to('/admin/download')
                     ->with('success', 'File berhasil diupdate.');
}

    // --- untuk user biasa / desa ---
    public function publicList()
    {
        $data['files'] = $this->model->findAll();
        return view('download/public_list', $data);
    }

    public function download($id)
    {
        $file = $this->model->find($id);
        if (!$file || !file_exists(WRITEPATH.$file['path_file'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        return $this->response->download(WRITEPATH.$file['path_file'], null);
    }
}