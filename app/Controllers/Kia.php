<?php

namespace App\Controllers;

use App\Models\PengajuanKiaModel;
use App\Models\DesaModel;
use App\Models\KecamatanModel;

class Kia extends BaseController
{
    protected $kia;
    protected $desa;
    protected $kecamatan;

    public function __construct()
    {
        $this->kia = new PengajuanKiaModel();
        $this->desa = new DesaModel();
        $this->kecamatan = new KecamatanModel();
    }

    // =========================
    // LIST DATA
    // =========================
    public function index()
{
    $role = session()->get('role');
    $kode_desa = session()->get('kode_desa');

    $selected_status = $this->request->getGet('status');
    $selected_desa   = $this->request->getGet('desa');
    $selected_kec    = $this->request->getGet('kecamatan');
    $search_nama     = $this->request->getGet('nama');

    // 🔥 JOIN 2 TABEL
    $builder = $this->kia
        ->select('pengajuan_kia.*, desa.nama_desa, kecamatan.nama_kecamatan')
        ->join('desa', 'desa.kode_desa = pengajuan_kia.kode_desa', 'left')
        ->join('kecamatan', 'kecamatan.kode_kecamatan = desa.kode_kecamatan', 'left');

    // FILTER STATUS
    if ($selected_status) {
        $builder->where('pengajuan_kia.status', $selected_status);
    }

    // FILTER DESA
    if ($role == 'admin' && $selected_desa) {
        $builder->where('pengajuan_kia.kode_desa', $selected_desa);
    }

    // FILTER KECAMATAN
    if ($role == 'admin' && $selected_kec) {
        $builder->where('desa.kode_kecamatan', $selected_kec);
    }

    // SEARCH NAMA
    if ($search_nama) {
        $builder->like('pengajuan_kia.nama_anak', $search_nama);
    }

    // KHUSUS DESA LOGIN
    if ($role == 'desa') {
        $builder->where('pengajuan_kia.kode_desa', $kode_desa);
    }

    $data['kia'] = $builder
        ->orderBy("
            CASE 
                WHEN pengajuan_kia.status = 'Pengajuan' THEN 1
                WHEN pengajuan_kia.status = 'Proses' THEN 2
                WHEN pengajuan_kia.status = 'Revisi' THEN 3
                WHEN pengajuan_kia.status = 'Selesai' THEN 4
                WHEN pengajuan_kia.status = 'Pengembalian' THEN 5
            END
        ", "ASC")
        ->orderBy('pengajuan_kia.tanggal_pengajuan', 'DESC')
        ->findAll();

    // 🔥 kirim data dropdown
    $data['desa'] = $this->desa->findAll();
    $data['kecamatan'] = $this->kecamatan->findAll();

    $data['selected_status'] = $selected_status;
    $data['selected_desa']   = $selected_desa;
    $data['selected_kec']    = $selected_kec;
    $data['search_nama']     = $search_nama;

    return view('kia/index', $data);
}

    // =========================
    // FORM
    // =========================
    public function form()
    {
        return view('kia/form');
    }

    // =========================
    // SIMPAN
    // =========================
    public function simpan()
{
    $jenis = $this->request->getPost('jenis_pengajuan');

    // ================= FILE FOTO =================
    $foto = $this->request->getFile('foto_anak');
    $fotoName = null;

    if ($foto && $foto->isValid()) {

        if (!in_array($foto->getExtension(), ['jpg','jpeg'])) {
            return redirect()->back()->withInput()->with('error', 'Foto harus JPG');
        }

        if ($foto->getSize() > 400 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Foto maksimal 400KB');
        }

        $fotoName = $foto->getRandomName();
        $foto->move('uploads/kia/', $fotoName);
    }

    // ================= FILE KK =================
    $kk = $this->request->getFile('file_kk');
    $kkName = null;

    if ($kk && $kk->isValid()) {

        if ($kk->getExtension() != 'pdf') {
            return redirect()->back()->withInput()->with('error', 'KK harus PDF');
        }

        if ($kk->getSize() > 400 * 1024) {
            return redirect()->back()->withInput()->with('error', 'KK maksimal 400KB');
        }

        $kkName = $kk->getRandomName();
        $kk->move('uploads/kia/', $kkName);
    }

    // ================= FILE AKTA =================
    $akta = $this->request->getFile('file_akta');
    $aktaName = null;

    if ($akta && $akta->isValid()) {

        if ($akta->getExtension() != 'pdf') {
            return redirect()->back()->withInput()->with('error', 'Akta harus PDF');
        }

        if ($akta->getSize() > 400 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Akta maksimal 400KB');
        }

        $aktaName = $akta->getRandomName();
        $akta->move('uploads/kia/', $aktaName);
    }

    // ================= FILE F1.02 =================
    $f102Files = $this->request->getFiles();
    $f102Names = [];

    if (isset($f102Files['file_f102'])) {

        foreach ($f102Files['file_f102'] as $file) {

            if ($file->isValid() && !$file->hasMoved()) {

                if (!in_array($file->getExtension(), ['jpg','jpeg'])) {
                    return redirect()->back()->withInput()->with('error', 'F1.02 harus JPG');
                }

                if ($file->getSize() > 400 * 1024) {
                    return redirect()->back()->withInput()->with('error', 'F1.02 maksimal 400KB');
                }

                $newName = $file->getRandomName();
                $file->move('uploads/kia/', $newName);
                $f102Names[] = $newName;
            }
        }
    }

    $f102String = !empty($f102Names) ? implode(',', $f102Names) : null;

    // ================= VALIDASI WAJIB =================
    if ($jenis == 'sudah') {

        if ($kk->getError() == 4) {
            return redirect()->back()->withInput()->with('error', 'File KK wajib diupload');
        }

        if ($akta->getError() == 4) {
            return redirect()->back()->withInput()->with('error', 'File Akta wajib diupload');
        }

        if (!$this->request->getPost('nik_anak')) {
            return redirect()->back()->withInput()->with('error', 'NIK wajib diisi');
        }
    }

    // ================= SIMPAN =================
    $role = session()->get('role');
    $kode_desa = session()->get('kode_desa');

    $data = [
        'jenis_pengajuan' => $jenis,
        'nama_anak'       => $this->request->getPost('nama_anak'),
        'nik_anak'        => $this->request->getPost('nik_anak') ?: null,
        'tanggal_lahir'   => $this->request->getPost('tanggal_lahir'),
        'jenis_kelamin'   => $this->request->getPost('jenis_kelamin'),
        'tempat_lahir'    => $this->request->getPost('tempat_lahir'),

        'nama_ayah'       => $this->request->getPost('nama_ayah'),
        'nik_ayah'        => $this->request->getPost('nik_ayah'),

        'foto_anak'       => $fotoName,
        'file_kk'         => $kkName,
        'file_akta'       => $aktaName,
        'file_f102'       => $f102String,

        'status'          => 'Pengajuan',
        'kode_desa' => ($role == 'desa') ? $kode_desa : 'ADMIN'
    ];

    $this->kia->insert($data);

    return redirect()->to('/kia')->with('success', 'Pengajuan berhasil dikirim');
}
    public function detail($id)
    {
        $data['kia'] = $this->kia->find($id);

        return view('kia/detail', $data);
    }

    public function edit($id)
    {
        $data['kia'] = $this->kia->find($id);

        return view('kia/form_edit', $data);
    }

    public function update($id)
    {
        $dataLama = $this->kia->find($id);

        // ================= FOTO =================
        $foto = $this->request->getFile('foto_anak');
        $fotoName = $dataLama['foto_anak'];

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            $foto->move('uploads/kia/', $fotoName);
        }

        // ================= KK =================
        $kk = $this->request->getFile('file_kk');
        $kkName = $dataLama['file_kk'];

        if ($kk && $kk->isValid() && !$kk->hasMoved()) {
            $kkName = $kk->getRandomName();
            $kk->move('uploads/kia/', $kkName);
        }

        // ================= AKTA =================
        $akta = $this->request->getFile('file_akta');
        $aktaName = $dataLama['file_akta'];

        if ($akta && $akta->isValid() && !$akta->hasMoved()) {
            $aktaName = $akta->getRandomName();
            $akta->move('uploads/kia/', $aktaName);
        }

        // ================= F102 LAMA =================
        $f102Lama = !empty($dataLama['file_f102']) 
            ? explode(',', $dataLama['file_f102']) 
            : [];

        // ================= HAPUS F102 =================
        $hapus = $this->request->getPost('hapus_f102');

        if ($hapus) {
            foreach ($hapus as $h) {
                if (($key = array_search($h, $f102Lama)) !== false) {
                    unset($f102Lama[$key]);
                    @unlink('uploads/kia/'.$h);
                }
            }
        }

        // ================= UPLOAD F102 BARU =================
        $f102Baru = [];
        $files = $this->request->getFiles();

        if (isset($files['file_f102'])) {
            foreach ($files['file_f102'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $name = $file->getRandomName();
                    $file->move('uploads/kia/', $name);
                    $f102Baru[] = $name;
                }
            }
        }

        // ================= GABUNG =================
        $f102Final = array_merge($f102Lama, $f102Baru);

        // ================= UPDATE =================
        $data = [
    'nama_anak'     => $this->request->getPost('nama_anak'),
    'nik_anak'      => $this->request->getPost('nik_anak'),
    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
    'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
    'tempat_lahir'  => $this->request->getPost('tempat_lahir'),

    'nama_ayah'     => $this->request->getPost('nama_ayah'),
    'nik_ayah'      => $this->request->getPost('nik_ayah'),

    'foto_anak'     => $fotoName,
    'file_kk'       => $kkName,
    'file_akta'     => $aktaName,
    'file_f102'     => !empty($f102Final) ? implode(',', $f102Final) : null,

    'status'                => 'Pengajuan',
    'catatan'               => null,
    'catatan_pengembalian'  => null,
    'catatan_penolakan'     => null
];

        $this->kia->update($id, $data);

        return redirect()->to('/kia')->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $data = $this->kia->find($id);

        // hapus file dari folder
        if (!empty($data['foto_anak'])) {
            @unlink('uploads/kia/'.$data['foto_anak']);
        }

        if (!empty($data['file_kk'])) {
            @unlink('uploads/kia/'.$data['file_kk']);
        }

        if (!empty($data['file_akta'])) {
            @unlink('uploads/kia/'.$data['file_akta']);
        }

        if (!empty($data['file_f102'])) {
            $files = explode(',', $data['file_f102']);
            foreach ($files as $f) {
                @unlink('uploads/kia/'.$f);
            }
        }

        $this->kia->delete($id);

        return redirect()->to('/kia')->with('success', 'Data berhasil dihapus');
    }
    
