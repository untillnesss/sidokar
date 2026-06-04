<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit Pengajuan Pindah</h3>

<?php
// =========================
// MAPPING DOKUMEN
// =========================
$dok = [];

if(!empty($dokumen)){
    foreach($dokumen as $d){
        $dok[$d['jenis_dokumen']][] = $d;
    }
}

// =========================
// HELPER PREVIEW
// =========================
function isImage($file){
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg','jpeg','png']);
}

function tampilFile($file){
    $url = base_url('uploads/pindah/'.$file);

    if(isImage($file)){
        return '
        <img src="'.$url.'"
            class="img-thumbnail preview-img"
            data-bs-toggle="modal"
            data-bs-target="#modalPreview"
            data-img="'.$url.'"
            style="width:120px;height:120px;object-fit:cover;cursor:pointer;">
        ';
    } else {
        return '<a href="'.$url.'" target="_blank">📄 Lihat</a>';
    }
}
?>

<form action="<?= base_url('pindah/update/'.$pengajuan['id_pengajuan']) ?>"
      method="post"
      enctype="multipart/form-data">

<!-- =========================
DATA PEMOHON
========================= -->
<h5>Data Pemohon</h5>

<input type="text"
       name="nama_pemohon"
       class="form-control mb-2 nama"
       value="<?= $pengajuan['nama_pemohon'] ?>"
       placeholder="Nama Pemohon"
       required>

<select name="jenis_pindah"
        class="form-control mb-2"
        required>
    <option value="">-- Pilih Jenis Pindah --</option>

    <option value="datang"
        <?= $pengajuan['jenis_pindah']=='datang'?'selected':'' ?>>
        Datang
    </option>

    <option value="keluar"
        <?= $pengajuan['jenis_pindah']=='keluar'?'selected':'' ?>>
        Keluar
    </option>
</select>

<select name="kategori_pindah"
        id="kategori"
        class="form-control mb-3"
        onchange="handleKategori()"
        required>

    <option value="">-- Pilih Kategori --</option>

    <option value="Kepala Keluarga"
        <?= $pengajuan['kategori_pindah']=='Kepala Keluarga'?'selected':'' ?>>
        Kepala Keluarga
    </option>

    <option value="Sebagian Anggota Keluarga"
        <?= $pengajuan['kategori_pindah']=='Sebagian Anggota Keluarga'?'selected':'' ?>>
        Sebagian Anggota Keluarga
    </option>

    <option value="Seluruh Anggota Keluarga"
        <?= $pengajuan['kategori_pindah']=='Seluruh Anggota Keluarga'?'selected':'' ?>>
        Seluruh Anggota Keluarga
    </option>
</select>

<!-- =========================
ANGGOTA
========================= -->
<div id="anggota-section">

<h5>Data Anggota</h5>

<div id="anggota-wrapper">

<?php if(!empty($anggota)): ?>
<?php foreach($anggota as $i => $a): ?>

<div class="border rounded p-3 mb-3 anggota-item">

    <div class="row">

        <div class="col-md-5 mb-2">
            <input type="text"
                   name="nama_anggota[]"
                   class="form-control nama"
                   value="<?= $a['nama_anggota'] ?>"
                   placeholder="Nama Anggota"
                   required>
        </div>

        <div class="col-md-5 mb-2">
            <input type="text"
                   name="nik_anggota[]"
                   class="form-control nik"
                   value="<?= $a['nik_anggota'] ?>"
                   maxlength="16"
                   placeholder="NIK"
                   required>
        </div>

        <div class="col-md-2 mb-2">
            <button type="button"
                    onclick="hapusAnggota(this)"
                    class="btn btn-danger w-100">
                Hapus
            </button>
        </div>

    </div>

    <?php
    $ktpFile = null;

    if(!empty($dok['KTP'])){
        $ktpFile = $dok['KTP'][$i]['nama_file'] ?? null;
    }
    ?>

    <div class="mt-2">

        <label class="form-label">
            KTP Anggota
        </label>

        <?php if($ktpFile): ?>

            <div class="mb-2">
                <?= tampilFile($ktpFile) ?>
            </div>

        <?php else: ?>

            <small class="text-muted d-block mb-2">
                Tidak ada file
            </small>

        <?php endif; ?>

        <input type="file"
               name="ktp_individu[]"
               class="form-control">

    </div>

