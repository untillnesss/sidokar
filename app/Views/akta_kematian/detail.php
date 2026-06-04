<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Detail Pengajuan Akta Kematian</h3>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php
$dok = [];

if (!empty($dokumen)) {
    foreach ($dokumen as $d) {
        $dok[$d['jenis_dokumen']][] = $d;
    }
}

function isImageKematian($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return in_array($ext, ['jpg', 'jpeg', 'png']);
}

function tampilFileKematian($file)
{
    if (isImageKematian($file)) {

        return '<img src="' . base_url('uploads/akta_kematian/' . $file) . '" 
                    class="img-thumbnail"
                    style="width:120px;height:120px;object-fit:cover;cursor:pointer;"
                    onclick="showImage(this.src)">';

    } else {

        return '<a href="' . base_url('uploads/akta_kematian/' . $file) . '" target="_blank">
                    📄 Lihat
                </a>';
    }
}
?>

<!-- DATA JENAZAH -->
<h5>Data Jenazah</h5>

<div class="mb-3">
    <label>Nama Jenazah</label>

    <input type="text"
           value="<?= $pengajuan['nama_jenazah'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>NIK Jenazah</label>

    <input type="text"
           value="<?= $pengajuan['nik_jenazah'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Jenis Kelamin</label>

    <input type="text"
           value="<?= $pengajuan['jenis_kelamin'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Tempat Lahir</label>

    <input type="text"
           value="<?= $pengajuan['tempat_lahir'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Tanggal Lahir</label>

    <input type="date"
           value="<?= $pengajuan['tanggal_lahir'] ?>"
           class="form-control"
           readonly>
</div>

<!-- DATA KEMATIAN -->
<hr>

<h5>Data Kematian</h5>

<div class="mb-3">
    <label>Tanggal Kematian</label>

    <input type="date"
           value="<?= $pengajuan['tanggal_kematian'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Jam Kematian</label>

    <input type="time"
           value="<?= $pengajuan['jam_kematian'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Tempat Kematian</label>

    <input type="text"
           value="<?= $pengajuan['tempat_kematian'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Sebab Kematian</label>

    <input type="text"
           value="<?= $pengajuan['sebab_kematian'] ?>"
           class="form-control"
           readonly>
</div>

<!-- DATA PELAPOR -->
<hr>

<h5>Data Pelapor</h5>

<div class="mb-3">
    <label>Nama Pelapor</label>

    <input type="text"
           value="<?= $pengajuan['nama_pelapor'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>NIK Pelapor</label>

    <input type="text"
           value="<?= $pengajuan['nik_pelapor'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Hubungan Pelapor</label>

    <input type="text"
           value="<?= $pengajuan['hubungan_pelapor'] ?>"
           class="form-control"
           readonly>
</div>

<!-- DATA SAKSI -->
<hr>

<h5>Data Saksi</h5>

<div class="mb-3">
    <label>Nama Saksi 1</label>

    <input type="text"
           value="<?= $pengajuan['nama_saksi_1'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>NIK Saksi 1</label>

    <input type="text"
           value="<?= $pengajuan['nik_saksi_1'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>Nama Saksi 2</label>

    <input type="text"
           value="<?= $pengajuan['nama_saksi_2'] ?>"
           class="form-control"
           readonly>
</div>

<div class="mb-3">
    <label>NIK Saksi 2</label>

    <input type="text"
           value="<?= $pengajuan['nik_saksi_2'] ?>"
           class="form-control"
           readonly>
</div>

<!-- CATATAN REVISI -->
<?php if (!empty($pengajuan['catatan_revisi'])): ?>

<hr>

<div class="alert alert-danger">
    <b>Catatan Revisi:</b><br>

    <?= $pengajuan['catatan_revisi'] ?>
</div>

<?php endif; ?>

<!-- DOKUMEN -->
<hr>

<h5>Dokumen</h5>

<div class="row">

    <?php
    $single = [
        'Surat Kematian Desa',
        'Surat Kematian Instansi',
        'KTP Pelapor',
        'KTP Saksi 1',
        'KTP Saksi 2',
        'KK Jenazah',
        'KTP Jenazah'
    ];
    ?>

    <?php foreach ($single as $j): ?>

        <div class="col-md-4 mb-3">

            <div class="border p-2 text-center">

                <b><?= $j ?></b><br>

                <?php if (!empty($dok[$j])): ?>

                    <?= tampilFileKematian($dok[$j][0]['file_dokumen']) ?>

                <?php else: ?>

                    <small class="text-muted">
                        Tidak ada
                    </small>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

    <!-- F2.01 -->
    <div class="col-12 mt-3">

        <h6>F2.01</h6>

        <div class="row">

            <?php if (!empty($dok['F2.01'])): ?>

                <?php foreach ($dok['F2.01'] as $d): ?>

                    <div class="col-md-2 mb-3">

                        <div class="border p-2 text-center">

                            <?= tampilFileKematian($d['file_dokumen']) ?>

                        </div>

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

<!-- BUTTON -->
<div class="mt-4 d-flex gap-2 flex-wrap">

    <a href="<?= base_url('akta-kematian') ?>"
       class="btn btn-secondary">

        ← Kembali

    </a>

    <?php if (
        session()->get('role') == 'admin'
        && $pengajuan['status'] == 'Pengajuan'
    ): ?>

        <!-- SETUJUI -->
        <form action="<?= base_url('akta-kematian/setujui-pengajuan/'.$pengajuan['id_permohonan']) ?>"
              method="post"
              onsubmit="return confirmSetujui()">

            <?= csrf_field(); ?>

            <button type="submit"
                    class="btn btn-success">

                ✔ Setujui

            </button>

        </form>

        <!-- TOLAK -->
        <button type="button"
                class="btn btn-danger"
                onclick="showTolakForm(<?= $pengajuan['id_permohonan'] ?>)">

            ✖ Tolak

        </button>

    <?php endif; ?>

</div>

<!-- FORM TOLAK -->
<div id="tolakForm"
     class="card mt-3 d-none">

    <div class="card-body">

        <h5 class="mb-3 text-danger">
            Form Penolakan
        </h5>

        <form id="formTolak"
              method="post">

            <?= csrf_field(); ?>

            <div class="mb-3">

                <label>Catatan Revisi</label>

                <textarea name="catatan_revisi"
                          class="form-control"
                          rows="4"
                          required></textarea>

            </div>

            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-danger">

                    Kirim Penolakan

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

<!-- MODAL PREVIEW -->
<div class="modal fade"
     id="imageModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center">

                <img id="previewImage"
                     src=""
                     class="img-fluid">

            </div>

        </div>

    </div>

</div>

<script>
function showImage(src)
{
    document.getElementById('previewImage').src = src;

    new bootstrap.Modal(
        document.getElementById('imageModal')
    ).show();
}

function confirmSetujui()
{
    return confirm('Setujui pengajuan ini?');
}

function showTolakForm(id)
{
    document.getElementById('tolakForm')
        .classList.remove('d-none');

    document.getElementById('formTolak').action =
        "<?= base_url('akta-kematian/tolak-pengajuan/') ?>/" + id;
}

function hideTolakForm()
{
    document.getElementById('tolakForm')
        .classList.add('d-none');
}
</script>

<?= $this->endSection() ?>