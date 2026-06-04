<?php

namespace App\Controllers;

use App\Models\PengajuanKkModel;
use App\Models\AnggotaKkModel;
use App\Models\DokumenKkModel;

class Kk extends BaseController
{
    protected $pengajuan;
    protected $anggota;
    protected $dokumen;

    public function __construct()
    {
        $this->pengajuan = new PengajuanKkModel();
        $this->anggota   = new AnggotaKkModel();
        $this->dokumen   = new DokumenKkModel();
    }

    // =========================
    // INDEX + FILTER
    // =========================
    public function index()
    {
        $selected_status = $this->request->getGet('status');
        $search_nama     = $this->request->getGet('nama');
        $filter_desa     = $this->request->getGet('desa');
        $filter_kec      = $this->request->getGet('kecamatan');

        $role = session()->get('role');
        $kode_desa = session()->get('kode_desa');

        $builder = $this->pengajuan
        ->select('pengajuan_kk.*, desa.nama_desa, kecamatan.nama_kecamatan, h.file_hasil')
        ->join('desa', 'desa.kode_desa = pengajuan_kk.kode_desa', 'left')
        ->join('kecamatan', 'kecamatan.kode_kecamatan = desa.kode_kecamatan', 'left')
        ->join('hasil_layanan h', 'h.id_ref = pengajuan_kk.id_pengajuan AND h.jenis_layanan="kk"', 'left')
        ->groupBy('pengajuan_kk.id_pengajuan');

        if ($role == 'desa') {
            $builder->where('pengajuan_kk.kode_desa', $kode_desa);
        }

        if (!empty($search_nama)) {
            $builder->like('pengajuan_kk.nama_kepala', $search_nama);
        }

        if (!empty($selected_status)) {
            $builder->where('pengajuan_kk.status', $selected_status);
        }

        if ($role == 'admin') {
            if (!empty($filter_kec)) {
                $builder->where('kecamatan.kode_kecamatan', $filter_kec);
            }
            if (!empty($filter_desa)) {
                $builder->where('pengajuan_kk.kode_desa', $filter_desa);
            }
        }

        $pengajuan = $builder
            ->orderBy("FIELD(pengajuan_kk.status, 'Pengajuan','Proses','Revisi','Selesai')", '', false)
            ->orderBy('pengajuan_kk.id_pengajuan', 'DESC')
            ->findAll();

        $data = [
            'pengajuan' => $pengajuan,
            'selected_status' => $selected_status,
            'search_nama' => $search_nama,
            'filter_desa' => $filter_desa,
            'filter_kec' => $filter_kec,
            'desa' => (new \App\Models\DesaModel())->findAll(),
            'kecamatan' => (new \App\Models\KecamatanModel())->findAll()
        ];

        return view('kartu-keluarga/index', $data);
    }

    public function tambah()
    {
        return view('kartu-keluarga/tambah');
    }

    // =========================
    // SIMPAN
    // =========================
    public function simpan()
    {
        $jenis = $this->request->getPost('jenis_pengajuan');

        // ================= VALIDASI =================
        $rules = [
            'nama_kepala' => 'required|alpha_space',
            'nik_kepala'  => 'required|numeric|exact_length[16]',
        ];

        // 🔥 hanya wajib upload KK kalau PERUBAHAN
        if ($jenis == 'perubahan') {
            $rules['kk'] = 'uploaded[kk]|max_size[kk,400]|ext_in[kk,jpg,jpeg]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal!');
        }

        $jenis = $this->request->getPost('jenis_pengajuan');

        $data = [
            'jenis_pengajuan' => $jenis,
            'nama_kepala'     => $this->request->getPost('nama_kepala'),
            'nik_kepala'      => $this->request->getPost('nik_kepala'),
            'no_kk'           => $this->request->getPost('no_kk'),
            'alamat'          => $this->request->getPost('alamat'),
            'status'          => 'Pengajuan'
        ];

        if (session()->get('kode_desa')) {
            $data['kode_desa'] = session()->get('kode_desa');
        }

