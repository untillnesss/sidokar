<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="card-modern">
    <h4>Data Dukung Akta Kelahiran</h4>
    <hr>

    <?php if(!empty($data)): ?>
        <ol>
            <?php foreach($data as $row): ?>
                <li class="mb-3">
                    <strong><?= esc($row['judul']); ?></strong><br>
                    <?= esc($row['isi']); ?><br>

                    <?php if($row['catatan']): ?>
                        <small class="text-danger">
                            Catatan: <?= esc($row['catatan']); ?>
                        </small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php else: ?>
        <p>Belum ada data.</p>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>