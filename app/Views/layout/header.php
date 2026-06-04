<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIDOKU | Kabupaten Tuban</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* HEADER */
        .main-header {
            background: linear-gradient(135deg,#0b47a9,#1f64c2);
            color: white;
            height: 70px;
        }

        /* SIDEBAR DARK */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
            background: #1e1e2d;
            color: #ccc;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #aaa;
            padding: 12px 20px;
            font-size: 14px;
            transition: 0.2s;
        }

        .sidebar .nav-link:hover {
            background: #2c2c3e;
            color: white;
        }

        .sidebar .nav-link.active {
            background: #0b47a9;
            color: white;
            border-left: 4px solid #4dabf7;
        }

        .sidebar .submenu {
            padding-left: 25px;
        }

        .sidebar .submenu .nav-link {
            font-size: 13px;
            padding: 8px 20px;
        }

        /* CONTENT */
        .content-area {
            margin-left: 260px;
            padding: 100px 30px 30px 30px;
        }

        @media(max-width: 768px){
            .sidebar {
                display: none;
            }
            .content-area {
                margin-left: 0;
                padding-top: 90px;
            }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header class="main-header fixed-top d-flex align-items-center px-4 justify-content-between shadow">
    <div class="d-flex align-items-center gap-3">
        <img src="/Tubankab.png" width="45">
        <div>
            <div class="fw-bold">SIDOKU</div>
            <small>Kabupaten Tuban</small>
        </div>
    </div>

    <?php if(session()->get('logged_in')): ?>
    <div>
        👋 <?= session()->get('username'); ?>
        <a href="/logout" class="btn btn-sm btn-danger ms-3">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
    <?php endif; ?>
</header>

<?php if(session()->get('logged_in')): ?>

<!-- SIDEBAR -->
<div class="sidebar">

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="/dashboard" class="nav-link">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <!-- LAYANAN DROPDOWN -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#layananMenu">
                <i class="fas fa-folder me-2"></i> Layanan
                <i class="fas fa-chevron-down float-end mt-1"></i>
            </a>

            <div class="collapse submenu" id="layananMenu">
                <a href="#" class="nav-link"><i class="fas fa-baby me-2"></i> Akta Kelahiran</a>
                <a href="#" class="nav-link"><i class="fas fa-cross me-2"></i> Akta Kematian</a>
                <a href="#" class="nav-link"><i class="fas fa-id-card me-2"></i> Kartu Keluarga</a>
                <a href="#" class="nav-link"><i class="fas fa-id-badge me-2"></i> KIA</a>
                <a href="#" class="nav-link"><i class="fas fa-heart me-2"></i> Akta Kawin</a>
                <a href="#" class="nav-link"><i class="fas fa-heart-broken me-2"></i> Akta Cerai</a>
                <a href="#" class="nav-link"><i class="fas fa-truck me-2"></i> Pindah Penduduk</a>
            </div>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-folder-open me-2"></i> Data Dukung
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-download me-2"></i> Download
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-question-circle me-2"></i> Bantuan
            </a>
        </li>

    </ul>

</div>

<?php endif; ?>

<!-- CONTENT -->
<div class="content-area">
