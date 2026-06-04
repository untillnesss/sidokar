<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $data = $pengajuan; ?>

<h3 class="mb-4">Detail Pengajuan Kartu Keluarga</h3>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- ================= DATA KEPALA ================= -->
<h5>Data Kepala Keluarga</h5>

<input class="form-control mb-2" value="<?= esc($data['nama_kepala']) ?>" readonly>
<input class="form-control mb-2" value="<?= esc($data['nik_kepala']) ?>" readonly>
<input class="form-control mb-2" value="<?= esc($data['no_kk']) ?>" readonly>

<textarea class="form-control mb-2" readonly><?= esc($data['alamat']) ?></textarea>

<input class="form-control mb-2" value="<?= esc($data['jenis_pengajuan']) ?>" readonly>
<input class="form-control mb-2" value="<?= esc($data['status']) ?>" readonly>

<hr>

<!-- ================= ANGGOTA ================= -->
<h5>Data Anggota Keluarga</h5>

<?php if($data['jenis_pengajuan'] == 'baru'): ?>

<table class="table table-bordered">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>SHDK</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($anggota as $a): ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= esc($a['nama']) ?></td>
            <td><?= esc($a['nik']) ?></td>
            <td><?= esc($a['shdk']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

<table class="table table-bordered">
    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Field</th>
            <th>Lama</th>
            <th>Baru</th>
            <th>Dasar</th>
            <th>Dokumen</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($anggota as $a): ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= esc($a['nama']) ?></td>
            <td><?= esc($a['field_diubah']) ?></td>
            <td><?= esc($a['nilai_lama']) ?></td>
            <td><?= esc($a['nilai_baru']) ?></td>
            <td><?= esc($a['dasar_perubahan']) ?></td>
            <td>
                <?php if(!empty($a['file_dokumen'])): ?>
                    <a href="<?= base_url('uploads/kartu-keluarga/'.$a['file_dokumen']) ?>" target="_blank">
                        📄 Lihat
                    </a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<hr>

<!-- ================= DOKUMEN ================= -->
<h5>Dokumen</h5>

<?php
// FIX: dokumen sudah di-group dari controller
$dok = $dokumen;
?>

<?php foreach(['KK','F1.02','F1.06'] as $j): ?>

<div class="mb-3">
    <b><?= $j ?></b><br><br>

    <?php if(!empty($dok[$j])): ?>
        <div class="d-flex flex-wrap gap-2">

            <?php foreach($dok[$j] as $f): ?>

                <?php
                $file = $f['nama_file'];
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $url = base_url('uploads/kartu-keluarga/'.$file);
                ?>

                <?php if(in_array($ext, ['jpg','jpeg','png'])): ?>

                    <img src="<?= $url ?>"
                         class="img-thumbnail"
                         style="width:120px;height:120px;object-fit:cover;cursor:pointer"
                         onclick="showImage(this.src)">

                <?php else: ?>

                    <a href="<?= $url ?>" target="_blank">📄 Lihat</a>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <small class="text-muted">Tidak ada</small>
    <?php endif; ?>

</div>

<hr>

<?php endforeach; ?>

<!-- ================= BUTTON ================= -->
<div class="mt-4 d-flex gap-2 flex-wrap">

    <a href="<?= base_url('kartu-keluarga') ?>" class="btn btn-secondary">
        ← Kembali
    </a>

    <?php if(session()->get('role') == 'admin' && $data['status'] == 'Pengajuan'): ?>

        <!-- SETUJUI -->
        <form action="<?= base_url('kartu-keluarga/setujui/'.$data['id_pengajuan']) ?>"
              method="post"
              onsubmit="return confirm('Setujui pengajuan ini?')">

            <?= csrf_field(); ?>

            <button type="submit" class="btn btn-success">
                ✔ Setujui
            </button>

        </form>

        <!-- TOLAK -->
        <button type="button"
                class="btn btn-danger"
                onclick="showTolakForm(<?= $data['id_pengajuan'] ?>)">
            ✖ Tolak
        </button>

    <?php endif; ?>

</div>

<!-- ================= FORM TOLAK ================= -->
<div id="tolakForm" class="card mt-3 d-none">
    <div class="card-body">

        <h5 class="text-danger">Form Revisi / Penolakan</h5>

        <form id="formTolak" method="post">
            <?= csrf_field(); ?>

            <textarea name="catatan_revisi"
                      class="form-control mb-3"
                      rows="4"
                      required
                      placeholder="Isi catatan revisi wajib diisi"></textarea>

            <div class="d-flex gap-2">
                <button class="btn btn-danger">Kirim</button>
                <button type="button" class="btn btn-secondary" onclick="hideTolakForm()">
                    Batal
                </button>
            </div>

        </form>

    </div>
</div>

<!-- ================= MODAL IMAGE ================= -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="previewImage" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
function showImage(src){
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function showTolakForm(id){
    document.getElementById('tolakForm').classList.remove('d-none');

    document.getElementById('formTolak').action =
        "<?= base_url('kartu-keluarga/tolak/') ?>/" + id;
}

function hideTolakForm(){
    document.getElementById('tolakForm').classList.add('d-none');
}
</script>

<?= $this->endSection() ?>