<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SI DOKAR | Kabupaten Tuban</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
html,
body{
    height:100%;
    margin:0;
    padding:0;
}

body{
    background:#f1f5f9;
    font-family:'Segoe UI', sans-serif;
    overflow-x:hidden;
}

/* ================= HEADER ================= */

.header{
    background:#0A2540;
    color:white;
    height:120px;
    width:100%;
    padding:0 28px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:fixed;
    top:0;
    left:0;
    z-index:999;
}

.logo-img{
    height:58px;
    width:auto;
    flex-shrink:0;
}

.header-title{
    display:flex;
    flex-direction:column;
    justify-content:center;
    line-height:1.2;
}

.header-title strong{
    font-size:16px;
    font-weight:700;
}

.header-title small{
    font-size:12px;
    color:#dbeafe;
}

/* ================= LAYOUT ================= */

.wrapper{
    display:flex;
    min-height:100vh;
    padding-top:120px;
}

/* ================= SIDEBAR ================= */

.sidebar{
    width:260px;
    min-width:260px;
    background:#102A43;
    position:fixed;
    top:120px;
    left:0;
    height:calc(100vh - 120px);
    overflow-y:auto;
    z-index:998;
}

.app-brand{
    text-align:center;
    padding:22px 20px;
}

.app-brand img{
    width:180px;
}

.nav-link{
    color:#dbeafe;
    margin:6px 12px;
    border-radius:10px;
    transition:0.2s;
    padding:12px 16px;
    font-size:15px;
}

.nav-link:hover{
    background:#1D4E89;
    color:white;
}

.nav-link.active{
    background:#2563eb !important;
    color:white !important;
    font-weight:600;
}

.submenu{
    padding-left:10px;
}

.submenu .nav-link{
    padding-left:35px;
    font-size:14px;
}

/* ================= MAIN ================= */

.main-area{
    margin-left:260px;
    width:calc(100% - 260px);
    min-height:calc(100vh - 120px);
    display:flex;
    flex-direction:column;
}

.content{
    flex:1;
    width:100%;
    padding:30px;
}

/* FIX SEMUA CARD & FORM BIAR FULL */
.content .container,
.content .container-fluid,
.content .row,
.content .card,
.content form,
.content .box-section{
    width:100% !important;
    max-width:100% !important;
}

.footer{
    background:white;
    text-align:center;
    padding:15px;
}

/* ================= MENU TOGGLE ================= */

.menu-toggle{
    display:none;
}

/* ================= MOBILE ================= */