</div>

<?php endforeach; ?>
<?php endif; ?>

</div>

<button type="button"
        onclick="tambahAnggota()"
        class="btn btn-secondary btn-sm mb-4">

    + Tambah Anggota

</button>

</div>

<!-- =========================
TUJUAN
========================= -->
<h5>Data Tujuan</h5>

<select name="jenis_tujuan"
        class="form-control mb-2"
        required>

    <option value="">-- Pilih Tujuan --</option>

    <option value="Antar Desa"
        <?= $pengajuan['jenis_tujuan']=='Antar Desa'?'selected':'' ?>>
        Antar Desa
    </option>

    <option value="Antar Kecamatan"
        <?= $pengajuan['jenis_tujuan']=='Antar Kecamatan'?'selected':'' ?>>
        Antar Kecamatan
    </option>

    <option value="Antar Kabupaten/Kota"
        <?= $pengajuan['jenis_tujuan']=='Antar Kabupaten/Kota'?'selected':'' ?>>
        Antar Kabupaten/Kota
    </option>

</select>

<textarea name="alamat_asal"
          class="form-control mb-2"
          placeholder="Alamat Asal"><?= $pengajuan['alamat_asal'] ?></textarea>

<textarea name="alamat_tujuan"
          class="form-control mb-2"
          placeholder="Alamat Tujuan"><?= $pengajuan['alamat_tujuan'] ?></textarea>

<textarea name="alasan"
          class="form-control mb-4"
          placeholder="Alasan"><?= $pengajuan['alasan'] ?></textarea>

<hr>

<h5>Dokumen</h5>

<div class="row">

<?php
$single = [
    'KK' => 'Kartu Keluarga',
    'Surat Desa' => 'Surat Desa'
];
?>

<?php foreach($single as $key => $label): ?>

<div class="col-md-3 mb-3">

    <div class="border rounded p-2 text-center h-100 shadow-sm">

        <b><?= $label ?></b><br><br>

        <?php if(!empty($dok[$key])): ?>

            <?= tampilFile($dok[$key][0]['nama_file']) ?><br>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small><br>

        <?php endif; ?>

        <input type="file"
               name="<?= strtolower(str_replace(' ','_',$key)) ?>"
               class="form-control mt-2"
               accept=".jpg,.jpeg">

        <small class="text-muted d-block mt-1">
            JPG/JPEG • Maks 400KB
        </small>

    </div>

</div>

<?php endforeach; ?>

<!-- =========================
F1.02
========================= -->
<div class="col-12 mt-3">

    <h6>F1.02</h6>

    <div class="row">

    <?php if(!empty($dok['F1.02'])): ?>

        <?php foreach($dok['F1.02'] as $f): ?>

        <div class="col-md-2 mb-3">

            <div class="border rounded p-2 text-center shadow-sm h-100">

                <?= tampilFile($f['nama_file']) ?><br><br>

                <input type="checkbox"
                       name="hapus_f102[]"
                       value="<?= $f['id_dokumen'] ?>">

                <small>Hapus</small>

            </div>

        </div>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

    <input type="file"
           name="f102[]"
           multiple
           class="form-control"
           accept=".jpg,.jpeg">

    <small class="text-muted d-block mt-1">
        JPG/JPEG • Maks 400KB per file
    </small>

</div>

