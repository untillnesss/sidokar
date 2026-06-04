<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Download Formulir</h3>

<?php if(session()->get('role') == 'admin' && session()->get('is_master') == 1): ?>
    <div class="mb-4">
        <a href="<?= base_url('admin/download/create') ?>" class="btn btn-primary">
            <i class="fas fa-upload me-1"></i> Unggah File Baru
        </a>
    </div>
<?php endif; ?>

<?php if(empty($files)): ?>
    <p>Belum ada file yang diupload.</p>
<?php else: ?>
    <div class="row">
        <?php foreach($files as $file): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= esc($file['judul']) ?></h5>
                        <?php if(!empty($file['deskripsi'])): ?>
                            <p class="card-text"><?= esc($file['deskripsi']) ?></p>
                        <?php endif; ?>
                        <p class="card-text small text-muted">Nama File: <?= esc($file['nama_file']) ?></p>
                        <div class="mt-auto d-flex justify-content-between">

                            <!-- Download selalu muncul -->
                            <a href="<?= base_url('download-file/'.$file['id']) ?>" 
                               class="btn btn-success btn-sm" target="_blank">
                               <i class="fas fa-download me-1"></i> Download
                            </a>

                            <?php if(session()->get('role') == 'admin' && session()->get('is_master') == 1): ?>
                                <div class="d-flex gap-1">
                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('admin/download/edit/'.$file['id']) ?>" 
                                       class="btn btn-warning btn-sm">
                                       <i class="fas fa-edit me-1"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="<?= base_url('admin/download/delete/'.$file['id']) ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus file ini?');">
                                       <i class="fas fa-trash me-1"></i> Hapus
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