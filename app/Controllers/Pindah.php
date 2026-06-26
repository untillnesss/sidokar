<?php

namespace App\Controllers;

use App\Models\PengajuanPindahModel;
use App\Models\DokumenPindahModel;
use App\Models\AnggotaPindahModel;

class Pindah extends BaseController
{
    protected $pengajuan;
    protected $dokumen;
    protected $anggota;

    public function __construct()
    {
        $this->pengajuan = new PengajuanPindahModel();
        $this->dokumen   = new DokumenPindahModel();
        $this->anggota   = new AnggotaPindahModel();
    }

   public function index()
    {
        $selected_status = $this->request->getGet('status');
        $search_nama     = $this->request->getGet('nama');
        $selected_kec    = $this->request->getGet('kecamatan');
        $selected_desa   = $this->request->getGet('desa');

        $role = session()->get('role');
        $kode_desa = session()->get('kode_desa');

        $builder = $this->pengajuan
            ->select([
                'pengajuan_pindah.*',
                'desa.nama_desa',
                'desa.kode_kecamatan',
                'users.username'
            ])
            ->join('desa', 'desa.kode_desa = pengajuan_pindah.kode_desa', 'left')
            ->join('users', 'users.id = pengajuan_pindah.id_user', 'left');

        // =========================
        // FILTER ROLE DESA
        // =========================
        if ($role == 'desa') {
            $builder->where('pengajuan_pindah.kode_desa', $kode_desa);
        }

        // =========================
        // FILTER STATUS
        // =========================
        if (!empty($selected_status)) {
            $builder->where('pengajuan_pindah.status', $selected_status);
        }

        // =========================
        // FILTER KECAMATAN (ADMIN)
        // =========================
        if ($role == 'admin' && !empty($selected_kec)) {
            $builder->where('desa.kode_kecamatan', $selected_kec);
        }

        // =========================
        // FILTER DESA (ADMIN)
        // =========================
        if ($role == 'admin' && !empty($selected_desa)) {
            $builder->where('pengajuan_pindah.kode_desa', $selected_desa);
        }

        // =========================
        // FILTER NAMA (SEMUA ROLE)
        // =========================
        if (!empty($search_nama)) {
            $builder->groupStart()
                ->like('pengajuan_pindah.nama_pemohon', $search_nama)
                ->orLike('users.username', $search_nama)
            ->groupEnd();
        }

        $pengajuan = $builder
            ->orderBy("FIELD(pengajuan_pindah.status, 'Pengajuan','Proses','Revisi','Selesai')", '', false)
            ->orderBy('pengajuan_pindah.id_pengajuan', 'DESC')
            ->findAll();

        // =========================
        // LOOP TAMBAHAN (ANGGOTA + FILE)
        // =========================
        foreach ($pengajuan as &$row) {

            // ambil anggota pertama
            $anggota = $this->anggota
                ->where('id_pengajuan', $row['id_pengajuan'])
                ->orderBy('id_anggota', 'ASC')
                ->first();

            $row['nama_pindah'] = $anggota['nama_anggota'] ?? $row['nama_pemohon'];

            // ambil file hasil
            $files = \Config\Database::connect()
                ->table('hasil_layanan')
                ->where('id_ref', $row['id_pengajuan'])
                ->get()
                ->getResultArray();

            $row['file_skpwni'] = null;
            $row['file_kk']     = null;

            foreach ($files as $f) {
                if ($f['jenis_file'] == 'SKPWNI') {
                    $row['file_skpwni'] = $f['file_hasil'];
                }

                if ($f['jenis_file'] == 'KK_BARU') {
                    $row['file_kk'] = $f['file_hasil'];
                }
            }
        }

        // =========================
        // DATA FILTER (UNTUK DROPDOWN)
        // =========================
        $db = \Config\Database::connect();

        $data['kecamatan'] = $db->table('kecamatan')->get()->getResultArray();
        $data['desa']      = $db->table('desa')->get()->getResultArray();

        $data['pengajuan']        = $pengajuan;
        $data['selected_status']  = $selected_status;
        $data['search_nama']      = $search_nama;
        $data['selected_kec']     = $selected_kec;
        $data['selected_desa']    = $selected_desa;

        return view('pindah/index', $data);
    }
    public function tambah()
    {
        return view('pindah/tambah');
    }

