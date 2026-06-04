<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit Pengajuan Akta Nikah</h3>

<?php if (session()->getFlashdata('error')): ?>
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

        if(isset($d['jenis_dokumen'])){
            $dok[$d['jenis_dokumen']][] = $d;
        }
    }
}

// =========================
// HELPER PREVIEW
// =========================
function tampilFile($file){

    $url = base_url('uploads/akta_nikah/'.$file);

    return '
    <img src="'.$url.'"
         class="img-thumbnail preview-img"
         data-img="'.$url.'"
         style="
            width:120px;
            height:120px;
            object-fit:cover;
            cursor:pointer;
         ">
    ';
}
?>

<form action="<?= base_url('akta-nikah/update/'.$pengajuan['id_permohonan']) ?>" 
      method="post" 
      enctype="multipart/form-data">

<!-- ================= DATA LAKI ================= -->
<h5>Data Laki-laki</h5>

<input type="text"
       name="nama_laki_laki"
       value="<?= $pengajuan['nama_laki_laki'] ?>"
       class="form-control mb-2 nama"
       placeholder="Nama Laki-laki"
       required>

<input type="text"
       name="nik_laki_laki"
       value="<?= $pengajuan['nik_laki_laki'] ?>"
       class="form-control mb-3 nik"
       placeholder="NIK Laki-laki"
       maxlength="16"
       required>

<!-- ================= DATA PEREMPUAN ================= -->
<h5>Data Perempuan</h5>

<input type="text"
       name="nama_perempuan"
       value="<?= $pengajuan['nama_perempuan'] ?>"
       class="form-control mb-2 nama"
       placeholder="Nama Perempuan"
       required>

<input type="text"
       name="nik_perempuan"
       value="<?= $pengajuan['nik_perempuan'] ?>"
       class="form-control mb-3 nik"
       placeholder="NIK Perempuan"
       maxlength="16"
       required>

<!-- ================= DATA PERNIKAHAN ================= -->
<h5>Data Pernikahan</h5>

<input type="text"
       name="agama"
       value="<?= $pengajuan['agama'] ?>"
       class="form-control mb-2"
       placeholder="Agama"
       required>

<select name="tempat_pernikahan"
        class="form-control mb-2"
        required>

    <option value="">-- Pilih --</option>

    <option value="Gereja"
        <?= ($pengajuan['tempat_pernikahan']=='Gereja')?'selected':'' ?>>
        Gereja
    </option>

    <option value="Pura"
        <?= ($pengajuan['tempat_pernikahan']=='Pura')?'selected':'' ?>>
        Pura
    </option>

    <option value="Vihara"
        <?= ($pengajuan['tempat_pernikahan']=='Vihara')?'selected':'' ?>>
        Vihara
    </option>

    <option value="Klenteng"
        <?= ($pengajuan['tempat_pernikahan']=='Klenteng')?'selected':'' ?>>
        Klenteng
    </option>

    <option value="Lainnya"
        <?= ($pengajuan['tempat_pernikahan']=='Lainnya')?'selected':'' ?>>
        Lainnya
    </option>

</select>

<input type="date"
       name="tanggal_perkawinan"
       value="<?= $pengajuan['tanggal_perkawinan'] ?>"
       class="form-control mb-2"
       required>

<input type="text"
       name="nama_pemuka_agama"
       value="<?= $pengajuan['nama_pemuka_agama'] ?>"
       class="form-control mb-3 nama"
       placeholder="Nama Pemuka Agama"
       required>

<!-- ================= SAKSI ================= -->
<h5>Data Saksi</h5>

<input type="text"
       name="nama_saksi_1"
       value="<?= $pengajuan['nama_saksi_1'] ?>"
       class="form-control mb-2 nama"
       placeholder="Nama Saksi 1"
       required>

<input type="text"
       name="nama_saksi_2"
       value="<?= $pengajuan['nama_saksi_2'] ?>"
       class="form-control mb-3 nama"
       placeholder="Nama Saksi 2"
       required>

<hr>

<!-- ================= DOKUMEN ================= -->
<h5>Dokumen</h5>

