<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>

/* ================= WRAPPER ================= */

.bantuan-wrapper{
    width:100%;
}

/* ================= BOX ================= */

.box-section{
    background:#fff;
    border-radius:18px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
    padding:30px;
    margin-bottom:30px;
    width:100%;
}

/* ================= TITLE ================= */

.page-title{
    color:#1a3a8f;
    font-weight:700;
    margin-bottom:25px;
    font-size:30px;
}

.title-section{
    color:#1a3a8f;
    font-weight:700;
    margin-bottom:20px;
    font-size:22px;
}

/* ================= FAQ ================= */

.faq-item{
    border:1px solid #e2e8f0;
    border-radius:14px;
    margin-bottom:15px;
    overflow:hidden;
    transition:0.2s;
}

.faq-item:hover{
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

.faq-question{
    background:#f0f4ff;
    padding:18px 20px;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:600;
    font-size:16px;
}

.faq-answer{
    max-height:0;
    overflow:hidden;
    transition:0.3s ease;
    padding:0 20px;
}

.faq-answer p{
    margin:0;
    line-height:1.7;
}

.faq-item.active .faq-answer{
    max-height:500px;
    padding:20px;
}

/* ================= CONTACT ================= */

.contact-box{
    background:#e8f4ff;
    border-left:5px solid #1a3a8f;
    border-radius:14px;
    padding:25px;
}

.contact-box p{
    margin-bottom:10px;
    font-size:16px;
}

/* ================= FORM ================= */

.form-control{
    border-radius:12px;
    padding:12px;
}

.btn{
    border-radius:10px;
    padding:10px 18px;
    font-weight:600;
}

/* ================= MOBILE ================= */

@media(max-width:1200px){

    .page-title{
        font-size:22px;
    }

    .title-section{
        font-size:18px;
    }

    .box-section{
        padding:20px;
        border-radius:16px;
    }

    .faq-question{
        padding:15px;
        font-size:14px;
    }

    .faq-answer{
        padding:0 15px;
    }

    .faq-item.active .faq-answer{
        padding:15px;
    }

    .contact-box{
        padding:18px;
    }
}

</style>

<div class="bantuan-wrapper">

    <!-- ================= TITLE ================= -->

    <h3 class="page-title">
        <i class="fas fa-question-circle me-2"></i>
        Bantuan SI DOKAR
    </h3>

    <!-- ================= PANDUAN ================= -->

    <div class="box-section">

        <h5 class="title-section">
            Panduan Penggunaan Sistem
        </h5>

        <?php if(session()->get('is_master') == 1): ?>

        <form action="/bantuan/update-konten" method="post">

            <textarea 
                name="panduan" 
                class="form-control mb-3" 
                rows="6"><?= $konten['panduan'] ?? '' ?></textarea>

            <button class="btn btn-primary">
                Simpan Panduan
            </button>

        </form>

        <?php else: ?>

            <p class="mb-0">
                <?= nl2br($konten['panduan'] ?? '') ?>
            </p>

        <?php endif; ?>

    </div>

    <!-- ================= FAQ ================= -->

    <div class="box-section">

        <h5 class="title-section">
            Pertanyaan Umum (FAQ)
        </h5>

        <?php foreach($faq as $f): ?>

        <div class="faq-item">

            <div class="faq-question">

                <span><?= $f['pertanyaan'] ?></span>

                <i class="fas fa-chevron-down"></i>

            </div>

            <div class="faq-answer">

                <p><?= nl2br($f['jawaban']) ?></p>

                <?php if(session()->get('is_master') == 1): ?>

                <form action="/bantuan/update/<?= $f['id'] ?>" method="post" class="mt-4">

                    <input 
                        type="text" 
                        name="pertanyaan" 
                        value="<?= $f['pertanyaan'] ?>" 
                        class="form-control mb-2">

                    <textarea 
                        name="jawaban" 
                        class="form-control mb-2"
                        rows="4"><?= $f['jawaban'] ?></textarea>

                    <div class="d-flex gap-2 flex-wrap">

                        <button class="btn btn-warning">
                            Update
                        </button>

                        <a href="/bantuan/delete/<?= $f['id'] ?>" class="btn btn-danger">
                            Hapus
                        </a>

                    </div>

                </form>

                <?php endif; ?>

            </div>

        </div>

        <?php endforeach; ?>

        <?php if(session()->get('is_master') == 1): ?>

        <!-- TAMBAH FAQ -->

        <form action="/bantuan/create" method="post" class="mt-4">

            <input 
                type="text" 
                name="pertanyaan" 
                class="form-control mb-3" 
                placeholder="Pertanyaan baru">

            <textarea 
                name="jawaban" 
                class="form-control mb-3" 
                rows="4"
                placeholder="Jawaban"></textarea>

            <button class="btn btn-success">
                Tambah FAQ
            </button>

        </form>

        <?php endif; ?>

    </div>

    <!-- ================= KONTAK ================= -->

    <div class="box-section">

        <h5 class="title-section">
            Hubungi Kami
        </h5>

        <div class="contact-box">

            <?php if(session()->get('is_master') == 1): ?>

            <form action="/bantuan/update-konten" method="post">

                <input 
                    type="text" 
                    name="telepon" 
                    class="form-control mb-3" 
                    value="<?= $konten['telepon'] ?? '' ?>" 
                    placeholder="Telepon">

                <input 
                    type="text" 
                    name="email" 
                    class="form-control mb-3" 
                    value="<?= $konten['email'] ?? '' ?>" 
                    placeholder="Email">

                <textarea 
                    name="alamat" 
                    class="form-control mb-3" 
                    rows="4"
                    placeholder="Alamat"><?= $konten['alamat'] ?? '' ?></textarea>

                <button class="btn btn-primary">
                    Simpan Kontak
                </button>

            </form>

            <?php else: ?>

                <p>
                    <strong>Telepon:</strong>
                    <?= $konten['telepon'] ?? '-' ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?= $konten['email'] ?? '-' ?>
                </p>

                <p class="mb-0">
                    <strong>Alamat:</strong>
                    <?= $konten['alamat'] ?? '-' ?>
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>

<script>

document.querySelectorAll(".faq-question").forEach((item) => {

    item.addEventListener("click", () => {

        const parent = item.parentElement;

        parent.classList.toggle("active");

        document.querySelectorAll(".faq-item").forEach((el) => {

            if (el !== parent){
                el.classList.remove("active");
            }

        });

    });

});

</script>

<?= $this->endSection(); ?>