<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $data = $pengajuan; ?>

<h3 class="mb-4">Detail Pengajuan Akta Nikah</h3>

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

function tampilFileNikah($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $url = base_url('uploads/akta_nikah/'.$file);

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

<!-- ================= DATA LAKI ================= -->
<h5>Data Laki-laki</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_laki_laki']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['nik_laki_laki']) ?>"
       readonly>

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

<!-- ================= DATA PERNIKAHAN ================= -->
<h5>Data Pernikahan</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['agama']) ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['tempat_pernikahan']) ?>"
       readonly>

<input type="date"
       class="form-control mb-2"
       value="<?= esc($data['tanggal_perkawinan']) ?>"
       readonly>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_pemuka_agama']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['status']) ?>"
       readonly>

<!-- ================= DATA SAKSI ================= -->
<h5>Data Saksi</h5>

<input type="text"
       class="form-control mb-2"
       value="<?= esc($data['nama_saksi_1']) ?>"
       readonly>

<input type="text"
       class="form-control mb-3"
       value="<?= esc($data['nama_saksi_2']) ?>"
       readonly>

<!-- ================= CATATAN REVISI ================= -->
<?php if(!empty($data['catatan'])): ?>

<div class="alert alert-danger">
    <b>Catatan Revisi:</b><br>
    <?= esc($data['catatan']) ?>
</div>

<?php endif; ?>

<hr>

<!-- ================= DOKUMEN ================= -->
<h5>Dokumen</h5>

<?php
$list = [
    'KK',
    'KTP Laki-laki',
    'KTP Perempuan',
    'Akta Lahir Laki',
    'Akta Lahir Perempuan',
    'Surat Desa',
    'KTP Saksi',
    'Pas Foto',
];
?>

<div class="row">

<?php foreach($list as $j): ?>

<div class="col-md-3 mb-3">

    <div class="border p-2 text-center h-100">

        <b><?= $j ?></b><br><br>

        <?php if(!empty($dok[$j])): ?>

            <?= tampilFileNikah($dok[$j][0]['file']) ?>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada
            </small>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<!-- ================= F1.02 ================= -->
<div class="col-12 mt-3">

    <h6>F1.02</h6>

    <?php if(!empty($dok['F1.02'])): ?>

        <div class="d-flex flex-wrap gap-2">

            <?php foreach($dok['F1.02'] as $f): ?>

                <?= tampilFileNikah($f['file']) ?>

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

    <a href="<?= base_url('akta-nikah') ?>"
       class="btn btn-secondary">
        ← Kembali
    </a>

    <?php if(session()->get('role') == 'admin' && $data['status'] == 'Pengajuan'): ?>

        <!-- SETUJUI -->
        <form action="<?= base_url('akta-nikah/setujui/'.$data['id_permohonan']) ?>"
              method="post"
              onsubmit="return confirm('Setujui pengajuan ini?')">

            <?= csrf_field(); ?>

            <!-- BUTTON SETUJUI -->
<button type="button"
        class="btn btn-success"
        onclick="showSetujuiForm(<?= $data['id_permohonan'] ?>)">
    ✔ Setujui
</button>

        </form>

        <!-- TOLAK -->
        <button type="button"
                class="btn btn-danger"
                onclick="showTolakForm(<?= $data['id_permohonan'] ?>)">
            ✖ Tolak
        </button>

    <?php endif; ?>

</div>

<!-- ================= FORM TOLAK ================= -->
<div id="tolakForm" class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="text-danger">
            Form Revisi / Penolakan
        </h5>

        <form id="formTolak" method="post">

            <?= csrf_field(); ?>

            <textarea name="catatan"
                      class="form-control mb-3"
                      rows="4"
                      required
                      placeholder="Isi catatan revisi wajib diisi"></textarea>

            <div class="d-flex gap-2">

                <button class="btn btn-danger">
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

function showTolakForm(id){

    document.getElementById('tolakForm')
        .classList.remove('d-none');

    document.getElementById('formTolak').action =
        "<?= base_url('akta-nikah/tolak') ?>/" + id;
}

function hideTolakForm(){

    document.getElementById('tolakForm')
        .classList.add('d-none');
}

</script>

<?= $this->endSection() ?>