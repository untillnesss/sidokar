<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit Pengajuan Akta Cerai</h3>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php
// =========================
// MAPPING DOKUMEN
// =========================
$dok = [];

if(!empty($dokumen)){
    foreach($dokumen as $d){
        $dok[$d['jenis_dokumen']][] = $d;
    }
}

// =========================
// HELPER FILE
// =========================
function isImage($file){
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg','jpeg']);
}

function tampilFile($file){

    $url = base_url('uploads/akta_cerai/'.$file);

    return '
    <img src="'.$url.'" 
        class="img-thumbnail preview-img"
        data-bs-toggle="modal"
        data-bs-target="#modalPreview"
        data-img="'.$url.'"
        style="width:120px;height:120px;object-fit:cover;cursor:pointer;">
    ';
}
?>

<form action="<?= base_url('akta-cerai/update/'.$pengajuan['id_permohonan']) ?>" 
      method="post" 
      enctype="multipart/form-data">

<!-- ================= DATA PEREMPUAN ================= -->
<h5>Data Perempuan</h5>

<input type="text"
       name="nama_perempuan"
       class="form-control mb-2 nama"
       value="<?= $pengajuan['nama_perempuan'] ?>"
       placeholder="Nama Perempuan"
       required>

<input type="text"
       name="nik_perempuan"
       class="form-control mb-3 nik"
       value="<?= $pengajuan['nik_perempuan'] ?>"
       placeholder="NIK Perempuan"
       maxlength="16"
       required>

<!-- ================= DATA LAKI ================= -->
<h5>Data Laki-laki</h5>

<input type="text"
       name="nama_laki"
       class="form-control mb-2 nama"
       value="<?= $pengajuan['nama_laki'] ?>"
       placeholder="Nama Laki-laki"
       required>

<input type="text"
       name="nik_laki"
       class="form-control mb-3 nik"
       value="<?= $pengajuan['nik_laki'] ?>"
       placeholder="NIK Laki-laki"
       maxlength="16"
       required>

<!-- ================= DATA CERAI ================= -->
<h5>Data Perceraian</h5>

<input type="date"
       name="tanggal_cerai"
       class="form-control mb-2"
       value="<?= $pengajuan['tanggal_cerai'] ?>"
       required>

<input type="text"
       name="tempat_cerai"
       class="form-control mb-2"
       value="<?= $pengajuan['tempat_cerai'] ?>"
       placeholder="Tempat Cerai"
       required>

<input type="text"
       name="nomor_akta_perkawinan"
       class="form-control mb-2"
       value="<?= $pengajuan['nomor_akta_perkawinan'] ?>"
       placeholder="Nomor Akta Perkawinan"
       required>

<input type="date"
       name="tanggal_perkawinan"
       class="form-control mb-3"
       value="<?= $pengajuan['tanggal_perkawinan'] ?>"
       required>

<hr>
<h5>Dokumen</h5>

<div class="row">

<?php 
$single = [
    'pn'                => 'Putusan Pengadilan',
    'ktp_perempuan'     => 'KTP Perempuan',
    'ktp_laki'          => 'KTP Laki-laki',
    'kk'                => 'Kartu Keluarga',
    'akta_perkawinan'   => 'Akta Perkawinan'
];
?>

<?php foreach($single as $key => $label): ?>

<div class="col-md-3 mb-3">

    <div class="border p-2 text-center h-100">

        <b><?= $label ?></b><br>

        <?php if(!empty($dok[$key])): ?>

            <?= tampilFile($dok[$key][0]['file_path']) ?><br>

            <input type="checkbox"
                   name="hapus_single[]"
                   value="<?= $dok[$key][0]['id_dokumen'] ?>">

            <small class="text-danger">
                Hapus
            </small>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small>

        <?php endif; ?>

        <input type="file"
               name="<?= $key ?>"
               class="form-control mt-2 file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted d-block mt-1">
            JPG max 400KB
        </small>

    </div>

</div>

<?php endforeach; ?>

<!-- =========================
F2.01
========================= -->
<div class="col-12 mt-3">

    <h6>Form F2.01</h6>

    <div class="row">

    <?php if(!empty($dok['f201'])): ?>

        <?php foreach($dok['f201'] as $d): ?>

        <div class="col-md-2 mb-3">

            <div class="border p-2 text-center">

                <?= tampilFile($d['file_path']) ?><br>

                <input type="checkbox"
                       name="hapus_f201[]"
                       value="<?= $d['id_dokumen'] ?>">

                <small class="text-danger">
                    Hapus
                </small>

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
           name="f201[]"
           multiple
           class="form-control file-validation"
           accept=".jpg,.jpeg,image/jpeg">

    <small class="text-muted">
        JPG max 400KB
    </small>

</div>

</div>

<hr>

<div class="mt-4 d-flex gap-2">

    <button type="submit" class="btn btn-success">
        💾 Simpan
    </button>

    <a href="<?= base_url('akta-cerai') ?>" class="btn btn-secondary">
        ← Kembali
    </a>

</div>

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

<script>

// =========================
// VALIDASI NAMA & NIK
// =========================
document.addEventListener('input', function(e) {

    if (e.target.classList.contains('nama')) {

        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
    }

    if (e.target.classList.contains('nik')) {

        e.target.value = e.target.value
            .replace(/[^0-9]/g, '')
            .slice(0,16);
    }
});

// =========================
// VALIDASI FILE
// =========================
const maxSize = 400 * 1024;

document.querySelectorAll('.file-validation').forEach(input => {

    input.addEventListener('change', function(){

        let files = this.files;

        for(let i = 0; i < files.length; i++){

            let file = files[i];

            // cek format
            if(!['image/jpeg','image/jpg'].includes(file.type)){

                alert("File harus format JPG");

                this.value = "";

                return;
            }

            // cek ukuran
            if(file.size > maxSize){

                alert("Ukuran file maksimal 400KB");

                this.value = "";

                return;
            }
        }
    });
});

// =========================
// PREVIEW MODAL
// =========================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;
    }
});

</script>

<?= $this->endSection() ?>