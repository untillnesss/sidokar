<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php

$data = $kia ?? [];

// =========================
// MAPPING FILE
// =========================
$f102 = !empty($data['file_f102'])
    ? explode(',', $data['file_f102'])
    : [];

// =========================
// HELPER PREVIEW
// =========================
function isImageKia($file){

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return in_array($ext, ['jpg','jpeg','png']);
}

function tampilFileKia($file)
{
    $url = base_url('uploads/kia/'.$file);

    if(isImageKia($file)){

        return '
        <img src="'.$url.'"
             class="img-thumbnail preview-img"
             data-bs-toggle="modal"
             data-bs-target="#modalPreview"
             data-img="'.$url.'"
             style="
                width:120px;
                height:120px;
                object-fit:cover;
                cursor:pointer;
             ">
        ';
    }

    return '
    <a href="'.$url.'"
       target="_blank"
       class="btn btn-sm btn-primary">
       📄 Lihat File
    </a>';
}

?>

<h3 class="mb-4">Detail Pengajuan KIA</h3>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- =========================
DATA ANAK
========================= -->
<h5>Data Anak</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_anak'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nik_anak'] ?? '-') ?>"
       readonly>

<input type="date"
       class="form-control mb-2"
       value="<?= esc($data['tanggal_lahir'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['jenis_kelamin'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['tempat_lahir'] ?? '-') ?>"
       readonly>

<!-- =========================
DATA AYAH
========================= -->
<hr>

<h5>Data Ayah</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_ayah'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['nik_ayah'] ?? '-') ?>"
       readonly>

<!-- =========================
STATUS
========================= -->
<hr>

<h5>Status Pengajuan</h5>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['status'] ?? '-') ?>"
       readonly>

<!-- =========================
CATATAN REVISI
========================= -->
<?php if(!empty($data['catatan'])): ?>

<div class="alert alert-danger">
    <b>Catatan Revisi:</b><br>
    <?= esc($data['catatan']) ?>
</div>

<?php endif; ?>

<hr>

<!-- =========================
DOKUMEN
========================= -->
<h5>Dokumen Pendukung</h5>

<div class="row">

<?php
$single = [

    'foto_anak' => [
        'label' => 'Foto Anak',
        'file'  => $data['foto_anak'] ?? null
    ],

    'file_kk' => [
        'label' => 'Kartu Keluarga',
        'file'  => $data['file_kk'] ?? null
    ],

    'file_akta' => [
        'label' => 'Akta Kelahiran',
        'file'  => $data['file_akta'] ?? null
    ]
];
?>

<?php foreach($single as $item): ?>

<div class="col-md-4 mb-3">

    <div class="border rounded p-3 text-center h-100 shadow-sm">

        <b><?= $item['label'] ?></b><br><br>

        <?php if(!empty($item['file'])): ?>

            <?= tampilFileKia($item['file']) ?>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<!-- =========================
F1.02
========================= -->
<div class="col-12 mt-4">

    <h6>Form F1.02</h6>

    <div class="d-flex flex-wrap gap-3">

    <?php if(!empty($f102)): ?>

        <?php foreach($f102 as $f): ?>

            <div class="border rounded p-2 text-center shadow-sm">

                <?= tampilFileKia($f) ?>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

</div>

</div>

<hr>

<!-- =========================
BUTTON
========================= -->
<div class="mt-4 d-flex gap-2 flex-wrap">

    <a href="<?= base_url('kia') ?>"
       class="btn btn-secondary">
        ← Kembali
    </a>

    <?php if(session()->get('role') == 'admin' && ($data['status'] ?? '') == 'Pengajuan'): ?>

        <!-- BUTTON SETUJUI -->
        <button type="button"
                class="btn btn-success"
                onclick="showSetujuiForm(<?= $data['id_kia'] ?>)">
            ✔ Setujui
        </button>

        <!-- BUTTON TOLAK -->
        <button type="button"
                class="btn btn-danger"
                onclick="showTolakForm(<?= $data['id_kia'] ?>)">
            ✖ Tolak
        </button>

    <?php endif; ?>

</div>

<!-- =========================
FORM SETUJUI
========================= -->
<div id="setujuiForm"
     class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="text-success">
            Konfirmasi Persetujuan
        </h5>

        <p>
            Apakah Anda yakin ingin menyetujui pengajuan ini?
        </p>

        <form id="formSetujui"
              method="post">

            <?= csrf_field(); ?>

            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-success">
                    Ya, Setujui
                </button>

                <button type="button"
                        class="btn btn-secondary"
                        onclick="hideSetujuiForm()">
                    Batal
                </button>

            </div>

        </form>

    </div>

</div>

<!-- =========================
FORM TOLAK
========================= -->
<div id="tolakForm"
     class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="text-danger">
            Form Revisi / Penolakan
        </h5>

        <form id="formTolak"
              method="post">

            <?= csrf_field(); ?>

            <textarea name="catatan"
                      class="form-control mb-3"
                      rows="4"
                      required
                      placeholder="Isi catatan revisi wajib diisi"></textarea>

            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-danger">
                    Kirim
                </button>

                <button type="button"
                        class="btn btn-secondary"
                        onclick="hideTolakForm()">
                    Batal
                </button>

            </div>

        </form>

    </div>

</div>

<!-- =========================
MODAL PREVIEW
========================= -->
<div class="modal fade"
     id="modalPreview"
     tabindex="-1">

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

<!-- =========================
SCRIPT
========================= -->
<script>

// =========================
// PREVIEW MODAL
// =========================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;
    }
});

// =========================
// TOLAK
// =========================
function showTolakForm(id){

    document.getElementById('tolakForm')
        .classList.remove('d-none');

    document.getElementById('formTolak').action =
        "<?= base_url('kia/tolak') ?>/" + id;
}

function hideTolakForm(){

    document.getElementById('tolakForm')
        .classList.add('d-none');
}

// =========================
// SETUJUI
// =========================
function showSetujuiForm(id){

    document.getElementById('setujuiForm')
        .classList.remove('d-none');

    document.getElementById('formSetujui').action =
        "<?= base_url('kia/setujui') ?>/" + id;
}

function hideSetujuiForm(){

    document.getElementById('setujuiForm')
        .classList.add('d-none');
}

</script>

<?= $this->endSection() ?>