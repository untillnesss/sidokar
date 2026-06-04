<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Edit File: <?= esc($file['judul']) ?></h3>

<form action="<?= base_url('admin/download/update/'.$file['id']) ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" name="judul" id="judul" class="form-control" value="<?= esc($file['judul']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?= esc($file['deskripsi']) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="file_upload" class="form-label">Ganti File (Opsional)</label>
        <input type="file" name="file_upload" id="file_upload" class="form-control">
        <small class="text-muted">File lama: <?= esc($file['nama_file']) ?></small>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Update File
    </button>
    <a href="<?= base_url('admin/download') ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>