    public function getDesa($kode_kecamatan)
    {
        $data = $this->desa
            ->where('kode_kecamatan', $kode_kecamatan)
            ->findAll();

        return $this->response->setJSON($data);
    }
    public function updateStatus()
    {
        
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $catatan = $this->request->getPost('catatan');

        $data = [
            'status' => $status,
            'catatan' => null,
            'catatan_pengembalian' => null,
            'catatan_penolakan' => null
        ];

        // ================= REVISI =================
        if ($status == 'Revisi') {

            if (!$catatan) {
                return redirect()->back()->with('error', 'Catatan wajib diisi untuk revisi');
            }

            $data['catatan'] = $catatan;
        }
        // ================= PENGEMBALIAN =================
        if ($status == 'Pengembalian') {

            $catatanPengembalian = $this->request->getPost('catatan_pengembalian');

            if (!$catatanPengembalian) {
                return redirect()->back()
                    ->with('error', 'Catatan pengembalian wajib diisi');
            }

            $data['catatan_pengembalian'] = $catatanPengembalian;
        }

        // ================= SELESAI =================
        if ($status == 'Selesai') {

        $file = $this->request->getFile('file_hasil');

        if (!$file || $file->getError() == 4) {
            return redirect()->back()->with('error', 'File hasil wajib diupload');
        }

        if ($file->getExtension() != 'pdf') {
            return redirect()->back()->with('error', 'File harus PDF');
        }

        $name = $file->getRandomName();
        $file->move('uploads/kia/', $name);

        $data['hasil_pdf'] = 'uploads/kia/' . $name;
}

        $this->kia->update($id, $data);

        return redirect()->to('/kia')->with('success', 'Status berhasil diupdate');
    }

