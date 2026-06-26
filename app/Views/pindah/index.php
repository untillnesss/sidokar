<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Layanan Pindah</h3>

<?php if(session()->get('role') == 'desa' || session()->get('role') == 'admin'): ?>
<a href="<?= base_url('pindah/tambah') ?>" class="btn btn-primary mb-3">
    + Ajukan Pindah
</a>
<?php endif; ?>

<!-- FILTER -->
<form method="get" class="mb-3">
    <div class="d-flex flex-wrap gap-2">

        <select name="status" class="form-select w-auto">
            <option value="">Semua Status</option>
            <option value="Pengajuan" <?= ($selected_status=='Pengajuan')?'selected':'' ?>>Pengajuan</option>
            <option value="Proses" <?= ($selected_status=='Proses')?'selected':'' ?>>Proses</option>
            <option value="Revisi" <?= ($selected_status=='Revisi')?'selected':'' ?>>Revisi</option>
            <option value="Selesai" <?= ($selected_status=='Selesai')?'selected':'' ?>>Selesai</option>
            <option value="Pengembalian" <?= ($selected_status=='Pengembalian')?'selected':'' ?>>Pengembalian</option>
        </select>

        <?php if(session()->get('role') == 'admin'): ?>

            <select name="kecamatan" id="kecamatan" class="form-select w-auto">
                <option value="">Semua Kecamatan</option>
                <?php foreach($kecamatan as $k): ?>
                    <option value="<?= $k['kode_kecamatan'] ?>"
                        <?= ($selected_kec == $k['kode_kecamatan']) ? 'selected' : '' ?>>
                        <?= $k['nama_kecamatan'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="desa" id="desa" class="form-select w-auto">
                <option value="">Semua Desa</option>
                <?php foreach($desa as $d): ?>
                    <option value="<?= $d['kode_desa'] ?>"
                        data-kec="<?= $d['kode_kecamatan'] ?>"
                        <?= ($selected_desa == $d['kode_desa']) ? 'selected' : '' ?>>
                        <?= $d['nama_desa'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <?php endif; ?>

        <input type="text"
               name="nama"
               class="form-control w-auto"
               placeholder="Cari nama..."
               value="<?= $search_nama ?? '' ?>">

        <button class="btn btn-secondary btn-sm">Filter</button>
        <a href="<?= base_url('pindah') ?>" class="btn btn-light btn-sm">Reset</a>

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
    <th>Nama Pindah</th>
<?php else: ?>
    <th>Nama Desa</th>
    <th>Nama Pindah</th>
<?php endif; ?>

    <th>Jenis</th>
    <th>Tujuan</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no=1; foreach($pengajuan as $row): ?>
<tr>

<td class="text-center"><?= $no++ ?></td>

<td>
    <?php
        $waktuTampil = $row['updated_at'] ?? $row['tanggal_pengajuan'] ?? null;
        echo $waktuTampil ? date('d-m-Y H:i', strtotime($waktuTampil)) : '-';
    ?>
</td>

<?php if(session()->get('role') == 'desa'): ?>
    <td>
        <?= !empty($row['nama_pindah']) 
            ? esc($row['nama_pindah']) 
            : esc($row['nama_pemohon']) ?>
    </td>
<?php else: ?>
    <td>
        <?= esc($row['nama_desa'] ?? '-') ?>
    </td>
    <td>
        <?= !empty($row['nama_pindah']) 
            ? esc($row['nama_pindah']) 
            : esc($row['nama_pemohon']) ?>
    </td>
<?php endif; ?>

<td><?= esc($row['kategori_pindah']) ?></td>
<td><?= esc($row['jenis_tujuan']) ?></td>

<!-- STATUS -->
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

<!-- AKSI -->
<!-- GANTI TOTAL BAGIAN <td style="min-width:260px;"> ... </td> PADA INDEX PINDAH MENJADI INI -->

<td>
<?php if($row['status'] == 'Pengajuan'): ?>

    <?php if(session()->get('role') == 'admin'): ?>
            <a href="<?= base_url('pindah/detail/'.$row['id_pengajuan']) ?>"
               class="btn btn-info btn-sm">
                Lihat
            </a>
        <?php endif; ?>

    <a href="<?= base_url('pindah/edit/'.$row['id_pengajuan']) ?>" 
       class="btn btn-sm btn-warning">Edit</a>

    <a href="<?= base_url('pindah/delete/'.$row['id_pengajuan']) ?>" 
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>

<?php elseif($row['status'] == 'Revisi'): ?>

    <a href="<?= base_url('pindah/edit/'.$row['id_pengajuan']) ?>" 
       class="btn btn-sm btn-warning">Perbaiki</a>

<?php elseif($row['status'] == 'Selesai'): ?>

    <?php if(!empty($row['file_skpwni'])): ?>
        <a href="<?= base_url($row['file_skpwni']) ?>" 
           class="btn btn-success btn-sm"
           target="_blank">Download SKPWNI</a>
    <?php endif; ?>

    <?php if(!empty($row['file_kk'])): ?>
        <a href="<?= base_url($row['file_kk']) ?>" 
           class="btn btn-success btn-sm"
           target="_blank">Download KK</a>
    <?php endif; ?>

    <?php if(session()->get('role') != 'admin'): ?>
        <button type="button"
                class="btn btn-danger btn-sm"
                onclick="showPengembalian(<?= $row['id_pengajuan'] ?>)">
            Ajukan Pengembalian
        </button>
    <?php endif; ?>

    <?php if(session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('pindah/edit-hasil/'.$row['id_pengajuan']) ?>" 
           class="btn btn-warning btn-sm">
           Edit File
        </a>
    <?php endif; ?>

    <!-- FORM PENGEMBALIAN -->
    <?php if(session()->get('role') != 'admin'): ?>
    <div id="form_pengembalian_<?= $row['id_pengajuan'] ?>"
         class="d-none mt-2"
         style="width:100%;">

        <form action="<?= base_url('pindah/pengembalian/'.$row['id_pengajuan']) ?>" 
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
                        onclick="batalPengembalian(<?= $row['id_pengajuan'] ?>)">
                    Batal
                </button>
            </div>

        </form>
    </div>
    <?php endif; ?>

    <?php if(!empty($row['catatan_penolakan'])): ?>
        <small class="text-danger d-block mt-1">
            Pengembalian ditolak: <?= esc($row['catatan_penolakan']) ?>
        </small>
    <?php endif; ?>

<?php endif; ?>


<!-- STATUS PROSES -->
<?php if($row['status'] == 'Proses'): ?>
    <span class="badge bg-warning text-dark">
        Permohonan Anda sedang diproses
    </span>
<?php endif; ?>


<!-- STATUS PENGEMBALIAN -->
<?php if($row['status'] == 'Pengembalian'): ?>

    <small class="text-warning d-block mb-1">
        Menunggu persetujuan admin
    </small>

    <?php if(!empty($row['catatan_pengembalian'])): ?>
        <small class="text-dark d-block">
            Alasan: <?= esc($row['catatan_pengembalian']) ?>
        </small>
    <?php endif; ?>

<?php endif; ?>


<!-- ADMIN KHUSUS PENGEMBALIAN -->
<?php if(session()->get('role') == 'admin' && $row['status'] == 'Pengembalian'): ?>

<div class="d-flex gap-1 flex-wrap mt-2">

    <form action="<?= base_url('pindah/setujui-pengembalian/'.$row['id_pengajuan']) ?>" 
          method="post">

        <?= csrf_field(); ?>

        <button class="btn btn-success btn-sm">
            Setujui
        </button>
    </form>

    <button type="button"
            class="btn btn-danger btn-sm"
            onclick="showTolakForm(<?= $row['id_pengajuan'] ?>)">
        Tolak
    </button>

</div>

<div id="form_tolak_<?= $row['id_pengajuan'] ?>"
     class="d-none mt-2">

    <form action="<?= base_url('pindah/tolak-pengembalian/'.$row['id_pengajuan']) ?>" 
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


<!-- ADMIN UPDATE STATUS -->
<?php if(session()->get('role') == 'admin' && $row['status'] != 'Pengembalian'): ?>

<form action="<?= base_url('pindah/update-status') ?>" 
      method="post" 
      enctype="multipart/form-data"
      class="mt-2">

    <?= csrf_field(); ?>

    <input type="hidden" 
           name="id" 
           value="<?= $row['id_pengajuan'] ?>">

    <select name="status" 
            class="form-select form-select-sm mb-1"
            onchange="handleStatusChange(this, <?= $row['id_pengajuan'] ?>)">

        <option value="">Ubah Status</option>
        <option value="Pengajuan">Pengajuan</option>
        <option value="Proses">Proses</option>
        <option value="Revisi">Revisi</option>
        <option value="Selesai">Selesai</option>
    </select>

    <textarea name="catatan_revisi" 
              id="catatan_<?= $row['id_pengajuan'] ?>"
              class="form-control form-control-sm d-none mb-1"
              placeholder="Tulis alasan revisi..."></textarea>

    <input type="file" 
           name="file_skpwni"
           id="file_skpwni_<?= $row['id_pengajuan'] ?>"
           class="form-control form-control-sm d-none mb-1"
           accept="application/pdf">

    <input type="file" 
           name="file_kk_baru"
           id="file_kk_<?= $row['id_pengajuan'] ?>"
           class="form-control form-control-sm d-none mb-1"
           accept="application/pdf">

    <button type="submit" class="btn btn-secondary btn-sm w-100">
        Simpan
    </button>

</form>

<?php endif; ?>

</td>

</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>

<?php endif; ?>

<!-- SCRIPT -->
<script>
// =========================
// STATUS CHANGE
// =========================
function handleStatusChange(select, id) {
    let catatan = document.getElementById('catatan_' + id);
    let skpwni  = document.getElementById('file_skpwni_' + id);
    let kk      = document.getElementById('file_kk_' + id);

    // reset semua
    catatan.classList.add('d-none');
    catatan.required = false;

    skpwni.classList.add('d-none');
    skpwni.required = false;

    kk.classList.add('d-none');
    kk.required = false;

    // revisi
    if(select.value === 'Revisi') {
        catatan.classList.remove('d-none');
        catatan.required = true;
    }

    // selesai
    if(select.value === 'Selesai') {
        skpwni.classList.remove('d-none');
        kk.classList.remove('d-none');

        skpwni.required = true;
        kk.required = true;
    }
}

// =========================
// FILTER DESA BY KECAMATAN
// =========================
function filterDesa() {
    let kec = document.getElementById('kecamatan')?.value;
    let desaSelect = document.getElementById('desa');

    if (!desaSelect) return;

    for (let option of desaSelect.options) {
        let kecOption = option.getAttribute('data-kec');

        if (!kec || kecOption === kec) {
            option.style.display = '';
            option.disabled = false; // ✅ penting
        } else {
            option.style.display = 'none';
            option.disabled = true; // ✅ biar gak ke-submit
        }
    }
}

// =========================
// EVENT LISTENER
// =========================
document.getElementById('kecamatan')?.addEventListener('change', function(){
    filterDesa();
    document.getElementById('desa').value = '';
});

// jalan saat halaman load
window.addEventListener('load', function(){
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