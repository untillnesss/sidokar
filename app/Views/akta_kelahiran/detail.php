<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Lihat Pengajuan Akta Kelahiran</h3>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php
function isImageKelahiran($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png']);
}

function tampilFileKelahiran($path)
{
    if (!$path) return '<small class="text-muted">Tidak ada</small>';

    if (isImageKelahiran($path)) {
        return '
            <img src="' . base_url($path) . '" 
                 class="img-thumbnail"
                 style="width:120px;height:120px;object-fit:cover;cursor:pointer;"
                 onclick="showImage(this.src)">
        ';
    } else {
        return '
            <a href="' . base_url($path) . '" target="_blank">
                📄 Lihat File
            </a>
        ';
    }
}
?>

<form>

    <!-- ================= DATA ANAK ================= -->
    <h5>Data Anak</h5>

    <div class="mb-3">
        <label>Nama Anak</label>
        <input type="text" value="<?= esc($pengajuan['nama_anak']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Jenis Kelamin</label>
        <input type="text" value="<?= esc($pengajuan['jenis_kelamin']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Tempat Kelahiran</label>
        <input type="text" value="<?= esc($pengajuan['tempat_kelahiran']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" value="<?= esc($pengajuan['tanggal_lahir']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Jam Lahir</label>
        <input type="time" value="<?= esc($pengajuan['jam_lahir']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Anak Ke</label>
        <input type="text" value="<?= esc($pengajuan['kelahiran_ke']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Berat Bayi</label>
        <input type="text" value="<?= esc($pengajuan['berat_bayi']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>Panjang Bayi</label>
        <input type="text" value="<?= esc($pengajuan['panjang_bayi']) ?>" class="form-control" readonly>
    </div>

    <!-- ================= DATA IBU ================= -->
    <hr>
    <h5>Data Ibu</h5>

    <div class="mb-3">
        <label>Nama Ibu</label>
        <input type="text" value="<?= esc($pengajuan['nama_ibu']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>NIK Ibu</label>
        <input type="text" value="<?= esc($pengajuan['nik_ibu']) ?>" class="form-control" readonly>
    </div>

    <!-- ================= DATA AYAH ================= -->
    <hr>
    <h5>Data Ayah</h5>

    <div class="mb-3">
        <label>Nama Ayah</label>
        <input type="text" value="<?= esc($pengajuan['nama_ayah']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>NIK Ayah</label>
        <input type="text" value="<?= esc($pengajuan['nik_ayah']) ?>" class="form-control" readonly>
    </div>

    <!-- ================= DATA PELAPOR ================= -->
    <hr>
    <h5>Data Pelapor</h5>

    <div class="mb-3">
        <label>Nama Pelapor</label>
        <input type="text" value="<?= esc($pengajuan['nama_pelapor']) ?>" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label>NIK Pelapor</label>
        <input type="text" value="<?= esc($pengajuan['nik_pelapor']) ?>" class="form-control" readonly>
    </div>

    <!-- ================= DATA SAKSI ================= -->
    <hr>
    <h5>Data Saksi</h5>

    <div class="mb-3">
        <label>Nama Saksi 1</label>
        <input type="text"
               value="<?= esc($pengajuan['nama_saksi_1'] ?? '-') ?>"
               class="form-control"
               readonly>
    </div>

    <div class="mb-3">
        <label>NIK Saksi 1</label>
        <input type="text"
               value="<?= esc($pengajuan['nik_saksi_1'] ?? '-') ?>"
               class="form-control"
               readonly>
    </div>

    <div class="mb-3">
        <label>Nama Saksi 2</label>
        <input type="text"
               value="<?= esc($pengajuan['nama_saksi_2'] ?? '-') ?>"
               class="form-control"
               readonly>
    </div>

    <div class="mb-3">
        <label>NIK Saksi 2</label>
        <input type="text"
               value="<?= esc($pengajuan['nik_saksi_2'] ?? '-') ?>"
               class="form-control"
               readonly>
    </div>

    <!-- ================= DOKUMEN ================= -->
    <hr>
    <h5>Dokumen</h5>

    <div class="row">

        <?php
        $jenisDokumen = [
            'KTP Ayah',
            'KTP Ibu',
            'KTP Saksi 1',
            'KTP Saksi 2',
            'Surat Lahir Desa',
            'Surat Lahir RS'
        ];
        ?>

        <?php foreach ($jenisDokumen as $jenis): ?>

            <div class="col-md-4 mb-3">

                <div class="border rounded p-3 text-center h-100">

                    <b><?= esc($jenis) ?></b>

                    <hr>

                    <?php if (!empty($dokumen[$jenis])): ?>

                        <?php
                        $file = $dokumen[$jenis][0];
                        echo tampilFileKelahiran($file['path_file'] ?? '');
                        ?>

                    <?php else: ?>

                        <small class="text-muted">Tidak ada file</small>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <!-- ================= FORMULIR F1.02 ================= -->
    <div class="mt-4">

        <h5>Formulir F1.02</h5>

        <div class="row">

            <?php if (!empty($dokumen['Formulir F1.02'])): ?>

                <?php foreach ($dokumen['Formulir F1.02'] as $f): ?>

                    <div class="col-md-3 mb-3">

                        <div class="border rounded p-2 text-center">

                            <?= tampilFileKelahiran($f['path_file'] ?? '') ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <small class="text-muted">Tidak ada file F1.02</small>

            <?php endif; ?>

        </div>

    </div>

