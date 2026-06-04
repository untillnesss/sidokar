<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Manajemen User</h3>

    <!-- FILTER -->
    <form method="get" class="row mb-3">
        <div class="col-md-3">
            <select name="role" class="form-control">
                <option value="">-- Semua Role --</option>
                <option value="admin" <?= ($role=='admin')?'selected':'' ?>>Admin</option>
                <option value="desa" <?= ($role=='desa')?'selected':'' ?>>Desa</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" 
                   name="keyword" 
                   class="form-control"
                   placeholder="Cari nama atau desa..."
                   value="<?= esc($keyword ?? '') ?>">
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>

        <div class="col-md-2">
            <a href="/user-management" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Instansi / Desa</th>
                <th>Role</th>
                <th>Master</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>

                    <form action="<?= base_url('user-management/update/' . $user['id']) ?>" method="post">

                        <td><?= esc($user['nama_lengkap']) ?></td>

                        <td>
                            <?= $user['role'] == 'admin' 
                                ? 'Admin Dukcapil' 
                                : esc($user['nama_desa'] ?? '-') ?>
                        </td>

                        <td>
                            <select name="role" class="form-control">
                                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                                <option value="desa" <?= $user['role']=='desa'?'selected':'' ?>>Desa</option>
                            </select>
                        </td>

                        <td class="text-center">
                            <input type="checkbox" 
                                name="is_master" 
                                value="1"
                                <?= $user['is_master']==1?'checked':'' ?>
                                <?= $user['id']==session()->get('id')?'disabled':'' ?>>
                        </td>

                        <td>
                            <button type="submit" class="btn btn-sm btn-primary">
                                Update
                            </button>

                            <a href="<?= base_url('user-management/delete/' . $user['id']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus user ini? Data tidak bisa dikembalikan!')">
                               Hapus
                            </a>
                        </td>

                    </form>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>