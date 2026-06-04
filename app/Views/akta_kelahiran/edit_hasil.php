<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h4>Edit File Hasil</h4>

<div class="card p-4">

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('akta-kelahiran/update-hasil/'.$id) ?>" method="post" enctype="multipart/form-data">

        <?php if($file): ?>
            <p>File sekarang:</p>
            <b><?= $file['nama_file_asli'] ?></b>
        <?php else: ?>
            <p class="text-muted">Belum ada file</p>
        <?php endif; ?>

        <div class="mt-3">
            <label>Upload File Baru</label>
            <input type="file" name="file_hasil" class="form-control" required>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                Update File
            </button>

            <a href="<?= site_url('akta-kelahiran') ?>" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </form>

</div>

<?= $this->endSection() ?>