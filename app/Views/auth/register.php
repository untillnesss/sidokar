<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | SI DOKAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4b79a1, #283e51);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .login-title {
            font-weight: 700;
            color: #2c5aa0;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #d6e2f0;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2c5aa0;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #2c5aa0;
            border: none;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #1f447c;
        }

        .extra-links a {
            text-decoration: none;
            color: #2c5aa0;
            font-weight: 500;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .logo-img {
            width: 90px;
        }
    </style>
</head>
<body>

<div class="login-card">

    <!-- LOGO -->
    <div class="text-center mb-3">
        <img src="<?= base_url('assets/img/logo.png') ?>" class="logo-img">
    </div>

    <h3 class="text-center login-title mb-4">
        <i class="fas fa-user-plus me-2"></i> Register SI DOKAR
    </h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="post">
        <?= csrf_field(); ?>

        <div class="mb-3">
            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="mb-3">
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin Dukcapil</option>
                <option value="desa">Pihak Desa</option>
            </select>
        </div>

        <!-- Kecamatan -->
        <div id="kecamatanDiv" style="display:none;">
            <select id="kecamatan" class="form-select mb-3">
                <option value="">-- Pilih Kecamatan --</option>
                <?php foreach($kecamatan as $kec): ?>
                    <option value="<?= $kec->kode_kecamatan ?>">
                        <?= $kec->nama_kecamatan ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Desa -->
        <div id="desaDiv" style="display:none;">
            <select name="id_desa" id="desa" class="form-select mb-3">
                <option value="">-- Pilih Desa --</option>
            </select>
        </div>

        <button class="btn btn-primary w-100">
            <i class="fas fa-user-check me-1"></i> Daftar
        </button>
    </form>

    <div class="extra-links text-center mt-3">
        <p>Sudah punya akun? <a href="<?= base_url('login') ?>">Login</a></p>
    </div>

</div>

<script>
const role = document.getElementById('role');
const kecDiv = document.getElementById('kecamatanDiv');
const desaDiv = document.getElementById('desaDiv');
const kecamatan = document.getElementById('kecamatan');
const desa = document.getElementById('desa');

role.addEventListener('change', function() {
    if(this.value === 'desa'){
        kecDiv.style.display = 'block';
        desaDiv.style.display = 'block';
    } else {
        kecDiv.style.display = 'none';
        desaDiv.style.display = 'none';
        desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
        kecamatan.value = '';
    }
});

kecamatan.addEventListener('change', function() {

    let kode = this.value;

    if(kode === ""){
        desa.innerHTML = '<option value="">-- Pilih Desa --</option>';
        return;
    }

    desa.innerHTML = '<option value="">Loading...</option>';

    fetch("<?= base_url('get-desa/') ?>" + kode)
    .then(response => response.json())
    .then(data => {

        desa.innerHTML = '<option value="">-- Pilih Desa --</option>';

        if(data.length === 0){
            desa.innerHTML += '<option value="">Data desa tidak ditemukan</option>';
        }

        data.forEach(function(item){
            desa.innerHTML += `
                <option value="${item.id_desa}">
                    ${item.nama_desa}
                </option>
            `;
        });
    })
    .catch(error => {
        console.log(error);
        desa.innerHTML = '<option value="">Terjadi kesalahan</option>';
    });
});
</script>

</body>
</html>