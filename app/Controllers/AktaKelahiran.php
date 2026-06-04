<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PermohonanModel;
use App\Models\HasilLayananModel;
use App\Models\DokumenModel;

class AktaKelahiran extends BaseController
{
    protected $permohonanModel;
    protected $hasilModel;
    public function __construct()
    {
        $this->permohonanModel = new PermohonanModel();
        $this->hasilModel      = new HasilLayananModel();
        $this->dokumenModel    = new DokumenModel();
    }

    // =========================
    // HELPER UPLOAD
    // =========================
 private function uploadDokumen($id, $input, $jenis)
{
    $dokumenModel = new \App\Models\DokumenModel();

    // =========================
    // CEK MULTIPLE (KHUSUS F102)
    // =========================
    if ($input == 'f102') {

        $files = $this->request->getFileMultiple('f102');

        if (!$files) return;

        // 🔥 MAX 5 FILE
        if (count($files) > 5) {
            session()->setFlashdata('error', 'Formulir F1.02 maksimal 5 file');
            return;
        }

        foreach ($files as $file) {

            if (!$file || $file->getError() == 4) continue;

            $mime = $file->getClientMimeType();
            $size = $file->getSize();

            // VALIDASI
            if (!in_array($mime, ['image/jpeg','image/jpg','image/pjpeg'])) {
                session()->setFlashdata('error', 'F1.02 harus JPG');
                return;
            }

            if ($size > 400 * 1024) {
                session()->setFlashdata('error', 'F1.02 maksimal 400KB');
                return;
            }

            if (!$file->hasMoved()) {

                $newName = time().'_'.uniqid().'_'.$file->getClientName();

                $file->move(FCPATH . 'uploads/dokumen', $newName);

                $dokumenModel->insert([
                    'id_permohonan' => $id,
                    'jenis_dokumen' => $jenis,
                    'nama_file'     => $file->getClientName(),
                    'path_file'     => 'uploads/dokumen/'.$newName,
                    'ukuran_file'   => $size,
                    'tipe_file'     => $mime,
                    'uploaded_at'   => date('Y-m-d H:i:s')
                ]);
            }
        }

    } else {
        // =========================
        // SINGLE FILE (LAINNYA)
        // =========================
        $file = $this->request->getFile($input);

        if (!$file || $file->getError() == 4) return;

        $mime = $file->getClientMimeType();
        $size = $file->getSize();

        if (!in_array($mime, ['image/jpeg','image/jpg','image/pjpeg'])) {
            session()->setFlashdata('error', $jenis . ' harus JPG');
            return;
        }

        if ($size > 400 * 1024) {
            session()->setFlashdata('error', $jenis . ' maksimal 400KB');
            return;
        }

        if (!$file->hasMoved()) {

            // 🔥 HAPUS FILE LAMA (KHUSUS SINGLE)
            $lamaList = $dokumenModel
                ->where('id_permohonan', $id)
                ->where('jenis_dokumen', $jenis)
                ->findAll();

            foreach ($lamaList as $lama) {
                if (!empty($lama['path_file']) && file_exists(FCPATH . $lama['path_file'])) {
                    unlink(FCPATH . $lama['path_file']);
                }
            }

            $dokumenModel->where('id_permohonan', $id)
                         ->where('jenis_dokumen', $jenis)
                         ->delete();

            $newName = time().'_'.$file->getClientName();

            $file->move(FCPATH . 'uploads/dokumen', $newName);

            $dokumenModel->insert([
                'id_permohonan' => $id,
                'jenis_dokumen' => $jenis,
                'nama_file'     => $file->getClientName(),
                'path_file'     => 'uploads/dokumen/'.$newName,
                'ukuran_file'   => $size,
                'tipe_file'     => $mime,
                'uploaded_at'   => date('Y-m-d H:i:s')
            ]);
        }
    }
}
    // =========================
    // LIST
    // =========================
public function index()
{
    $role      = session()->get('role');
    $kode_desa = session()->get('kode_desa');

    $status     = $this->request->getGet('status');
    $kecamatan  = $this->request->getGet('kecamatan');
    $desa       = $this->request->getGet('desa');
    $search     = $this->request->getGet('search');

    $builder = $this->permohonanModel
    ->select('permohonan.*, permohonan.catatan_pengembalian, desa.nama_desa, desa.kode_kecamatan, anak.nama_anak, hasil_layanan.file_hasil')
    ->join('desa', 'desa.kode_desa = permohonan.kode_desa', 'left')
    ->join('anak', 'anak.id_anak = permohonan.id_anak', 'left')
    ->join(
        'hasil_layanan',
        'hasil_layanan.id_ref = permohonan.id_permohonan 
         AND hasil_layanan.jenis_layanan = "akta_kelahiran"',
        'left'
    );

    // =========================
    // ROLE DESA
    // =========================
    if ($role === 'desa') {
        $builder->where('permohonan.kode_desa', $kode_desa);
    }

    // =========================
    // FILTER
    // =========================

    if (!empty($status)) {
        $builder->where('permohonan.status', $status);
    }

    if (!empty($kecamatan)) {
        $builder->where('desa.kode_kecamatan', $kecamatan);
    }

    if (!empty($desa)) {
        $builder->where('permohonan.kode_desa', $desa);
    }

    if (!empty($search)) {
    $builder->like('anak.nama_anak', $search);
}

        $data['permohonan'] = $builder
        ->orderBy("
            CASE 
                WHEN permohonan.status = 'Pengajuan' THEN 1
                WHEN permohonan.status = 'Proses' THEN 2
                WHEN permohonan.status = 'Revisi' THEN 3
                WHEN permohonan.status = 'Selesai' THEN 4
                WHEN permohonan.status = 'Pengembalian' THEN 5
                ELSE 6
            END
        ", "ASC")
        ->orderBy('permohonan.updated_at', 'DESC')
        ->paginate(10); // 🔥 INI KUNCINYA
        $data['pager'] = $this->permohonanModel->pager;

    // =========================
    // DATA DROPDOWN
    // =========================
    $data['kecamatan_list'] = (new \App\Models\KecamatanModel())
        ->orderBy('nama_kecamatan', 'ASC')
        ->findAll();

    $data['desa_list'] = (new \App\Models\DesaModel())
        ->orderBy('nama_desa', 'ASC')
        ->findAll();

    // =========================
    // SELECTED VALUE
    // =========================
    $data['selected_status']     = $status ?? '';
    $data['selected_kecamatan']  = $kecamatan ?? '';
    $data['selected_desa']       = $desa ?? '';
    $data['search']              = $search ?? '';

    return view('akta_kelahiran/index', $data);
}
    public function create()
    {
        return view('akta_kelahiran/create');
    }

    // =========================
    // STORE
    // =========================
    public function store()
{
    // =========================
    // VALIDASI WAJIB
    // =========================
    $rules = [

        // SAKSI 1
        'nama_saksi1' => 'required',
        'nik_saksi1'  => 'required|min_length[16]|max_length[16]',

        // SAKSI 2
        'nama_saksi2' => 'required',
        'nik_saksi2'  => 'required|min_length[16]|max_length[16]',

        // FILE SAKSI
        'ktp_saksi1' => 'uploaded[ktp_saksi1]',
        'ktp_saksi2' => 'uploaded[ktp_saksi2]',
    ];

    if (!$this->validate($rules)) {

        return redirect()->back()
            ->withInput()
            ->with('error', 'Semua data saksi wajib diisi');
    }

    // =========================
    // LANJUT SIMPAN
    // =========================
    $kode_desa = session()->get('kode_desa');
    // =========================
    // =========================
// SIMPAN PERMOHONAN DULU
// =========================
$this->permohonanModel->insert([
    'kode_desa' => $kode_desa,
    'status' => 'Pengajuan',
    'created_at' => date('Y-m-d H:i:s')
]);

$id = $this->permohonanModel->getInsertID();

// =========================
// SIMPAN PELAPOR
// =========================
$pelaporModel = new \App\Models\PelaporModel();

$pelaporModel->insert([
    'nama_pelapor'   => $this->request->getPost('nama_pelapor'),
    'nik_pelapor'    => $this->request->getPost('nik_pelapor'),
    'alamat_pelapor' => $this->request->getPost('alamat_pelapor'),
    'id_permohonan'  => $id
]);

// =========================
// SIMPAN ANAK
// =========================
$anakModel = new \App\Models\AnakModel();

$anakModel->insert([
    'nama_anak'     => $this->request->getPost('nama_anak'),
    'jk_anak'       => $this->request->getPost('jk_anak'),
    'anak_ke'       => $this->request->getPost('anak_ke'),
    'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
    'tgl_lahir'     => $this->request->getPost('tgl_lahir'),
    'jam_lahir'     => $this->request->getPost('jam_lahir'),
    'berat_bayi'    => $this->request->getPost('berat_bayi'),
    'panjang_bayi'  => $this->request->getPost('panjang_bayi'),
    'id_permohonan' => $id
]);

$id_anak = $anakModel->getInsertID();

// 🔥 SIMPAN ID ANAK KE PERMOHONAN
$this->permohonanModel->update($id, [
    'id_anak' => $id_anak
]);

        (new \App\Models\OrangtuaModel())->insert([
            'nama_ayah'=>$this->request->getPost('nama_ayah'),
            'nik_ayah'=>$this->request->getPost('nik_ayah'),
            'nama_ibu'=>$this->request->getPost('nama_ibu'),
            'nik_ibu'=>$this->request->getPost('nik_ibu'),
            'id_permohonan'=>$id
        ]);

        $saksiModel = new \App\Models\SaksiModel();

    // Saksi 1
    if ($this->request->getPost('nama_saksi1') && $this->request->getPost('nik_saksi1')) {

        $saksiModel->insert([
            'nama_saksi'     => $this->request->getPost('nama_saksi1'),
            'nik_saksi'      => $this->request->getPost('nik_saksi1'),
            'id_permohonan'  => $id
        ]);
    }

    // Saksi 2
    if ($this->request->getPost('nama_saksi2') && $this->request->getPost('nik_saksi2')) {

        $saksiModel->insert([
            'nama_saksi'     => $this->request->getPost('nama_saksi2'),
            'nik_saksi'      => $this->request->getPost('nik_saksi2'),
            'id_permohonan'  => $id
        ]);
    }

    $this->uploadDokumen($id, 'ktp_ayah', 'KTP Ayah');
    $this->uploadDokumen($id, 'ktp_ibu', 'KTP Ibu');

    // 🔥 TAMBAHAN
    $this->uploadDokumen($id, 'ktp_saksi1', 'KTP Saksi 1');
    $this->uploadDokumen($id, 'ktp_saksi2', 'KTP Saksi 2');

    $this->uploadDokumen($id, 'surat_desa', 'Surat Lahir Desa');
    $this->uploadDokumen($id, 'surat_rs', 'Surat Lahir RS');
    $this->uploadDokumen($id, 'f102', 'Formulir F1.02');

    return redirect()->to('/akta-kelahiran')->with('success','Berhasil');
    }
    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $pelapor  = (new \App\Models\PelaporModel())->where('id_permohonan',$id)->first();
        $anak     = (new \App\Models\AnakModel())->where('id_permohonan',$id)->first();
        $orangtua = (new \App\Models\OrangtuaModel())->where('id_permohonan',$id)->first();
        $saksi = (new \App\Models\SaksiModel())
            ->where('id_permohonan', $id)
            ->orderBy('id_saksi', 'ASC')
            ->findAll();

        // 🔥 RESET INDEX ARRAY
        $saksi = array_values($saksi);

        $s1 = $saksi[0] ?? [];
        $s2 = $saksi[1] ?? [];

        $data['data_edit'] = [
            'id_permohonan'=>$id,
            'nama_pelapor'=>$pelapor['nama_pelapor'] ?? '',
            'nik_pelapor'=>$pelapor['nik_pelapor'] ?? '',
            'alamat_pelapor'=>$pelapor['alamat_pelapor'] ?? '',

            'nama_anak'=>$anak['nama_anak'] ?? '',
            'jk_anak'=>$anak['jk_anak'] ?? '',
            'tempat_lahir'=>$anak['tempat_lahir'] ?? '',
            'tgl_lahir'=>$anak['tgl_lahir'] ?? '',
            'jam_lahir'=>$anak['jam_lahir'] ?? '',
            'anak_ke'=>$anak['anak_ke'] ?? '',
            'berat_bayi'=>$anak['berat_bayi'] ?? '',     
            'panjang_bayi'=>$anak['panjang_bayi'] ?? '', 

            'nama_ayah'=>$orangtua['nama_ayah'] ?? '',
            'nik_ayah'=>$orangtua['nik_ayah'] ?? '',
            'nama_ibu'=>$orangtua['nama_ibu'] ?? '',
            'nik_ibu'=>$orangtua['nik_ibu'] ?? '',

            'nama_saksi1'=>$s1['nama_saksi'] ?? '',
            'nik_saksi1'=>$s1['nik_saksi'] ?? '',
            'nama_saksi2'=>$s2['nama_saksi'] ?? '',
            'nik_saksi2'=>$s2['nik_saksi'] ?? '',
        ];
        // =========================
        // DOKUMEN
        // =========================
        $dokumen = $this->dokumenModel
            ->where('id_permohonan', $id)
            ->findAll();

        $data['dokumen'] = [];

        foreach ($dokumen as $d) {

            $jenis = $d['jenis_dokumen'] ?? '';

            if (!$jenis) {
                continue;
            }

            $data['dokumen'][$jenis][] = $d;
        }
        return view('akta_kelahiran/create', $data);
    }

