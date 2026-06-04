<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Layanan Kartu Keluarga</h3>

<?php if(session()->get('role') == 'desa' || session()->get('role') == 'admin'): ?>
<a href="<?= base_url('kartu-keluarga/tambah') ?>" class="btn btn-primary mb-3">
    + Pengajuan KK
</a>
<?php endif; ?>

<!-- FILTER -->
<form method="get" class="mb-3">
    <div class="d-flex align-items-center gap-2">

        <select name="status" class="form-select w-auto">
            <option value="">Semua Status</option>
            <option value="Pengajuan" <?= ($selected_status=='Pengajuan')?'selected':'' ?>>Pengajuan</option>
            <option value="Proses" <?= ($selected_status=='Proses')?'selected':'' ?>>Proses</option>
            <option value="Revisi" <?= ($selected_status=='Revisi')?'selected':'' ?>>Revisi</option>
            <option value="Selesai" <?= ($selected_status=='Selesai')?'selected':'' ?>>Selesai</option>
            <option value="Pengembalian" <?= ($selected_status=='Pengembalian')?'selected':'' ?>>Pengembalian</option>
        </select>
        <?php if(session()->get('role') == 'admin'): ?>

        <!-- KECAMATAN -->
        <select name="kecamatan" id="kecamatan" class="form-select w-auto">
            <option value="">Semua Kecamatan</option>
            <?php foreach($kecamatan as $k): ?>
                <option value="<?= $k['kode_kecamatan'] ?>"
                    <?= ($filter_kec==$k['kode_kecamatan'])?'selected':'' ?>>
                    <?= $k['nama_kecamatan'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- DESA -->
        <select name="desa" id="desa" class="form-select w-auto">
            <option value="">Semua Desa</option>
            <?php foreach($desa as $d): ?>
                <option value="<?= $d['kode_desa'] ?>"
                    data-kec="<?= $d['kode_kecamatan'] ?>"   <!-- 🔥 INI WAJIB -->
                    <?= ($filter_desa==$d['kode_desa'])?'selected':'' ?>>
                    <?= $d['nama_desa'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php endif; ?>
                <input type="text" 
            name="nama" 
            class="form-control w-auto"
            placeholder="Cari Nama"
            value="<?= esc($search_nama) ?>">

        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        <a href="<?= base_url('kartu-keluarga') ?>" class="btn btn-light btn-sm">Reset</a>

    </div>
</form>

<?php if(empty($pengajuan)): ?>
<div class="alert alert-info">Belum ada pengajuan.</div>
<?php else: ?>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">

<thead class="table-dark text-center">
<tr>
    <th>No</th>
    <th>Tanggal</th>

<?php if(session()->get('role') == 'desa'): ?>
    <th>Nama Kepala</th>
<?php else: ?>
    <th>Nama Desa</th>
    <th>Nama Kepala</th>
<?php endif; ?>

    <th>No KK</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=1; foreach($pengajuan as $row): ?>
<tr>

<td class="text-center"><?= $no++ ?></td>

<td>
    <?= date('d-m-Y H:i', strtotime($row['updated_at'] ?? $row['created_at'])) ?>
</td>

<?php if(session()->get('role') == 'desa'): ?>
    <td><?= esc($row['nama_kepala']) ?></td>
<?php else: ?>
    <td><?= esc($row['nama_desa'] ?? '-') ?></td>
    <td><?= esc($row['nama_kepala']) ?></td>
<?php endif; ?>

<td><?= esc($row['no_kk'] ?? '-') ?></td>

<td class="text-center">
<?php
$status = $row['status'];
$badge = 'secondary';

if($status == 'Pengajuan') $badge = 'primary';
elseif($status == 'Proses') $badge = 'warning';
elseif($status == 'Revisi') $badge = 'danger';
elseif($status == 'Selesai') $badge = 'success';
elseif($status == 'Pengembalian') $badge = 'dark';
?>
<span class="badge bg-<?= $badge ?>">
    <?= esc($status) ?>
</span>

<?php if($status == 'Revisi' && !empty($row['catatan_revisi'])): ?>
    <div class="mt-1 text-danger small">
        <?= esc($row['catatan_revisi']) ?>
    </div>
<?php endif; ?>
</td>

<td style="width:320px;">
<div class="d-flex flex-column gap-2">

    <div class="d-flex flex-wrap gap-1">

    <?php if($row['status'] == 'Pengajuan'): ?>
        <?php if(session()->get('role') == 'admin'): ?>
            <a href="<?= base_url('kartu-keluarga/detail/'.$row['id_pengajuan']) ?>"
               class="btn btn-info btn-sm">
                Lihat
            </a>
        <?php endif; ?>

        <a href="<?= base_url('kartu-keluarga/edit/'.$row['id_pengajuan']) ?>" 
           class="btn btn-sm btn-warning">Edit</a>

        <a href="<?= base_url('kartu-keluarga/delete/'.$row['id_pengajuan']) ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>

    <?php elseif($row['status'] == 'Revisi'): ?>

        <a href="<?= base_url('kartu-keluarga/edit/'.$row['id_pengajuan']) ?>" 
           class="btn btn-sm btn-warning">Perbaiki</a>

    <?php elseif($row['status'] == 'Selesai'): ?>

    <?php if(!empty($row['file_hasil'])): ?>

        <!-- BUTTON UTAMA -->
        <a href="<?= base_url('uploads/hasil/'.$row['file_hasil']) ?>" 
            target="_blank"
            class="btn btn-success btn-sm">
                Download
            </a>

        <?php if(session()->get('role') != 'admin'): ?>
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="showPengembalian(<?= $row['id_pengajuan'] ?>)">
                Ajukan Pengembalian
            </button>
        <?php endif; ?>

        <?php if(session()->get('role') == 'admin'): ?>
            <button type="button"
                    class="btn btn-warning btn-sm"
                    onclick="showEditFile(<?= $row['id_pengajuan'] ?>)">
                Edit File
            </button>
        <?php endif; ?>

        <!-- FORM EDIT FILE -->
        <?php if(session()->get('role') == 'admin'): ?>
        <div id="edit_file_<?= $row['id_pengajuan'] ?>" class="d-none mt-2">
            <form action="<?= base_url('kartu-keluarga/update-hasil/'.$row['id_pengajuan']) ?>" 
                  method="post" enctype="multipart/form-data">

                <?= csrf_field(); ?>

                <input type="file" 
                       name="file_hasil"
                       class="form-control form-control-sm mb-2"
                       accept="application/pdf"
                       required>

                <button type="submit" class="btn btn-danger btn-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- CATATAN PENOLAKAN -->
        <?php if(!empty($row['catatan_penolakan'])): ?>
            <small class="text-danger d-block mt-1">
                Pengembalian ditolak: <?= esc($row['catatan_penolakan']) ?>
            </small>
        <?php endif; ?>

        <!-- FORM PENGEMBALIAN -->
        <?php if(session()->get('role') != 'admin'): ?>
        <div id="form_pengembalian_<?= $row['id_pengajuan'] ?>" 
            class="d-none mt-2"
            style="width:100%;">

            <form action="<?= base_url('kartu-keluarga/pengembalian/'.$row['id_pengajuan']) ?>" method="post">
                <?= csrf_field(); ?>

                <textarea name="catatan_pengembalian"
                          class="form-control form-control-sm mb-2"
                          placeholder="Alasan pengembalian..."
                          rows="2"
                          required></textarea>

                <div class="d-flex gap-1 flex-wrap align-items-start">
                    <button class="btn btn-danger btn-sm">Ajukan</button>

                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            onclick="batalPengembalian(<?= $row['id_pengajuan'] ?>)">
                        Batal
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>


    <?php else: ?>

        <?php if(session()->get('role') == 'admin'): ?>
            <span class="text-muted small">Belum ada file</span>
        <?php endif; ?>

    <?php endif; ?>

<?php endif; ?>

    <?php if($row['status'] == 'Proses'): ?>
        <span class="badge bg-warning text-dark">
            Permohonan Anda sedang diproses
        </span>
    <?php endif; ?>

    </div>

    <?php if(session()->get('role') == 'admin' && $row['status'] != 'Pengembalian'): ?>

<form action="<?= base_url('kartu-keluarga/update-status') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <input type="hidden" name="id" value="<?= $row['id_pengajuan'] ?>">

        <div class="d-flex flex-column gap-1">

            <select name="status" 
                    class="form-select form-select-sm"
                    onchange="handleStatusChange(this, <?= $row['id_pengajuan'] ?>)">

                <option value="">Ubah Status</option>
                <option value="Pengajuan">Pengajuan</option>
                <option value="Proses">Proses</option>
                <option value="Revisi">Revisi</option>
                <option value="Selesai">Selesai</option>
            </select>

            <textarea 
                name="catatan_revisi" 
                id="catatan_<?= $row['id_pengajuan'] ?>"
                class="form-control form-control-sm d-none"
                placeholder="Tulis alasan revisi..."></textarea>
            
                <?php if($status == 'Pengembalian' && !empty($row['catatan_pengembalian'])): ?>
                    <div class="mt-1 text-warning small">
                        Alasan: <?= esc($row['catatan_pengembalian']) ?>
                    </div>
                <?php endif; ?>

            <input 
                type="file" 
                name="file_kk"
                id="file_kk_<?= $row['id_pengajuan'] ?>"
                class="form-control form-control-sm d-none"
                accept="application/pdf">

            <button type="submit" class="btn btn-sm btn-secondary">
                Simpan
            </button>

        </div>
    </form>

    <?php endif; ?>
        <?php if($row['status'] == 'Pengembalian'): ?>

<small class="text-warning d-block mb-1">
    Menunggu persetujuan admin
</small>

<?php if(session()->get('role') == 'admin'): ?>

<div class="d-flex gap-1 flex-wrap">

    <!-- SETUJUI -->
    <form action="<?= base_url('kartu-keluarga/setujui-pengembalian/'.$row['id_pengajuan']) ?>" method="post">
        <?= csrf_field(); ?>
        <button class="btn btn-success btn-sm">Setujui</button>
    </form>

    <!-- TOLAK -->
    <button type="button"
            class="btn btn-danger btn-sm"
            onclick="showTolakForm(<?= $row['id_pengajuan'] ?>)">
        Tolak
    </button>

</div>

<!-- FORM TOLAK -->
<div id="form_tolak_<?= $row['id_pengajuan'] ?>" class="d-none mt-2">

    <form action="<?= base_url('kartu-keluarga/tolak-pengembalian/'.$row['id_pengajuan']) ?>" method="post">
        <?= csrf_field(); ?>

        <textarea name="catatan_penolakan"
                  class="form-control form-control-sm mb-2"
                  placeholder="Alasan penolakan..."
                  required></textarea>

        <button class="btn btn-danger btn-sm">Kirim</button>
    </form>

</div>

<?php endif; ?>
<?php endif; ?>
</div>
</td>

</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>

<?php endif; ?>

<script>
function handleStatusChange(select, id) {
    let catatan = document.getElementById('catatan_' + id);
    let kk      = document.getElementById('file_kk_' + id);

    catatan.classList.add('d-none');
    catatan.required = false;

    kk.classList.add('d-none');
    kk.required = false;

    if (select.value === 'Revisi') {
        catatan.classList.remove('d-none');
        catatan.required = true;
    }

    if (select.value === 'Selesai') {
        kk.classList.remove('d-none');
        kk.required = true;
    }
}

function showEditFile(id) {
    let el = document.getElementById('edit_file_' + id);
    el.classList.toggle('d-none');
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const kecamatanSelect = document.getElementById('kecamatan');
    const desaSelect      = document.getElementById('desa');

    function filterDesa() {

        if (!desaSelect) return;

        let kec = kecamatanSelect?.value || '';

        for (let option of desaSelect.options) {

            let kecOption = option.getAttribute('data-kec');

            if (!kec || kecOption === kec) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }

        desaSelect.value = '';
    }

    // 🔥 jalan saat pilih kecamatan
    kecamatanSelect?.addEventListener('change', filterDesa);

    // 🔥 jalan saat pertama load (biar konsisten)
    filterDesa();
});

</script>
<script>
function showPengembalian(id){
    let form = document.getElementById('form_pengembalian_' + id);
    form.classList.remove('d-none');
}

function batalPengembalian(id){
    let form = document.getElementById('form_pengembalian_' + id);
    form.classList.add('d-none');
}

function showTolakForm(id){
    let form = document.getElementById('form_tolak_' + id);
    form.classList.toggle('d-none');
}
</script>
<?= $this->endSection() ?>