<div class="row">

<?php 
$single = [
    'KK',
    'KTP Laki-laki',
    'KTP Perempuan',
    'Akta Lahir Laki',
    'Akta Lahir Perempuan',
    'Surat Desa',
    'KTP Saksi',
    'Pas Foto'
];
?>

<?php foreach($single as $j): ?>

<div class="col-md-3 mb-3">

    <div class="border p-2 text-center h-100">

        <b><?= $j ?></b><br>

        <?php if(!empty($dok[$j])): ?>

            <?= tampilFile($dok[$j][0]['file']) ?><br>

            <input type="checkbox"
                   name="hapus_single[]"
                   value="<?= $dok[$j][0]['id_dokumen'] ?>">

            <small class="text-danger">
                Hapus
            </small>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada
            </small>

        <?php endif; ?>

        <input type="file"
               name="<?= strtolower(str_replace(' ', '_', $j)) ?>"
               class="form-control mt-2 file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted d-block mt-1">
            JPG max 400KB
        </small>

    </div>

</div>

<?php endforeach; ?>

<!-- ================= F1.02 ================= -->
<div class="col-12 mt-3">

    <h6>F1.02</h6>

    <div class="row">

    <?php if(!empty($dok['F1.02'])): ?>

        <?php foreach($dok['F1.02'] as $d): ?>

        <div class="col-md-2 mb-3">

            <div class="border p-2 text-center">

                <?= tampilFile($d['file']) ?><br>

                <input type="checkbox"
                       name="hapus_f102[]"
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
           name="f102[]"
           multiple
           class="form-control file-validation"
           accept=".jpg,.jpeg,image/jpeg">

    <small class="text-muted">
        JPG max 400KB
    </small>

</div>

</div>

<hr>

<!-- ================= STATUS NIKAH ================= -->
<h5>Status Pernikahan Sebelumnya</h5>

<label class="mb-3">

    <input type="checkbox" id="pernahMenikah">

    Pernah menikah

</label>

<div id="ceraiSection" style="display:none;">

    <div class="mb-3">

        <label>Surat Cerai Laki-laki</label>

        <input type="file"
               name="surat_cerai_laki"
               class="form-control file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted">
            JPG max 400KB
        </small>

    </div>

    <div class="mb-3">

        <label>Surat Cerai Perempuan</label>

        <input type="file"
               name="surat_cerai_perempuan"
               class="form-control file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted">
            JPG max 400KB
        </small>

    </div>

</div>

<!-- ================= BUTTON ================= -->
<div class="mt-4 d-flex gap-2">

    <button type="submit" class="btn btn-success">
        💾 Simpan
    </button>

    <a href="<?= base_url('akta-nikah') ?>" 
       class="btn btn-secondary">
        ← Kembali
    </a>

</div>

</form>

<!-- ================= MODAL PREVIEW ================= -->
<div class="modal fade" id="imageModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center">

                <img id="previewImage"
                     src=""
                     class="img-fluid rounded">

            </div>

        </div>

    </div>

</div>

<script>

// ================= VALIDASI NAMA & NIK =================
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

// ================= VALIDASI FILE =================
const maxSize = 400 * 1024;

document.querySelectorAll('.file-validation').forEach(input => {

    input.addEventListener('change', function(){

        let files = this.files;

        for(let i = 0; i < files.length; i++){

            let file = files[i];

            // format
            if(!['image/jpeg','image/jpg'].includes(file.type)){

                alert("File harus format JPG");

                this.value = "";

                return;
            }

            // size
            if(file.size > maxSize){

                alert("Ukuran file maksimal 400KB");

                this.value = "";

                return;
            }
        }
    });
});

// ================= SHOW CERAI =================
document.getElementById('pernahMenikah').addEventListener('change', function() {

    document.getElementById('ceraiSection').style.display =
        this.checked ? 'block' : 'none';
});

// ================= PREVIEW MODAL =================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;

        new bootstrap.Modal(
            document.getElementById('imageModal')
        ).show();
    }
});

</script>

<?= $this->endSection() ?>