    // =========================
    // UPDATE (🔥 FIX UTAMA)
    // =========================
public function update($id)
{
    // =========================
    // UPDATE DATA PELAPOR
    // =========================
    if ($this->request->getPost('nama_pelapor') !== null) {

        (new \App\Models\PelaporModel())
            ->where('id_permohonan', $id)
            ->set([
                'nama_pelapor'   => $this->request->getPost('nama_pelapor'),
                'nik_pelapor'    => $this->request->getPost('nik_pelapor'),
                'alamat_pelapor' => $this->request->getPost('alamat_pelapor')
            ])->update();
    }

    // =========================
    // UPDATE DATA ANAK
    // =========================
    if ($this->request->getPost('nama_anak') !== null) {

        (new \App\Models\AnakModel())
            ->where('id_permohonan', $id)
            ->set([
                'nama_anak'     => $this->request->getPost('nama_anak'),
                'jk_anak'       => $this->request->getPost('jk_anak'),
                'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
                'tgl_lahir'     => $this->request->getPost('tgl_lahir'),
                'jam_lahir'     => $this->request->getPost('jam_lahir'),
                'anak_ke'       => $this->request->getPost('anak_ke'),
                'berat_bayi'    => $this->request->getPost('berat_bayi'),
                'panjang_bayi'  => $this->request->getPost('panjang_bayi')
            ])->update();
    }

    // =========================
    // UPDATE DATA ORANG TUA
    // =========================
    if ($this->request->getPost('nama_ayah') !== null) {

        (new \App\Models\OrangtuaModel())
            ->where('id_permohonan', $id)
            ->set([
                'nama_ayah' => $this->request->getPost('nama_ayah'),
                'nik_ayah'  => $this->request->getPost('nik_ayah'),
                'nama_ibu'  => $this->request->getPost('nama_ibu'),
                'nik_ibu'   => $this->request->getPost('nik_ibu')
            ])->update();
    }

    // =========================
    // UPDATE DATA SAKSI
    // =========================
    if ($this->request->getPost('nama_saksi1') !== null) {

        $saksiModel = new \App\Models\SaksiModel();

        $saksi = $saksiModel
            ->where('id_permohonan', $id)
            ->findAll();

        if (count($saksi) >= 2) {

            $saksiModel->update($saksi[0]['id_saksi'], [
                'nama_saksi' => $this->request->getPost('nama_saksi1'),
                'nik_saksi'  => $this->request->getPost('nik_saksi1')
            ]);

            $saksiModel->update($saksi[1]['id_saksi'], [
                'nama_saksi' => $this->request->getPost('nama_saksi2'),
                'nik_saksi'  => $this->request->getPost('nik_saksi2')
            ]);
        }
    }

    // =========================
    // HAPUS DOKUMEN SINGLE
    // =========================
    $hapusDokumen = $this->request->getPost('hapus_dokumen');

    if ($hapusDokumen) {

        foreach ($hapusDokumen as $idDokumen) {

            $dok = $this->dokumenModel
                ->where('id_dokumen', $idDokumen)
                ->first();

            if ($dok) {

                // hapus file fisik
                if (
                    !empty($dok['path_file']) &&
                    file_exists(FCPATH . $dok['path_file'])
                ) {
                    unlink(FCPATH . $dok['path_file']);
                }

                // hapus database
                $this->dokumenModel
                    ->where('id_dokumen', $idDokumen)
                    ->delete();
            }
        }
    }

    // =========================
    // HAPUS DOKUMEN F1.02
    // =========================
    $hapusF102 = $this->request->getPost('hapus_f102');

    if ($hapusF102) {

        foreach ($hapusF102 as $idDokumen) {

            $dok = $this->dokumenModel
                ->where('id_dokumen', $idDokumen)
                ->first();

            if ($dok) {

                if (
                    !empty($dok['path_file']) &&
                    file_exists(FCPATH . $dok['path_file'])
                ) {
                    unlink(FCPATH . $dok['path_file']);
                }

                $this->dokumenModel
                    ->where('id_dokumen', $idDokumen)
                    ->delete();
            }
        }
    }

    // =========================
    // UPLOAD DOKUMEN
    // =========================
    $this->uploadDokumen($id, 'ktp_ayah', 'KTP Ayah');
    $this->uploadDokumen($id, 'ktp_ibu', 'KTP Ibu');
    $this->uploadDokumen($id, 'ktp_saksi1', 'KTP Saksi 1');
    $this->uploadDokumen($id, 'ktp_saksi2', 'KTP Saksi 2');
    $this->uploadDokumen($id, 'surat_desa', 'Surat Lahir Desa');
    $this->uploadDokumen($id, 'surat_rs', 'Surat Lahir RS');
    $this->uploadDokumen($id, 'f102', 'Formulir F1.02');

    // =========================
    // STATUS
    // =========================
    $status  = $this->request->getPost('status');
    $catatan = $this->request->getPost('catatan_revisi');

    $dataUpdate = [];

    // AUTO KEMBALI KE PENGAJUAN
    if (
        $this->request->getPost('nama_anak') !== null &&
        empty($status)
    ) {

        $dataUpdate['status'] = 'Pengajuan';
        $dataUpdate['catatan_revisi'] = null;
    }

    // ADMIN UPDATE STATUS
    if (!empty($status)) {

        $dataUpdate['status'] = $status;

        if ($status == 'Revisi') {

            if (!empty($catatan)) {
                $dataUpdate['catatan_revisi'] = $catatan;
            }

        } else {

            $dataUpdate['catatan_revisi'] = null;
        }
    }

    if (!empty($dataUpdate)) {

        $dataUpdate['updated_at'] = date('Y-m-d H:i:s');

        $this->permohonanModel->update($id, $dataUpdate);
    }

    // =========================
    // REDIRECT
    // =========================
    try {

        return redirect()->to('/akta-kelahiran')
            ->with(
                'success',
                'Data berhasil diperbarui pada ' . date('d-m-Y H:i:s')
            );

    } catch (\Exception $e) {

        return redirect()->back()
            ->with('error', 'Update gagal: ' . $e->getMessage());
    }
}