        $id = $this->pengajuan->insert($data);

        // ================= ANGGOTA =================
        $nama  = $this->request->getPost('nama');
        $nik   = $this->request->getPost('nik');
        $shdk  = $this->request->getPost('shdk');

        $field = $this->request->getPost('field_diubah');
        $lama  = $this->request->getPost('nilai_lama');
        $baru  = $this->request->getPost('nilai_baru');
        $dasar = $this->request->getPost('dasar_perubahan');

        $files = $this->request->getFiles()['file_dokumen'] ?? [];

        if ($nama) {
            foreach ($nama as $i => $n) {

                if (!preg_match('/^[A-Za-z\s]+$/', $n)) continue;
                if (!preg_match('/^[0-9]{16}$/', $nik[$i])) continue;

                $fileName = null;

                // 🔥 HANYA UNTUK PERUBAHAN
                if ($jenis == 'perubahan') {
                    if (isset($files[$i]) && $files[$i]->isValid()) {

                        if ($files[$i]->getSize() > 400 * 1024) continue;

                        $fileName = $files[$i]->getRandomName();
                        $files[$i]->move('uploads/kartu-keluarga/', $fileName);
                    }
                }

                $insert = [
                    'id_pengajuan' => $id,
                    'nama' => $n,
                    'nik'  => $nik[$i],
                    'shdk' => $shdk[$i] ?? null,
                ];

                if ($jenis == 'perubahan') {
                    $insert['field_diubah'] = $field[$i] ?? null;
                    $insert['nilai_lama']   = $lama[$i] ?? null;
                    $insert['nilai_baru']   = $baru[$i] ?? null;
                    $insert['dasar_perubahan'] = $dasar[$i] ?? null;
                    $insert['file_dokumen'] = $fileName;
                }

                $this->anggota->insert($insert);
            }
        }

        // ================= DOKUMEN =================

