<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | SI DOKAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icon -->
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

        .form-control {
            border-radius: 10px;
            border: 1px solid #d6e2f0;
            padding-right: 45px;
        }

        .btn-primary {
            background-color: #2c5aa0;
            border: none;
            font-weight: 600;
            border-radius: 10px;
        }

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

    <div class="text-center mb-3">
        <img src="<?= base_url('assets/img/logo.png') ?>" class="logo-img">
    </div>

    <h3 class="text-center login-title mb-4">
        <i class="fas fa-lock me-2"></i> Reset Password
    </h3>

    <form action="<?= base_url('/reset-password') ?>" method="post">
        <?= csrf_field(); ?>

        <input type="hidden" name="token" value="<?= $token ?>">

        <div class="mb-3 password-wrapper">
            <label>Password Baru</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <div class="mb-3 password-wrapper">
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" id="confirmPassword" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-save me-1"></i> Reset Password
        </button>
    </form>

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