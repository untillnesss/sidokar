<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DataDukungModel;

class DataDukung extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DataDukungModel();

        // hanya admin master
        if (
            session()->get('role') != 'admin' ||
            session()->get('is_master') != 1
        ) {
            exit('Akses ditolak. Hanya Admin Master.');
        }
    }

    public function create()
    {
        return view('admin/data_dukung/create');
    }

    public function store()
    {
        $this->model->save([
            'kategori' => $this->request->getPost('kategori'),
            'judul'    => $this->request->getPost('judul'),
            'isi'      => $this->request->getPost('isi'),
            'catatan'  => $this->request->getPost('catatan')
        ]);

        return redirect()->to('/admin/data-dukung');
    }

    public function edit($id)
    {
        $data['data'] = $this->model->find($id);
        return view('admin/data_dukung/edit', $data);
    }

    public function update($id)
    {
        $this->model->update($id, [
            'kategori' => $this->request->getPost('kategori'),
            'judul'    => $this->request->getPost('judul'),
            'isi'      => $this->request->getPost('isi'),
            'catatan'  => $this->request->getPost('catatan')
        ]);

        return redirect()->to('/admin/data-dukung');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('/admin/data-dukung');
    }
    public function index()
{
    $data['data_dukung'] = $this->model->findAll();
    return view('admin/data_dukung/index', $data);
}
}