<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan Akta Nikah</h3>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php if(session()->get('role') == 'admin'): ?>
<!-- kalau nanti mau tambah field admin -->
<?php endif; ?>

<form action="/akta-nikah/store" method="post" enctype="multipart/form-data">

    <!-- ================= DATA LAKI ================= -->
    <h5>Data Laki-laki</h5>

    <div class="mb-3">
        <label>Nama Laki-laki</label>
        <input type="text" name="nama_laki_laki" value="<?= old('nama_laki_laki') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Laki-laki</label>
        <input type="text" name="nik_laki_laki" value="<?= old('nik_laki_laki') ?>" class="form-control nik" maxlength="16" required>
    </div>

    <!-- ================= DATA PEREMPUAN ================= -->
    <hr>
    <h5>Data Perempuan</h5>

    <div class="mb-3">
        <label>Nama Perempuan</label>
        <input type="text" name="nama_perempuan" value="<?= old('nama_perempuan') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Perempuan</label>
        <input type="text" name="nik_perempuan" value="<?= old('nik_perempuan') ?>" class="form-control nik" maxlength="16" required>
    </div>

    <!-- ================= DATA PERNIKAHAN ================= -->
    <hr>
    <h5>Data Pernikahan</h5>

    <div class="mb-3">
        <label>Agama</label>
        <input type="text" name="agama" value="<?= old('agama') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tempat Pernikahan</label>
        <select name="tempat_pernikahan" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="Gereja">Gereja</option>
            <option value="Pura">Pura</option>
            <option value="Vihara">Vihara</option>
            <option value="Klenteng">Klenteng</option>
            <option value="Lainnya">Lainnya</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal Perkawinan</label>
        <input type="date" name="tanggal_perkawinan" value="<?= old('tanggal_perkawinan') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama Pemuka Agama (yang menikahkan)</label>
        <input type="text" name="nama_pemuka_agama" value="<?= old('nama_pemuka_agama') ?>" class="form-control nama" required>
    </div>

    <!-- ================= SAKSI ================= -->
    <hr>
    <h5>Data Saksi</h5>

    <div class="mb-3">
        <label>Nama Saksi 1</label>
        <input type="text" name="nama_saksi_1" value="<?= old('nama_saksi_1') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>Nama Saksi 2</label>
        <input type="text" name="nama_saksi_2" value="<?= old('nama_saksi_2') ?>" class="form-control nama" required>
    </div>

    <!-- ================= DOKUMEN ================= -->
    <hr>
    <h5>Dokumen</h5>

    <!-- F1.02 MULTIPLE -->
    <div class="mb-3">
        <label>F1.02 (JPG maks 400KB, bisa banyak)</label>
        <input type="file" name="f102[]" multiple class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>Akta Kelahiran Laki-laki (JPG maks 400KB)</label>
        <input type="file" name="akta_laki" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>Akta Kelahiran Perempuan (JPG maks 400KB)</label>
        <input type="file" name="akta_perempuan" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>Surat Keterangan Desa (JPG maks 400KB)</label>
        <input type="file" name="suket_desa" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>KTP Laki-laki (JPG maks 400KB)</label>
        <input type="file" name="ktp_laki" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>KTP Perempuan (JPG maks 400KB)</label>
        <input type="file" name="ktp_perempuan" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>Kartu Keluarga (KK terbaru / KK orang tua) (JPG maks 400KB)</label>
        <input type="file" name="kk" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <div class="mb-3">
        <label>KTP Saksi (JPG maks 400KB)</label>
        <input type="file" name="ktp_saksi" class="form-control" accept=".jpg,.jpeg" required>
    </div>

    <hr>
<h5>Status Pernikahan Sebelumnya</h5>

<div class="mb-3">
    <label>
        <input type="checkbox" id="pernahMenikah"> Pernah menikah
    </label>
</div>

<div id="ceraiSection" style="display:none;">

    <div class="mb-3">
        <label>Surat Cerai Laki-laki</label>
        <input type="file" name="surat_cerai_laki" class="form-control" accept=".jpg,.jpeg">
    </div>

    <div class="mb-3">
        <label>Surat Cerai Perempuan</label>
        <input type="file" name="surat_cerai_perempuan" class="form-control" accept=".jpg,.jpeg">
    </div>

</div>

    <div class="mb-3">
        <label>Pas Foto Berdampingan (JPG maks 400KB)</label>
        <input type="file" name="pas_foto" class="form-control" accept=".jpg,.jpeg" required>
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

// ================= TOGGLE SURAT CERAI =================
document.addEventListener('DOMContentLoaded', function() {

    let checkbox = document.getElementById('pernahMenikah');
    let section = document.getElementById('ceraiSection');

    checkbox.addEventListener('change', function() {
        section.style.display = this.checked ? 'block' : 'none';

        if (!this.checked) {
            section.querySelectorAll('input[type="file"]').forEach(input => {
                input.value = '';
            });
        }
    });

});

// ================= AUTO FOCUS ERROR =================
<?php if (session()->getFlashdata('error')): ?>
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
<?php endif; ?>

</script>

<?= $this->endSection() ?>