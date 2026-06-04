<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Upload File Download</h3>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form action="<?= base_url('admin/download/store') ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul File</label>
        <input type="text" class="form-control" id="judul" name="judul" required>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
    </div>

    <div class="mb-3">
        <label for="file_upload" class="form-label">Pilih File PDF</label>
        <input type="file" class="form-control" id="file_upload" name="file_upload" accept=".pdf" required>
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>

<?= $this->endSection() ?>