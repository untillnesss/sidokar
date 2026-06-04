<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<h3 class="mb-4 text-primary">
    <i class="fas fa-folder-open"></i> Menu Layanan
</h3>

<div class="row g-4">

<div class="col-md-4 col-lg-3">
    <a href="<?= base_url('akta-kelahiran'); ?>" 
       style="text-decoration:none; color:inherit;">
        <div class="card shadow-sm border-0 text-center p-4">
            <i class="fas fa-baby fa-2x text-primary mb-3"></i>
            <h6>Akta Kelahiran</h6>
        </div>
    </a>
</div>

</div>

<?= $this->endSection(); ?>