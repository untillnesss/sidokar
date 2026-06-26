<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Layanan KIA</h3>

<?php if(session()->get('role') == 'desa' || session()->get('role') == 'admin'): ?>
<a href="<?= base_url('kia/form') ?>" class="btn btn-primary mb-3">
    + Ajukan KIA
</a>
<?php endif; ?>

<!-- FILTER -->
<form method="get" class="mb-3">
<div class="d-flex flex-wrap gap-2">

<!-- STATUS -->
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
            <?= ($selected_kec == $k['kode_kecamatan'])?'selected':'' ?>>
            <?= $k['nama_kecamatan'] ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- DESA -->
<select name="desa" id="desa" class="form-select w-auto">
    <option value="">Semua Desa</option>
    <?php foreach($desa as $d): ?>
        <option 
            value="<?= $d['kode_desa'] ?>"
            data-kec="<?= $d['kode_kecamatan'] ?>"
            <?= ($selected_desa == $d['kode_desa'])?'selected':'' ?>>
            <?= $d['nama_desa'] ?>
        </option>
    <?php endforeach; ?>
</select>

<?php endif; ?>

<!-- SEARCH -->
<input type="text" name="nama" class="form-control w-auto"
    placeholder="Cari nama anak..."
    value="<?= $search_nama ?? '' ?>">

<button class="btn btn-secondary btn-sm">Filter</button>
<a href="<?= base_url('kia') ?>" class="btn btn-light btn-sm">Reset</a>

</div>
</form>

<?php if(empty($kia)): ?>
<div class="alert alert-info">Belum ada pengajuan.</div>
<?php else: ?>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">

<thead class="table-dark text-center">
<tr>
    <th>No</th>
    <th>Tanggal</th>

<?php if(session()->get('role') == 'desa'): ?>
    <th>Nama Anak</th>
<?php else: ?>
    <th>Nama Desa</th>
    <th>Nama Anak</th>
<?php endif; ?>

    <th>NIK Anak</th>
    <th>Nama Ayah</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=1; foreach($kia as $row): ?>
<tr>

<td class="text-center"><?= $no++ ?></td>

<td>
<?= !empty($row['updated_at'])
    ? date('d-m-Y H:i', strtotime($row['updated_at']))
    : date('d-m-Y H:i', strtotime($row['tanggal_pengajuan'])) ?>
</td>

<?php if(session()->get('role') == 'desa'): ?>
    <td><?= esc($row['nama_anak']) ?></td>
<?php else: ?>
    <td><?= esc($row['nama_desa'] ?? '-') ?></td>
    <td><?= esc($row['nama_anak']) ?></td>
<?php endif; ?>

<td><?= esc($row['nik_anak']) ?></td>
<td><?= esc($row['nama_ayah']) ?></td>

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
<?php if($row['status'] == 'Revisi' && !empty($row['catatan'])): ?>
    <div>
        <small class="text-danger d-block">
            Catatan Revisi: <?= esc($row['catatan']) ?>
        </small>
    </div>
<?php endif; ?>
</td>

<td>
<?php if($row['status'] == 'Pengajuan'): ?>
    <?php if(session()->get('role') == 'admin'): ?>
            <a href="<?= base_url('kia/detail/'.$row['id_kia']) ?>"
               class="btn btn-info btn-sm">
                Lihat
            </a>
        <?php endif; ?>

    <a href="<?= base_url('kia/edit/'.$row['id_kia']) ?>" 
       class="btn btn-sm btn-warning">Edit</a>

    <a href="<?= base_url('kia/delete/'.$row['id_kia']) ?>" 
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>


<?php elseif($row['status'] == 'Revisi'): ?>

    <a href="<?= base_url('kia/edit/'.$row['id_kia']) ?>" 
       class="btn btn-sm btn-warning">Perbaiki</a>


<?php elseif($row['status'] == 'Selesai'): ?>

    <!-- DOWNLOAD -->
    <a href="<?= base_url($row['hasil_pdf']) ?>" 
       class="btn btn-success btn-sm" 
       target="_blank">Download</a>

    <?php if(session()->get('role') == 'desa'): ?>

    <?php if(session()->get('role') != 'admin'): ?>
    <button type="button"
            class="btn btn-danger btn-sm"
            onclick="showPengembalian(<?= $row['id_kia'] ?>)">
        Ajukan Pengembalian
    </button>
        <?php endif; ?>

        <!-- CATATAN PENOLAKAN -->
        <?php if(!empty($row['catatan_penolakan'])): ?>
        <small class="text-danger d-block mt-1">
            Pengembalian ditolak: <?= esc($row['catatan_penolakan']) ?>
        </small>
        <?php endif; ?>

        <?php if(session()->get('role') != 'admin'): ?>

        <div id="form_pengembalian_<?= $row['id_kia'] ?>"
            class="d-none mt-2"
            style="width:100%;">

        <form action="<?= base_url('kia/pengembalian/'.$row['id_kia']) ?>" 
            method="post">

        <?= csrf_field(); ?>

        <textarea name="catatan_pengembalian"
                class="form-control form-control-sm mb-2"
                placeholder="Alasan pengembalian..."
                rows="2"
                required></textarea>

        <div class="d-flex gap-1 flex-wrap align-items-start">

        <button class="btn btn-danger btn-sm">
            Ajukan
        </button>

        <button type="button"
                class="btn btn-secondary btn-sm"
                onclick="batalPengembalian(<?= $row['id_kia'] ?>)">
            Batal
        </button>

        </div>
    </form>
</div>

<?php endif; ?>

