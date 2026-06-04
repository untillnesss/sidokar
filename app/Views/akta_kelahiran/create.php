<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<?php
$data = $data_edit ?? [];
$dokumen = $dokumen ?? [];
?>

<h3 class="mb-4">
<?= isset($data_edit) ? 'Edit Pengajuan Akta Kelahiran' : 'Buat Pengajuan Akta Kelahiran' ?>
</h3>

<form action="<?= isset($data_edit) 
    ? base_url('akta-kelahiran/update/'.$data_edit['id_permohonan']) 
    : base_url('akta-kelahiran/store') ?>" 
method="post" enctype="multipart/form-data">

<!-- ================= DATA PELAPOR ================= -->
<h5>Data Pelapor</h5>
<div class="row">

    <div class="col-md-4 mb-3">
        <input type="text" name="nama_pelapor" class="form-control"
        placeholder="Nama Pelapor"
        value="<?= old('nama_pelapor') ?: ($data['nama_pelapor'] ?? '') ?>"
        required
        pattern="[A-Za-z\s]+"
        oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="nik_pelapor" class="form-control"
        placeholder="NIK Pelapor"
        value="<?= old('nik_pelapor') ?: ($data['nik_pelapor'] ?? '') ?>"
        required
        maxlength="16"
        pattern="\d{16}"
        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="alamat_pelapor" class="form-control"
        placeholder="Alamat"
        value="<?= old('alamat_pelapor', $data['alamat_pelapor'] ?? '') ?>" required>
    </div>

</div>

<hr>

<!-- ================= DATA ANAK ================= -->
<h5>Data Anak</h5>
<div class="row">

    <div class="col-md-4 mb-3">
        <input type="text" name="nama_anak" class="form-control"
        placeholder="Nama Anak"
        value="<?= old('nama_anak', $data['nama_anak'] ?? '') ?>"
        required
        pattern="[A-Za-z\s]+"
        oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <select name="jk_anak" class="form-control" required>
        <option value="">-- Pilih --</option>
        <option value="laki-laki" <?= old('jk_anak', $data['jk_anak'] ?? '')=='laki-laki'?'selected':'' ?>>Laki-laki</option>
        <option value="perempuan" <?= old('jk_anak', $data['jk_anak'] ?? '')=='perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <input type="number" name="anak_ke" class="form-control"
        placeholder="Anak Ke"
        value="<?= old('anak_ke', $data['anak_ke'] ?? '') ?>">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="tempat_lahir" class="form-control"
        placeholder="Tempat Lahir"
        value="<?= old('tempat_lahir', $data['tempat_lahir'] ?? '') ?>" required>
    </div>

    <div class="col-md-4 mb-3">
        <input type="date" name="tgl_lahir" class="form-control"
        value="<?= old('tgl_lahir', $data['tgl_lahir'] ?? '') ?>" required>
    </div>

    <div class="col-md-4 mb-3">
        <input type="time" name="jam_lahir" class="form-control"
        value="<?= old('jam_lahir', $data['jam_lahir'] ?? '') ?>" required>
    </div>

    <div class="col-md-4 mb-3">
        <input type="number" step="0.01" name="berat_bayi" class="form-control"
        placeholder="Berat Bayi (kg)"
        value="<?= old('berat_bayi', $data['berat_bayi'] ?? '') ?>">
    </div>

    <div class="col-md-4 mb-3">
        <input type="number" step="0.1" name="panjang_bayi" class="form-control"
        placeholder="Panjang Bayi (cm)"
        value="<?= old('panjang_bayi', $data['panjang_bayi'] ?? '') ?>">
    </div>

</div>

<hr>

<!-- ================= ORANG TUA ================= -->
<h5>Data Orang Tua</h5>
<div class="row">

    <div class="col-md-4 mb-3">
        <input type="text" name="nama_ayah" class="form-control"
        placeholder="Nama Ayah"
        value="<?= old('nama_ayah') ?: ($data['nama_ayah'] ?? '') ?>"
        required
        pattern="[A-Za-z\s]+"
        oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="nik_ayah" class="form-control"
        placeholder="NIK Ayah"
        value="<?= old('nik_ayah') ?: ($data['nik_ayah'] ?? '') ?>"
        required
        maxlength="16"
        pattern="\d{16}"
        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="nama_ibu" class="form-control"
        placeholder="Nama Ibu"
        value="<?= old('nama_ibu') ?: ($data['nama_ibu'] ?? '') ?>"
        required
        pattern="[A-Za-z\s]+"
        oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text" name="nik_ibu" class="form-control"
        placeholder="NIK Ibu"
        value="<?= old('nik_ibu') ?: ($data['nik_ibu'] ?? '') ?>"
        required
        maxlength="16"
        pattern="\d{16}"
        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
    </div>
</div>

<hr>

<!-- ================= SAKSI ================= -->
<h5>Data Saksi</h5>

<!-- ================= SAKSI 1 ================= -->
<div class="row">

    <div class="col-md-4 mb-3">
        <input type="text"
               name="nama_saksi1"
               class="form-control"
               placeholder="Nama Saksi 1"
               value="<?= old('nama_saksi1', $data['nama_saksi1'] ?? '') ?>"
               required
               pattern="[A-Za-z\s]+"
               oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text"
               name="nik_saksi1"
               class="form-control"
               placeholder="NIK Saksi 1"
               maxlength="16"
               value="<?= old('nik_saksi1', $data['nik_saksi1'] ?? '') ?>"
               required
               pattern="\d{16}"
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
    </div>

