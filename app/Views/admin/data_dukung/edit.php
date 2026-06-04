<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <h3 class="mb-4">Edit Data Dukung</h3>

    <form action="<?= base_url('data-dukung/update/'.$data['id']) ?>" method="post">
        <?= csrf_field() ?>

        <!-- penting: kembali ke halaman sebelumnya -->
        <input type="hidden" name="redirect_back" value="<?= previous_url() ?>">

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori"
                   class="form-control"
                   value="<?= esc($data['kategori']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul"
                   class="form-control"
                   value="<?= esc($data['judul']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="isi"
                      class="form-control"
                      rows="5" required><?= esc($data['isi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan"
                      class="form-control"
                      rows="3"><?= esc($data['catatan']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= base_url('data-dukung') ?>" class="btn btn-secondary">Kembali</a>

    </form>

</div>

<?= $this->endSection() ?>