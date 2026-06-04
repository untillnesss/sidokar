<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$data = $pengajuan ?? [];

// =========================
// GROUP DOKUMEN
// =========================
$dok = [];

if(!empty($dokumen)){
    foreach($dokumen as $d){
        $dok[$d['jenis_dokumen']][] = $d;
    }
}

// =========================
// HELPER
// =========================
function isImagePindah($file){
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg','jpeg','png']);
}

function tampilFilePindah($file){

    $url = base_url('uploads/pindah/'.$file);

    if(isImagePindah($file)){

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
    <a href="'.$url.'" target="_blank">
        📄 Lihat
    </a>';
}
?>

<h3 class="mb-4">Detail Pengajuan Pindah</h3>

<?php if(session()->getFlashdata('error')): ?>

<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>

<?php endif; ?>

<!-- =========================
DATA PEMOHON
========================= -->
<h5>Data Pemohon</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_pemohon'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['jenis_pindah'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['kategori_pindah'] ?? '-') ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['jenis_tujuan'] ?? '-') ?>"
       readonly>

<textarea class="form-control mb-2"
          readonly><?= esc($data['alamat_asal'] ?? '-') ?></textarea>

<textarea class="form-control mb-2"
          readonly><?= esc($data['alamat_tujuan'] ?? '-') ?></textarea>

<textarea class="form-control mb-3"
          readonly><?= esc($data['alasan'] ?? '-') ?></textarea>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['status'] ?? '-') ?>"
       readonly>

<!-- =========================
CATATAN REVISI
========================= -->
<?php if(!empty($data['catatan_revisi'])): ?>

<div class="alert alert-danger">

    <b>Catatan Revisi:</b><br>

    <?= esc($data['catatan_revisi']) ?>

</div>

<?php endif; ?>

<hr>

<!-- =========================
DATA ANGGOTA
========================= -->
<h5>Data Anggota</h5>

<?php if(!empty($anggota)): ?>

    <?php foreach($anggota as $a): ?>

    <div class="border rounded p-3 mb-3">

        <input type="text"
               class="form-control mb-2"
               value="<?= esc($a['nama_anggota']) ?>"
               readonly>

        <input type="text"
               class="form-control"
               value="<?= esc($a['nik_anggota']) ?>"
               readonly>

    </div>

    <?php endforeach; ?>

<?php else: ?>

<small class="text-muted">
    Tidak ada anggota
</small>

<?php endif; ?>

<hr>

<!-- =========================
DOKUMEN
========================= -->
<h5>Dokumen</h5>

<div class="row">

<?php
$single = [
    'KK' => 'Kartu Keluarga',
    'Surat Desa' => 'Surat Desa'
];
?>

<?php foreach($single as $key => $label): ?>

<div class="col-md-3 mb-3">

    <div class="border rounded p-2 text-center h-100 shadow-sm">

        <b><?= $label ?></b><br><br>

        <?php if(!empty($dok[$key])): ?>

            <?= tampilFilePindah($dok[$key][0]['nama_file']) ?>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<!-- =========================
KTP
========================= -->
<div class="col-12 mt-3">

    <h6>KTP Anggota</h6>

    <div class="d-flex flex-wrap gap-3">

    <?php if(!empty($dok['KTP'])): ?>

        <?php foreach($dok['KTP'] as $f): ?>

            <?= tampilFilePindah($f['nama_file']) ?>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

</div>

<!-- =========================
F1.02
========================= -->
<div class="col-12 mt-4">

    <h6>F1.02</h6>

    <div class="d-flex flex-wrap gap-3">

    <?php if(!empty($dok['F1.02'])): ?>

        <?php foreach($dok['F1.02'] as $f): ?>

            <?= tampilFilePindah($f['nama_file']) ?>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

</div>

<!-- =========================
F1.06
========================= -->
<div class="col-12 mt-4">

    <h6>F1.06</h6>

    <div class="d-flex flex-wrap gap-3">

    <?php if(!empty($dok['F1.06'])): ?>

        <?php foreach($dok['F1.06'] as $f): ?>

            <?= tampilFilePindah($f['nama_file']) ?>

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

    <a href="<?= base_url('pindah') ?>"
       class="btn btn-secondary">

        ← Kembali

    </a>

    <?php if(session()->get('role') == 'admin' && ($data['status'] ?? '') == 'Pengajuan'): ?>

    <button type="button"
            class="btn btn-success"
            onclick="showSetujuiForm(<?= $data['id_pengajuan'] ?>)">

        ✔ Setujui

    </button>

    <button type="button"
            class="btn btn-danger"
            onclick="showTolakForm(<?= $data['id_pengajuan'] ?>)">

        ✖ Tolak

    </button>

    <?php endif; ?>

</div>

<!-- =========================
FORM SETUJUI
========================= -->
<div id="setujuiForm" class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="text-success">
            Konfirmasi Persetujuan
        </h5>

        <p>
            Apakah Anda yakin ingin menyetujui pengajuan ini?
        </p>

        <form id="formSetujui" method="post">

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
<div id="tolakForm" class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="text-danger">
            Form Revisi
        </h5>

        <form id="formTolak" method="post">

            <?= csrf_field(); ?>

            <textarea name="catatan_revisi"
                      class="form-control mb-3"
                      rows="4"
                      required
                      placeholder="Isi catatan revisi"></textarea>

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
// PREVIEW
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
        "<?= base_url('pindah/tolak') ?>/" + id;
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
        "<?= base_url('pindah/setujui') ?>/" + id;
}

function hideSetujuiForm(){

    document.getElementById('setujuiForm')
        .classList.add('d-none');
}

</script>

<?= $this->endSection() ?>