<!-- =========================
F1.06
========================= -->
<div class="col-12 mt-4">

    <h6>F1.06</h6>

    <div class="row">

    <?php if(!empty($dok['F1.06'])): ?>

        <?php foreach($dok['F1.06'] as $f): ?>

        <div class="col-md-2 mb-3">

            <div class="border rounded p-2 text-center shadow-sm h-100">

                <?= tampilFile($f['nama_file']) ?><br><br>

                <input type="checkbox"
                       name="hapus_f106[]"
                       value="<?= $f['id_dokumen'] ?>">

                <small>Hapus</small>

            </div>

        </div>

        <?php endforeach; ?>

    <?php else: ?>

        <small class="text-muted">
            Tidak ada file
        </small>

    <?php endif; ?>

    </div>

    <input type="file"
           name="f106[]"
           multiple
           class="form-control"
           accept=".jpg,.jpeg">

    <small class="text-muted d-block mt-1">
        JPG/JPEG • Maks 400KB per file
    </small>

</div>

</div>

<script>
// =========================
// VALIDASI FILE
// =========================
document.addEventListener('change', function(e){

    if(e.target.type !== 'file') return;

    let files = e.target.files;

    for(let file of files){

        // validasi ekstensi
        let allowed = ['image/jpeg','image/jpg'];

        if(!allowed.includes(file.type)){
            alert('File harus JPG/JPEG!');
            e.target.value = '';
            return;
        }

        // validasi ukuran
        if(file.size > 400 * 1024){
            alert('Ukuran file maksimal 400KB!');
            e.target.value = '';
            return;
        }
    }
});
</script>

<hr>

<div class="mt-4 d-flex gap-2">

    <button type="submit" class="btn btn-success">
        💾 Simpan
    </button>

    <a href="<?= base_url('pindah') ?>"
       class="btn btn-secondary">

       ← Kembali

    </a>

</div>

</form>

<!-- =========================
MODAL PREVIEW
========================= -->
<div class="modal fade" id="modalPreview" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Preview Dokumen
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body text-center">

                <img id="previewImage"
                     src=""
                     class="img-fluid rounded">

            </div>

        </div>

    </div>

</div>

<script>

// =========================
// VALIDASI INPUT
// =========================
document.addEventListener('input', function(e){

    if(e.target.classList.contains('nama')){
        e.target.value = e.target.value.replace(/[^A-Za-z\s]/g,'');
    }

    if(e.target.classList.contains('nik')){
        e.target.value = e.target.value.replace(/[^0-9]/g,'');
    }

});

// =========================
// PREVIEW MODAL
// =========================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;
    }

});

// =========================
// KATEGORI
// =========================
function handleKategori(){

    let val = document.getElementById('kategori').value;

    document.getElementById('anggota-section').style.display =
        val ? 'block' : 'none';
}

// =========================
// TAMBAH ANGGOTA
// =========================
function tambahAnggota(){

    let html = `
    <div class="border rounded p-3 mb-3 anggota-item">

        <div class="row">

            <div class="col-md-5 mb-2">
                <input type="text"
                       name="nama_anggota[]"
                       class="form-control nama"
                       placeholder="Nama Anggota"
                       required>
            </div>

            <div class="col-md-5 mb-2">
                <input type="text"
                       name="nik_anggota[]"
                       class="form-control nik"
                       maxlength="16"
                       placeholder="NIK"
                       required>
            </div>

            <div class="col-md-2 mb-2">
                <button type="button"
                        onclick="hapusAnggota(this)"
                        class="btn btn-danger w-100">

                    Hapus

                </button>
            </div>

        </div>

        <input type="file"
               name="ktp_individu[]"
               class="form-control mt-2">

    </div>
    `;

    document.getElementById('anggota-wrapper')
        .insertAdjacentHTML('beforeend', html);
}

// =========================
// HAPUS ANGGOTA
// =========================
function hapusAnggota(btn){
    btn.closest('.anggota-item').remove();
}

handleKategori();

</script>
<script>

<?= $this->endSection() ?>