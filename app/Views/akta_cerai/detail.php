<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $data = $pengajuan; ?>

<h3 class="mb-4">Detail Pengajuan Akta Cerai</h3>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php

// ================= MAPPING DOKUMEN =================
$dok = [];

if(!empty($dokumen)){
    foreach($dokumen as $d){

        if(isset($d['jenis_dokumen'])){
            $dok[$d['jenis_dokumen']][] = $d;
        }
    }
}

// ================= HELPER FILE =================
function tampilFileCerai($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $url = base_url('uploads/akta_cerai/'.$file);

    if(in_array($ext, ['jpg','jpeg','png'])){

        return '
        <img src="'.$url.'"
             class="img-thumbnail"
             style="
                width:120px;
                height:120px;
                object-fit:cover;
                cursor:pointer
             "
             onclick="showImage(this.src)">
        ';
    }

    return '<a href="'.$url.'" target="_blank">📄 Lihat</a>';
}
?>

<!-- ================= DATA PEREMPUAN ================= -->
<h5>Data Perempuan</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_perempuan']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['nik_perempuan']) ?>"
       readonly>

<!-- ================= DATA LAKI ================= -->
<h5>Data Laki-laki</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_laki']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['nik_laki']) ?>"
       readonly>

<!-- ================= DATA PERCERAIAN ================= -->
<h5>Data Perceraian</h5>

<input type="date"
       class="form-control mb-2"
       value="<?= esc($data['tanggal_cerai']) ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['tempat_cerai']) ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nomor_akta_perkawinan']) ?>"
       readonly>

<input type="date"
       class="form-control mb-3"
       value="<?= esc($data['tanggal_perkawinan']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['status']) ?>"
       readonly>

<!-- ================= CATATAN REVISI ================= -->
<?php if(!empty($data['catatan_revisi'])): ?>

<div class="alert alert-danger">
    <b>Catatan Revisi:</b><br>
    <?= esc($data['catatan_revisi']) ?>
</div>

<?php endif; ?>

<hr>

<!-- ================= DOKUMEN ================= -->
<h5>Dokumen</h5>

<?php
$list = [
    'pn'                => 'Putusan Pengadilan',
    'ktp_perempuan'     => 'KTP Perempuan',
    'ktp_laki'          => 'KTP Laki-laki',
    'kk'                => 'Kartu Keluarga',
    'akta_perkawinan'   => 'Akta Perkawinan'
];
?>

<div class="row">

<?php foreach($list as $key => $label): ?>

<div class="col-md-3 mb-3">

    <div class="border p-2 text-center h-100">

        <b><?= $label ?></b><br><br>

        <?php if(!empty($dok[$key])): ?>

            <?= tampilFileCerai($dok[$key][0]['file_path']) ?>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada
            </small>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<!-- ================= F2.01 ================= -->
<div class="col-12 mt-3">

    <h6>Form F2.01</h6>

    <?php if(!empty($dok['f201'])): ?>

        <div class="d-flex flex-wrap gap-2">

            <?php foreach($dok['f201'] as $f): ?>

                <?= tampilFileCerai($f['file_path']) ?>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada
        </small>

    <?php endif; ?>

</div>

</div>

<hr>

<!-- ================= BUTTON ================= -->
<div class="mt-4 d-flex gap-2 flex-wrap">

    <a href="<?= base_url('akta-cerai') ?>"
       class="btn btn-secondary">
        ← Kembali
    </a>

    <?php if(session()->get('role') == 'admin'): ?>

        <?php if(
            $data['status'] != 'Proses' &&
            $data['status'] != 'Selesai'
        ): ?>

            <!-- BUTTON SETUJUI -->
            <button type="button"
                    class="btn btn-success"
                    onclick="showSetujuiForm(<?= $data['id_permohonan'] ?>)">
                ✔ Setujui
            </button>

            <!-- BUTTON TOLAK -->
            <button type="button"
                    class="btn btn-danger"
                    onclick="showTolakForm(<?= $data['id_permohonan'] ?>)">
                ✖ Tolak
            </button>

        <?php endif; ?>

    <?php endif; ?>

</div>

<!-- ================= FORM SETUJUI ================= -->
<div id="setujuiForm" class="card mt-3 d-none">

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

<!-- ================= FORM TOLAK ================= -->
<div id="tolakForm" class="card mt-3 d-none">

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

<!-- ================= MODAL IMAGE ================= -->
<div class="modal fade" id="imageModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center">

                <img id="previewImage"
                     class="img-fluid">

            </div>

        </div>

    </div>

</div>

<!-- ================= SCRIPT ================= -->
<script>

function showImage(src){

    document.getElementById('previewImage').src = src;

    new bootstrap.Modal(
        document.getElementById('imageModal')
    ).show();
}

// ================= TOLAK =================
function showTolakForm(id){

    document.getElementById('tolakForm')
        .classList.remove('d-none');

    document.getElementById('formTolak').action =
        "<?= base_url('akta-cerai/tolak') ?>/" + id;
}

function hideTolakForm(){

    document.getElementById('tolakForm')
        .classList.add('d-none');
}

// ================= SETUJUI =================
function showSetujuiForm(id){

    document.getElementById('setujuiForm')
        .classList.remove('d-none');

    document.getElementById('formSetujui').action =
        "<?= base_url('akta-cerai/setujui') ?>/" + id;
}

function hideSetujuiForm(){

    document.getElementById('setujuiForm')
        .classList.add('d-none');
}

</script>

<?= $this->endSection() ?>