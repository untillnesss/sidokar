<?php

namespace App\Controllers;

use App\Models\DataDukungModel;

class DataDukung extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DataDukungModel();
    }

    // ======================================================
    // INDEX
    // ======================================================
    public function index()
    {
        session()->set('last_url', current_url());

        return view('data_dukung/index', [
            'title' => 'Data Dukung',
            'data_dukung' => $this->model->findAll()
        ]);
    }

    // ======================================================
    // CREATE
    // ======================================================
    public function create()
    {
        return view('data_dukung/create', [
            'title' => 'Tambah Data Dukung'
        ]);
    }

    // ======================================================
    // STORE
    // ======================================================
    public function store()
    {
        $this->model->save([
            'kategori' => $this->request->getPost('kategori'),
            'judul'    => $this->request->getPost('judul'),
            'isi'      => $this->request->getPost('isi'),
            'catatan'  => $this->request->getPost('catatan')
        ]);

        $redirect = $this->request->getPost('redirect_back');

        // fallback kalau kosong
        if (!$redirect) {
            $redirect = base_url('data-dukung');
        }

        return redirect()->to($redirect)
            ->with('success', 'Data berhasil ditambahkan');
    }

    // ======================================================
    // EDIT
    // ======================================================
    public function edit($id)
    {
        return view('data_dukung/edit', [
            'title' => 'Edit Data Dukung',
            'data'  => $this->model->find($id)
        ]);
    }

    // ======================================================
    // UPDATE
    // ======================================================
    public function update($id)
    {
        $this->model->update($id, [
            'kategori' => $this->request->getPost('kategori'),
            'judul'    => $this->request->getPost('judul'),
            'isi'      => $this->request->getPost('isi'),
            'catatan'  => $this->request->getPost('catatan')
        ]);

        return redirect()->to($this->getBack())
            ->with('success', 'Data berhasil diupdate');
    }

    // ======================================================
    // DELETE
    // ======================================================
    public function delete($id)
{
    $this->model->delete($id);

    return redirect()->to(base_url('data-dukung'))
        ->with('success', 'Data berhasil dihapus');
}
    // ======================================================
    // KATEGORI
    // ======================================================
    public function kategori($slug)
    {
        session()->set('last_url', current_url());

        $items = $this->model
            ->where('kategori', $slug)
            ->findAll();

        return view('data_dukung/kategori', [
            'items' => $items,
            'kategori' => str_replace('-', ' ', $slug)
        ]);
    }

    // ======================================================
    // MENU
    // ======================================================
    public function menu()
    {
        return view('data_dukung/menu');
    }

    // ======================================================
    // BACK FIX (ANTI NYASAR ADMIN)
    // ======================================================
    private function getBack()
    {
        $url = session()->get('last_url');

        // kalau kosong atau nyasar admin → fallback aman
        if (!$url || str_contains($url, '/admin/data-dukung')) {
            return base_url('data-dukung');
        }

        return $url;
    }
}