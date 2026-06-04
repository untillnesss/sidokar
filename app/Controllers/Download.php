<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DownloadModel;

class Download extends BaseController
{
    protected $model;
    protected $uploadPath;

    public function __construct()
    {
        $this->model = new DownloadModel();

        // 🔥 path utama upload
        $this->uploadPath = WRITEPATH . 'uploads/downloads/';

        // 🔥 auto buat folder kalau belum ada
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function publicList()
    {
        $data['files'] = $this->model->findAll();
        return view('admin/download/list', $data);
    }

    public function download($id)
    {
        $file = $this->model->find($id);

        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data file tidak ditemukan.');
        }

        $filePath = $this->uploadPath . basename($file['path_file']);

        if (!file_exists($filePath)) {
            // 🔥 DEBUG (hapus kalau sudah aman)
            // dd($filePath);

            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan di server.');
        }

        return $this->response
            ->download($filePath, null)
            ->setFileName($file['nama_file']);
    }

    private function checkAdminMaster()
    {
        if (session()->get('role') != 'admin' || session()->get('is_master') != 1) {
            exit('Akses ditolak. Hanya Admin Master.');
        }
    }

    public function create()
    {
        $this->checkAdminMaster();
        return view('admin/download/create');
    }

    public function store()
    {
        $this->checkAdminMaster();

        $file = $this->request->getFile('file_upload');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $originalName = $file->getClientName();
            $safeName     = date('YmdHis') . '_' . $file->getRandomName();

            // 🔥 pindahkan ke folder
            $file->move($this->uploadPath, $safeName);

            $this->model->save([
                'judul'       => $this->request->getPost('judul'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'nama_file'   => $originalName,
                'path_file'   => $safeName, // 🔥 simpan nama saja (lebih aman)
                'uploaded_by' => session()->get('id')
            ]);

            return redirect()->to('/download-files')
                ->with('success', 'File berhasil diupload.');
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }

    public function edit($id)
    {
        $this->checkAdminMaster();

        $file = $this->model->find($id);

        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        return view('admin/download/edit', ['file' => $file]);
    }

    public function update($id)
    {
        $this->checkAdminMaster();

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

            // 🔥 hapus file lama
            $oldPath = $this->uploadPath . basename($fileData['path_file']);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $originalName = $file->getClientName();
            $safeName     = date('YmdHis') . '_' . $file->getRandomName();

            $file->move($this->uploadPath, $safeName);

            $updateData['nama_file'] = $originalName;
            $updateData['path_file'] = $safeName;
        }

        $this->model->update($id, $updateData);

        return redirect()->to('/download-files')
            ->with('success', 'File berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->checkAdminMaster();

        $file = $this->model->find($id);

        if ($file) {

            $filePath = $this->uploadPath . basename($file['path_file']);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $this->model->delete($id);
        }

        return redirect()->to('/download-files')
            ->with('success', 'File berhasil dihapus.');
    }

    public function lihat($id)
    {
        $file = $this->model->find($id);

        if (!$file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        $filePath = $this->uploadPath . basename($file['path_file']);

        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan di server.');
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }
}