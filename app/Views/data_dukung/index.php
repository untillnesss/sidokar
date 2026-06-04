<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3>Data Dukung</h3>

<?php if(session()->getFlashdata('success')): ?>
    <div style="color:green;">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<a href="<?= base_url('data-dukung/create') ?>" class="btn btn-primary mb-3">
    + Tambah Data
</a>

<table border="1" cellpadding="8" width="100%">
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Judul</th>
        <th>Catatan</th>
        <th width="150">Aksi</th>
    </tr>

    <?php $no=1; foreach($data_dukung as $d): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= esc($d['kategori']) ?></td>
        <td><?= esc($d['judul']) ?></td>
        <td><?= esc($d['catatan'] ?? '-') ?></td>
        <td>
            <a href="<?= base_url('data-dukung/edit/'.$d['id']) ?>">Edit</a> |
            <button onclick="return confirm('Yakin hapus?')">
                Hapus
            </button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>