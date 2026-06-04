<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
.menu-card{
    text-decoration:none;
}

.card-box{
    background:white;
    padding:25px;
    border-radius:18px;
    transition:0.25s;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
    height:100%;
}

.card-box:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 25px rgba(0,0,0,0.12);
}

.icon-box{
    width:65px;
    height:65px;
    margin:auto;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:12px;
    font-size:22px;
    color:white;
}

/* WARNA ADMIN (lebih formal) */
.bg-blue{ background:#2563eb; }
.bg-indigo{ background:#4f46e5; }
.bg-teal{ background:#14b8a6; }
.bg-purple{ background:#7c3aed; }
.bg-rose{ background:#e11d48; }
.bg-amber{ background:#f59e0b; }
.bg-slate{ background:#334155; }

.card-box h6{
    font-weight:600;
    color:#1e293b;
}
</style>

<div class="container-fluid">
    <h3 class="mb-4 text-primary">
        <i class="fas fa-user-shield me-2"></i> Layanan Admin
    </h3>

    <div class="row g-4">

        <?php 
        $menu = [
            ['url'=>'akta-kelahiran','nama'=>'Akta Kelahiran','icon'=>'fa-baby','color'=>'bg-blue'],
            ['url'=>'akta-kematian','nama'=>'Akta Kematian','icon'=>'fa-cross','color'=>'bg-slate'],
            ['url'=>'kartu-keluarga','nama'=>'Kartu Keluarga','icon'=>'fa-users','color'=>'bg-teal'],
            ['url'=>'akta-nikah','nama'=>'Akta Kawin','icon'=>'fa-heart','color'=>'bg-rose'],
            ['url'=>'akta-cerai','nama'=>'Akta Cerai','icon'=>'fa-heart-broken','color'=>'bg-amber'],
            ['url'=>'kia','nama'=>'KIA','icon'=>'fa-id-card','color'=>'bg-purple'],
            ['url'=>'pindah','nama'=>'Pindah Masuk & Keluar','icon'=>'fa-truck','color'=>'bg-indigo'],
        ];
        ?>

        <?php foreach($menu as $m): ?>
        <div class="col-md-4 col-lg-3">
            <a href="<?= base_url($m['url']); ?>" class="menu-card">
                <div class="card-box text-center">
                    <div class="icon-box <?= $m['color'] ?>">
                        <i class="fas <?= $m['icon'] ?>"></i>
                    </div>
                    <h6><?= $m['nama'] ?></h6>
                </div>
            </a>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<?= $this->endSection(); ?>