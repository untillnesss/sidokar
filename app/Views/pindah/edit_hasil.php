<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h4 class="mb-4">Edit File Hasil</h4>

<form action="<?= base_url('pindah/update-hasil/'.$id) ?>" method="post" enctype="multipart/form-data">

    <!-- SKPWNI -->
    <div class="mb-3">
        <label class="form-label">SKPWNI</label><br>

        <?php if($skpwni): ?>
            <a href="<?= base_url($skpwni['file_hasil']) ?>" target="_blank" class="btn btn-info btn-sm">
                Lihat SKPWNI
            </a>
        <?php else: ?>
            <small class="text-muted">Belum ada file</small>
        <?php endif; ?>

        <input type="file" name="file_skpwni" class="form-control mt-2">
    </div>

    <!-- KK -->
    <div class="mb-3">
        <label class="form-label">KK Baru</label><br>

        <?php if($kk): ?>
            <a href="<?= base_url($kk['file_hasil']) ?>" target="_blank" class="btn btn-info btn-sm">
                Lihat KK
            </a>
        <?php else: ?>
            <small class="text-muted">Belum ada file</small>
        <?php endif; ?>

        <input type="file" name="file_kk" class="form-control mt-2">
    </div>

    <button type="submit" class="btn btn-success">Update File</button>
    <a href="<?= base_url('pindah') ?>" class="btn btn-secondary">Kembali</a>

</form>

<?= $this->endSection() ?>