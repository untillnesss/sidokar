<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Form Pengajuan Pindah</h3>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form action="/pindah/simpan" method="post" enctype="multipart/form-data">

    <!-- NAMA PEMOHON -->
    <div class="mb-3">
        <label>Nama Pemohon</label>
        <input type="text" name="nama_pemohon" class="form-control nama" placeholder="Masukkan nama pemohon" required>
    </div>

    <div class="mb-3">
    <label>Jenis Pindah</label>
    <select name="jenis_pindah" class="form-control" required>
        <option value="">-- Pilih --</option>
        <option value="datang">Datang</option>
        <option value="keluar">Keluar</option>
    </select>
</div>

    <!-- KATEGORI -->
    <div class="mb-3">
        <label>Kategori Pindah</label>
        <select name="kategori_pindah" id="kategori" class="form-control" onchange="handleKategori()" required>
            <option value="">-- Pilih --</option>
            <option value="Kepala Keluarga">Kepala Keluarga</option>
            <option value="Sebagian Anggota Keluarga">Sebagian Anggota Keluarga</option>
            <option value="Seluruh Anggota Keluarga">Seluruh Anggota Keluarga</option>
        </select>
    </div>

    <!-- ANGGOTA -->
    <div id="anggota-section" style="display:none;">
        <h5>Data Anggota yang Pindah</h5>
        <div id="anggota-wrapper"></div>

        <button type="button" id="btn-tambah" onclick="tambahAnggota()" class="btn btn-sm btn-secondary mb-3">
            + Tambah Anggota
        </button>
    </div>

    <!-- TUJUAN -->
    <div class="mb-3">
        <label>Jenis Tujuan</label>
        <select name="jenis_tujuan" id="tujuan" class="form-control" onchange="handleTujuan()" required>
            <option value="">-- Pilih --</option>
            <option value="Antar Desa">Antar Desa</option>
            <option value="Antar Kecamatan">Antar Kecamatan</option>
            <option value="Antar Kabupaten/Kota">Antar Kabupaten</option>
        </select>
    </div>

    <!-- SURAT -->
    <div class="mb-3" id="surat-section" style="display:none;">
        <label>Surat Tujuan (JPG max 400KB)</label>
        <input type="file" name="surat_tujuan" class="form-control">
    </div>

    <!-- ALAMAT -->
    <div class="mb-3">
        <label>Alamat Asal</label>
        <textarea name="alamat_asal" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Alamat Tujuan</label>
        <textarea name="alamat_tujuan" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Alasan Pindah</label>
        <textarea name="alasan" class="form-control" required></textarea>
    </div>

    <!-- DOKUMEN -->
    <hr>
    <h5>Dokumen Wajib</h5>

    <div class="mb-3">
        <label>KK (JPG max 400KB)</label>
        <input type="file" name="kk" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Surat Desa Asal (JPG max 400KB)</label>
        <input type="file" name="surat_desa" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>F1.02 (max 5 JPG, @400KB)</label>
        <input type="file" name="f102[]" multiple class="form-control" required>
    </div>

    <div class="mb-3">
        <label>F1.06 (max 5 JPG, @400KB)</label>
        <input type="file" name="f106[]" multiple class="form-control" required>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

<script>

// =========================
// VALIDASI REALTIME
// =========================
document.addEventListener('input', function(e) {

    if (e.target.classList.contains('nama')) {
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g, '');
    }

    if (e.target.classList.contains('nik')) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    }

});

// =========================
// HANDLE KATEGORI
// =========================
function handleKategori() {
    let kategori = document.getElementById('kategori').value;
    let section = document.getElementById('anggota-section');
    let wrapper = document.getElementById('anggota-wrapper');
    let btnTambah = document.getElementById('btn-tambah');

    wrapper.innerHTML = '';

    if (!kategori) {
        section.style.display = 'none';
        return;
    }

    section.style.display = 'block';

    if (kategori === 'Kepala Keluarga') {
        wrapper.innerHTML = generateRow(0, true);
        btnTambah.style.display = 'none';
    } else {
        tambahAnggota();
        btnTambah.style.display = 'inline-block';
    }
}

// =========================
// TEMPLATE ROW (ADA CHECKBOX KTP)
// =========================
function generateRow(index, isKepala = false) {
    return `
    <div class="row mb-2 anggota-item align-items-center">

        <div class="col">
            <input type="text" name="nama_anggota[]" class="form-control nama"
                placeholder="Nama" required>
        </div>

        <div class="col">
            <input type="text" name="nik_anggota[]" class="form-control nik"
                placeholder="NIK (16 digit)" maxlength="16" required>
        </div>

        <div class="col">
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                    onchange="toggleKTP(this, ${index})">
                <label class="form-check-label">Punya KTP</label>
            </div>
        </div>

        <div class="col">
            <input type="file" name="ktp_individu[]"
                id="ktp_${index}"
                class="form-control"
                style="display:none;">
        </div>

        ${isKepala ? '' : `
        <div class="col-1">
            <button type="button" onclick="hapusAnggota(this)" class="btn btn-danger btn-sm">X</button>
        </div>`}

    </div>
    `;
}

// =========================
// TAMBAH ANGGOTA
// =========================
function tambahAnggota() {
    let index = document.querySelectorAll('.anggota-item').length;
    document.getElementById('anggota-wrapper')
        .insertAdjacentHTML('beforeend', generateRow(index));
}

// =========================
// HAPUS
// =========================
function hapusAnggota(btn) {
    btn.closest('.anggota-item').remove();
}

// =========================
// TOGGLE KTP
// =========================
function toggleKTP(checkbox, index) {
    let input = document.getElementById('ktp_' + index);

    if (checkbox.checked) {
        input.style.display = 'block';
        input.required = true;
    } else {
        input.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}

// =========================
// HANDLE TUJUAN
// =========================
function handleTujuan() {
    let tujuan = document.getElementById('tujuan').value;
    let surat = document.getElementById('surat-section');

    surat.style.display =
        (tujuan === 'Antar Desa' || tujuan === 'Antar Kecamatan') ? 'block' : 'none';
}

// =========================
// VALIDASI FILE REALTIME
// =========================
document.addEventListener('change', function(e) {

    if (e.target.type === 'file') {

        let files = e.target.files;

        for (let i = 0; i < files.length; i++) {

            let file = files[i];

            // VALIDASI EXTENSION
            let ext = file.name.split('.').pop().toLowerCase();
            if (ext !== 'jpg' && ext !== 'jpeg') {
                alert('File harus JPG!');
                e.target.value = '';
                return;
            }

            // VALIDASI SIZE (400KB)
            if (file.size > 400 * 1024) {
                alert('File terlalu besar! Maksimal 400KB');
                e.target.value = '';
                return;
            }
        }
    }

});
</script>

<?= $this->endSection() ?>