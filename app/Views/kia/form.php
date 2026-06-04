<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan KIA</h3>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form action="/kia/simpan" method="post" enctype="multipart/form-data">

    <!-- ================= JENIS PENGAJUAN ================= -->
    <div class="mb-3">
        <label>Jenis Pengajuan</label>
        <select name="jenis_pengajuan" id="jenis_pengajuan" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="baru" <?= old('jenis_pengajuan')=='baru'?'selected':'' ?>>Baru Lahir</option>
            <option value="sudah" <?= old('jenis_pengajuan')=='sudah'?'selected':'' ?>>Sudah punya dokumen</option>
        </select>
    </div>

    <!-- ================= DATA ANAK ================= -->
    <h5>Data Anak</h5>

    <div class="mb-3">
        <label>Nama Anak</label>
        <input type="text" name="nama_anak" value="<?= old('nama_anak') ?>" class="form-control nama" required>
    </div>

    <div class="mb-3">
        <label>NIK Anak</label>
        <input type="text" name="nik_anak" id="nik_anak" value="<?= old('nik_anak') ?>" class="form-control nik" maxlength="16">
    </div>

    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control">
            <option value="L" <?= old('jenis_kelamin')=='L'?'selected':'' ?>>Laki-laki</option>
            <option value="P" <?= old('jenis_kelamin')=='P'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="<?= old('tempat_lahir') ?>" class="form-control">
    </div>

    <!-- ================= DATA AYAH ================= -->
    <hr>
    <h5>Data Ayah</h5>

    <div class="mb-3">
        <label>Nama Ayah</label>
        <input type="text" name="nama_ayah" value="<?= old('nama_ayah') ?>" class="form-control nama">
    </div>

    <div class="mb-3">
        <label>NIK Ayah</label>
        <input type="text" name="nik_ayah" value="<?= old('nik_ayah') ?>" class="form-control nik" maxlength="16">
    </div>

    <!-- ================= UPLOAD ================= -->
    <hr>
    <h5>Dokumen</h5>

    <!-- FOTO -->
    <div class="mb-3">
        <label>Foto Anak (JPG maks 400KB jika ≥5 tahun)</label>
        <input type="file" name="foto_anak" id="foto_anak"
               class="form-control"
               accept=".jpg,.jpeg"
               disabled>
    </div>

    <!-- KK -->
    <div class="mb-3">
        <label>Kartu Keluarga (PDF)</label>
        <input type="file" name="file_kk" id="file_kk"
               class="form-control"
               accept=".pdf">
    </div>

    <!-- AKTA -->
    <div class="mb-3">
        <label>Akta Kelahiran (PDF)</label>
        <input type="file" name="file_akta" id="file_akta"
               class="form-control"
               accept=".pdf">
    </div>

    <!-- F102 -->
    <div class="mb-3">
        <label>F1.02 (JPG maks 400KB, bisa banyak)</label>
        <input type="file" name="file_f102[]" multiple
               class="form-control"
               accept=".jpg,.jpeg"
               required>
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

// ================= VALIDASI FILE ADVANCE =================
document.addEventListener('change', function(e) {

    if (e.target.type === 'file') {

        let files = e.target.files;

        for (let file of files) {

            let ext = file.name.split('.').pop().toLowerCase();

            // FOTO & F102 → JPG 400KB
            if (e.target.name === 'foto_anak' || e.target.name === 'file_f102[]') {

                if (!['jpg','jpeg'].includes(ext)) {
                    alert('Harus JPG!');
                    e.target.value = '';
                    return;
                }

                if (file.size > 400 * 1024) {
                    alert('Maksimal 400KB!');
                    e.target.value = '';
                    return;
                }
            }

            // KK & AKTA → PDF 2MB
            if (e.target.name === 'file_kk' || e.target.name === 'file_akta') {

                if (ext !== 'pdf') {
                    alert('Harus PDF!');
                    e.target.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('Maksimal 2MB!');
                    e.target.value = '';
                    return;
                }
            }
        }
    }

});

// ================= UMUR =================
document.getElementById('tanggal_lahir').addEventListener('change', function() {

    let tgl = new Date(this.value);
    let now = new Date();

    let umur = now.getFullYear() - tgl.getFullYear();
    let m = now.getMonth() - tgl.getMonth();

    if (m < 0 || (m === 0 && now.getDate() < tgl.getDate())) umur--;

    let foto = document.getElementById('foto_anak');

    if (umur >= 5) {
        foto.disabled = false;
        foto.required = true;
    } else {
        foto.disabled = true;
        foto.required = false;
        foto.value = '';
    }

});

// ================= JENIS =================
document.getElementById('jenis_pengajuan').addEventListener('change', function() {

    let jenis = this.value;

    let nik = document.getElementById('nik_anak');
    let kk = document.getElementById('file_kk');
    let akta = document.getElementById('file_akta');

    if (jenis === 'baru') {

        nik.disabled = true; nik.value = '';
        kk.disabled = true; kk.value = '';
        akta.disabled = true; akta.value = '';

    } else {

        nik.disabled = false;
        kk.disabled = false;
        akta.disabled = false;
    }

});

// ================= AUTO FOCUS ERROR =================
<?php if (session()->getFlashdata('error')): ?>
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
<?php endif; ?>

</script>

<?= $this->endSection() ?>