<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan Kartu Keluarga</h3>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form action="<?= base_url('kartu-keluarga/simpan') ?>" method="post" enctype="multipart/form-data">

<!-- JENIS -->
<div class="mb-3">
    <label>Jenis Pengajuan</label>
    <select name="jenis_pengajuan" id="jenis_pengajuan" class="form-control" required>
        <option value="">-- Pilih --</option>
        <option value="Baru">Baru</option>
        <option value="Perubahan">Perubahan</option>
    </select>
</div>

<div id="formUtama" class="d-none">

<h5>Data Kepala Keluarga</h5>

<input type="text" name="nama_kepala" class="form-control nama mb-2" placeholder="Nama Kepala" required>
<input type="text" name="nik_kepala" maxlength="16" class="form-control nik mb-2" placeholder="NIK Kepala" required>

<!-- ❌ NO KK (HANYA PERUBAHAN) -->
<div id="noKKWrapper" class="d-none">
    <input type="text" name="no_kk" maxlength="16" class="form-control nik mb-2" placeholder="No KK">
</div>

<textarea name="alamat" class="form-control mb-3" placeholder="Alamat"></textarea>

<hr>

<!-- PERUBAHAN -->
<div id="pilihPerubahan" class="d-none mb-3">
    <select id="field_diubah_global" class="form-control">
        <option value="">-- Pilih Data Diubah --</option>
        <option>Nama</option>
        <option>NIK</option>
        <option>Alamat</option>
        <option>Status Kawin</option>
        <option>Pendidikan</option>
        <option>Pekerjaan</option>
    </select>
</div>

<h5 id="titleAnggota" class="d-none">Data Anggota</h5>
<div id="anggotaContainer"></div>

<button type="button" id="btnTambah" class="btn btn-primary btn-sm mt-2 d-none">
    + Tambah Anggota
</button>

<hr>

<h5>Dokumen</h5>

<!-- ❌ KK hanya perubahan -->
<div id="kkWrapper" class="d-none">
    <label>KK</label>
    <input type="file" name="kk" class="form-control mb-2" accept=".jpg,.jpeg">
</div>

<label>F1.02</label>
<input type="file" name="f102[]" multiple class="form-control mb-2" accept=".jpg,.jpeg">

<label>F1.06</label>
<input type="file" name="f106[]" multiple class="form-control mb-3" accept=".jpg,.jpeg">

<button class="btn btn-success">Simpan</button>

</div>
</form>

<script>

let jenis = '';

document.getElementById('jenis_pengajuan').addEventListener('change', function(){

    jenis = this.value;

    let form = document.getElementById('formUtama');
    let pilih = document.getElementById('pilihPerubahan');
    let title = document.getElementById('titleAnggota');
    let btn = document.getElementById('btnTambah');
    let container = document.getElementById('anggotaContainer');
    let kk = document.getElementById('kkWrapper');
    let noKK = document.getElementById('noKKWrapper');

    container.innerHTML = '';
    form.classList.remove('d-none');

    if(jenis === 'Perubahan'){
        pilih.classList.remove('d-none');
        kk.classList.remove('d-none');
        noKK.classList.remove('d-none');

        title.classList.add('d-none');
        btn.classList.add('d-none');
    } else {
        pilih.classList.add('d-none');
        kk.classList.add('d-none');
        noKK.classList.add('d-none');

        title.classList.remove('d-none');
        btn.classList.remove('d-none');

        tambahAnggota(); // 🔥 penting biar SHDK muncul
    }

});

document.getElementById('field_diubah_global').addEventListener('change', function(){
    if(this.value !== ''){
        document.getElementById('titleAnggota').classList.remove('d-none');
        document.getElementById('btnTambah').classList.remove('d-none');

        document.getElementById('anggotaContainer').innerHTML = '';
        tambahAnggota();
    }
});

function tambahAnggota(){

    let container = document.getElementById('anggotaContainer');

    let html = `
    <div class="border p-3 mb-3">

        <input type="text" name="nama[]" class="form-control nama mb-2" placeholder="Nama" required>

        <input type="text" name="nik[]" class="form-control nik mb-2" placeholder="NIK" required>

        <!-- ✅ SHDK FIX -->
        <select name="shdk[]" class="form-control mb-2" required>
            <option value="">-- Pilih SHDK --</option>
            <option>Kepala Keluarga</option>
            <option>Istri</option>
            <option>Anak</option>
            <option>Menantu</option>
            <option>Cucu</option>
            <option>Orang Tua</option>
            <option>Mertua</option>
            <option>Famili Lain</option>
        </select>
    `;

    if(jenis === 'Perubahan'){
        html += `
        <input type="text" name="field_diubah[]" class="form-control mb-2" value="${document.getElementById('field_diubah_global').value}" readonly>
        <input type="text" name="nilai_lama[]" class="form-control mb-2" placeholder="Nilai Lama">
        <input type="text" name="nilai_baru[]" class="form-control mb-2" placeholder="Nilai Baru">
        <input type="text" name="dasar_perubahan[]" class="form-control mb-2" placeholder="Dasar Perubahan">
        <input type="file" name="file_dokumen[]" class="form-control mb-2" accept=".jpg,.jpeg">
        `;
    }

    html += `</div>`;

    container.insertAdjacentHTML('beforeend', html);
}

// tombol tambah
document.getElementById('btnTambah').addEventListener('click', tambahAnggota);

// VALIDASI
document.addEventListener('input', function(e){
    if(e.target.classList.contains('nama')){
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g,'');
    }
    if(e.target.classList.contains('nik')){
    e.target.value = e.target.value.replace(/[^0-9]/g,'').slice(0,16);
}
});

</script>

<?= $this->endSection() ?>