<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3>Data Dukung (Admin Master)</h3>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<a href="<?= base_url('data-dukung/create') ?>" class="btn btn-primary mb-3">
    + Tambah Data
</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Judul</th>
        <th>Isi</th>
        <th>Catatan</th>
        <th width="150">Aksi</th>
    </tr>

    <?php $no=1; foreach($data_dukung as $d): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= esc($d['kategori']) ?></td>
        <td><?= esc($d['judul']) ?></td>
        <td><?= nl2br(esc($d['isi'])) ?></td>
        <td><?= esc($d['catatan'] ?? '-') ?></td>
        <td>
            <a href="<?= base_url('data-dukung/edit/'.$d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= base_url('data-dukung/delete/'.$d['id']) ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('Yakin hapus?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>