<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$isMaster = session()->get('role') === 'admin'
            && (int) session()->get('is_master') === 1;
?>

<h3 class="mb-4">Download Formulir</h3>

<?php if($isMaster): ?>
    <a href="<?= base_url('admin/download/create') ?>" class="btn btn-primary mb-3">
        <i class="fas fa-plus me-1"></i> Upload File Baru
    </a>
<?php endif; ?>

<?php if(empty($files)): ?>
    <p>Belum ada file yang diupload.</p>
<?php else: ?>
<div class="row">
    <?php foreach($files as $file): ?>
    <div class="col-md-4 mb-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= esc($file['judul']) ?></h5>

                <?php if(!empty($file['deskripsi'])): ?>
                    <p class="card-text"><?= esc($file['deskripsi']) ?></p>
                <?php endif; ?>

                <p class="card-text small text-muted">
                    <?= esc($file['nama_file']) ?>
                </p>

                <div class="mt-auto">

                    <div class="d-flex justify-content-between mb-2">

                        <!-- Tombol Lihat (Semua Role) -->
                        <a href="<?= base_url('lihat-file/'.$file['id']) ?>" 
                           target="_blank"
                           class="btn btn-info btn-sm">
                            <i class="fas fa-eye me-1"></i> Lihat
                        </a>

                        <!-- Download (Semua Role) -->
                        <a href="<?= base_url('download-file/'.$file['id']) ?>" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-download me-1"></i> Download
                        </a>

                    </div>

                    <?php if($isMaster): ?>
                        <div class="d-flex gap-1">
                            <a href="<?= base_url('admin/download/edit/'.$file['id']) ?>" 
                               class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a href="<?= base_url('admin/download/delete/'.$file['id']) ?>" 
                               class="btn btn-danger btn-sm w-100"
                               onclick="return confirm('Yakin ingin menghapus file ini?');">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>