    // =========================
    // SIMPAN (TETAP SAMA)
    // =========================
    public function simpan()
    {   
    $namaPemohon = $this->request->getPost('nama_pemohon');

        if (!preg_match("/^[A-Za-z\s]+$/", $namaPemohon)) {
            return redirect()->back()->withInput()->with('error', 'Nama pemohon hanya huruf!');
        }

        $kk        = $this->request->getFile('kk');
        $suratDesa = $this->request->getFile('surat_desa');
        $f102      = $this->request->getFiles()['f102'] ?? [];
        $f106      = $this->request->getFiles()['f106'] ?? [];
        $ktpFiles  = $this->request->getFiles()['ktp_individu'] ?? [];

        // VALIDASI SINGLE
        $singleFiles = [
            'KK' => $kk,
            'Surat Desa' => $suratDesa
        ];

        foreach ($singleFiles as $label => $file) {

            if (!$file || !$file->isValid()) {
                return redirect()->back()->withInput()->with('error', "$label wajib diupload!");
            }

            if (!in_array($file->getExtension(), ['jpg','jpeg'])) {
                return redirect()->back()->withInput()->with('error', "$label harus JPG!");
            }

            if ($file->getSize() > 400 * 1024) {
                return redirect()->back()->withInput()->with('error', "$label maksimal 400KB!");
            }
        }

        // VALIDASI MULTI
        $multiCheck = [
            'F1.02' => $f102,
            'F1.06' => $f106
        ];

        foreach ($multiCheck as $label => $files) {

            if (count($files) > 5) {
                return redirect()->back()->withInput()->with('error', "$label maksimal 5 file!");
            }

            foreach ($files as $file) {

                if (!$file->isValid()) {
                    return redirect()->back()->withInput()->with('error', "$label wajib diupload!");
                }

                if (!in_array($file->getExtension(), ['jpg','jpeg'])) {
                    return redirect()->back()->withInput()->with('error', "$label harus JPG!");
                }

                if ($file->getSize() > 400 * 1024) {
                    return redirect()->back()->withInput()->with('error', "$label maksimal 400KB!");
                }
            }
            // =========================
            // VALIDASI JENIS PINDAH (PINDAH KE SINI)
            // =========================
            $jenisPindah = $this->request->getPost('jenis_pindah');

            if (!$jenisPindah) {
                return redirect()->back()->withInput()->with('error', 'Jenis pindah wajib dipilih!');
            }
        }

        // INSERT PENGAJUAN
        $jenisPindah = $this->request->getPost('jenis_pindah');

        $data = [
            'nama_pemohon'       => $namaPemohon,
            'jenis_pindah'       => $jenisPindah,
            'kategori_pindah'    => $this->request->getPost('kategori_pindah'),
            'jenis_tujuan'       => $this->request->getPost('jenis_tujuan'),
            'alamat_asal'        => $this->request->getPost('alamat_asal'),
            'alamat_tujuan'      => $this->request->getPost('alamat_tujuan'),
            'alasan'             => $this->request->getPost('alasan'),
            'status'             => 'Pengajuan',
            'tanggal_pengajuan'  => date('Y-m-d H:i:s')
        ];

        // ⬇️ hanya isi kalau ada
        if (session()->get('kode_desa')) {
            $data['kode_desa'] = session()->get('kode_desa');
        }

        $id = $this->pengajuan->insert($data);

// lanjutnya jangan jalan dulu
                // SIMPAN ANGGOTA
        $namaAnggota = $this->request->getPost('nama_anggota');
        $nikAnggota  = $this->request->getPost('nik_anggota');

        if ($namaAnggota) {
            foreach ($namaAnggota as $i => $nama) {

                if (!preg_match("/^[A-Za-z\s]+$/", $nama)) {
                    return redirect()->back()->withInput()->with('error', 'Nama anggota hanya huruf!');
                }

                if (!preg_match("/^[0-9]{16}$/", $nikAnggota[$i])) {
                    return redirect()->back()->withInput()->with('error', 'NIK harus 16 digit!');
                }

                $this->anggota->insert([
                    'id_pengajuan' => $id,
                    'nama_anggota' => $nama,
                    'nik_anggota'  => $nikAnggota[$i]
                ]);

                // SIMPAN KTP PER ORANG
                if (isset($ktpFiles[$i]) && $ktpFiles[$i]->isValid()) {

                    $file = $ktpFiles[$i];

                    if (!in_array($file->getExtension(), ['jpg','jpeg'])) {
                        return redirect()->back()->withInput()->with('error', 'KTP harus JPG!');
                    }

                    if ($file->getSize() > 400 * 1024) {
                        return redirect()->back()->withInput()->with('error', 'KTP maksimal 400KB!');
                    }

                    $namaFile = $file->getRandomName();
                    $file->move('uploads/pindah/', $namaFile);

                    $this->dokumen->insert([
                        'id_pengajuan' => $id,
                        'jenis_dokumen' => 'KTP',
                        'nama_file' => $namaFile
                    ]);
                }
            }
        }

        // SIMPAN FILE SINGLE
        foreach ($singleFiles as $label => $file) {
            $nama = $file->getRandomName();
            $file->move('uploads/pindah/', $nama);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => $label,
                'nama_file' => $nama
            ]);
        }

        // SIMPAN MULTI
        foreach ($f102 as $file) {
            $nama = $file->getRandomName();
            $file->move('uploads/pindah/', $nama);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.02',
                'nama_file' => $nama
            ]);
        }

        foreach ($f106 as $file) {
            $nama = $file->getRandomName();
            $file->move('uploads/pindah/', $nama);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.06',
                'nama_file' => $nama
            ]);
        }

        return redirect()->to('/pindah')->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function detail($id)
    {
        $data['pengajuan'] = $this->pengajuan->find($id);
        $data['anggota']   = $this->anggota->where('id_pengajuan', $id)->findAll();
        $data['dokumen']   = $this->dokumen->where('id_pengajuan', $id)->findAll();

        return view('pindah/detail', $data);
    }

    public function edit($id)
{
    $data['pengajuan'] = $this->pengajuan->find($id);

    $role = session()->get('role');

if (
    $role != 'admin' &&
    !in_array($data['pengajuan']['status'], ['Pengajuan', 'Revisi'])
) {
    return redirect()->to('/pindah')->with('error', 'Data tidak bisa diedit!');
}

    // =========================
    // ANGGOTA
    // =========================
    $data['anggota'] = $this->anggota
        ->where('id_pengajuan', $id)
        ->findAll();

    // =========================
    // SEMUA DOKUMEN (INI YANG KURANG)
    // =========================
    $data['dokumen'] = $this->dokumen
        ->where('id_pengajuan', $id)
        ->findAll();

    // =========================
    // KHUSUS F102 & F106
    // =========================
    $data['f102'] = $this->dokumen
        ->where('id_pengajuan', $id)
        ->where('jenis_dokumen', 'F1.02')
        ->findAll();

    $data['f106'] = $this->dokumen
        ->where('id_pengajuan', $id)
        ->where('jenis_dokumen', 'F1.06')
        ->findAll();

    return view('pindah/edit', $data);
}

    public function update($id)
{
    // =========================
    // UPDATE DATA UTAMA
    // =========================
    
    $data = [
        'nama_pemohon'    => $this->request->getPost('nama_pemohon'),
        'jenis_pindah'    => $this->request->getPost('jenis_pindah'),
        'kategori_pindah' => $this->request->getPost('kategori_pindah'),
        'jenis_tujuan'    => $this->request->getPost('jenis_tujuan'),
        'alamat_asal'     => $this->request->getPost('alamat_asal'),
        'alamat_tujuan'   => $this->request->getPost('alamat_tujuan'),
        'alasan'          => $this->request->getPost('alasan'),
     'status' => 'Pengajuan'
        ];
        // =========================
// UPDATE ANGGOTA (INI YANG KURANG)
// =========================
$this->anggota->where('id_pengajuan', $id)->delete();

$namaAnggota = $this->request->getPost('nama_anggota');
$nikAnggota  = $this->request->getPost('nik_anggota');

if ($namaAnggota) {
    foreach ($namaAnggota as $i => $nama) {
        $this->anggota->insert([
            'id_pengajuan' => $id,
            'nama_anggota' => $nama,
            'nik_anggota'  => $nikAnggota[$i]
        ]);
    }
}

    $this->pengajuan->update($id, $data);

    // =========================
    // HANDLE FILE BARU
    // =========================
    $kk        = $this->request->getFile('kk');
    $suratDesa = $this->request->getFile('surat_desa');
    $f102      = $this->request->getFiles()['f102'] ?? [];
    $f106      = $this->request->getFiles()['f106'] ?? [];

    // =========================
    // UPDATE KK
    // =========================
    if ($kk && $kk->isValid()) {

        // hapus lama
        $old = $this->dokumen
            ->where('id_pengajuan', $id)
            ->where('jenis_dokumen', 'KK')
            ->first();

        if ($old && file_exists('uploads/pindah/'.$old['nama_file'])) {
            unlink('uploads/pindah/'.$old['nama_file']);
            $this->dokumen->delete($old['id_dokumen']);
        }

        // upload baru
        $nama = $kk->getRandomName();
        $kk->move('uploads/pindah/', $nama);

        $this->dokumen->insert([
            'id_pengajuan' => $id,
            'jenis_dokumen' => 'KK',
            'nama_file' => $nama
        ]);
    }

    // =========================
    // UPDATE SURAT DESA
    // =========================
    if ($suratDesa && $suratDesa->isValid()) {

        $old = $this->dokumen
            ->where('id_pengajuan', $id)
            ->where('jenis_dokumen', 'Surat Desa')
            ->first();

        if ($old && file_exists('uploads/pindah/'.$old['nama_file'])) {
            unlink('uploads/pindah/'.$old['nama_file']);
            $this->dokumen->delete($old['id_dokumen']);
        }

        $nama = $suratDesa->getRandomName();
        $suratDesa->move('uploads/pindah/', $nama);

        $this->dokumen->insert([
            'id_pengajuan' => $id,
            'jenis_dokumen' => 'Surat Desa',
            'nama_file' => $nama
        ]);
    }

    // =========================
// HAPUS F102
// =========================
$hapusF102 = $this->request->getPost('hapus_f102');

if ($hapusF102) {
    foreach ($hapusF102 as $id_dokumen) {

        $file = $this->dokumen->find($id_dokumen);

        if ($file) {
            if (file_exists('uploads/pindah/' . $file['nama_file'])) {
                unlink('uploads/pindah/' . $file['nama_file']);
            }

            $this->dokumen->delete($id_dokumen);
        }
    }
}

// =========================
// HAPUS F106
// =========================
$hapusF106 = $this->request->getPost('hapus_f106');

if ($hapusF106) {
    foreach ($hapusF106 as $id_dokumen) {

        $file = $this->dokumen->find($id_dokumen);

        if ($file) {
            if (file_exists('uploads/pindah/' . $file['nama_file'])) {
                unlink('uploads/pindah/' . $file['nama_file']);
            }

            $this->dokumen->delete($id_dokumen);
        }
    }
}
    // =========================
    // TAMBAH F102 BARU
    // =========================
    foreach ($f102 as $file) {
        if ($file->isValid()) {
            $nama = $file->getRandomName();
            $file->move('uploads/pindah/', $nama);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.02',
                'nama_file' => $nama
            ]);
        }
    }

    // =========================
    // TAMBAH F106 BARU
    // =========================
    foreach ($f106 as $file) {
        if ($file->isValid()) {
            $nama = $file->getRandomName();
            $file->move('uploads/pindah/', $nama);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.06',
                'nama_file' => $nama
            ]);
        }
        
    }
    
    return redirect()->to('/pindah')->with('success', 'Data berhasil diupdate');
}
public function delete($id)
{
    // =========================
    // HAPUS DOKUMEN + FILE
    // =========================
    $dokumen = $this->dokumen
        ->where('id_pengajuan', $id)
        ->findAll();

    foreach ($dokumen as $d) {
        $path = 'uploads/pindah/' . $d['nama_file'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    $this->dokumen->where('id_pengajuan', $id)->delete();

    // =========================
    // HAPUS ANGGOTA
    // =========================
    $this->anggota->where('id_pengajuan', $id)->delete();

    // =========================
    // HAPUS PENGAJUAN
    // =========================
    $this->pengajuan->delete($id);

    return redirect()->to('/pindah')->with('success', 'Data berhasil dihapus');
}

    public function updateStatus()
{
    $id     = $this->request->getPost('id');
    $status = $this->request->getPost('status');

    $data = [
    'status'     => $status,
    'updated_at' => date('Y-m-d H:i:s')
];

    // =========================
    // REVISI
    // =========================
    if ($status == 'Revisi') {
        $catatan = $this->request->getPost('catatan_revisi');

        if (!$catatan) {
            return redirect()->back()->with('error', 'Catatan wajib diisi!');
        }

        $data['catatan_revisi'] = $catatan;
    }

    // =========================
    // SELESAI (INI YANG KAMU MAU)
    // =========================
    if ($status == 'Selesai') {

        $fileSkpwni = $this->request->getFile('file_skpwni');
        $fileKK     = $this->request->getFile('file_kk_baru');

        if (!$fileSkpwni || !$fileSkpwni->isValid()) {
            return redirect()->back()->with('error', 'SKPWNI wajib diupload!');
        }

        $db = \Config\Database::connect();

        // 🔹 upload SKPWNI
        $namaSkpwni = $fileSkpwni->getRandomName();
        $fileSkpwni->move('uploads/hasil_pindah/', $namaSkpwni);

        $db->table('hasil_layanan')->insert([
            'jenis_layanan' => 'pindah',
            'id_ref'        => $id,
            'jenis_file'    => 'SKPWNI',
            'file_hasil'    => 'uploads/hasil_pindah/' . $namaSkpwni,
            'nama_file_asli'=> $fileSkpwni->getClientName(),
            'uploaded_by'   => session()->get('id')
        ]);

        // 🔹 cek kategori
        $pengajuan = $this->pengajuan->find($id);

        if ($pengajuan['kategori_pindah'] != 'Seluruh Anggota Keluarga') {

            if (!$fileKK || !$fileKK->isValid()) {
                return redirect()->back()->with('error', 'KK baru wajib diupload!');
            }

            $namaKK = $fileKK->getRandomName();
            $fileKK->move('uploads/hasil_pindah/', $namaKK);

            $db->table('hasil_layanan')->insert([
                'jenis_layanan' => 'pindah',
                'id_ref'        => $id,
                'jenis_file'    => 'KK_BARU',
                'file_hasil'    => 'uploads/hasil_pindah/' . $namaKK,
                'nama_file_asli'=> $fileKK->getClientName(),
                'uploaded_by'   => session()->get('id')
            ]);
        }
    }

    // 🔥 update status
    $this->pengajuan->update($id, $data);

    return redirect()->to('/pindah')->with('success', 'Status berhasil diupdate');
}

    public function editHasil($id)
{
    $db = \Config\Database::connect();

    $files = $db->table('hasil_layanan')
        ->where('id_ref', $id)
        ->get()
        ->getResultArray();

    $data['skpwni'] = null;
    $data['kk']     = null;

    foreach ($files as $f) {
        if ($f['jenis_file'] == 'SKPWNI') {
            $data['skpwni'] = $f;
        }
        if ($f['jenis_file'] == 'KK_BARU') {
            $data['kk'] = $f;
        }
    }

    $data['id'] = $id;

    return view('pindah/edit_hasil', $data);
}
    public function updateHasil($id)
{
    $db = \Config\Database::connect();

    $fileSkpwni = $this->request->getFile('file_skpwni');
    $fileKK     = $this->request->getFile('file_kk');

    if ($fileSkpwni && $fileSkpwni->isValid()) {

        $nama = $fileSkpwni->getRandomName();
        $fileSkpwni->move('uploads/hasil_pindah/', $nama);

        $db->table('hasil_layanan')
            ->where('id_ref', $id)
            ->where('jenis_file', 'SKPWNI')
            ->update([
                'file_hasil' => 'uploads/hasil_pindah/'.$nama
            ]);
    }

    if ($fileKK && $fileKK->isValid()) {

        $nama = $fileKK->getRandomName();
        $fileKK->move('uploads/hasil_pindah/', $nama);

        $db->table('hasil_layanan')
            ->where('id_ref', $id)
            ->where('jenis_file', 'KK_BARU')
            ->update([
                'file_hasil' => 'uploads/hasil_pindah/'.$nama
            ]);
    }

    return redirect()->to('/pindah')->with('success', 'File berhasil diupdate');
}
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        $this->pengajuan->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan
        ]);

        return redirect()->to('/pindah')
            ->with('success', 'Pengembalian berhasil diajukan');
    }

    public function setujuiPengembalian($id)
    {
        $this->pengajuan->update($id, [
            'status' => 'Proses'
        ]);

        return redirect()->to('/pindah')
            ->with('success', 'Pengembalian disetujui');
    }

    public function tolakPengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_penolakan');

        $this->pengajuan->update($id, [
            'status' => 'Selesai',
            'catatan_penolakan' => $catatan
        ]);

        return redirect()->to('/pindah')
            ->with('success', 'Pengembalian ditolak');
    }
    // =========================
// SETUJUI
// =========================
public function setujui($id)
{
    $this->pengajuan->update($id, [
        'status' => 'Proses',
        'catatan_revisi' => null
    ]);

    return redirect()->to('/pindah')
        ->with('success', 'Pengajuan berhasil disetujui');
}

// =========================
// TOLAK / REVISI
// =========================
public function tolak($id)
{
    $catatan = $this->request->getPost('catatan_revisi');

    if (!$catatan) {
        return redirect()->back()
            ->with('error', 'Catatan revisi wajib diisi');
    }

    $this->pengajuan->update($id, [
        'status' => 'Revisi',
        'catatan_revisi' => $catatan
    ]);

    return redirect()->to('/pindah')
        ->with('success', 'Pengajuan berhasil direvisi');
}
}