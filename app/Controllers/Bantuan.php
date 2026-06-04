<?php

namespace App\Controllers;

use App\Models\BantuanModel;
use App\Models\BantuanKontenModel;

class Bantuan extends BaseController
{
    protected $faqModel;
    protected $kontenModel;

    public function __construct()
    {
        $this->faqModel = new BantuanModel();
        $this->kontenModel = new BantuanKontenModel();
    }

    // ======================
    // INDEX
    // ======================
    public function index()
    {
        $faq = $this->faqModel->findAll();
        $konten = $this->kontenModel->first();

        return view('bantuan/index', [
            'faq' => $faq,
            'konten' => $konten
        ]);
    }

    // ======================
    // CREATE FAQ
    // ======================
    public function create()
    {
        $this->faqModel->save([
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'jawaban'    => $this->request->getPost('jawaban'),
        ]);

        return redirect()->back()->with('success', 'FAQ berhasil ditambahkan');
    }

    // ======================
    // UPDATE FAQ
    // ======================
    public function update($id)
    {
        $this->faqModel->update($id, [
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'jawaban'    => $this->request->getPost('jawaban'),
        ]);

        return redirect()->back()->with('success', 'FAQ berhasil diupdate');
    }

    // ======================
    // DELETE FAQ
    // ======================
    public function delete($id)
    {
        $this->faqModel->delete($id);

        return redirect()->back()->with('success', 'FAQ berhasil dihapus');
    }

    // ======================
    // UPDATE KONTEN
    // ======================
    public function updateKonten()
{
    $model = new \App\Models\BantuanKontenModel();

    $existing = $model->find(1);

    // pastikan selalu array
    if (!$existing) {
        $existing = [
            'panduan' => '',
            'telepon' => '',
            'email'   => '',
            'alamat'  => ''
        ];
    }

    // ambil input
    $panduan = $this->request->getPost('panduan');
    $telepon = $this->request->getPost('telepon');
    $email   = $this->request->getPost('email');
    $alamat  = $this->request->getPost('alamat');

    // pakai data lama kalau kosong
    $data = [
        'panduan' => $panduan !== null ? $panduan : $existing['panduan'],
        'telepon' => $telepon !== null ? $telepon : $existing['telepon'],
        'email'   => $email !== null ? $email : $existing['email'],
        'alamat'  => $alamat !== null ? $alamat : $existing['alamat'],
    ];

    // 🔥 CEGAH EMPTY UPDATE
    if (empty(array_filter($data))) {
        return redirect()->back()->with('error', 'Tidak ada perubahan');
    }

    $model->update(1, $data);

    return redirect()->back()->with('success', 'Berhasil disimpan');
}
}