</form>

<!-- ================= BUTTON ================= -->
<div class="mt-4 d-flex gap-2 flex-wrap">

    <a href="<?= base_url('akta-kelahiran') ?>" class="btn btn-secondary">
        ← Kembali
    </a>

    <?php if(session()->get('role') == 'admin' && $pengajuan['status'] == 'Pengajuan'): ?>

        <!-- SETUJUI -->
        <form action="<?= base_url('akta-kelahiran/upload-hasil/'.$pengajuan['id_permohonan']) ?>"
              method="post">

            <?= csrf_field(); ?>

            <input type="hidden" name="status" value="Proses">

            <button type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#setujuiModal">Setujui
            </button>

        </form>

        <!-- TOLAK -->
        <button type="button"
            class="btn btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#tolakModal"> Tolak
        </button>

    <?php endif; ?>

</div>

<!-- ================= FORM TOLAK ================= -->
<?php if(session()->get('role') == 'admin' && $pengajuan['status'] == 'Pengajuan'): ?>

<?php endif; ?>

<!-- ================= MODAL PREVIEW ================= -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function showImage(src) {
    document.getElementById('previewImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
<!-- MODAL SETUJUI -->
<div class="modal fade" id="setujuiModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow" style="border-radius:15px;">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">
            Konfirmasi Persetujuan
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>

        <p class="mb-0">
            Apakah Anda yakin ingin menyetujui pengajuan ini?
        </p>
      </div>

      <div class="modal-footer border-0 justify-content-center">

        <button type="button"
                class="btn btn-secondary px-4"
                data-bs-dismiss="modal">
            Batal
        </button>

        <!-- 🔥 FORM SETUJUI -->
        <form action="<?= base_url('akta-kelahiran/upload-hasil/'.$pengajuan['id_permohonan']) ?>"
              method="post">

            <?= csrf_field(); ?>

            <input type="hidden"
                   name="status"
                   value="Proses">

            <button type="submit"
                    class="btn btn-success px-4">
                Ya, Setujui
            </button>

        </form>

      </div>
    </div>
  </div>
</div>


<!-- MODAL REVISI -->
<div class="modal fade" id="tolakModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow" style="border-radius:15px;">

      <form action="<?= base_url('akta-kelahiran/upload-hasil/'.$pengajuan['id_permohonan']) ?>"
            method="post">

        <?= csrf_field(); ?>

        <div class="modal-header border-0">

          <h5 class="modal-title fw-bold text-warning">
              Revisi Pengajuan
          </h5>

          <button type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"></button>

        </div>

        <div class="modal-body text-center">

          <i class="fas fa-edit fa-3x text-warning mb-3"></i>

          <p>
              Masukkan catatan revisi untuk pemohon
          </p>

          <input type="hidden"
                 name="status"
                 value="Revisi">

          <textarea name="catatan_revisi"
                    class="form-control"
                    rows="4"
                    placeholder="Masukkan catatan revisi..."
                    required></textarea>

        </div>

        <div class="modal-footer border-0 justify-content-center">

          <button type="button"
                  class="btn btn-secondary px-4"
                  data-bs-dismiss="modal">
              Batal
          </button>

          <button type="submit"
                  class="btn btn-warning text-white px-4">
              Kirim Revisi
          </button>

        </div>

      </form>

    </div>
  </div>
</div>

<?= $this->endSection() ?>