@media(max-width:1200px){

    .menu-toggle{
        display:block;
        position:absolute;
        left:16px;
        top:50%;
        transform:translateY(-50%);
        font-size:22px;
        color:white;
        cursor:pointer;
        z-index:1001;
    }

    .header{
        height:110px;
        padding:0 15px 0 55px;
        gap:12px;
    }

    .header .flex-grow-1{
        min-width:0;
    }

    .header-title{
        min-width:0;
    }

    .header-title strong{
        font-size:12px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .header-title small{
        font-size:9px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .logo-img{
        height:42px;
    }

    /* MOBILE SIDEBAR */

    .sidebar{
        left:-260px;
        top:110px;
        height:calc(100vh - 110px);
        transition:0.3s;
    }

    .sidebar.active{
        left:0;
    }

    /* MOBILE MAIN */

    .main-area{
        margin-left:0;
        width:100%;
    }

    .wrapper{
        padding-top:110px;
    }

    .content{
        padding:18px 15px;
    }
}
</style>
</head>

<body>

<?php 
$uri = service('uri'); 
$userModel = new \App\Models\UserModel();
$userLogin = $userModel->find(session()->get('id'));
?>

<!-- HEADER -->
<div class="header">

    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <div class="d-flex align-items-center flex-grow-1">

        <img src="<?= base_url('assets/img/logo.png') ?>" class="logo-img">

        <div class="ms-3 header-title">
            <strong>DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL KABUPATEN TUBAN</strong>
            <small>SI DOKAR - Sistem Informasi Dokumen Administrasi Rakyat</small>
        </div>

    </div>

    <div class="d-flex align-items-center">

        <div class="me-2 text-end">
            <?= $userLogin['nama_lengkap'] ?? '-' ?>
        </div>

        <img src="<?= base_url('uploads/avatar/' . (session()->get('avatar') ?? 'default.png')) ?>" 
             width="42"
             height="42"
             class="dropdown-toggle"
             data-bs-toggle="dropdown"
             style="border-radius:50%; cursor:pointer; object-fit:cover;">

        <ul class="dropdown-menu dropdown-menu-end">

            <li class="px-3 py-2">
                <strong><?= $userLogin['nama_lengkap'] ?? '-' ?></strong><br>
                <small><?= $userLogin['username'] ?? '-' ?></small>
            </li>

            <li><hr></li>

            <li>
                <a class="dropdown-item" href="/profile">
                    Profil
                </a>
            </li>

            <li>
                <a class="dropdown-item text-danger"
                   href="#"
                   data-bs-toggle="modal"
                   data-bs-target="#logoutModal">
                    Logout
                </a>
            </li>

        </ul>

    </div>

</div>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="app-brand">
            <img src="<?= base_url('assets/img/logo-sidokar.png') ?>">
        </div>

        <ul class="nav flex-column mt-2">

            <li>
                <a href="<?= base_url('dashboard') ?>" 
                   class="nav-link <?= $uri->getSegment(1)=='dashboard'?'active':'' ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="<?= base_url('layanan') ?>" 
                   class="nav-link <?= $uri->getSegment(1)=='layanan'?'active':'' ?>">
                    <i class="fas fa-folder me-2"></i> Layanan
                </a>
            </li>

            <li>

                <a class="nav-link" data-bs-toggle="collapse" href="#dataMenu">
                    <i class="fas fa-folder-open me-2"></i> Data Dukung
                    <i class="fas fa-chevron-down float-end mt-1"></i>
                </a>

                <div class="collapse <?= $uri->getSegment(1)=='data-dukung'?'show':'' ?>" id="dataMenu">

                    <div class="submenu">

                        <a href="<?= base_url('data-dukung/akta-kelahiran') ?>" class="nav-link">
                            Akta Kelahiran
                        </a>

                        <a href="<?= base_url('data-dukung/akta-kematian') ?>" class="nav-link">
                            Akta Kematian
                        </a>

                        <a href="<?= base_url('data-dukung/kartu-keluarga') ?>" class="nav-link">
                            Kartu Keluarga
                        </a>

                        <a href="<?= base_url('data-dukung/kartu-identitas-anak') ?>" class="nav-link">
                            KIA
                        </a>

                        <a href="<?= base_url('data-dukung/akta-nikah') ?>" class="nav-link">
                            Akta Nikah
                        </a>

                        <a href="<?= base_url('data-dukung/akta-cerai') ?>" class="nav-link">
                            Akta Cerai
                        </a>

                        <a href="<?= base_url('data-dukung/pindah') ?>" class="nav-link">
                            Pindah Penduduk
                        </a>

                    </div>

                </div>

            </li>

            <li>
                <a href="<?= base_url('download-files') ?>" class="nav-link">
                    <i class="fas fa-download me-2"></i> Download Formulir
                </a>
            </li>

            <li>
                <a href="<?= base_url('bantuan') ?>" 
                   class="nav-link <?= $uri->getSegment(1)=='bantuan'?'active':'' ?>">
                    <i class="fas fa-question-circle me-2"></i> Bantuan
                </a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main-area">

        <div class="content">
            <?= $this->renderSection('content'); ?>
        </div>

        <div class="footer">
            © <?= date('Y'); ?> SI DOKAR
        </div>

    </div>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "<?= session()->getFlashdata('success') ?>",
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('active');
}

document.addEventListener('click', function(e){

    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.menu-toggle');

    if(
        !sidebar.contains(e.target) &&
        !toggle.contains(e.target)
    ){
        sidebar.classList.remove('active');
    }
});
</script>

<!-- MODAL LOGOUT -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content shadow" style="border-radius:15px;">

            <div class="modal-header border-0">

                <h5 class="modal-title fw-bold">
                    Konfirmasi Logout
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center">

                <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>

                <p class="mb-0">
                    Apakah Anda yakin ingin keluar dari sistem?
                </p>

            </div>

            <div class="modal-footer border-0 justify-content-center">

                <button type="button"
                        class="btn btn-secondary px-4"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <a href="<?= base_url('logout') ?>"
                   class="btn btn-danger px-4">
                    Ya, Logout
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>