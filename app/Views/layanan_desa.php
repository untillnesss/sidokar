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

/* WARNA */
.bg-green{ background:#22c55e; }
.bg-blue{ background:#3b82f6; }
.bg-purple{ background:#a855f7; }
.bg-pink{ background:#ec4899; }
.bg-orange{ background:#f59e0b; }
.bg-dark{ background:#1e293b; }
.bg-gray{ background:#6b7280; }

.card-box h6{
    font-weight:600;
    color:#1e293b;
}
</style>

<div class="container-fluid">
    <h3 class="mb-4 text-success">
        <i class="fas fa-building me-2"></i> Layanan Desa
    </h3>

    <div class="row g-4">

        <?php 
        $menu = [
            ['url'=>'akta-kelahiran','nama'=>'Akta Kelahiran','icon'=>'fa-baby','color'=>'bg-green'],
            ['url'=>'akta-kematian','nama'=>'Akta Kematian','icon'=>'fa-cross','color'=>'bg-gray'],
            ['url'=>'kartu-keluarga','nama'=>'Kartu Keluarga','icon'=>'fa-users','color'=>'bg-blue'],
            ['url'=>'kia','nama'=>'KIA','icon'=>'fa-id-card','color'=>'bg-purple'],
            ['url'=>'akta-nikah','nama'=>'Akta Nikah','icon'=>'fa-heart','color'=>'bg-pink'],
            ['url'=>'akta-cerai','nama'=>'Akta Cerai','icon'=>'fa-heart-broken','color'=>'bg-orange'],
            ['url'=>'pindah','nama'=>'Pindah Penduduk','icon'=>'fa-truck','color'=>'bg-dark'],
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