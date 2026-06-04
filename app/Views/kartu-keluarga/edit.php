<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $data = $pengajuan; ?>

<h3 class="mb-4">Edit Pengajuan Kartu Keluarga</h3>

<form action="<?= base_url('kartu-keluarga/update/'.$data['id_pengajuan']) ?>" 
      method="post" 
      enctype="multipart/form-data">

<!-- ================= JENIS ================= -->
<div class="mb-3">
    <label>Jenis Pengajuan</label>

    <select name="jenis_pengajuan"
            id="jenis_pengajuan"
            class="form-control"
            required>

        <option value="">-- Pilih --</option>

        <option value="baru"
            <?= $data['jenis_pengajuan']=='baru'?'selected':'' ?>>
            Baru
        </option>

        <option value="perubahan"
            <?= $data['jenis_pengajuan']=='perubahan'?'selected':'' ?>>
            Perubahan
        </option>

    </select>
</div>

<!-- ================= KEPALA KELUARGA ================= -->
<h5>Data Kepala Keluarga</h5>

<input type="text"
       name="nama_kepala"
       class="form-control mb-2"
       placeholder="Nama Kepala Keluarga"
       value="<?= $data['nama_kepala'] ?>">

<input type="text"
       name="nik_kepala"
       class="form-control mb-2"
       placeholder="NIK Kepala"
       maxlength="16"
       value="<?= $data['nik_kepala'] ?>"
       oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">

<input type="text"
       name="no_kk"
       class="form-control mb-2"
       placeholder="Nomor KK"
       maxlength="16"
       value="<?= $data['no_kk'] ?>"
       oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">

<textarea name="alamat"
          class="form-control mb-3"
          placeholder="Alamat"><?= $data['alamat'] ?></textarea>

<hr>

<!-- ================= ANGGOTA ================= -->
<h5>Data</h5>

<div id="anggotaContainer">

<?php foreach($anggota as $a): ?>

<div class="border p-3 mb-3 rounded">

    <input type="text"
           name="nama[]"
           class="form-control mb-2"
           placeholder="Nama"
           value="<?= $a['nama'] ?>">

    <input type="text"
           name="nik[]"
           class="form-control mb-2"
           placeholder="NIK"
           maxlength="16"
           value="<?= $a['nik'] ?>"
           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,16)">

    <select name="shdk[]" class="form-control mb-2">

        <option value="">-- Pilih SHDK --</option>

        <?php
        $list = [
            'Kepala Keluarga',
            'Istri',
            'Anak',
            'Menantu',
            'Cucu',
            'Orang Tua',
            'Mertua',
            'Famili Lain'
        ];

        foreach($list as $l):
        ?>

        <option <?= $a['shdk']==$l?'selected':'' ?>>
            <?= $l ?>
        </option>

        <?php endforeach; ?>

    </select>

    <!-- ================= KHUSUS PERUBAHAN ================= -->
    <?php if($data['jenis_pengajuan']=='perubahan'): ?>

        <input type="text"
               name="field_diubah[]"
               class="form-control mb-2"
               placeholder="Field yang diubah"
               value="<?= $a['field_diubah'] ?>">

        <input type="text"
               name="nilai_lama[]"
               class="form-control mb-2"
               placeholder="Nilai lama"
               value="<?= $a['nilai_lama'] ?>">

        <input type="text"
               name="nilai_baru[]"
               class="form-control mb-2"
               placeholder="Nilai baru"
               value="<?= $a['nilai_baru'] ?>">

        <input type="text"
               name="dasar_perubahan[]"
               class="form-control mb-2"
               placeholder="Dasar perubahan"
               value="<?= $a['dasar_perubahan'] ?>">

        <?php if(!empty($a['file_dokumen'])): ?>

            <div class="mb-2">

                <small class="d-block mb-1">
                    File lama:
                </small>

                <img src="<?= base_url('uploads/kartu-keluarga/'.$a['file_dokumen']) ?>"
                     class="img-thumbnail preview-img"
                     data-img="<?= base_url('uploads/kartu-keluarga/'.$a['file_dokumen']) ?>"
                     style="width:120px;height:120px;object-fit:cover;cursor:pointer;">

            </div>

        <?php endif; ?>

        <input type="file"
               name="file_dokumen[]"
               class="form-control file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted">
            JPG max 400KB
        </small>

    <?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<button type="button"
        id="btnTambah"
        class="btn btn-primary btn-sm mt-2">
    + Tambah Data
</button>

<hr>

<!-- ================= DOKUMEN ================= -->
<h5>Dokumen</h5>

<?php

$dok = [];