</div>

<!-- ================= SAKSI 2 ================= -->
<div class="row">

    <div class="col-md-4 mb-3">
        <input type="text"
               name="nama_saksi2"
               class="form-control"
               placeholder="Nama Saksi 2"
               value="<?= old('nama_saksi2', $data['nama_saksi2'] ?? '') ?>"
               required
               pattern="[A-Za-z\s]+"
               oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')">
    </div>

    <div class="col-md-4 mb-3">
        <input type="text"
               name="nik_saksi2"
               class="form-control"
               placeholder="NIK Saksi 2"
               maxlength="16"
               value="<?= old('nik_saksi2', $data['nik_saksi2'] ?? '') ?>"
               required
               pattern="\d{16}"
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">
    </div>

</div>

<hr>
<hr>
<hr>
<hr>
<h5>Dokumen Pendukung</h5>

<?php
// =========================
// NORMALISASI DOKUMEN
// =========================
$dok = [];

if (!empty($dokumen)) {

    // 🔥 kalau dari controller sudah berbentuk grouped
    if (array_keys($dokumen) !== range(0, count($dokumen) - 1)) {

        $dok = $dokumen;

    } else {

        // 🔥 kalau masih flat array
        foreach ($dokumen as $d) {

            $jenis = $d['jenis_dokumen'] ?? null;

            $file =
                $d['path_file']
                ?? $d['file_dokumen']
                ?? $d['nama_file']
                ?? null;

            if (!$jenis || !$file) {
                continue;
            }

            $d['path_file'] = $file;

            $dok[$jenis][] = $d;
        }
    }
}

// =========================
// PREVIEW FILE
// =========================
function tampilFile($file)
{
    $url = base_url($file);

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {

        return '
        <img src="' . $url . '" 
            class="img-thumbnail preview-img"
            data-bs-toggle="modal"
            data-bs-target="#modalPreview"
            data-img="' . $url . '"
            style="width:120px;height:120px;object-fit:cover;cursor:pointer;">
        ';
    }

    return '
    <a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
        Lihat File
    </a>';
}
?>

<div class="row">

<?php
$single = [
    'KTP Ayah'         => ['name'=>'ktp_ayah',   'label'=>'KTP Ayah'],
    'KTP Ibu'          => ['name'=>'ktp_ibu',    'label'=>'KTP Ibu'],
    'KTP Saksi 1'      => ['name'=>'ktp_saksi1', 'label'=>'KTP Saksi 1'],
    'KTP Saksi 2'      => ['name'=>'ktp_saksi2', 'label'=>'KTP Saksi 2'],
    'Surat Lahir Desa' => ['name'=>'surat_desa', 'label'=>'Surat Lahir Desa'],
    'Surat Lahir RS'   => ['name'=>'surat_rs',   'label'=>'Surat Lahir RS']
];
?>

<?php foreach($single as $jenis => $item): ?>

<div class="col-md-3 mb-3">

    <div class="border p-2 text-center h-100">

        <b><?= $item['label'] ?></b><br>

        <?php if(!empty($dok[$jenis])): ?>

            <?= tampilFile($dok[$jenis][0]['path_file']) ?><br><br>

            <?php if(isset($data_edit)): ?>
                <input type="checkbox"
                       name="hapus_dokumen[]"
                       value="<?= $dok[$jenis][0]['id_dokumen'] ?>">

                <small class="text-danger">
                    Hapus
                </small>
                <br><br>
            <?php endif; ?>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small><br><br>

        <?php endif; ?>

        <input type="file"
               name="<?= $item['name'] ?>"
               class="form-control mt-2 file-validation"
               accept=".jpg,.jpeg,.png">

        <small class="text-muted d-block mt-1">
            JPG / PNG max 400KB
        </small>

    </div>

</div>

<?php endforeach; ?>

<!-- =========================
F1.02
========================= -->
<div class="col-12 mt-3">

    <h6>Formulir F1.02</h6>

    <div class="row">

    <?php if(!empty($dok['Formulir F1.02'])): ?>

        <?php foreach($dok['Formulir F1.02'] as $f): ?>

        <div class="col-md-2 mb-3">

            <div class="border p-2 text-center">

                <?= tampilFile($f['path_file']) ?><br><br>

                <?php if(isset($data_edit)): ?>

                    <input type="checkbox"
                           name="hapus_f102[]"
                           value="<?= $f['id_dokumen'] ?>">

                    <small class="text-danger">
                        Hapus
                    </small>

                <?php endif; ?>

            </div>

        </div>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

    <input type="file"
           name="f102[]"
           multiple
           class="form-control mt-2 file-validation"
           accept=".jpg,.jpeg,.png">

    <small class="text-muted">
        JPG / PNG max 400KB
    </small>

</div>

</div><button type="submit" class="btn btn-primary">
<?= isset($data_edit) ? 'Update' : 'Simpan' ?>
</button>

<a href="<?= base_url('akta-kelahiran') ?>" class="btn btn-secondary">
Kembali
</a>

</form>
<!-- =========================
MODAL PREVIEW
========================= -->
<div class="modal fade" id="modalPreview" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Preview Dokumen
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage"
                     src=""
                     class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>