    // =========================
    // UPLOAD HASIL (ADMIN ONLY)
    // =========================
public function uploadHasil($id)
{
    $file = $this->request->getFile('file_hasil');

    // =========================
    // UPDATE STATUS DULU
    // =========================
    $status  = $this->request->getPost('status');
    $catatan = $this->request->getPost('catatan_revisi');

    if ($status) {

        // =========================
        // JIKA ADMIN SETUJUI
        // =========================
        if ($status == 'Proses') {

            $data = [
                'status' => 'Proses',
                'catatan_revisi' => null
            ];
        }

        // =========================
        // JIKA ADMIN MINTA REVISI
        // =========================
        elseif ($status == 'Revisi') {

            $data = [
                'status' => 'Revisi',
                'catatan_revisi' => $catatan
            ];
        }

        // =========================
        // STATUS LAIN
        // =========================
        else {

            $data = [
                'status' => $status
            ];
        }

        $this->permohonanModel->update($id, $data);
    }

    // =========================
    // JIKA TIDAK ADA FILE
    // =========================
    if (!$file || $file->getError() == 4) {

        return redirect()->to('/akta-kelahiran')
            ->with('success', 'Status berhasil diperbarui');
    }

    // =========================
    // UPLOAD FILE
    // =========================
    $path = FCPATH . 'uploads/hasil/';

    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    $newName = time() . '_' . str_replace(' ', '_', $file->getClientName());

    $file->move($path, $newName);

    // =========================
    // HAPUS FILE LAMA
    // =========================
    $lama = $this->hasilModel
        ->where('id_ref', $id)
        ->where('jenis_layanan', 'akta_kelahiran')
        ->findAll();

    foreach ($lama as $l) {

        $oldPath = $path . $l['file_hasil'];

        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    $this->hasilModel
        ->where('id_ref', $id)
        ->where('jenis_layanan', 'akta_kelahiran')
        ->delete();

    // =========================
    // SIMPAN DATABASE
    // =========================
    $this->hasilModel->insert([
        'jenis_layanan'  => 'akta_kelahiran',
        'id_ref'         => $id,
        'file_hasil'     => $newName,
        'nama_file_asli' => $file->getClientName(),
        'uploaded_by'    => session()->get('user_id') ?? 1,
        'created_at'     => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()
        ->with('success', 'File berhasil diupload');
}


// =========================
// DOWNLOAD HASIL (PEMOHON)
// =========================
public function download($id)
{
    $data = $this->hasilModel
        ->where('id_ref', $id)
        ->where('jenis_layanan', 'akta_kelahiran')
        ->orderBy('id_hasil', 'DESC')
        ->first();

    if (!$data) {
        return redirect()->back()->with('error', 'File belum tersedia');
    }

    $filePath = FCPATH . 'uploads/hasil/' . $data['file_hasil'];

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File tidak ditemukan di server');
    }

    // MIME TYPE OTOMATIS
    $mime = mime_content_type($filePath);

    return $this->response
        ->setHeader('Content-Type', $mime)
        ->setHeader('Content-Disposition', 'inline; filename="' . $data['nama_file_asli'] . '"')
        ->setBody(file_get_contents($filePath));
}

public function detail($id)
{
    $permohonan = (new \App\Models\PermohonanModel())
        ->where('id_permohonan', $id)
        ->first();

    $pelapor = (new \App\Models\PelaporModel())
        ->where('id_permohonan', $id)
        ->first();

    $anak = (new \App\Models\AnakModel())
        ->where('id_permohonan', $id)
        ->first();

    $orangtua = (new \App\Models\OrangtuaModel())
        ->where('id_permohonan', $id)
        ->first();

    $saksi = (new \App\Models\SaksiModel())
        ->where('id_permohonan', $id)
        ->findAll();

    $dok = (new \App\Models\DokumenModel())
        ->where('id_permohonan', $id)
        ->findAll();

    // =========================
    // DOKUMEN
    // =========================
    $data['dokumen'] = [];

    foreach ($dok as $d) {

        $jenis = $d['jenis_dokumen'] ?? '';

        // 🔥 support banyak kemungkinan nama field
        $file =
            $d['nama_file'] ??
            $d['file_dokumen'] ??
            $d['dokumen'] ??
            '';

        if (!$jenis || !$file) {
            continue;
        }

        $d['file_dokumen'] = $file;

        $data['dokumen'][$jenis][] = $d;
    }

    // =========================
    // SAKSI
    // =========================
    $s1 = $saksi[0] ?? [];
    $s2 = $saksi[1] ?? [];

    // =========================
    // DATA VIEW
    // =========================
    $data['pengajuan'] = [

        'id_permohonan' => $id,

        // STATUS
        'status'             => $permohonan['status'] ?? '',
        'catatan_revisi'     => $permohonan['catatan_revisi'] ?? '',

        // =====================
        // DATA ANAK
        // =====================
        'nama_anak'          => $anak['nama_anak'] ?? '',
        'jenis_kelamin'      => $anak['jk_anak'] ?? '',
        'tempat_dilahirkan'  => $anak['tempat_dilahirkan'] ?? '',
        'tempat_kelahiran'   => $anak['tempat_lahir'] ?? '',
        'tanggal_lahir'      => $anak['tgl_lahir'] ?? '',
        'jam_lahir'          => $anak['jam_lahir'] ?? '',
        'jenis_kelahiran'    => $anak['jenis_kelahiran'] ?? '',
        'kelahiran_ke'       => $anak['anak_ke'] ?? '',
        'penolong_kelahiran' => $anak['penolong_kelahiran'] ?? '',
        'berat_bayi'         => $anak['berat_bayi'] ?? '',
        'panjang_bayi'       => $anak['panjang_bayi'] ?? '',

        // =====================
        // DATA IBU
        // =====================
        'nama_ibu'           => $orangtua['nama_ibu'] ?? '',
        'nik_ibu'            => $orangtua['nik_ibu'] ?? '',
        'tanggal_lahir_ibu'  => $orangtua['tgl_lahir_ibu'] ?? '',
        'pekerjaan_ibu'      => $orangtua['pekerjaan_ibu'] ?? '',
        'alamat_ibu'         => $orangtua['alamat_ibu'] ?? '',

        // =====================
        // DATA AYAH
        // =====================
        'nama_ayah'          => $orangtua['nama_ayah'] ?? '',
        'nik_ayah'           => $orangtua['nik_ayah'] ?? '',
        'tanggal_lahir_ayah' => $orangtua['tgl_lahir_ayah'] ?? '',
        'pekerjaan_ayah'     => $orangtua['pekerjaan_ayah'] ?? '',
        'alamat_ayah'        => $orangtua['alamat_ayah'] ?? '',

        // =====================
        // DATA PELAPOR
        // =====================
        'nama_pelapor'       => $pelapor['nama_pelapor'] ?? '',
        'nik_pelapor'        => $pelapor['nik_pelapor'] ?? '',
        'hubungan_pelapor'   => $pelapor['hubungan_pelapor'] ?? '',

        // =====================
        // DATA SAKSI
        // =====================
        // =====================
        // DATA SAKSI
        // =====================
        'nama_saksi_1' => $s1['nama_saksi'] ?? '',
        'nik_saksi_1'  => $s1['nik_saksi'] ?? '',

        'nama_saksi_2' => $s2['nama_saksi'] ?? '',
        'nik_saksi_2'  => $s2['nik_saksi'] ?? '',
    ];

    $data['readonly'] = true;

    return view('akta_kelahiran/detail', $data);
}

public function delete($id)
{
    // =========================
    // HAPUS DATA TERKAIT
    // =========================

    // Hapus dokumen + file fisik
    $dokumenModel = new \App\Models\DokumenModel();
    $dokumen = $dokumenModel->where('id_permohonan', $id)->findAll();

    foreach ($dokumen as $d) {
        if (!empty($d['path_file']) && file_exists(FCPATH . $d['path_file'])) {
            unlink(FCPATH . $d['path_file']);
        }
    }
    $dokumenModel->where('id_permohonan', $id)->delete();

    // Hapus hasil layanan (file hasil)
    $hasil = $this->hasilModel
        ->where('id_ref', $id)
        ->where('jenis_layanan', 'akta_kelahiran')
        ->findAll();

    foreach ($hasil as $h) {
        $file = FCPATH . 'uploads/hasil/' . $h['file_hasil'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    $this->hasilModel
        ->where('id_ref', $id)
        ->where('jenis_layanan', 'akta_kelahiran')
        ->delete();

    // Hapus data relasi
    (new \App\Models\PelaporModel())->where('id_permohonan', $id)->delete();
    (new \App\Models\AnakModel())->where('id_permohonan', $id)->delete();
    (new \App\Models\OrangtuaModel())->where('id_permohonan', $id)->delete();
    (new \App\Models\SaksiModel())->where('id_permohonan', $id)->delete();

    // =========================
    // HAPUS DATA UTAMA
    // =========================
    $this->permohonanModel->delete($id);

    return redirect()->to('/akta-kelahiran')->with('success', 'Data berhasil dihapus');
}

public function pengembalian($id)
{
    $catatan = $this->request->getPost('catatan_pengembalian');

    $this->permohonanModel->update($id, [
        'status' => 'Pengembalian',
        'catatan_pengembalian' => $catatan,
        'catatan_penolakan' => null, // 🔥 reset penolakan lama
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()->with('success','Pengajuan pengembalian berhasil dikirim');
}

public function setujuiPengembalian($id)
{
    $this->permohonanModel->update($id, [
        'status' => 'Proses',
        'catatan_pengembalian' => null,
        'catatan_penolakan' => null,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()->with('success', 'Pengembalian disetujui');
}

public function tolakPengembalian($id)
{
    $this->permohonanModel->update($id, [
        'status' => 'Selesai',
        'catatan_pengembalian' => null, // 🔥 HAPUS catatan pengajuan lama
        'catatan_penolakan' => $this->request->getPost('catatan_penolakan'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()->with('success', 'Pengembalian ditolak');
}
}