<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Layanan Akta Kematian</h3>

<?php if(session()->get('role') == 'desa' || session()->get('role') == 'admin'): ?>
    <a href="<?= base_url('akta-kematian/create') ?>" class="btn btn-primary mb-3">
        + Pengajuan Akta Kematian
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
        <a href="<?= base_url('akta-kematian') ?>" class="btn btn-light btn-sm">Reset</a>

    </div>
</form>

<?php if(empty($data)): ?>

    <div class="alert alert-info">Belum ada pengajuan.</div>

<?php else: ?>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">

    <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>Tanggal</th>

            <?php if(session()->get('role') == 'desa'): ?>
                <th>Nama Jenazah</th>
            <?php else: ?>
                <th>Nama Desa</th>
                <th>Nama Jenazah</th>
            <?php endif; ?>

            <th>Nama Pelapor</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php $no=1; foreach($data as $row): ?>
        <tr>

            <td class="text-center"><?= $no++ ?></td>

            <td>
            <?= !empty($row['updated_at'])
                ? date('d-m-Y H:i', strtotime($row['updated_at']))
                : '-' ?>
            </td>

            <?php if(session()->get('role') == 'desa'): ?>
                <td><?= esc($row['nama_jenazah']) ?></td>
            <?php else: ?>
                <td><?= esc($row['nama_desa'] ?? '-') ?></td>
                <td><?= esc($row['nama_jenazah']) ?></td>
            <?php endif; ?>

            <td><?= esc($row['nama_pelapor']) ?></td>

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
                <?php if(!empty($row['catatan_pengembalian'])): ?>
                    <small class="text-warning d-block mt-1">
                        Pengajuan pengembalian: <?= esc($row['catatan_pengembalian']) ?>
                    </small>
                <?php endif; ?>

                <?php if($row['status'] == 'Revisi' && !empty($row['catatan_revisi'])): ?>
                    <small class="text-danger d-block mt-1">
                        Catatan: <?= esc($row['catatan_revisi']) ?>
                    </small>
                <?php endif; ?>
            </td>

            <td>

<!-- ================= USER ACTION ================= -->
                <?php if($row['status'] == 'Pengajuan'): ?>
                    <?php if(session()->get('role') == 'admin'): ?>
                    <a href="<?= base_url('akta-kematian/detail/'.$row['id_permohonan']) ?>"
                    class="btn btn-info btn-sm">
                        Lihat
                    </a>
                <?php endif; ?>

                    <a href="<?= base_url('akta-kematian/edit/'.$row['id_permohonan']) ?>"
                    class="btn btn-sm btn-warning">Edit</a>

                    <a href="<?= base_url('akta-kematian/delete/'.$row['id_permohonan']) ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>

                <?php elseif($row['status'] == 'Revisi'): ?>

                    <a href="<?= base_url('akta-kematian/edit/'.$row['id_permohonan']) ?>"
                    class="btn btn-sm btn-warning">Perbaiki</a>

                <?php endif; ?>

                <!-- DETAIL hanya selain selesai -->
            <?php if($row['status'] == 'Proses'): ?>
                <span class="badge bg-warning text-dark">
                    Permohonan Anda sedang diproses
                </span>
            <?php endif; ?>

            <?php if($row['status'] == 'Selesai'): ?>

                <div class="d-flex gap-1 flex-wrap">

                    <!-- DOWNLOAD -->
                    <?php if(!empty($row['file_hasil'])): ?>
                        <a href="<?= base_url('akta-kematian/download/'.$row['id_permohonan']) ?>"
                        class="btn btn-success btn-sm"
                        target="_blank">
                            Download
                        </a>
                    <?php endif; ?>

                    <!-- USER: AJUKAN PENGEMBALIAN -->
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
                                onclick="toggleEditFile(<?= $row['id_permohonan'] ?>)">
                            Edit File
                        </button>
                    <?php endif; ?>

                </div>

                <!-- CATATAN PENOLAKAN -->
                <?php if(!empty($row['catatan_penolakan'])): ?>
                    <small class="text-danger d-block mt-2">
                        Pengembalian ditolak: <?= esc($row['catatan_penolakan']) ?>
                    </small>
                <?php endif; ?>

                <!-- FORM PENGEMBALIAN -->
                <?php if(session()->get('role') != 'admin'): ?>
                <div id="form_pengembalian_<?= $row['id_permohonan'] ?>" class="d-none mt-2">

                    <form action="<?= base_url('akta-kematian/pengembalian/'.$row['id_permohonan']) ?>"
                        method="post">

                        <textarea name="catatan_pengembalian"
                                class="form-control form-control-sm mb-2"
                                required></textarea>

                        <button class="btn btn-danger btn-sm">Ajukan</button>
                    </form>

                </div>
            <?php endif; ?>

        <?php endif; ?>

        <?php if($row['status'] == 'Pengembalian'): ?>

    <!-- INFO -->
    <small class="text-warning d-block mb-1">
        Menunggu persetujuan admin
    </small>

    <?php if(session()->get('role') == 'admin'): ?>

        <!-- BUTTON SETUJUI + TOLAK -->
        <div class="d-flex gap-1 flex-wrap">

            <!-- SETUJUI -->
            <form action="<?= base_url('akta-kematian/setujui-pengembalian/'.$row['id_permohonan']) ?>"
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

            <form action="<?= base_url('akta-kematian/tolak-pengembalian/'.$row['id_permohonan']) ?>"
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

