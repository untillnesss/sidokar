<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
.card{
    border-radius:16px !important;
    transition:all 0.2s ease;
}

.card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 28px rgba(0,0,0,0.08);
}

/* WELCOME */
.welcome-card h4{
    color:#1e293b;
}

.welcome-card p{
    color:#64748b;
}

/* QUICK MENU */
.quick-card{
    cursor:pointer;
}

.quick-card i{
    font-size:24px;
    margin-bottom:12px;
    display:inline-block;
    padding:12px;
    border-radius:12px;
    background:#f1f5f9;
}

/* STATUS */
.stat-box{
    border-radius:12px;
    padding:15px;
    transition:0.2s;
}

.stat-box:hover{
    transform:scale(1.03);
}

.stat-pengajuan{ background:#f1f5f9; }
.stat-proses{ background:#fff7ed; }
.stat-revisi{ background:#fef2f2; }
.stat-selesai{ background:#ecfdf5; }

/* TABLE */
.table td, .table th{
    vertical-align:middle;
}

.table-hover tbody tr:hover{
    background:#f8fafc;
}

/* TYPO */
h4, h5, h6{
    font-weight:600;
}
</style>


<!-- WELCOME -->
<div class="card welcome-card border-0 shadow-sm p-4 rounded-4 mb-4">

    <h4 class="mb-1">
        👋 Selamat Datang, <?= ucfirst(session()->get('username')); ?>
    </h4>

    <p class="mb-0 text-muted">
        SI DOKAR - Sistem Informasi Dokumen Administrasi Rakyat <br>
        Dinas Kependudukan dan Pencatatan Sipil Kabupaten Tuban
    </p>

</div>


<!-- QUICK MENU -->
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <a href="<?= base_url('layanan'); ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-4 text-center quick-card">
                <i class="fas fa-folder-open text-primary"></i>
                <h6>Layanan</h6>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="<?= base_url('data-dukung'); ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-4 text-center quick-card">
                <i class="fas fa-file-alt text-success"></i>
                <h6>Data Dukung</h6>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="<?= base_url('download-files'); ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-4 text-center quick-card">
                <i class="fas fa-download text-warning"></i>
                <h6>Download</h6>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="<?= base_url('bantuan'); ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-4 text-center quick-card">
                <i class="fas fa-question-circle text-danger"></i>
                <h6>Bantuan</h6>
            </div>
        </a>
    </div>

</div>


<!-- STATUS -->
<div class="card border-0 shadow-sm p-4 rounded-4 mb-4">

    <h6 class="mb-3">📌 Status Pengajuan</h6>

    <div class="row text-center">

        <div class="col-md-3">
            <div class="stat-box stat-pengajuan">
                <h4 class="mb-0"><?= $dataStatus[0] ?? 0 ?></h4>
                <small>Pengajuan</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box stat-proses">
                <h4 class="mb-0 text-warning"><?= $dataStatus[1] ?? 0 ?></h4>
                <small>Diproses</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box stat-revisi">
                <h4 class="mb-0 text-danger"><?= $dataStatus[2] ?? 0 ?></h4>
                <small>Revisi</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-box stat-selesai">
                <h4 class="mb-0 text-success"><?= $dataStatus[3] ?? 0 ?></h4>
                <small>Selesai</small>
            </div>
        </div>

    </div>

</div>


<!-- AKTIVITAS -->
<div class="card border-0 shadow-sm p-4 rounded-4">

    <h6 class="mb-3">🕒 Aktivitas Terbaru</h6>

    <div class="table-responsive">
        <table class="table table-hover table-sm">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Instansi</th>
                    <th>Layanan</th>
                    <th>Nama</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($aktivitas)): ?>
                <?php $no = 1; ?>

                <?php foreach ($aktivitas as $a): ?>
                <tr>
                    <td><?= $no++; ?></td>

                    <td>
                        <?= $a['instansi'] ?? '-' ?>
                    </td>

                    <td>
                        <?= $a['layanan']; ?>
                    </td>

                    <td>
                        <?= $a['nama']; ?>
                    </td>

                    <td>
                        <?php
                            $status = $a['status'];

                            if($status == 'Pengajuan') $badge = 'primary';
                            elseif($status == 'Proses') $badge = 'warning';
                            elseif($status == 'Revisi') $badge = 'danger';
                            elseif($status == 'Selesai') $badge = 'success';
                            elseif($status == 'Pengembalian') $badge = 'dark';
                            else $badge = 'secondary';
                            ?>

                            <span class="badge bg-<?= $badge ?>">
                                <?= $status ?>
                            </span>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada aktivitas
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

<?= $this->endSection(); ?>