<?php

namespace App\Controllers;

use App\Models\PengajuanAktaCeraiModel;
use App\Models\DokumenAktaCeraiModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;
use App\Models\HasilLayananModel;

class AktaCerai extends BaseController
{
    protected $pengajuanModel;
    protected $dokumenModel;
    protected $desaModel;
    protected $kecamatanModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanAktaCeraiModel();
        $this->dokumenModel   = new DokumenAktaCeraiModel();
        $this->desaModel      = new DesaModel();
        $this->kecamatanModel = new KecamatanModel();
        $this->hasilModel     = new HasilLayananModel();
    }

    // ======================
    // LIST DATA + FILTER
    // ======================
    public function index()
{
    $role       = session()->get('role');
    $kode_desa  = session()->get('kode_desa');

    $status = $this->request->getGet('status');
    $nama   = $this->request->getGet('nama');
    $kec    = $this->request->getGet('kecamatan');
    $desa   = $this->request->getGet('desa');

    // 🔥 JOIN DESA DI SINI
    $builder = $this->pengajuanModel
        ->select('pengajuan_akta_cerai.*, desa.nama_desa, hasil_layanan.file_hasil')
        ->join('desa', 'desa.kode_desa = pengajuan_akta_cerai.kode_desa', 'left')
        ->join('hasil_layanan', 
            'hasil_layanan.id_ref = pengajuan_akta_cerai.id_permohonan 
            AND hasil_layanan.jenis_layanan="akta_cerai"', 
        'left');

    // ================= ROLE DESA =================
    if ($role == 'desa') {
        $builder = $builder->where('pengajuan_akta_cerai.kode_desa', $kode_desa);
    }

    // ================= FILTER STATUS =================
    if (!empty($status)) {
        $builder = $builder->where('status', $status);
    }

    // ================= SEARCH NAMA =================
    if (!empty($nama)) {
        $builder = $builder->groupStart()
            ->like('nama_perempuan', $nama)
            ->orLike('nama_laki', $nama)
            ->groupEnd();
    }

    // ================= FILTER ADMIN =================
    if ($role == 'admin') {

        if (!empty($kec)) {
            $listDesa = $this->desaModel
                ->where('kode_kecamatan', $kec)
                ->findAll();

            $kodeDesaList = array_column($listDesa, 'kode_desa');

            if (!empty($kodeDesaList)) {
                $builder = $builder->whereIn('pengajuan_akta_cerai.kode_desa', $kodeDesaList);
            } else {
                $builder = $builder->where('pengajuan_akta_cerai.kode_desa', 'TIDAK_ADA');
            }
        }

        if (!empty($desa)) {
            $builder = $builder->where('pengajuan_akta_cerai.kode_desa', $desa);
        }
    }

    // ================= DATA =================
    $data['data'] = $builder
    ->orderBy("FIELD(status,'Pengajuan','Proses','Revisi','Selesai')")
    ->orderBy('tanggal_pengajuan', 'DESC')
    ->findAll();

    $data['selected_status'] = $status;
    $data['search_nama']     = $nama;
    $data['selected_kec']    = $kec;
    $data['selected_desa']   = $desa;

    if ($role == 'admin') {
        $data['kecamatan'] = $this->kecamatanModel->findAll();
        $data['desa']      = $this->desaModel->findAll();
    }

    return view('akta_cerai/index', $data);
}

    // ======================
    // FORM
    // ======================
    public function create()
    {
        return view('akta_cerai/create');
    }

    // ======================
    // SIMPAN DATA
    // ======================
    public function store()
    {
        if (!$this->validate([
            'nama_perempuan' => 'required',
            'nik_perempuan'  => 'required',
            'nama_laki'      => 'required',
            'nik_laki'       => 'required',
            'tanggal_cerai'  => 'required',
            'tempat_cerai'   => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        $id_user   = session()->get('id_user') ?? null;
        $kode_desa = session()->get('kode_desa');

        // 🔥 admin ambil dari form
        if (!$kode_desa) {
            $kode_desa = $this->request->getPost('kode_desa');
        }

        // 🔥 simpan pengajuan dulu
        $this->pengajuanModel->save([
            'id_user'              => $id_user,
            'kode_desa'            => $kode_desa,
            'nama_perempuan'       => $this->request->getPost('nama_perempuan'),
            'nik_perempuan'        => $this->request->getPost('nik_perempuan'),
            'nama_laki'            => $this->request->getPost('nama_laki'),
            'nik_laki'             => $this->request->getPost('nik_laki'),
            'tanggal_cerai'        => $this->request->getPost('tanggal_cerai'),
            'tempat_cerai'         => $this->request->getPost('tempat_cerai'),
            'nomor_akta_perkawinan'=> $this->request->getPost('nomor_akta_perkawinan'),
            'tanggal_perkawinan'   => $this->request->getPost('tanggal_perkawinan'),
            'status'               => 'Pengajuan',
            'tanggal_pengajuan'    => date('Y-m-d H:i:s')
        ]);

        // 🔥 ambil ID BARU
        $id_permohonan = $this->pengajuanModel->getInsertID();

        $path = 'uploads/akta_cerai/';

        // 🔥 pastikan folder ada
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // ================= FILE SINGLE =================
        $single = ['pn','ktp_perempuan','ktp_laki','kk','akta_perkawinan'];

        foreach ($single as $jenis) {

            $file = $this->request->getFile($jenis);

            if ($file && $file->isValid() && !$file->hasMoved()) {

                $name = $file->getRandomName();
                $file->move($path, $name);

                $this->dokumenModel->save([
                    'id_permohonan' => $id_permohonan,
                    'jenis_dokumen' => $jenis,
                    'file_path'     => $name
                ]);
            }
        }

        // ================= FILE MULTIPLE (F201) =================
        $files = $this->request->getFiles()['f201'] ?? [];

        foreach ($files as $file) {

            if ($file && $file->isValid() && !$file->hasMoved()) {

                $name = $file->getRandomName();
                $file->move($path, $name);

                $this->dokumenModel->save([
                    'id_permohonan' => $id_permohonan,
                    'jenis_dokumen' => 'f201',
                    'file_path'     => $name
                ]);
            }
        }

        return redirect()->to('/akta-cerai')->with('success', 'Berhasil disimpan');
    }

    // ======================
    // EDIT
    // ======================
    public function edit($id)
    {
        $data['pengajuan'] = $this->pengajuanModel->find($id);
        $data['dokumen']   = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        return view('akta_cerai/edit', $data);
    }

    
    // ======================
    // DELETE
    // ======================
    public function delete($id)
    {
        $this->pengajuanModel->delete($id);
        return redirect()->to('/akta-cerai')->with('success', 'Berhasil hapus');
    }

    // ======================
    // UPDATE STATUS (ADMIN)
    // ======================
    public function update($id)
{
    // ================= AMBIL DATA LAMA =================
    $lama = $this->pengajuanModel->find($id);

    // ================= UPDATE DATA UTAMA =================
    $dataUpdate = [
        'nama_perempuan'       => $this->request->getPost('nama_perempuan'),
        'nik_perempuan'        => $this->request->getPost('nik_perempuan'),
        'nama_laki'            => $this->request->getPost('nama_laki'),
        'nik_laki'             => $this->request->getPost('nik_laki'),
        'tanggal_cerai'        => $this->request->getPost('tanggal_cerai'),
        'tempat_cerai'         => $this->request->getPost('tempat_cerai'),
        'nomor_akta_perkawinan'=> $this->request->getPost('nomor_akta_perkawinan'),
        'tanggal_perkawinan'   => $this->request->getPost('tanggal_perkawinan'),
    ];

    // ================= LOGIKA REVISI =================
    if ($lama['status'] == 'Revisi') {
        $dataUpdate['status'] = 'Pengajuan';
        $dataUpdate['catatan_revisi'] = null;

        // 🔥 pakai waktu Indonesia
        date_default_timezone_set('Asia/Jakarta');
        $dataUpdate['tanggal_pengajuan'] = date('Y-m-d H:i:s');
    }

    $this->pengajuanModel->update($id, $dataUpdate);

    $path = 'uploads/akta_cerai/';

    // ================= FILE SINGLE (AUTO REPLACE) =================
    $single = ['pn','ktp_perempuan','ktp_laki','kk','akta_perkawinan'];

    foreach ($single as $jenis) {

        $file = $this->request->getFile($jenis);

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // 🔥 ambil file lama
            $old = $this->dokumenModel
                ->where('id_permohonan', $id)
                ->where('jenis_dokumen', $jenis)
                ->first();

            // 🔥 hapus file lama
            if ($old) {
                if (file_exists($path.$old['file_path'])) {
                    unlink($path.$old['file_path']);
                }
                $this->dokumenModel->delete($old['id_dokumen']);
            }

            // 🔥 upload baru
            $name = $file->getRandomName();
            $file->move($path, $name);

            $this->dokumenModel->save([
                'id_permohonan' => $id,
                'jenis_dokumen' => $jenis,
                'file_path'     => $name
            ]);
        }
    }

    // ================= FILE MULTIPLE (F201) =================

    // 🔥 hapus yang dicentang
    $hapus = $this->request->getPost('hapus_f201');

    if ($hapus) {
        foreach ($hapus as $docId) {

            $file = $this->dokumenModel->find($docId);

            if ($file) {
                if (file_exists($path.$file['file_path'])) {
                    unlink($path.$file['file_path']);
                }
                $this->dokumenModel->delete($docId);
            }
        }
    }

    // 🔥 tambah file baru
    $files = $this->request->getFiles()['f201'] ?? [];

    foreach ($files as $file) {
        if ($file && $file->isValid() && !$file->hasMoved()) {

            $name = $file->getRandomName();
            $file->move($path, $name);

            $this->dokumenModel->save([
                'id_permohonan' => $id,
                'jenis_dokumen' => 'f201',
                'file_path'     => $name
            ]);
        }
    }

    return redirect()->to('/akta-cerai')->with('success', 'Berhasil update');
}

    public function detail($id = null)
    {
        // 🔥 validasi id
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 🔥 ambil data pengajuan
        $pengajuan = $this->pengajuanModel
            ->where('id_permohonan', $id)
            ->first();

        if (!$pengajuan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 🔥 ambil dokumen
        $dokumen = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        // 🔥 kirim ke view
        return view('akta_cerai/detail', [
            'pengajuan' => $pengajuan,
            'dokumen'   => $dokumen
        ]);
    }

    public function updateStatus()
    {
        $id      = $this->request->getPost('id');
        $status  = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        $data = [
            'status' => $status,
            'catatan_revisi' => $status == 'Revisi' ? $catatan : null
        ];

        // ================= UPLOAD HASIL =================
        if ($status == 'Selesai') {

            $file = $this->request->getFile('file_hasil');

            if ($file && $file->isValid() && !$file->hasMoved()) {

                $path = 'uploads/hasil/';

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $name = $file->getRandomName();
                $file->move($path, $name);

                $cek = $this->hasilModel
                    ->where('id_ref', $id)
                    ->where('jenis_layanan', 'akta_cerai')
                    ->first();

                if ($cek) {

                    $this->hasilModel->update($cek['id_hasil'], [
                        'file_hasil'     => $name,
                        'nama_file_asli' => $file->getClientName(),
                        'uploaded_by'    => session()->get('id_user'),
                        'jenis_file'     => $file->getClientExtension()
                    ]);

                } else {

                    $this->hasilModel->save([
                        'jenis_layanan'   => 'akta_cerai',
                        'id_ref'          => $id, // 🔥 ini penting
                        'file_hasil'      => $name,
                        'nama_file_asli'  => $file->getClientName(),
                        'uploaded_by'     => session()->get('id_user'),
                        'jenis_file'      => $file->getClientExtension(),
                        'created_at'      => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        $this->pengajuanModel->update($id, $data);

        return redirect()->to('/akta-cerai')->with('success', 'Status diupdate');
    }

    public function updateHasil($id)
    {
        $file = $this->request->getFile('file_hasil');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $path = 'uploads/hasil/';

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $name = $file->getRandomName();
            $file->move($path, $name);

            // 🔥 cek apakah sudah ada
            $cek = $this->hasilModel
                ->where('id_ref', $id)
                ->where('jenis_layanan', 'akta_cerai')
                ->first();

            if ($cek) {

                $this->hasilModel->update($cek['id_hasil'], [
                    'file_hasil'     => $name,
                    'nama_file_asli' => $file->getClientName(),
                    'uploaded_by'    => session()->get('id_user'),
                    'jenis_file'     => $file->getClientExtension()
                ]);

            } else {

                $this->hasilModel->save([
                    'jenis_layanan'  => 'akta_cerai',
                    'id_ref'         => $id,
                    'file_hasil'     => $name,
                    'nama_file_asli' => $file->getClientName(),
                    'uploaded_by'    => session()->get('id_user'),
                    'jenis_file'     => $file->getClientExtension(),
                    'created_at'     => date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect()->to('/akta-cerai')->with('success', 'File berhasil diperbarui');
    }
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        $this->pengajuanModel->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan
        ]);

    return redirect()->back()->with('success', 'Pengembalian diajukan');
    }

    public function setujuiPengembalian($id)
    {
        $this->pengajuanModel->update($id, [
            'status' => 'Proses',
            'catatan_penolakan' => null
        ]);

        return redirect()->back()->with('success', 'Pengembalian disetujui');
    }

    public function tolakPengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_penolakan');

        $this->pengajuanModel->update($id, [
            'status' => 'Selesai',
            'catatan_penolakan' => $catatan
        ]);

        return redirect()->back()->with('success', 'Pengembalian ditolak');
    }

// ======================
// SETUJUI
// ======================
public function setujui($id)
{
    $this->pengajuanModel->update($id, [
        'status' => 'Proses',
        'catatan_revisi' => null
    ]);

    return redirect()->to('/akta-cerai')
        ->with('success', 'Pengajuan disetujui');
}

// ======================
// TOLAK / REVISI
// ======================
public function tolak($id)
{
    $catatan = $this->request->getPost('catatan');

    $this->pengajuanModel->update($id, [
        'status' => 'Revisi',
        'catatan_revisi' => $catatan
    ]);

    return redirect()->to('/akta-cerai')
        ->with('success', 'Pengajuan dikembalikan untuk revisi');
}
}