<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Layanan Akta Kelahiran</h3>

<?php if(session()->get('role') == 'desa' || session()->get('role') == 'admin'): ?>
<a href="<?= base_url('akta-kelahiran/create') ?>" class="btn btn-primary mb-3">
    + Buat Pengajuan
</a>
<?php endif; ?>

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
            <?php foreach($kecamatan_list as $k): ?>
                <option value="<?= $k['kode_kecamatan'] ?>"
                    <?= ($selected_kecamatan==$k['kode_kecamatan'])?'selected':'' ?>>
                    <?= $k['nama_kecamatan'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="desa" id="desa" class="form-select w-auto">
            <option value="">Semua Desa</option>
            <?php foreach($desa_list as $d): ?>
                <option value="<?= $d['kode_desa'] ?>"
                    data-kec="<?= $d['kode_kecamatan'] ?>"
                    <?= ($selected_desa==$d['kode_desa'])?'selected':'' ?>>
                    <?= $d['nama_desa'] ?>
                </option>
            <?php endforeach; ?>
        </select>

    <?php endif; ?>

    <input type="text" 
           name="search" 
           class="form-control w-auto"
           placeholder="Cari nama..."
           value="<?= esc($search) ?>">

    <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    <a href="<?= base_url('akta-kelahiran') ?>" class="btn btn-light btn-sm">Reset</a>

</div>
</form>

<?php if(empty($permohonan)): ?>
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
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php 
$no = 1 + (10 * ($pager->getCurrentPage() - 1)); 
foreach($permohonan as $row): 
?>
<tr>
<td class="text-center"><?= $no++ ?></td>
<?php
$waktu = $row['updated_at'] ?? $row['created_at'];
?>
<td><?= date('d-m-Y H:i', strtotime($waktu)) ?></td>

<?php if(session()->get('role') == 'desa'): ?>
    <td><?= esc($row['nama_anak'] ?? '-') ?></td>
<?php else: ?>
    <td><?= esc($row['nama_desa'] ?? '-') ?></td>
    <td><?= esc($row['nama_anak'] ?? '-') ?></td>
<?php endif; ?>

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

    <?php if($row['status'] == 'Pengembalian'): ?>
    <div>
        <small class="text-danger">
            Pengajuan pengembalian:
            <?= esc($row['catatan_pengembalian']) ?>
        </small>
    </div>
<?php endif; ?>
    <span class="badge bg-<?= $badge ?>">
    <?= esc($status) ?>
    </span>
</td>

<td>
<div class="d-flex flex-column gap-2">

    <!-- ================= STATUS PENGAJUAN ================= -->
    <?php if($row['status'] == 'Pengajuan'): ?>

    <div class="d-flex gap-1 flex-wrap">

        <?php if(session()->get('role') == 'admin'): ?>
            <a href="<?= base_url('akta-kelahiran/detail/'.$row['id_permohonan']) ?>"
               class="btn btn-info btn-sm">
                Lihat
            </a>
        <?php endif; ?>

        <a href="<?= base_url('akta-kelahiran/edit/'.$row['id_permohonan']) ?>"
           class="btn btn-warning btn-sm">
            Edit
        </a>

        <button type="button"
                class="btn btn-danger btn-sm"
                onclick="confirmDelete(<?= $row['id_permohonan'] ?>)">
            Hapus
        </button>
    </div>
<?php endif; ?>

    <?php if($row['status'] == 'Revisi'): ?>

        <a href="<?= base_url('akta-kelahiran/edit/'.$row['id_permohonan']) ?>"
           class="btn btn-warning btn-sm">
            Perbaiki
        </a>

        <?php if(!empty($row['catatan_revisi'])): ?>
            <small class="text-danger">
                Catatan: <?= esc($row['catatan_revisi']) ?>
            </small>
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

<?php endif; ?>

<?php
$fileHasil = trim($row['file_hasil'] ?? '');
$statusRow = trim($row['status'] ?? '');
?>

<?php if($statusRow == 'Selesai'): ?>

    <div class="d-flex gap-1 flex-wrap">

        <?php if($fileHasil != ''): ?>
            <a href="<?= base_url('akta-kelahiran/download/'.$row['id_permohonan']) ?>"
               class="btn btn-success btn-sm"
               target="_blank">
                Download
            </a>
        <?php endif; ?>

        <!-- USER (DESA): BUTTON AJUKAN PENGEMBALIAN -->
        <?php if(session()->get('role') != 'admin'): ?>
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="showPengembalian(<?= $row['id_permohonan'] ?>)">
                Ajukan Pengembalian
            </button>
        <?php endif; ?>

        <!-- ADMIN: EDIT FILE -->
        <?php if(session()->get('role') == 'admin'): ?>
            <button type="button"
                    class="btn btn-warning btn-sm"
                    onclick="showEditFile(<?= $row['id_permohonan'] ?>)">
                Edit File
            </button>
        <?php endif; ?>

    </div>

    <!-- CATATAN PENOLAKAN HANYA TAMPIL SAAT STATUS MASIH SELESAI -->
    <?php if(
        !empty($row['catatan_penolakan']) &&
        $row['status'] == 'Selesai'
    ): ?>
        <small class="text-danger d-block mt-2">
            Pengembalian ditolak: <?= esc($row['catatan_penolakan']) ?>
        </small>
    <?php endif; ?>


    <!-- ================= FORM AJUKAN PENGEMBALIAN USER ================= -->
    <?php if(session()->get('role') != 'admin'): ?>

        <div id="form_pengembalian_<?= $row['id_permohonan'] ?>" class="d-none mt-2">

            <form action="<?= base_url('akta-kelahiran/pengembalian/'.$row['id_permohonan']) ?>"
                  method="post"
                  onsubmit="return confirmPengembalian(this)">

                <?= csrf_field(); ?>

                <textarea name="catatan_pengembalian"
                          class="form-control form-control-sm mb-2"
                          placeholder="Contoh: Nama / data salah..."
                          required></textarea>

                <div class="d-flex gap-1">

                    <button type="submit"
                            class="btn btn-danger btn-sm">
                        Ajukan
                    </button>

                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            onclick="hidePengembalian(<?= $row['id_permohonan'] ?>)">
                        Batal
                    </button>

                </div>

            </form>

        </div>

    <?php endif; ?>


    <!-- ================= FORM EDIT FILE ADMIN ================= -->
    <?php if(session()->get('role') == 'admin'): ?>

        <div id="edit_file_<?= $row['id_permohonan'] ?>" class="d-none mt-2">

            <form action="<?= base_url('akta-kelahiran/upload-hasil/'.$row['id_permohonan']) ?>"
                  method="post"
                  enctype="multipart/form-data"
                  onsubmit="return confirmEditFile()">

                <?= csrf_field(); ?>

                <input type="hidden" name="status" value="Selesai">

                <input type="file"
                       name="file_hasil"
                       class="form-control form-control-sm mb-2"
                       accept=".pdf"
                       required>

                <button type="submit"
                        class="btn btn-danger btn-sm">
                    Simpan Perubahan
                </button>

            </form>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <!-- ================= STATUS PENGEMBALIAN (ADMIN) ================= -->
    <?php if($row['status'] == 'Pengembalian' && session()->get('role') == 'admin'): ?>

        <small class="text-danger">
            Pengajuan pengembalian:
            <?= esc($row['catatan_pengembalian']) ?>
        </small>

        <!-- BUTTON SETUJUI + TOLAK -->
        <div class="d-flex gap-1 flex-wrap">

            <!-- SETUJUI -->
            <form action="<?= base_url('akta-kelahiran/setujui-pengembalian/'.$row['id_permohonan']) ?>"
                  method="post"
                  onsubmit="return confirmSetujui()">

                <?= csrf_field(); ?>

                <button type="submit"
                        class="btn btn-success btn-sm">
                    Setujui
                </button>
            </form>
            <!-- TOLAK -->
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="showTolakForm(<?= $row['id_permohonan'] ?>)">
                Tolak
            </button>
        </div>

        <!-- FORM TOLAK -->
        <div id="form_tolak_<?= $row['id_permohonan'] ?>" class="d-none mt-2">

            <form action="<?= base_url('akta-kelahiran/tolak-pengembalian/'.$row['id_permohonan']) ?>"
                  method="post"
                  onsubmit="return confirmTolak()">
                <?= csrf_field(); ?>
                <textarea name="catatan_penolakan"
                          class="form-control form-control-sm mb-2"
                          placeholder="Tulis alasan penolakan..."
                          required></textarea>
                <div class="d-flex gap-1">
                    <button type="submit"
                            class="btn btn-danger btn-sm">
                        Kirim
                    </button>
                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            onclick="hideTolakForm(<?= $row['id_permohonan'] ?>)">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- ================= ADMIN UBAH STATUS ================= -->
    <?php if(session()->get('role') == 'admin' && $row['status'] != 'Pengembalian'): ?>

        <form action="<?= base_url('akta-kelahiran/upload-hasil/'.$row['id_permohonan']) ?>"
            method="post"
            enctype="multipart/form-data"
            class="mt-1"
            onsubmit="return validateStatusForm(this, <?= $row['id_permohonan'] ?>)">
            <?= csrf_field(); ?>
            <select name="status"
                    class="form-select form-select-sm mb-1"
                    id="status_<?= $row['id_permohonan'] ?>"
                    onchange="handleStatusChange(this, <?= $row['id_permohonan'] ?>)"
                    required>
                <option value="">Ubah Status</option>
                <option value="Pengajuan">Pengajuan</option>
                <option value="Proses">Proses</option>
                <option value="Revisi">Revisi</option>
                <option value="Selesai">Selesai</option>
            </select>
            <!-- CATATAN REVISI -->
            <textarea name="catatan_revisi"
                    id="catatan_<?= $row['id_permohonan'] ?>"
                    class="form-control form-control-sm d-none mb-1"
                    placeholder="Alasan revisi..."></textarea>
            <!-- FILE PDF -->
            <input type="file"
                name="file_hasil"
                id="file_hasil_<?= $row['id_permohonan'] ?>"
                class="form-control form-control-sm d-none mb-1"
                accept=".pdf">

            <button type="submit"
                    class="btn btn-secondary btn-sm w-100">
                Simpan
            </button>
        </form>
    <?php endif; ?>
</div>
</td>

</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="mt-3 d-flex justify-content-center">
    <?= $pager->links('default', 'custom_pager') ?>
</div>
</div>

<?php endif; ?>

<!-- =========================
     SCRIPT VALIDASI STATUS
========================= -->
<script>
function handleStatusChange(select, id) {
    let fileInput = document.getElementById('file_hasil_' + id);
    let catatan = document.getElementById('catatan_' + id);

    if(select.value === 'Selesai') {
        fileInput.classList.remove('d-none');
        fileInput.required = true;

        catatan.classList.add('d-none');
        catatan.required = false;
    } 
    else if(select.value === 'Revisi') {
        catatan.classList.remove('d-none');
        catatan.required = true;

        fileInput.classList.add('d-none');
        fileInput.required = false;
    } 
    else {
        fileInput.classList.add('d-none');
        fileInput.required = false;

        catatan.classList.add('d-none');
        catatan.required = false;
    }
}

// =========================
// FILTER KECAMATAN → DESA
// =========================
document.addEventListener('DOMContentLoaded', function () {

    let kecamatan = document.getElementById('kecamatan');
    let desaSelect = document.getElementById('desa');

    if (!kecamatan || !desaSelect) return;

    kecamatan.addEventListener('change', function () {

        let kec = this.value;

        for (let option of desaSelect.options) {

            let kecOption = option.getAttribute('data-kec');

            // ⛔ skip placeholder
            if (!option.value) {
                option.style.display = '';
                continue;
            }

            // ✅ tampilkan sesuai kecamatan
            if (kec === '' || kecOption === kec) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }

        // reset pilihan desa
        desaSelect.value = '';
    });

});
</script>

<script>
function showEditFile(id) {
    let el = document.getElementById('edit_file_' + id);
    el.classList.toggle('d-none');
}

function confirmEditFile() {
    return confirm('Yakin ingin mengganti file ini?');
}
</script>

<!-- MODAL HAPUS -->
<div class="modal fade" id="modalDelete" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title w-100">Konfirmasi Hapus</h5>
      </div>

      <div class="modal-body">
        <h5>⚠️ Anda yakin akan menghapus pengajuan ini?</h5>
        <p class="text-muted">Data yang dihapus tidak bisa dikembalikan.</p>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
        </button>

        <a href="#" id="btnDelete" class="btn btn-danger">
            Ya, Hapus
        </a>
      </div>

    </div>
  </div>
</div>
<script>
function confirmDelete(id) {
    let url = "<?= base_url('akta-kelahiran/delete/') ?>/" + id;

    document.getElementById('btnDelete').setAttribute('href', url);

    let modal = new bootstrap.Modal(document.getElementById('modalDelete'));
    modal.show();
}
</script>
<script>
function showPengembalian(id){
    document.getElementById('form_pengembalian_' + id).classList.remove('d-none');
}

function hidePengembalian(id){
    document.getElementById('form_pengembalian_' + id).classList.add('d-none');
}

function confirmPengembalian(form){
    if(confirm('Apakah Anda yakin data perbaikan sudah benar?')){
        form.submit();
    }
    return false;
}

function showTolakForm(id){
    document.getElementById('form_tolak_' + id).classList.remove('d-none');
}

function hideTolakForm(id){
    document.getElementById('form_tolak_' + id).classList.add('d-none');
}

function confirmSetujui(){
    return confirm('Setujui pengembalian dan proses ulang dokumen?');
}

function confirmTolak(){
    return confirm('Yakin ingin menolak pengembalian ini?');
}
</script>
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<script>
function validateStatusForm(form, id){

    let status = document.getElementById('status_' + id).value;
    let catatan = document.getElementById('catatan_' + id);
    let file = document.getElementById('file_hasil_' + id);

    // STATUS WAJIB
    if(status === ''){
        alert('Silakan pilih status terlebih dahulu');
        return false;
    }

    // REVISI WAJIB CATATAN
    if(status === 'Revisi' && catatan.value.trim() === ''){
        alert('Catatan revisi wajib diisi');
        catatan.focus();
        return false;
    }

    // SELESAI WAJIB FILE PDF
    if(status === 'Selesai' && file.files.length === 0){
        alert('File hasil PDF wajib diupload');
        return false;
    }

    return confirm('Yakin ingin menyimpan perubahan status?');
}
</script>

<?= $this->endSection() ?>