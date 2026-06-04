<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP | SI DOKAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4b79a1, #283e51);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .otp-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 18px;
            background: rgba(255,255,255,0.97);
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }

        .otp-title {
            font-weight: 700;
            color: #0b47a9;
        }

        .otp-input {
            font-size: 22px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0b47a9;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #083a8c;
        }

        .small-text {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="otp-card">

    <h4 class="text-center otp-title mb-3">
        Verifikasi OTP
    </h4>

    <p class="text-center small-text">
        Kode OTP telah dikirim ke:
        <br>
        <b><?= session()->get('otp_email'); ?></b>
    </p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/verifikasi_otp') ?>" method="post">
        <?= csrf_field(); ?>

        <div class="mb-3">
            <input type="text"
                   name="otp"
                   maxlength="6"
                   class="form-control otp-input"
                   placeholder="------"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Verifikasi
        </button>
    </form>

    <div class="text-center mt-3 small-text">
        Kode berlaku selama <b>5 menit</b>.
    </div>

    <div class="text-center mt-2">
        <a href="<?= base_url('/login') ?>" class="small-text">
            ← Kembali ke Login
        </a>
    </div>

</div>

</body>
</html>
