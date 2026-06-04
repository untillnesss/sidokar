<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit Pengajuan Akta Kematian</h3>

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
        return '<a href="' . base_url('uploads/akta_kematian/' . $file) . '" target="_blank">📄 Lihat</a>';
    }
}
?>

<form action="<?= base_url('akta-kematian/update/' . $pengajuan['id_permohonan']) ?>" method="post" enctype="multipart/form-data">

    <!-- DATA JENAZAH -->
    <h5>Data Jenazah</h5>

    <div class="mb-3">
        <label>Nama Jenazah</label>
        <input type="text" name="nama_jenazah" value="<?= $pengajuan['nama_jenazah'] ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Jenazah</label>
        <input type="text" name="nik_jenazah" value="<?= $pengajuan['nik_jenazah'] ?>" class="form-control nik" maxlength="16" required>
    </div>

    <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="Laki-laki" <?= ($pengajuan['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= ($pengajuan['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="<?= $pengajuan['tempat_lahir'] ?>" class="form-control nama">
    </div>

    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?= $pengajuan['tanggal_lahir'] ?>" class="form-control">
    </div>

    <!-- DATA KEMATIAN -->
    <hr>
    <h5>Data Kematian</h5>

    <div class="mb-3">
        <label>Tanggal Kematian</label>
        <input type="date" name="tanggal_kematian" value="<?= $pengajuan['tanggal_kematian'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jam Kematian</label>
        <input type="time" name="jam_kematian" value="<?= $pengajuan['jam_kematian'] ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Tempat Kematian</label>
        <input type="text" name="tempat_kematian" value="<?= $pengajuan['tempat_kematian'] ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Sebab Kematian</label>
        <input type="text" name="sebab_kematian" value="<?= $pengajuan['sebab_kematian'] ?>" class="form-control">
    </div>

    <!-- DATA PELAPOR -->
    <hr>
    <h5>Data Pelapor</h5>

    <div class="mb-3">
        <label>Nama Pelapor</label>
        <input type="text" name="nama_pelapor" value="<?= $pengajuan['nama_pelapor'] ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Pelapor</label>
        <input type="text" name="nik_pelapor" value="<?= $pengajuan['nik_pelapor'] ?>" class="form-control nik" maxlength="16" required>
    </div>

    <div class="mb-3">
        <label>Hubungan Pelapor</label>
        <input type="text" name="hubungan_pelapor" value="<?= $pengajuan['hubungan_pelapor'] ?>" class="form-control">
    </div>

    <!-- DATA SAKSI -->
    <hr>
    <h5>Data Saksi</h5>

    <div class="mb-3">
        <label>Nama Saksi 1</label>
        <input type="text" name="nama_saksi_1" value="<?= $pengajuan['nama_saksi_1'] ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Saksi 1</label>
        <input type="text" name="nik_saksi_1" value="<?= $pengajuan['nik_saksi_1'] ?>" class="form-control nik" maxlength="16" required>
    </div>

    <div class="mb-3">
        <label>Nama Saksi 2</label>
        <input type="text" name="nama_saksi_2" value="<?= $pengajuan['nama_saksi_2'] ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Saksi 2</label>
        <input type="text" name="nik_saksi_2" value="<?= $pengajuan['nik_saksi_2'] ?>" class="form-control nik" maxlength="16" required>
    </div>

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
                        <?= tampilFileKematian($dok[$j][0]['file_dokumen']) ?><br>

                        <input type="checkbox" name="hapus_single[]" value="<?= $dok[$j][0]['id_dokumen'] ?>">
                        <small>Hapus</small>
                    <?php else: ?>
                        <small class="text-muted">Tidak ada</small>
                    <?php endif; ?>

                    <input type="file"
                           name="<?= strtolower(str_replace(' ', '_', $j)) ?>"
                           class="form-control mt-2"
                           accept=".jpg,.jpeg">
                </div>
            </div>
        <?php endforeach; ?>

        <!-- MULTIPLE F2.01 -->
        <div class="col-12 mt-3">
            <h6>F2.01</h6>

            <div class="row">
                <?php if (!empty($dok['F2.01'])): ?>
                    <?php foreach ($dok['F2.01'] as $d): ?>
                        <div class="col-md-2 mb-3">
                            <div class="border p-2 text-center">
                                <?= tampilFileKematian($d['file_dokumen']) ?><br>

                                <input type="checkbox" name="hapus_single[]" value="<?= $d['id_dokumen'] ?>">
                                <small>Hapus</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <small class="text-muted">Tidak ada file</small>
                <?php endif; ?>
            </div>

            <input type="file" name="f201[]" multiple class="form-control" accept=".jpg,.jpeg">
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">
            💾 Simpan
        </button>

        <a href="<?= base_url('akta-kematian') ?>" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>
</form>

<script>
document.addEventListener('input', function(e)
{
    if (e.target.classList.contains('nama'))
    {
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
    }

    if (e.target.classList.contains('nik'))
    {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    }
});
</script>
<!-- MODAL PREVIEW -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="previewImage" src="" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<script>
function showImage(src) {
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

<?= $this->endSection() ?>