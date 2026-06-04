<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan Akta Cerai</h3>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php if(session()->get('role') == 'admin'): ?>



<?php endif; ?>

<form action="/akta-cerai/store" method="post" enctype="multipart/form-data">

    <!-- ================= DATA PEREMPUAN ================= -->
    <h5>Data Perempuan</h5>

    <div class="mb-3">
        <label>Nama Perempuan</label>
        <input type="text" name="nama_perempuan" value="<?= old('nama_perempuan') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Perempuan</label>
        <input type="text" name="nik_perempuan" value="<?= old('nik_perempuan') ?>" class="form-control nik" maxlength="16" required>
    </div>

    <!-- ================= DATA LAKI ================= -->
    <hr>
    <h5>Data Laki-laki</h5>

    <div class="mb-3">
        <label>Nama Laki-laki</label>
        <input type="text" name="nama_laki" value="<?= old('nama_laki') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Laki-laki</label>
        <input type="text" name="nik_laki" value="<?= old('nik_laki') ?>" class="form-control nik" maxlength="16" required>
    </div>

    <!-- ================= DATA PERCERAIAN ================= -->
    <hr>
    <h5>Data Perceraian</h5>

    <div class="mb-3">
        <label>Tanggal Cerai</label>
        <input type="date" name="tanggal_cerai" value="<?= old('tanggal_cerai') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tempat Cerai</label>
        <input type="text" name="tempat_cerai" value="<?= old('tempat_cerai') ?>" class="form-control" required>
    </div>

    <!-- ================= DATA PERKAWINAN ================= -->
    <hr>
    <h5>Data Perkawinan</h5>

    <div class="mb-3">
        <label>Nomor Akta Perkawinan</label>
        <input type="text" name="nomor_akta_perkawinan" value="<?= old('nomor_akta_perkawinan') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Perkawinan</label>
        <input type="date" name="tanggal_perkawinan" value="<?= old('tanggal_perkawinan') ?>" class="form-control" required>
    </div>

    <!-- ================= DOKUMEN ================= -->
    <hr>
    <h5>Dokumen</h5>

    <!-- F2.01 MULTIPLE -->
    <div class="mb-3">
        <label>F2.01 (JPG maks 400KB, bisa banyak)</label>
        <input type="file" name="f201[]" multiple class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <!-- PN -->
    <div class="mb-3">
        <label>Salinan Penetapan PN (JPG maks 400KB)</label>
        <input type="file" name="pn" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <!-- KTP PEREMPUAN -->
    <div class="mb-3">
        <label>KTP Perempuan (JPG maks 400KB)</label>
        <input type="file" name="ktp_perempuan" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <!-- KTP LAKI -->
    <div class="mb-3">
        <label>KTP Laki-laki (JPG maks 400KB)</label>
        <input type="file" name="ktp_laki" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <!-- KK -->
    <div class="mb-3">
        <label>Kartu Keluarga Lama (JPG maks 400KB)</label>
        <input type="file" name="kk" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <!-- AKTA PERKAWINAN -->
    <div class="mb-3">
        <label>Akta Perkawinan (JPG maks 400KB)</label>
        <input type="file" name="akta_perkawinan" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <button class="btn btn-success">Simpan</button>

</form>

<script>

// ================= VALIDASI INPUT =================
document.addEventListener('input', function(e) {

    if (e.target.classList.contains('nama')) {
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
    }

    if (e.target.classList.contains('nik')) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    }

});

// ================= VALIDASI FILE =================
document.addEventListener('change', function(e) {

    if (e.target.type === 'file') {

        let files = e.target.files;

        for (let file of files) {

            let ext = file.name.split('.').pop().toLowerCase();

            if (!['jpg','jpeg'].includes(ext)) {
                alert('Semua dokumen harus JPG!');
                e.target.value = '';
                return;
            }

            if (file.size > 400 * 1024) {
                alert('Maksimal 400KB!');
                e.target.value = '';
                return;
            }
        }
    }

});

// ================= AUTO FOCUS ERROR =================
<?php if (session()->getFlashdata('error')): ?>
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
<?php endif; ?>

</script>

<?= $this->endSection() ?>