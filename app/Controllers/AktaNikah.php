<?php

namespace App\Controllers;

use App\Models\PengajuanAktaNikahModel;
use App\Models\DokumenAktaNikahModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;

class AktaNikah extends BaseController
{
    protected $pengajuanModel;
    protected $dokumenModel;
    protected $kecamatanModel;
    protected $desaModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanAktaNikahModel();
        $this->dokumenModel = new DokumenAktaNikahModel();
        $this->kecamatanModel = new KecamatanModel();
        $this->desaModel      = new DesaModel();

        $this->db = \Config\Database::connect(); // 🔥 INI YANG KURANG
    }

    public function index()
    {
        $request = service('request');

        // ambil filter dari URL
        $selected_status = $request->getGet('status');
        $selected_kec    = $request->getGet('kecamatan');
        $selected_desa   = $request->getGet('desa');
        $search_nama     = $request->getGet('nama');

        // ================= QUERY =================
        $builder = $this->pengajuanModel
    ->select('pengajuan_akta_nikah.*, desa.nama_desa, desa.kode_kecamatan, hasil_layanan.file_hasil')
    ->join('desa', 'desa.kode_desa = pengajuan_akta_nikah.kode_desa', 'left')
    ->join(
        'hasil_layanan',
        "hasil_layanan.id_ref = pengajuan_akta_nikah.id_permohonan 
         AND hasil_layanan.jenis_layanan = 'akta_nikah'",
        'left'
    );
            $role = session()->get('role');
        $kode_desa_user = session()->get('kode_desa');

// 🔥 FILTER KHUSUS DESA
if ($role == 'desa') {
    $builder->where('pengajuan_akta_nikah.kode_desa', $kode_desa_user);
}
        
            // FILTER STATUS
        if ($selected_status) {
            $builder->where('pengajuan_akta_nikah.status', $selected_status);
        }

        // 🔥 FILTER KECAMATAN (INI YANG KAMU BELUM ADA)
        if ($selected_kec) {
            $builder->where('desa.kode_kecamatan', $selected_kec);
        }

        // FILTER DESA
        if ($selected_desa) {
            $builder->where('desa.kode_desa', $selected_desa);
        }

        // FILTER NAMA
        if ($search_nama) {
            $builder->groupStart()
                ->like('pengajuan_akta_nikah.nama_laki_laki', $search_nama)
                ->orLike('pengajuan_akta_nikah.nama_perempuan', $search_nama)
                ->groupEnd();
        }

        // AMBIL DATA
        $data['data'] = $builder
            ->orderBy("
            CASE 
                WHEN status='Pengajuan' THEN 1
                WHEN status='Proses' THEN 2
                WHEN status='Revisi' THEN 3
                WHEN status='Pengembalian' THEN 4
                WHEN status='Selesai' THEN 5
            END
            ", '', false)
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        // ================= KIRIM FILTER =================
        $data['selected_status'] = $selected_status;
        $data['selected_kec']    = $selected_kec;
        $data['selected_desa']   = $selected_desa;
        $data['search_nama']     = $search_nama;

        // ================= AMBIL MASTER =================
        $data['kecamatan'] = $this->kecamatanModel->findAll();
        $data['desa']      = $this->desaModel->findAll();

        return view('akta_nikah/index', $data);
    }

    public function create()
    {
        return view('akta_nikah/create');
    }

    public function store()
    {
        $rules = [
            'nama_laki_laki' => 'required|regex_match[/^[a-zA-Z\s]+$/]',
            'nama_perempuan' => 'required|regex_match[/^[a-zA-Z\s]+$/]',
            'nama_pemuka_agama' => 'required|regex_match[/^[a-zA-Z\s]+$/]',
            'nama_saksi_1' => 'required|regex_match[/^[a-zA-Z\s]+$/]',
            'nama_saksi_2' => 'required|regex_match[/^[a-zA-Z\s]+$/]',

            'nik_laki_laki' => 'required|numeric|exact_length[16]',
            'nik_perempuan' => 'required|numeric|exact_length[16]',

            'kk' => 'uploaded[kk]|is_image[kk]|mime_in[kk,image/jpg,image/jpeg]|max_size[kk,400]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        // simpan pengajuan
        $id_user   = session()->get('id_user') ?? null;
        $kode_desa = session()->get('kode_desa') ?? null; // 🔥 cukup ini

        $this->pengajuanModel->save([
            'id_user' => $id_user,
            'kode_desa' => $kode_desa, // desa isi otomatis, admin NULL
            'nama_laki_laki' => $this->request->getPost('nama_laki_laki'),
            'nik_laki_laki' => $this->request->getPost('nik_laki_laki'),
            'nama_perempuan' => $this->request->getPost('nama_perempuan'),
            'nik_perempuan' => $this->request->getPost('nik_perempuan'),
            'agama' => $this->request->getPost('agama'),
            'tempat_pernikahan' => $this->request->getPost('tempat_pernikahan'),
            'tanggal_perkawinan' => $this->request->getPost('tanggal_perkawinan'),
            'nama_pemuka_agama' => $this->request->getPost('nama_pemuka_agama'),
            'nama_saksi_1' => $this->request->getPost('nama_saksi_1'),
            'nama_saksi_2' => $this->request->getPost('nama_saksi_2'),
            'status' => 'Pengajuan'
        ]);
        // 🔥 WAJIB ADA INI
        $id = $this->pengajuanModel->getInsertID();

        // upload dokumen
        $this->uploadSingle('kk', 'KK', $id);
        $this->uploadSingle('ktp_laki', 'KTP Laki-laki', $id);
        $this->uploadSingle('ktp_perempuan', 'KTP Perempuan', $id);
        $this->uploadSingle('akta_laki', 'Akta Lahir Laki', $id);
        $this->uploadSingle('akta_perempuan', 'Akta Lahir Perempuan', $id);
        $this->uploadSingle('suket_desa', 'Surat Desa', $id);
        $this->uploadSingle('ktp_saksi', 'KTP Saksi', $id);
        $this->uploadSingle('surat_cerai_laki', 'Surat Cerai Laki-laki', $id);
        $this->uploadSingle('surat_cerai_perempuan', 'Surat Cerai Perempuan', $id);
        $this->uploadSingle('pas_foto', 'Pas Foto', $id);

        // multiple F1.02
        $files = $this->request->getFiles();
        if (isset($files['f102'])) {
            foreach ($files['f102'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $nama = $file->getRandomName();
                    $file->move('uploads/akta_nikah', $nama);

                    $this->dokumenModel->save([
                        'id_permohonan' => $id,
                        'jenis_dokumen' => 'F1.02',
                        'file' => $nama
                    ]);
                }
            }
        }

        return redirect()->to('/akta-nikah');
    }

    private function uploadSingle($input, $jenis, $id)
    {
        $file = $this->request->getFile($input);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nama = $file->getRandomName();
            $file->move('uploads/akta_nikah', $nama);

            $this->dokumenModel->save([
                'id_permohonan' => $id,
                'jenis_dokumen' => $jenis,
                'file' => $nama
            ]);
        }
    }
    public function detail($id)
    {
        $data['pengajuan'] = $this->pengajuanModel->find($id);

        $data['dokumen'] = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        return view('akta_nikah/detail', $data);
    }
    public function delete($id)
    {
        $this->pengajuanModel->delete($id);
        return redirect()->to('/akta-nikah');
    }

    public function update($id)
    {
        $this->pengajuanModel->update($id, [
            'nama_laki_laki' => $this->request->getPost('nama_laki_laki'),
            'nik_laki_laki' => $this->request->getPost('nik_laki_laki'),
            'nama_perempuan' => $this->request->getPost('nama_perempuan'),
            'nik_perempuan' => $this->request->getPost('nik_perempuan'),
            'agama' => $this->request->getPost('agama'),
            'tempat_pernikahan' => $this->request->getPost('tempat_pernikahan'),
            'tanggal_perkawinan' => $this->request->getPost('tanggal_perkawinan'),
            'nama_pemuka_agama' => $this->request->getPost('nama_pemuka_agama'),
            'nama_saksi_1' => $this->request->getPost('nama_saksi_1'),
            'nama_saksi_2' => $this->request->getPost('nama_saksi_2'),

            // 🔥 otomatis balik ke pengajuan
            'status' => 'Pengajuan',
            'catatan' => null
        ]);
        // hapus single
        $hapusSingle = $this->request->getPost('hapus_single');
        if($hapusSingle){
            foreach($hapusSingle as $id){
                $file = $this->dokumenModel->find($id);
                if($file){
                    unlink('uploads/akta_nikah/'.$file['file']);
                    $this->dokumenModel->delete($id);
                }
            }
        }

// hapus multiple
    $hapusF102 = $this->request->getPost('hapus_f102');
    if($hapusF102){
        foreach($hapusF102 as $id){
            $file = $this->dokumenModel->find($id);
            if($file){
                unlink('uploads/akta_nikah/'.$file['file']);
                $this->dokumenModel->delete($id);
            }
        }
    } // ================= UPLOAD ULANG FILE =================

    // single file
    $this->uploadSingleUpdate('kk', 'KK', $id);
    $this->uploadSingleUpdate('ktp_laki', 'KTP Laki-laki', $id);
    $this->uploadSingleUpdate('ktp_perempuan', 'KTP Perempuan', $id);
    $this->uploadSingleUpdate('akta_laki', 'Akta Lahir Laki', $id);
    $this->uploadSingleUpdate('akta_perempuan', 'Akta Lahir Perempuan', $id);
    $this->uploadSingleUpdate('suket_desa', 'Surat Desa', $id);
    $this->uploadSingleUpdate('ktp_saksi', 'KTP Saksi', $id);
    $this->uploadSingleUpdate('pas_foto', 'Pas Foto', $id);
    $this->uploadSingleUpdate('surat_cerai_laki', 'Surat Cerai Laki-laki', $id);
    $this->uploadSingleUpdate('surat_cerai_perempuan', 'Surat Cerai Perempuan', $id);


// ================= MULTIPLE F1.02 =================
    $files = $this->request->getFiles();

    if (isset($files['f102'])) {
        foreach ($files['f102'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {

                $nama = $file->getRandomName();
                $file->move('uploads/akta_nikah', $nama);

                $this->dokumenModel->save([
                    'id_permohonan' => $id,
                    'jenis_dokumen' => 'F1.02',
                    'file' => $nama
                ]);
            }
        }
    }
        return redirect()->to('/akta-nikah');
    }
   public function edit($id)
    {
        $data['pengajuan'] = $this->pengajuanModel->find($id);

        if (!$data['pengajuan']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 🔥 TAMBAHKAN INI
        $data['dokumen'] = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        return view('akta_nikah/edit', $data);
    }

    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // ================= REVISI =================
        if ($status == 'Revisi') {

            $catatan = $this->request->getPost('catatan');

            if (!$catatan) {
                return redirect()->back()->with('error', 'Catatan revisi wajib diisi');
            }

            $data['catatan'] = $catatan;
        }

        // ================= SELESAI =================
        if ($status == 'Selesai') {

            $file = $this->request->getFile('file_hasil');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'File hasil wajib diupload');
            }

            $namaBaru = $file->getRandomName();

            $file->move('uploads/hasil', $namaBaru);

            $existing = $this->db->table('hasil_layanan')
                ->where('jenis_layanan', 'akta_nikah')
                ->where('id_ref', $id)
                ->get()
                ->getRowArray();

            $payload = [
                'file_hasil' => $namaBaru,
                'nama_file_asli' => $file->getClientName(),
                'uploaded_by' => session()->get('id_user'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($existing) {

                $this->db->table('hasil_layanan')
                    ->where('jenis_layanan', 'akta_nikah')
                    ->where('id_ref', $id)
                    ->update($payload);

            } else {

                $payload['jenis_layanan'] = 'akta_nikah';
                $payload['id_ref'] = $id;

                $this->db->table('hasil_layanan')->insert($payload);
            }
        }

        $this->pengajuanModel->update($id, $data);

        return redirect()->to('/akta-nikah')
            ->with('success', 'Status berhasil diupdate');
    }

    private function uploadSingleUpdate($input, $jenis, $id)
    {
        $file = $this->request->getFile($input);

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // 🔥 ambil file lama
            $old = $this->dokumenModel
                ->where('id_permohonan', $id)
                ->where('jenis_dokumen', $jenis)
                ->first();

            // 🔥 hapus file lama
            if ($old) {
                $path = 'uploads/akta_nikah/'.$old['file'];

                if (file_exists($path)) {
                    unlink($path);
                }

                $this->dokumenModel->delete($old['id_dokumen']);
            }

            // 🔥 upload file baru
            $nama = $file->getRandomName();
            $file->move('uploads/akta_nikah', $nama);

            $this->dokumenModel->save([
                'id_permohonan' => $id,
                'jenis_dokumen' => $jenis,
                'file' => $nama
            ]);
        }
    }

    public function updateHasil($id)
    {
        $file = $this->request->getFile('file_hasil');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $nama = $file->getRandomName();
            $file->move('uploads/hasil', $nama);

            $existing = $this->db->table('hasil_layanan')
                ->where('jenis_layanan', 'akta_nikah')
                ->where('id_ref', $id)
                ->get()
                ->getRowArray();

            if ($existing) {
                // update file lama
                $this->db->table('hasil_layanan')
                    ->where('id_ref', $id)
                    ->where('jenis_layanan', 'akta_nikah')
                    ->update([
                        'file_hasil' => $nama,
                        'nama_file_asli' => $file->getClientName(),
                        'uploaded_by' => session()->get('id_user')
                    ]);
            } else {
                // insert baru
                $this->db->table('hasil_layanan')->insert([
                    'jenis_layanan' => 'akta_nikah',
                    'id_ref' => $id,
                    'file_hasil' => $nama,
                    'nama_file_asli' => $file->getClientName(),
                    'uploaded_by' => session()->get('id_user')
                ]);
            }
        }

        return redirect()->to('/akta-nikah');
    }
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        $this->pengajuanModel->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian diajukan');
    }

    public function setujuiPengembalian($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Proses',
            'catatan_penolakan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian disetujui');
    }

    public function tolakPengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_penolakan');

        $this->pengajuanModel->update($id, [
            'status' => 'Selesai',
            'catatan_penolakan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian ditolak');
    }
    public function setujui($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Proses',
            'catatan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan disetujui');
    }

    public function tolak($id)
    {
        $catatan = $this->request->getPost('catatan');

        $this->pengajuanModel->update($id, [
            'status' => 'Revisi',
            'catatan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan ditolak');
    }
}