        // KK
        $kk = $this->request->getFile('kk');
        if ($kk && $kk->isValid()) {
            $namaFile = $kk->getRandomName();
            $kk->move('uploads/kartu-keluarga/', $namaFile);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'KK',
                'nama_file' => $namaFile
            ]);
        }

        // F1.02
        $f102 = $this->request->getFiles()['f102'] ?? [];
        foreach ($f102 as $file) {
            if ($file && $file->isValid()) {

                $namaFile = $file->getRandomName();
                $file->move('uploads/kartu-keluarga/', $namaFile);

                $this->dokumen->insert([
                    'id_pengajuan' => $id,
                    'jenis_dokumen' => 'F1.02',
                    'nama_file' => $namaFile
                ]);
            }
        }

        // F1.06
        $f106 = $this->request->getFiles()['f106'] ?? [];
        foreach ($f106 as $file) {
            if ($file && $file->isValid()) {

                $namaFile = $file->getRandomName();
                $file->move('uploads/kartu-keluarga/', $namaFile);

                $this->dokumen->insert([
                    'id_pengajuan' => $id,
                    'jenis_dokumen' => 'F1.06',
                    'nama_file' => $namaFile
                ]);
            }
        }
        return redirect()->to('/kartu-keluarga')->with('success', 'Pengajuan berhasil!');
    }

    // =========================
    // UPDATE
    // =========================
    public function update($id)
{
    // ================= UPDATE DATA UTAMA =================
    $data = [
        'jenis_pengajuan' => $this->request->getPost('jenis_pengajuan'),
        'nama_kepala'     => $this->request->getPost('nama_kepala'),
        'nik_kepala'      => $this->request->getPost('nik_kepala'),
        'no_kk'           => $this->request->getPost('no_kk'),
        'alamat'          => $this->request->getPost('alamat'),
        'status'          => 'Pengajuan'
    ];

    $this->pengajuan->update($id, $data);

    // ================= HAPUS ANGGOTA LAMA =================
    $this->anggota->where('id_pengajuan', $id)->delete();

    $jenis = $this->request->getPost('jenis_pengajuan');

    $nama  = $this->request->getPost('nama');
    $nik   = $this->request->getPost('nik');
    $shdk  = $this->request->getPost('shdk');

    $field = $this->request->getPost('field_diubah');
    $lama  = $this->request->getPost('nilai_lama');
    $baru  = $this->request->getPost('nilai_baru');
    $dasar = $this->request->getPost('dasar_perubahan');

    $files = $this->request->getFiles()['file_dokumen'] ?? [];

    // ================= INSERT ANGGOTA =================
    if ($nama) {
        foreach ($nama as $i => $n) {

            $fileName = null;

            // upload file perubahan (kalau ada)
            if ($jenis == 'perubahan') {
                if (isset($files[$i]) && $files[$i]->isValid()) {

                    if ($files[$i]->getSize() <= 400 * 1024) {
                        $fileName = $files[$i]->getRandomName();
                        $files[$i]->move('uploads/kartu-keluarga/', $fileName);
                    }
                }
            }

            $insert = [
                'id_pengajuan' => $id,
                'nama' => $n,
                'nik'  => $nik[$i] ?? null,
                'shdk' => $shdk[$i] ?? null,
            ];

            // 🔥 BAGIAN PENTING (PERUBAHAN)
            if ($jenis == 'perubahan') {
                $insert['field_diubah']     = $field[$i] ?? null;
                $insert['nilai_lama']       = $lama[$i] ?? null;
                $insert['nilai_baru']       = $baru[$i] ?? null;
                $insert['dasar_perubahan']  = $dasar[$i] ?? null;
                $insert['file_dokumen']     = $fileName;
            }

            $this->anggota->insert($insert);
        }
    }

    // ================= HAPUS DOKUMEN =================
    $hapus = $this->request->getPost('hapus_dokumen');

    if (!empty($hapus)) {
        foreach ($hapus as $id_dok) {

            $doc = $this->dokumen->find($id_dok);

            if ($doc) {
                $path = 'uploads/kartu-keluarga/' . $doc['nama_file'];

                if (file_exists($path)) {
                    unlink($path);
                }

                $this->dokumen->delete($id_dok);
            }
        }
    }

    // ================= UPDATE KK =================
    $kk = $this->request->getFile('kk');

    if ($kk && $kk->isValid()) {

        // hapus lama
        $kkLama = $this->dokumen
            ->where('id_pengajuan', $id)
            ->where('jenis_dokumen', 'KK')
            ->findAll();

        foreach ($kkLama as $d) {
            $path = 'uploads/kartu-keluarga/' . $d['nama_file'];

            if (file_exists($path)) {
                unlink($path);
            }

            $this->dokumen->delete($d['id_dokumen']);
        }

        // upload baru
        $namaFile = $kk->getRandomName();
        $kk->move('uploads/kartu-keluarga/', $namaFile);

        $this->dokumen->insert([
            'id_pengajuan' => $id,
            'jenis_dokumen' => 'KK',
            'nama_file' => $namaFile
        ]);
    }

    // ================= TAMBAH F1.02 =================
    $f102 = $this->request->getFiles()['f102'] ?? [];

    foreach ($f102 as $file) {
        if ($file && $file->isValid()) {

            $namaFile = $file->getRandomName();
            $file->move('uploads/kartu-keluarga/', $namaFile);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.02',
                'nama_file' => $namaFile
            ]);
        }
    }

    // ================= TAMBAH F1.06 =================
    $f106 = $this->request->getFiles()['f106'] ?? [];

    foreach ($f106 as $file) {
        if ($file && $file->isValid()) {

            $namaFile = $file->getRandomName();
            $file->move('uploads/kartu-keluarga/', $namaFile);

            $this->dokumen->insert([
                'id_pengajuan' => $id,
                'jenis_dokumen' => 'F1.06',
                'nama_file' => $namaFile
            ]);
        }
    }

    return redirect()->to('/kartu-keluarga')
        ->with('success', 'Data berhasil diupdate');
}

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        $anggota = $this->anggota->where('id_pengajuan', $id)->findAll();
        foreach ($anggota as $a) {
            if ($a['file_dokumen'] && file_exists('uploads/kk/'.$a['file_dokumen'])) {
                unlink('uploads/kk/'.$a['file_dokumen']);
            }
        }

        $dokumen = $this->dokumen->where('id_pengajuan', $id)->findAll();
        foreach ($dokumen as $d) {
            if (file_exists('uploads/kk/'.$d['nama_file'])) {
                unlink('uploads/kk/'.$d['nama_file']);
            }
        }

        $this->anggota->where('id_pengajuan', $id)->delete();
        $this->dokumen->where('id_pengajuan', $id)->delete();
        $this->pengajuan->delete($id);

        return redirect()->to('/kartu-keluarga')->with('success', 'Data berhasil dihapus');
    }

    // =========================
    // UPDATE STATUS
    // =========================
    public function updateStatus()
{
    $id     = $this->request->getPost('id');
    $status = $this->request->getPost('status');

    $data = [
    'status' => $status,
    'updated_at' => date('Y-m-d H:i:s')
];

    // ================= REVISI =================
    if ($status == 'Revisi') {
        $catatan = $this->request->getPost('catatan_revisi');

        if (!$catatan) {
            return redirect()->back()->with('error', 'Catatan wajib diisi!');
        }

        $data['catatan_revisi'] = $catatan;
    }

    // ================= SELESAI =================
    if ($status == 'Selesai') {

        $file = $this->request->getFile('file_kk');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File KK wajib diupload!');
        }

        $namaAsli = $file->getClientName();
        $namaBaru = $file->getRandomName();
        $ext      = $file->getExtension();

        $file->move('uploads/hasil', $namaBaru);

        $db = \Config\Database::connect();

        $existing = $db->table('hasil_layanan')
            ->where('id_ref', $id)
            ->where('jenis_layanan', 'kk')
            ->get()
            ->getRow();

        if ($existing) {
            $db->table('hasil_layanan')
                ->where('id_ref', $id)
                ->where('jenis_layanan', 'kk')
                ->update([
                    'file_hasil' => $namaBaru,
                    'nama_file_asli' => $namaAsli,
                    'jenis_file' => $ext,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            $db->table('hasil_layanan')->insert([
                'jenis_layanan' => 'kk',
                'id_ref' => $id,
                'file_hasil' => $namaBaru,
                'nama_file_asli' => $namaAsli,
                'jenis_file' => $ext,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    $this->pengajuan->update($id, $data);

    return redirect()->to('/kartu-keluarga')->with('success', 'Status berhasil diupdate');
}
    public function detail($id)
    {
        $pengajuan = $this->pengajuan
            ->select('pengajuan_kk.*')
            ->find($id);

        if (!$pengajuan) {
            return redirect()->to('/kartu-keluarga')
                ->with('error', 'Data tidak ditemukan');
        }

        // ================= ANGGOTA =================
        $anggota = $this->anggota
            ->where('id_pengajuan', $id)
            ->findAll();

        // ================= DOKUMEN =================
        $dokumen = $this->dokumen
            ->where('id_pengajuan', $id)
            ->findAll();

        // group dokumen biar enak di view (seperti kematian)
        $dok = [];
        foreach ($dokumen as $d) {
            $dok[$d['jenis_dokumen']][] = $d;
        }

        // helper mapping biar view lebih bersih
        $dokSingle = ['KK', 'F1.02', 'F1.06'];

        return view('kartu-keluarga/detail', [
            'pengajuan' => $pengajuan,
            'anggota'   => $anggota,
            'dokumen'   => $dok,
            'dokSingle' => $dokSingle
        ]);
    }
    public function edit($id)
    {
        $pengajuan = $this->pengajuan->find($id);

        if (!$pengajuan) {
            return redirect()->to('/kartu-keluarga')
                ->with('error', 'Data tidak ditemukan');
        }

        $anggota = $this->anggota
            ->where('id_pengajuan', $id)
            ->findAll();

        // 🔥 WAJIB
        $dokumen = $this->dokumen
            ->where('id_pengajuan', $id)
            ->findAll();

        return view('kartu-keluarga/edit', [
            'pengajuan' => $pengajuan,
            'anggota'   => $anggota,
            'dokumen'   => $dokumen
        ]);
    }
    public function uploadHasil($id_pengajuan)
{
    $file = $this->request->getFile('file_hasil');

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid');
    }

    $namaAsli = $file->getClientName();
    $namaBaru = $file->getRandomName();
    $ext      = $file->getExtension();

    $file->move('uploads/hasil', $namaBaru);

    $db = \Config\Database::connect();

    // 🔥 CEK DULU SUDAH ADA BELUM
    $existing = $db->table('hasil_layanan')
        ->where('id_ref', $id_pengajuan)
        ->where('jenis_layanan', 'kk')
        ->get()
        ->getRow();

    if ($existing) {
        // ✅ UPDATE
        $db->table('hasil_layanan')
            ->where('id_ref', $id_pengajuan)
            ->where('jenis_layanan', 'kk')
            ->update([
                'file_hasil' => $namaBaru,
                'nama_file_asli' => $namaAsli,
                'jenis_file' => $ext,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    } else {
        // ✅ INSERT
        $db->table('hasil_layanan')->insert([
            'jenis_layanan'   => 'kk',
            'id_ref'          => $id_pengajuan,
            'file_hasil'      => $namaBaru,
            'nama_file_asli'  => $namaAsli,
            'jenis_file'      => $ext,
            'created_at'      => date('Y-m-d H:i:s')
        ]);
    }

    return redirect()->back()->with('success', 'File berhasil disimpan');
}
    public function updateHasil($id)
{
    $file = $this->request->getFile('file_hasil');

    if ($file && $file->isValid()) {

        // ambil data SEBELUM move
        $namaAsli = $file->getClientName();
        $ext      = $file->getClientExtension();
        $namaBaru = $file->getRandomName();

        // pindahkan file
        $file->move('uploads/hasil', $namaBaru);

        $db = \Config\Database::connect();

        $db->table('hasil_layanan')
            ->where('id_ref', $id)
            ->where('jenis_layanan', 'kk')
            ->update([
                'file_hasil'      => $namaBaru,
                'nama_file_asli'  => $namaAsli,
                'jenis_file'      => $ext,
                'uploaded_by'     => session()->get('id_user'),
                'created_at'      => date('Y-m-d H:i:s')
            ]);
    }

    return redirect()->back()->with('success', 'File berhasil diperbarui');
}
    public function editHasil($id)
    {
        $pengajuan = $this->pengajuan->find($id);

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        return view('kartu-keluarga/edit_hasil', [
            'pengajuan' => $pengajuan
        ]);
    }
    public function pengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_pengembalian');

        $this->pengajuan->update($id, [
            'status' => 'Pengembalian',
            'catatan_pengembalian' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian diajukan');
    }

    public function setujuiPengembalian($id)
    {
        $this->pengajuan->update($id, [
            'status' => 'Proses',
            'catatan_penolakan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian disetujui');
    }

    public function tolakPengembalian($id)
    {
        $catatan = $this->request->getPost('catatan_penolakan');

        $this->pengajuan->update($id, [
            'status' => 'Selesai',
            'catatan_penolakan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pengembalian ditolak');
    }
    public function tolak($id)
    {
        $catatan = $this->request->getPost('catatan_revisi');

        if (!$catatan) {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi');
        }

        $this->pengajuan->update($id, [
            'status' => 'Revisi',
            'catatan_revisi' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/kartu-keluarga')
            ->with('success', 'Pengajuan ditolak (Revisi)');
    }
    public function setujui($id)
    {
        $this->pengajuan->update($id, [
            'status' => 'Proses',
            'catatan_revisi' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/kartu-keluarga')
            ->with('success', 'Pengajuan disetujui');
    }
}