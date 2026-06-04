<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan Akta Kematian</h3>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('akta-kematian/store') ?>" method="post" enctype="multipart/form-data">

<h5>Data Jenazah</h5>

<div class="mb-3">
<label>Nama Jenazah</label>
<input type="text" name="nama_jenazah" class="form-control nama" required>
</div>

<div class="mb-3">
<label>NIK Jenazah</label>
<input type="text" name="nik_jenazah" maxlength="16" class="form-control nik" required>
</div>

<div class="mb-3">
<label>Jenis Kelamin</label>
<select name="jenis_kelamin" class="form-control" required>
<option value="">-- Pilih --</option>
<option value="Laki-laki">Laki-laki</option>
<option value="Perempuan">Perempuan</option>
</select>
</div>

<div class="mb-3">
<label>Tempat Lahir</label>
<input type="text" name="tempat_lahir" class="form-control nama">
</div>

<div class="mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" class="form-control">
</div>

<hr>
<h5>Data Kematian</h5>

<div class="mb-3">
<label>Tanggal Kematian</label>
<input type="date" name="tanggal_kematian" class="form-control" required>
</div>

<div class="mb-3">
<label>Jam Kematian</label>
<input type="time" name="jam_kematian" class="form-control">
</div>

<div class="mb-3">
<label>Tempat Kematian</label>
<input type="text" name="tempat_kematian" class="form-control">
</div>

<div class="mb-3">
<label>Sebab Kematian</label>
<input type="text" name="sebab_kematian" class="form-control">
</div>

<hr>
<h5>Data Pelapor</h5>

<div class="mb-3">
<label>Nama Pelapor</label>
<input type="text" name="nama_pelapor" class="form-control nama" required>
</div>

<div class="mb-3">
<label>NIK Pelapor</label>
<input type="text" name="nik_pelapor" maxlength="16" class="form-control nik" required>
</div>

<div class="mb-3">
<label>Hubungan Pelapor</label>
<input type="text" name="hubungan_pelapor" class="form-control">
</div>

<hr>
<h5>Data Saksi</h5>

<div class="mb-3">
<label>Nama Saksi 1</label>
<input type="text" name="nama_saksi_1" class="form-control nama" required>
</div>

<div class="mb-3">
<label>NIK Saksi 1</label>
<input type="text" name="nik_saksi_1" maxlength="16" class="form-control nik" required>
</div>

<div class="mb-3">
<label>Nama Saksi 2</label>
<input type="text" name="nama_saksi_2" class="form-control nama" required>
</div>

<div class="mb-3">
<label>NIK Saksi 2</label>
<input type="text" name="nik_saksi_2" maxlength="16" class="form-control nik" required>
</div>

<hr>
<h5>Dokumen</h5>

<div class="mb-3">
<label>F2.01 (bisa banyak file)</label>
<input type="file" name="f201[]" multiple class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
    <label>Surat Kematian dari Desa</label>
    <input type="file" name="surat_kematian_desa" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
    <label>Surat Kematian dari RS / Puskesmas / Instansi</label>
    <input type="file" name="surat_kematian_instansi" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
<label>KTP Pelapor</label>
<input type="file" name="ktp_pelapor" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
<label>KTP Saksi 1</label>
<input type="file" name="ktp_saksi_1" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
<label>KTP Saksi 2</label>
<input type="file" name="ktp_saksi_2" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
<label>KK Jenazah</label>
<input type="file" name="kk_jenazah" class="form-control" accept=".jpg,.jpeg" required>
</div>

<div class="mb-3">
<label>KTP Jenazah</label>
<input type="file" name="ktp_jenazah" class="form-control" accept=".jpg,.jpeg" required>
</div>

<button class="btn btn-success">Simpan</button>

</form>

<script>
document.addEventListener('input', function(e) {

if (e.target.classList.contains('nama')) {
e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
}

if (e.target.classList.contains('nik')) {
e.target.value = e.target.value.replace(/[^0-9]/g, '');
}

});

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
</script>

<?= $this->endSection() ?>