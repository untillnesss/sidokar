<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Kelola File Download</h3>

<button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#uploadModal" onclick="prepareModal('new')">
    <i class="fas fa-plus me-1"></i> Upload File Baru
</button>

<?php if(empty($files)): ?>
    <div class="alert alert-info">Belum ada file yang diupload.</div>
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
                <p class="text-muted small">Diunggah: <?= date('d M Y', strtotime($file['created_at'])) ?></p>
                <div class="mt-auto d-flex gap-2">
                    <a href="<?= base_url('download-file/'.$file['id']) ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    <button class="btn btn-warning btn-sm" 
                            onclick="prepareModal('edit', <?= $file['id'] ?>, '<?= esc($file['judul'], 'js') ?>', '<?= esc($file['deskripsi'], 'js') ?>')">
                        <i class="fas fa-edit me-1"></i> Ganti File
                    </button>
                    <a href="<?= base_url('admin/download/delete/'.$file['id']) ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin ingin menghapus file ini?');">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal Upload / Edit -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="uploadForm" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Upload File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="file_id" id="file_id">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" class="form-control" name="judul" id="judul" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="file_upload" class="form-label">Pilih File (PDF, max 5MB)</label>
                <input type="file" class="form-control" name="file_upload" id="file_upload" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">Upload</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script>
function prepareModal(type, id = null, judul = '', deskripsi = '') {
    const modalTitle = document.getElementById('modalTitle');
    const fileId = document.getElementById('file_id');
    const judulInput = document.getElementById('judul');
    const deskripsiInput = document.getElementById('deskripsi');
    const fileInput = document.getElementById('file_upload');
    const form = document.getElementById('uploadForm');

    if(type === 'new') {
        modalTitle.innerText = 'Upload File Baru';
        fileId.value = '';
        judulInput.value = '';
        deskripsiInput.value = '';
        fileInput.required = true;
        form.action = "<?= base_url('admin/download/store') ?>";
    } else if(type === 'edit') {
        modalTitle.innerText = 'Ganti File';
        fileId.value = id;
        judulInput.value = judul;
        deskripsiInput.value = deskripsi;
        fileInput.required = false; // opsional ganti file
        form.action = "<?= base_url('admin/download/store') ?>?edit=" + id;
    }
}
</script>

<?= $this->endSection() ?>