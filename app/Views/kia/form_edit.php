<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit Pengajuan KIA</h3>

<?php
$data = $kia ?? [];

// =========================
// MAPPING FILE
// =========================
$f102 = !empty($data['file_f102'])
    ? explode(',', $data['file_f102'])
    : [];

// =========================
// HELPER PREVIEW
// =========================
function isImage($file){
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg','jpeg','png']);
}

function tampilFile($file){

    $url = base_url('uploads/kia/'.$file);

    if(isImage($file)){

        return '
        <img src="'.$url.'"
            class="img-thumbnail preview-img"
            data-bs-toggle="modal"
            data-bs-target="#modalPreview"
            data-img="'.$url.'"
            style="
                width:120px;
                height:120px;
                object-fit:cover;
                cursor:pointer;
            ">
        ';
    }

    return '<a href="'.$url.'" target="_blank">📄 Lihat</a>';
}
?>

<form action="/kia/update/<?= $data['id_kia'] ?>"
      method="post"
      enctype="multipart/form-data">

<!-- =========================
DATA ANAK
========================= -->
<h5>Data Anak</h5>

<div class="mb-3">
    <label>Nama Anak</label>
    <input type="text"
           name="nama_anak"
           class="form-control nama"
           value="<?= $data['nama_anak'] ?>"
           required>
</div>

<div class="mb-3">
    <label>NIK Anak</label>
    <input type="text"
           name="nik_anak"
           class="form-control nik"
           maxlength="16"
           value="<?= $data['nik_anak'] ?>"
           required>
</div>

<div class="mb-3">
    <label>Tanggal Lahir</label>
    <input type="date"
           name="tanggal_lahir"
           class="form-control"
           value="<?= $data['tanggal_lahir'] ?>"
           required>
</div>

<div class="mb-3">
    <label>Jenis Kelamin</label>

    <select name="jenis_kelamin"
            class="form-control"
            required>

        <option value="L"
            <?= $data['jenis_kelamin']=='L'?'selected':'' ?>>
            Laki-laki
        </option>

        <option value="P"
            <?= $data['jenis_kelamin']=='P'?'selected':'' ?>>
            Perempuan
        </option>

    </select>
</div>

<div class="mb-3">
    <label>Tempat Lahir</label>

    <input type="text"
           name="tempat_lahir"
           class="form-control nama"
           value="<?= $data['tempat_lahir'] ?>"
           required>
</div>

<!-- =========================
DATA AYAH
========================= -->
<hr>

<h5>Data Ayah</h5>

<div class="mb-3">
    <label>Nama Ayah</label>

    <input type="text"
           name="nama_ayah"
           class="form-control nama"
           value="<?= $data['nama_ayah'] ?>"
           required>
</div>

<div class="mb-3">
    <label>NIK Ayah</label>

    <input type="text"
           name="nik_ayah"
           class="form-control nik"
           maxlength="16"
           value="<?= $data['nik_ayah'] ?>"
           required>
</div>

<!-- =========================
DOKUMEN
========================= -->
<hr>

<h5>Dokumen</h5>

<div class="row">

<?php
$single = [
    'foto_anak' => [
        'label' => 'Foto Anak',
        'file'  => $data['foto_anak'] ?? null
    ],

    'file_kk' => [
        'label' => 'Kartu Keluarga',
        'file'  => $data['file_kk'] ?? null
    ],

    'file_akta' => [
        'label' => 'Akta Kelahiran',
        'file'  => $data['file_akta'] ?? null
    ]
];
?>

<?php foreach($single as $name => $item): ?>

<div class="col-md-4 mb-3">

    <div class="border rounded p-3 text-center h-100 shadow-sm">

        <b><?= $item['label'] ?></b><br><br>

        <?php if(!empty($item['file'])): ?>

            <?= tampilFile($item['file']) ?><br>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada file
            </small><br>

        <?php endif; ?>

        <input type="file"
               name="<?= $name ?>"
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
<div class="col-12 mt-4">

    <h6>F1.02</h6>

    <div class="row">

    <?php if(!empty($f102)): ?>

        <?php foreach($f102 as $f): ?>

        <div class="col-md-2 mb-3">

            <div class="border rounded p-2 text-center shadow-sm h-100">

                <?= tampilFile($f) ?><br><br>

                <input type="checkbox"
                       name="hapus_f102[]"
                       value="<?= $f ?>">

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
           name="file_f102[]"
           multiple
           class="form-control"
           accept=".jpg,.jpeg">

    <small class="text-muted d-block mt-1">
        JPG/JPEG • Maks 400KB per file
    </small>

</div>

</div>

<hr>

<div class="mt-4 d-flex gap-2">

    <button class="btn btn-primary">
        Update
    </button>

    <a href="<?= base_url('kia') ?>"
       class="btn btn-secondary">
        Kembali
    </a>

</div>

</form>

<!-- =========================
MODAL PREVIEW
========================= -->
<div class="modal fade"
     id="modalPreview"
     tabindex="-1">

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
// PREVIEW MODAL
// =========================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;
    }
});

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
// VALIDASI FILE
// =========================
document.addEventListener('change', function(e){

    if(e.target.type !== 'file') return;

    let files = e.target.files;

    for(let file of files){

        // hanya jpg/jpeg
        let allowed = ['image/jpeg','image/jpg'];

        if(!allowed.includes(file.type)){

            alert('File harus JPG/JPEG!');

            e.target.value = '';

            return;
        }

        // max 400KB
        if(file.size > 400 * 1024){

            alert('Ukuran file maksimal 400KB!');

            e.target.value = '';

            return;
        }
    }
});
</script>

<?= $this->endSection() ?>