    public function editHasil($id)
    {
        $data['kia'] = $this->kia->find($id);
        return view('kia/edit_hasil', $data);
    }

    public function updateHasil($id)
    {
        $file = $this->request->getFile('hasil_pdf');

        if ($file && $file->isValid()) {

            $name = $file->getRandomName();
            $file->move('uploads/kia/', $name);

            $this->kia->update($id, [
                'hasil_pdf' => 'uploads/kia/'.$name
            ]);
        }

        return redirect()->to('/kia')->with('success', 'File berhasil diupdate');
    }
    // =========================
    // AJUKAN PENGEMBALIAN
    // =========================
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        if (!$catatan) {
            return redirect()->back()
                ->with('error', 'Catatan pengembalian wajib diisi');
        }

        $this->kia->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan
        ]);

        return redirect()->to('/kia')
            ->with('success', 'Pengembalian berhasil diajukan');
    }

    // =========================
    // SETUJUI PENGEMBALIAN
    // =========================
    public function setujuiPengembalian($id)
    {
        $this->kia->update($id, [
            'status' => 'Proses',
            'catatan_penolakan' => null
        ]);

        return redirect()->to('/kia')
            ->with('success', 'Pengembalian disetujui');
    }
    // =========================
    // TOLAK PENGEMBALIAN
    // =========================
    public function tolakPengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_penolakan');

        if (!$catatan) {
            return redirect()->back()
                ->with('error', 'Alasan penolakan wajib diisi');
        }

        $this->kia->update($id, [
            'status' => 'Selesai',
            'catatan_penolakan' => $catatan
        ]);

        return redirect()->to('/kia')
            ->with('success', 'Pengembalian ditolak');
    }
    // =========================
    // SETUJUI
    // =========================
    public function setujui($id)
    {
        $this->kia->update($id, [
            'status' => 'Proses',
            'catatan' => null
        ]);

        return redirect()->to('/kia')
            ->with('success', 'Pengajuan berhasil disetujui');
    }

    // =========================
    // TOLAK / REVISI
    // =========================
    public function tolak($id)
    {
        $catatan = $this->request->getPost('catatan');

        if (!$catatan) {
            return redirect()->back()
                ->with('error', 'Catatan revisi wajib diisi');
        }

        $this->kia->update($id, [
            'status' => 'Revisi',
            'catatan' => $catatan
        ]);

        return redirect()->to('/kia')
            ->with('success', 'Pengajuan berhasil direvisi');
    }
}