<?php endif; ?>
                <!-- ================= ADMIN STATUS UPDATE ================= -->
                <?php if(session()->get('role') == 'admin' && $row['status'] != 'Pengembalian'): ?>

                <form action="<?= base_url('akta-kematian/update-status') ?>"
                    method="post"
                    enctype="multipart/form-data"
                    class="mt-2">

                    <input type="hidden" name="id" value="<?= $row['id_permohonan'] ?>">

                    <select name="status"
                            class="form-select form-select-sm mb-1"
                            onchange="handleStatusChange(this, <?= $row['id_permohonan'] ?>)">
                        <option value="">Ubah Status</option>
                        <option value="Pengajuan">Pengajuan</option>
                        <option value="Proses">Proses</option>
                        <option value="Revisi">Revisi</option>
                        <option value="Selesai">Selesai</option>
                    </select>

                    <textarea name="catatan"
                            id="catatan_<?= $row['id_permohonan'] ?>"
                            class="form-control form-control-sm d-none mb-1"
                            placeholder="Alasan revisi..."></textarea>

                    <input type="file"
                        name="file_hasil"
                        id="file_pdf_<?= $row['id_permohonan'] ?>"
                        class="form-control form-control-sm d-none mb-1">

                    <button class="btn btn-secondary btn-sm w-100">
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

<script>
    function handleStatusChange(select, id)
    {
        let catatan = document.getElementById('catatan_' + id);
        let pdf = document.getElementById('file_pdf_' + id);

        catatan.classList.add('d-none');
        pdf.classList.add('d-none');

        catatan.required = false;
        pdf.required = false;

        if(select.value === 'Revisi')
        {
            catatan.classList.remove('d-none');
            catatan.required = true;
        }

        if(select.value === 'Selesai')
        {
            pdf.classList.remove('d-none');
            pdf.required = true;
        }
    }

    document.getElementById('kecamatan')?.addEventListener('change', function()
    {
        let kec = this.value;
        let desaSelect = document.getElementById('desa');

        for (let option of desaSelect.options)
        {
            let kecOption = option.getAttribute('data-kec');

            if (!kec || kecOption === kec)
            {
                option.style.display = '';
            }
            else
            {
                option.style.display = 'none';
            }
        }

        desaSelect.value = '';
    });
</script>

<script>
    function toggleEditFile(id)
    {
        let form = document.getElementById('editFileForm_' + id);
        form.classList.toggle('d-none');
    }
</script>

<script>
    function showPengembalian(id){
        document.getElementById('form_pengembalian_' + id).classList.remove('d-none');
    }

    function showTolakForm(id){
        document.getElementById('form_tolak_' + id).classList.remove('d-none');
    }
</script>
<?= $this->endSection() ?>