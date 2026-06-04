<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-capitalize mb-0">
            <?= esc($kategori) ?>
        </h3>

        <?php if (session()->get('role') == 'admin' && session()->get('is_master') == 1): ?>
            <a href="<?= base_url('admin/data-dukung/create') ?>" 
               class="btn btn-primary btn-sm">
                + Tambah Data
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="alert alert-warning">
            Data belum tersedia.
        </div>
    <?php endif; ?>

    <?php foreach ($items as $row): ?>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <h5><?= esc($row['judul']) ?></h5>

                <ol type="a">
                    <?php
                    $lines = explode("\n", $row['isi']);
                    foreach ($lines as $line):
                        if (!empty(trim($line))):
                    ?>
                        <li><?= esc(trim($line)) ?></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ol>

                <?php if (!empty($row['catatan'])): ?>
                    <div class="alert alert-info mt-2">
                        <?= nl2br(esc($row['catatan'])) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->get('role') == 'admin' && session()->get('is_master') == 1): ?>
                    <div class="mt-3">
                        <a href="<?= base_url('admin/data-dukung/edit/' . $row['id']) ?>" 
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="<?= base_url('admin/data-dukung/delete/' . $row['id']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<?= $this->endSection() ?>