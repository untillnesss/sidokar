<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | SI DOKAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4b79a1, #283e51);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .login-title {
            font-weight: 700;
            color: #2c5aa0;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #d6e2f0;
            padding-right: 45px; /* kasih ruang icon */
        }

        .form-control:focus {
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

        /* PASSWORD WRAPPER FIX */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .toggle-password:hover {
            color: #2c5aa0;
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
        <img src="<?= base_url('assets/img/logo.png') ?>" 
             alt="Logo Tuban" 
             class="logo-img">
    </div>

    <h3 class="text-center login-title mb-4">
        <i class="fas fa-user-lock me-2"></i> Login SI DOKAR
    </h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field(); ?>

        <!-- Username -->
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <!-- Password -->
        <div class="mb-3 password-wrapper">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label class="form-label">Login Sebagai</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin Dukcapil</option>
                <option value="desa">Pihak Desa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-sign-in-alt me-1"></i> Masuk
        </button>
    </form>

    <div class="extra-links text-center mt-3">
        <p><a href="<?= base_url('forgot-password') ?>">Lupa Password?</a></p>
        <p>Belum punya akun? <a href="<?= base_url('register') ?>">Daftar di sini</a></p>
    </div>

</div>

<script>
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", function () {
    const type = password.type === "password" ? "text" : "password";
    password.type = type;
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>