<?php endif; ?>
    <!-- KHUSUS ADMIN -->
    <?php if(session()->get('role') == 'admin'): ?>

        <button type="button"
            class="btn btn-warning btn-sm"
            onclick="showEditFile(<?= $row['id_kia'] ?>)">
            Edit File
        </button>

        <div id="edit_file_<?= $row['id_kia'] ?>" class="d-none mt-2">

            <form action="<?= base_url('kia/update-hasil/'.$row['id_kia']) ?>" 
                  method="post" 
                  enctype="multipart/form-data"
                  onsubmit="return confirmEditFile()">

                <?= csrf_field(); ?>

                <input type="file" name="hasil_pdf" 
                       class="form-control form-control-sm mb-1" 
                       accept=".pdf" required>

                <button type="submit" class="btn btn-sm btn-danger">
                    Simpan Perubahan
                </button>

            </form>

        </div>

    <?php endif; ?>

<?php endif; ?>

<?php if($row['status'] == 'Proses'): ?>
    <span class="badge bg-warning text-dark">
        Permohonan Anda sedang diproses
    </span>
<?php endif; ?>
<?php if($row['status'] == 'Pengembalian'): ?>

    <small class="text-warning d-block mb-1">
        Menunggu persetujuan admin
    </small>

    <?php if(!empty($row['catatan_pengembalian'])): ?>
        <small class="text-dark d-block">
            Alasan: <?= esc($row['catatan_pengembalian']) ?>
        </small>
    <?php endif; ?>

    <?php if(!empty($row['catatan_penolakan'])): ?>
        <small class="text-danger d-block">
            Penolakan: <?= esc($row['catatan_penolakan']) ?>
        </small>
    <?php endif; ?>

<?php endif; ?>
<?php if(session()->get('role') == 'admin' && $row['status'] == 'Pengembalian'): ?>

<div class="d-flex gap-1 flex-wrap mt-2">

    <!-- SETUJUI -->
    <form action="<?= base_url('kia/setujui-pengembalian/'.$row['id_kia']) ?>" 
          method="post">

        <?= csrf_field(); ?>

        <button class="btn btn-success btn-sm">
            Setujui
        </button>
    </form>

    <!-- TOLAK -->
    <button type="button"
            class="btn btn-danger btn-sm"
            onclick="showTolakForm(<?= $row['id_kia'] ?>)">
        Tolak
    </button>

</div>

<!-- FORM TOLAK -->
<div id="form_tolak_<?= $row['id_kia'] ?>"
     class="d-none mt-2">

<form action="<?= base_url('kia/tolak-pengembalian/'.$row['id_kia']) ?>" 
      method="post">

    <?= csrf_field(); ?>

    <textarea name="catatan_penolakan"
            class="form-control form-control-sm mb-2"
            placeholder="Alasan penolakan..."
            required></textarea>

    <button class="btn btn-danger btn-sm">
        Simpan Penolakan
    </button>

</form>
</div>
<?php endif; ?>
<?php if(session()->get('role') == 'admin'): ?>

<?php if($row['status'] != 'Pengembalian'): ?>

<form action="<?= base_url('kia/update-status') ?>" 
      method="post" 
      enctype="multipart/form-data" 
      class="mt-2">

<input type="hidden" name="id" value="<?= $row['id_kia'] ?>">

<select name="status" class="form-select form-select-sm mb-1"
    onchange="handleStatusChange(this, <?= $row['id_kia'] ?>)">
    <option value="">Ubah Status</option>
    <option value="Pengajuan">Pengajuan</option>
    <option value="Proses">Proses</option>
    <option value="Revisi">Revisi</option>
    <option value="Selesai">Selesai</option>
</select>

<textarea 
    name="catatan" 
    id="catatan_<?= $row['id_kia'] ?>"
    class="form-control form-control-sm d-none mb-1"
    placeholder="Alasan revisi..."></textarea>

<input 
    type="file" 
    name="file_hasil"
    id="file_pdf_<?= $row['id_kia'] ?>"
    class="form-control form-control-sm d-none mb-1">

<button class="btn btn-secondary btn-sm w-100">
    Simpan
</button>

</form>

<?php endif; ?>

<?php endif; ?>
</td>

</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>

<?php endif; ?>

<!-- SCRIPT FILTER DESA -->
<script>
const kecamatan = document.getElementById('kecamatan');
const desa = document.getElementById('desa');

function filterDesa() {
    let selectedKec = kecamatan.value;

    for (let option of desa.options) {

        if (option.value === "") {
            option.style.display = 'block';
            continue;
        }

        let kec = option.getAttribute('data-kec');

        if (!selectedKec || kec === selectedKec) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    }

    desa.value = "";
}

// jalan saat pilih kecamatan
kecamatan?.addEventListener('change', filterDesa);

// jalan saat pertama load
window.onload = filterDesa;
</script>

<script>
function handleStatusChange(select, id) {
    let catatan = document.getElementById('catatan_' + id);
    let pdf     = document.getElementById('file_pdf_' + id);

    catatan.classList.add('d-none');
    catatan.required = false;

    pdf.classList.add('d-none');
    pdf.required = false;

    if(select.value === 'Revisi') {
        catatan.classList.remove('d-none');
        catatan.required = true;
    }

    if(select.value === 'Selesai') {
        pdf.classList.remove('d-none');
        pdf.required = true;
    }
}
</script>
<script>
function showEditFile(id) {
    let el = document.getElementById('edit_file_' + id);
    el.classList.toggle('d-none');
}

function confirmEditFile() {
    return confirm('Yakin ingin mengganti file ini?');
}
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