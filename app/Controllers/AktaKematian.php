<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengajuanAktaKematianModel;
use App\Models\DokumenAktaKematianModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;
use App\Models\HasilLayananModel;

class AktaKematian extends BaseController
{
    protected $pengajuanModel;
    protected $dokumenModel;
    protected $desaModel;
    protected $kecamatanModel;
    protected $hasilModel;

    public function __construct()
    {
        $this->pengajuanModel   = new PengajuanAktaKematianModel();
        $this->dokumenModel     = new DokumenAktaKematianModel();
        $this->desaModel        = new DesaModel();
        $this->kecamatanModel   = new KecamatanModel();
        $this->hasilModel       = new HasilLayananModel();
    }

    /* =======================================================
       INDEX
    ======================================================= */
    public function index()
    {
        $role = session()->get('role');

        $status = $this->request->getGet('status');
        $nama   = $this->request->getGet('nama');
        $kec    = $this->request->getGet('kecamatan');
        $desa   = $this->request->getGet('desa');

        $builder = $this->pengajuanModel
        ->select('pengajuan_akta_kematian.*, pengajuan_akta_kematian.catatan_penolakan, pengajuan_akta_kematian.catatan_pengembalian, desa.nama_desa, hasil_layanan.file_hasil, hasil_layanan.nama_file_asli')
        ->join('desa', 'desa.kode_desa = pengajuan_akta_kematian.kode_desa', 'left')
        ->join(
            'hasil_layanan',
            "hasil_layanan.id_ref = pengajuan_akta_kematian.id_permohonan 
            AND hasil_layanan.jenis_layanan = 'akta_kematian'",
            'left'
        );

        if ($role == 'desa') {
            $builder->where('pengajuan_akta_kematian.kode_desa', session()->get('kode_desa'));
        }

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        if (!empty($nama)) {
            $builder->groupStart()
                ->like('nama_jenazah', $nama)
                ->orLike('nama_pelapor', $nama)
                ->groupEnd();
        }

        if ($role == 'admin') {
            if (!empty($kec)) {
                $builder->where('desa.kode_kecamatan', $kec);
            }

            if (!empty($desa)) {
                $builder->where('pengajuan_akta_kematian.kode_desa', $desa);
            }
        }

        $data = [
            'data' => $builder
    ->orderBy("
        CASE 
            WHEN status = 'Pengajuan' THEN 1
            WHEN status = 'Proses' THEN 2
            WHEN status = 'Revisi' THEN 3
            WHEN status = 'Selesai' THEN 4
            WHEN status = 'Pengembalian' THEN 5
            ELSE 6
        END
    ", '', false)
    ->orderBy('created_at', 'DESC')
    ->findAll(),
            'desa' => $this->desaModel->findAll(),
            'kecamatan' => $this->kecamatanModel->findAll(),
            'selected_status' => $status,
            'selected_kec' => $kec,
            'selected_desa' => $desa,
            'search_nama' => $nama
        ];

        return view('akta_kematian/index', $data);
    }

    /* =======================================================
       CREATE
    ======================================================= */
    public function create()
    {
        return view('akta_kematian/create');
    }

    /* =======================================================
       STORE
    ======================================================= */
    public function store()
    {
        helper(['form']);

        $kodeDesa = null;

        if (session()->get('role') == 'desa') {
            $kodeDesa = session()->get('kode_desa');
        }

        $noPermohonan = 'AKM-' . date('YmdHis');

        $this->pengajuanModel->insert([
            'no_permohonan'      => $noPermohonan,
            'nik_jenazah'        => $this->request->getPost('nik_jenazah'),
            'nama_jenazah'       => $this->request->getPost('nama_jenazah'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'tanggal_kematian'   => $this->request->getPost('tanggal_kematian'),
            'jam_kematian'       => $this->request->getPost('jam_kematian'),
            'tempat_kematian'    => $this->request->getPost('tempat_kematian'),
            'sebab_kematian'     => $this->request->getPost('sebab_kematian'),
            'nama_pelapor'       => $this->request->getPost('nama_pelapor'),
            'nik_pelapor'        => $this->request->getPost('nik_pelapor'),
            'hubungan_pelapor'   => $this->request->getPost('hubungan_pelapor'),
            'nama_saksi_1'       => $this->request->getPost('nama_saksi_1'),
            'nik_saksi_1'        => $this->request->getPost('nik_saksi_1'),
            'nama_saksi_2'       => $this->request->getPost('nama_saksi_2'),
            'nik_saksi_2'        => $this->request->getPost('nik_saksi_2'),
            'status'             => 'Pengajuan',
            'kode_desa'          => $kodeDesa,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s')
        ]);

        $id = $this->pengajuanModel->getInsertID();

        $this->uploadSingle($id, 'surat_kematian_desa', 'Surat Kematian Desa');
        $this->uploadSingle($id, 'surat_kematian_instansi', 'Surat Kematian Instansi');
        $this->uploadSingle($id, 'ktp_pelapor', 'KTP Pelapor');
        $this->uploadSingle($id, 'ktp_saksi_1', 'KTP Saksi 1');
        $this->uploadSingle($id, 'ktp_saksi_2', 'KTP Saksi 2');
        $this->uploadSingle($id, 'kk_jenazah', 'KK Jenazah');
        $this->uploadSingle($id, 'ktp_jenazah', 'KTP Jenazah');

        $this->uploadMultiple($id, 'f201', 'F2.01');

        return redirect()->to('/akta-kematian')->with('success', 'Pengajuan berhasil dibuat');
    }

    /* =======================================================
       DETAIL
    ======================================================= */
    public function detail($id)
    {
        $pengajuan = $this->pengajuanModel->find($id);
        $dokumen = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        return view('akta_kematian/detail', [
            'pengajuan' => $pengajuan,
            'dokumen' => $dokumen
        ]);
    }

    /* =======================================================
       EDIT
    ======================================================= */
    public function edit($id)
    {
        return view('akta_kematian/edit', [
            'pengajuan' => $this->pengajuanModel->find($id),
            'dokumen' => $this->dokumenModel->where('id_permohonan', $id)->findAll(),
            'desa' => $this->desaModel->findAll()
        ]);
    }

    /* =======================================================
       UPDATE
    ======================================================= */
    public function update($id)
    {
        $this->pengajuanModel->update($id, [
            'nik_jenazah'        => $this->request->getPost('nik_jenazah'),
            'nama_jenazah'       => $this->request->getPost('nama_jenazah'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'tanggal_kematian'   => $this->request->getPost('tanggal_kematian'),
            'jam_kematian'       => $this->request->getPost('jam_kematian'),
            'tempat_kematian'    => $this->request->getPost('tempat_kematian'),
            'sebab_kematian'     => $this->request->getPost('sebab_kematian'),
            'nama_pelapor'       => $this->request->getPost('nama_pelapor'),
            'nik_pelapor'        => $this->request->getPost('nik_pelapor'),
            'hubungan_pelapor'   => $this->request->getPost('hubungan_pelapor'),
            'nama_saksi_1'       => $this->request->getPost('nama_saksi_1'),
            'nik_saksi_1'        => $this->request->getPost('nik_saksi_1'),
            'nama_saksi_2'       => $this->request->getPost('nama_saksi_2'),
            'nik_saksi_2'        => $this->request->getPost('nik_saksi_2'),

            'status'             => 'Pengajuan',
            'catatan_revisi'     => null,

            'updated_at'         => date('Y-m-d H:i:s')
        ]);

        if ($hapus = $this->request->getPost('hapus_single')) {
            foreach ($hapus as $idDok) {
                $doc = $this->dokumenModel->find($idDok);
                if ($doc) {
                    @unlink(FCPATH . 'uploads/akta_kematian/' . $doc['file_dokumen']);
                    $this->dokumenModel->delete($idDok);
                }
            }
        }

        // upload dokumen single
        $this->uploadSingle($id, 'surat_kematian_desa', 'Surat Kematian Desa');
        $this->uploadSingle($id, 'surat_kematian_instansi', 'Surat Kematian Instansi');

        $this->uploadSingle($id, 'ktp_pelapor', 'KTP Pelapor');
        $this->uploadSingle($id, 'ktp_saksi_1', 'KTP Saksi 1');
        $this->uploadSingle($id, 'ktp_saksi_2', 'KTP Saksi 2');
        $this->uploadSingle($id, 'kk_jenazah', 'KK Jenazah');
        $this->uploadSingle($id, 'ktp_jenazah', 'KTP Jenazah');

        // upload multi file
        $this->uploadMultiple($id, 'f201', 'F2.01');

        return redirect()->to('/akta-kematian')->with('success', 'Data berhasil diperbarui');
    }

    /* =======================================================
       DELETE
    ======================================================= */
    public function delete($id)
    {
        $docs = $this->dokumenModel->where('id_permohonan', $id)->findAll();

        foreach ($docs as $d) {
            @unlink(FCPATH . 'uploads/akta_kematian/' . $d['file_dokumen']);
        }

        $this->dokumenModel->where('id_permohonan', $id)->delete();
        $this->pengajuanModel->delete($id);

        return redirect()->to('/akta-kematian')->with('success', 'Data berhasil dihapus');
    }

    /* =======================================================
       UPDATE STATUS
    ======================================================= */
    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status == 'Revisi') {
            $data['catatan_revisi'] = $catatan;
        } else {
            $data['catatan_revisi'] = null;
        }

        $this->pengajuanModel->update($id, $data);

        if ($status == 'Selesai')
        {
            $file = $this->request->getFile('file_hasil');

            if ($file && $file->isValid() && !$file->hasMoved())
            {
                $namaRandom = $file->getRandomName();
                $namaAsli   = $file->getClientName();

                $file->move(FCPATH . 'uploads/hasil/', $namaRandom);

                $this->hasilModel->insert([
                    'jenis_layanan' => 'akta_kematian',
                    'id_ref'         => $id,
                    'file_hasil'     => $namaRandom,
                    'nama_file_asli' => $namaAsli,
                    'uploaded_by'    => session()->get('id_user'),
                    'created_at'     => date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect()->to('/akta-kematian')->with('success', 'Status berhasil diperbarui');
    }

    /* =======================================================
       UPDATE HASIL
    ======================================================= */
    public function updateHasil($id)
    {
        $file = $this->request->getFile('file_hasil');

        if ($file && $file->isValid() && !$file->hasMoved())
        {
            $lama = $this->hasilModel
                ->where('jenis_layanan', 'akta_kematian')
                ->where('id_ref', $id)
                ->first();

            $namaRandom = $file->getRandomName();
            $namaAsli   = $file->getClientName();

            $file->move(FCPATH . 'uploads/hasil/', $namaRandom);

            if ($lama)
            {
                @unlink(FCPATH . 'uploads/hasil/' . $lama['file_hasil']);

                $this->hasilModel->update($lama['id_hasil'], [
                    'file_hasil'     => $namaRandom,
                    'nama_file_asli' => $namaAsli
                ]);
            }
            else
            {
                $this->hasilModel->insert([
                    'jenis_layanan' => 'akta_kematian',
                    'id_ref'         => $id,
                    'file_hasil'     => $namaRandom,
                    'nama_file_asli' => $namaAsli,
                    'uploaded_by'    => session()->get('id_user'),
                    'created_at'     => date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect()->to('/akta-kematian')->with('success', 'File hasil diperbarui');
    }

    /* =======================================================
       HELPER UPLOAD SINGLE
    ======================================================= */
    private function uploadSingle($id, $inputName, $jenis)
{
    $file = $this->request->getFile($inputName);

    if ($file && $file->isValid() && !$file->hasMoved()) 
    {
        $lama = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->where('jenis_dokumen', $jenis)
            ->first();

        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/akta_kematian/', $nama);

        if ($lama)
        {
            @unlink(FCPATH . 'uploads/akta_kematian/' . $lama['file_dokumen']);

            $this->dokumenModel->update($lama['id_dokumen'], [
                'file_dokumen' => $nama
            ]);
        }
        else
        {
            $this->dokumenModel->insert([
                'id_permohonan' => $id,
                'jenis_dokumen' => $jenis,
                'file_dokumen'  => $nama,
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }
}

    /* =======================================================
       HELPER UPLOAD MULTIPLE
    ======================================================= */
    private function uploadMultiple($id, $inputName, $jenis)
    {
        $files = $this->request->getFiles();

        if (!isset($files[$inputName])) return;

        foreach ($files[$inputName] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $nama = $file->getRandomName();
                $file->move(FCPATH . 'uploads/akta_kematian/', $nama);

                $this->dokumenModel->insert([
                    'id_permohonan' => $id,
                    'jenis_dokumen' => $jenis,
                    'file_dokumen' => $nama,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
    public function download($id)
    {
        $file = $this->hasilModel
            ->where('jenis_layanan', 'akta_kematian')
            ->where('id_ref', $id)
            ->first();

        if (!$file) {
            return redirect()->back()->with('error', 'File hasil tidak ditemukan');
        }

        $path = FCPATH . 'uploads/hasil/' . $file['file_hasil'];

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ada di server');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $file['nama_file_asli'] . '"')
            ->setBody(file_get_contents($path));
    }
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        $this->pengajuanModel->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan,
            'catatan_penolakan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success','Pengajuan pengembalian berhasil dikirim');
    }
    public function setujuiPengembalian($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Proses',
            'catatan_pengembalian' => null,
            'catatan_penolakan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian disetujui');
    }
    public function tolakPengembalian($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Selesai',
            'catatan_pengembalian' => null,
            'catatan_penolakan' => $this->request->getPost('catatan_penolakan'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian ditolak');
    }
    public function setujuiPengajuan($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Proses',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan disetujui');
    }

    public function tolakPengajuan($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Revisi',
            'catatan_revisi' => $this->request->getPost('catatan_revisi'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan ditolak');
    }
}