if (!empty($dokumen)) {

    foreach ($dokumen as $d) {

        if(isset($d['jenis_dokumen'])){
            $dok[$d['jenis_dokumen']][] = $d;
        }
    }
}

function tampilFileKK($file)
{
    $url = base_url('uploads/kartu-keluarga/' . $file);

    return '
    <img src="'.$url.'"
         class="img-thumbnail preview-img"
         data-img="'.$url.'"
         style="
            width:120px;
            height:120px;
            object-fit:cover;
            cursor:pointer;
         ">
    ';
}
?>

<div class="row">

<!-- ================= KK ================= -->
<div class="col-md-4 mb-3">

    <div class="border p-2 text-center h-100">

        <b>KK Lama</b><br>

        <?php if (!empty($dok['KK'])): ?>

            <?= tampilFileKK($dok['KK'][0]['nama_file']) ?><br>

            <input type="checkbox"
                   name="hapus_dokumen[]"
                   value="<?= $dok['KK'][0]['id_dokumen'] ?>">

            <small class="text-danger">
                Hapus
            </small>

        <?php else: ?>

            <small class="text-muted">
                Tidak ada
            </small>

        <?php endif; ?>

        <input type="file"
               name="kk"
               class="form-control mt-2 file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted d-block mt-1">
            JPG max 400KB
        </small>

    </div>

</div>

<!-- ================= F1.02 ================= -->
<div class="col-md-4 mb-3">

    <div class="border p-2 text-center h-100">

        <b>F1.02</b><br>

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">

            <?php if (!empty($dok['F1.02'])): ?>

                <?php foreach ($dok['F1.02'] as $f): ?>

                    <div>

                        <?= tampilFileKK($f['nama_file']) ?><br>

                        <input type="checkbox"
                               name="hapus_dokumen[]"
                               value="<?= $f['id_dokumen'] ?>">

                        <small class="text-danger">
                            Hapus
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <small class="text-muted">
                    Tidak ada
                </small>

            <?php endif; ?>

        </div>

        <input type="file"
               name="f102[]"
               multiple
               class="form-control file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted d-block mt-1">
            JPG max 400KB
        </small>

    </div>

</div>

<!-- ================= F1.06 ================= -->
<div class="col-md-4 mb-3">

    <div class="border p-2 text-center h-100">

        <b>F1.06</b><br>

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-2">

            <?php if (!empty($dok['F1.06'])): ?>

                <?php foreach ($dok['F1.06'] as $f): ?>

                    <div>

                        <?= tampilFileKK($f['nama_file']) ?><br>

                        <input type="checkbox"
                               name="hapus_dokumen[]"
                               value="<?= $f['id_dokumen'] ?>">

                        <small class="text-danger">
                            Hapus
                        </small>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <small class="text-muted">
                    Tidak ada
                </small>

            <?php endif; ?>

        </div>

        <input type="file"
               name="f106[]"
               multiple
               class="form-control file-validation"
               accept=".jpg,.jpeg,image/jpeg">

        <small class="text-muted d-block mt-1">
            JPG max 400KB
        </small>

    </div>

</div>

</div>

<!-- ================= BUTTON ================= -->
<div class="mt-3">

    <button class="btn btn-success">
        Update
    </button>

    <a href="<?= base_url('kartu-keluarga') ?>"
       class="btn btn-secondary">
        Kembali
    </a>

</div>

</form>

<!-- ================= MODAL PREVIEW ================= -->
<div class="modal fade" id="imageModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body text-center">

                <img id="previewImage"
                     src=""
                     class="img-fluid rounded">

            </div>

        </div>

    </div>

</div>

<!-- ================= SCRIPT ================= -->
<script>

// ================= VALIDASI FILE =================
const maxSize = 400 * 1024;

document.querySelectorAll('.file-validation').forEach(input => {

    input.addEventListener('change', function(){

        let files = this.files;

        for(let i = 0; i < files.length; i++){

            let file = files[i];

            // FORMAT
            if(!['image/jpeg','image/jpg'].includes(file.type)){

                alert("File harus format JPG");

                this.value = "";

                return;
            }

            // SIZE
            if(file.size > maxSize){

                alert("Ukuran file maksimal 400KB");

                this.value = "";

                return;
            }
        }
    });
});

// ================= PREVIEW =================
document.addEventListener('click', function(e){

    if(e.target.classList.contains('preview-img')){

        let img = e.target.getAttribute('data-img');

        document.getElementById('previewImage').src = img;

        new bootstrap.Modal(
            document.getElementById('imageModal')
        ).show();
    }
});

</script>

<?= $this->endSection() ?>