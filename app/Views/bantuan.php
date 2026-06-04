<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<style>
.page-header {
  margin-bottom: 20px;
}

.page-header h1 {
  color: #1a3a8f;
  display: flex;
  align-items: center;
  font-weight: 600;
}

.page-header h1 i {
  margin-right: 10px;
}

.breadcrumb-custom {
  margin-top: 5px;
  color: #555;
  font-size: 14px;
}

.breadcrumb-custom a {
  color: #1a3a8f;
  text-decoration: none;
}

.help-container {
  background: white;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  padding: 30px;
  margin-bottom: 30px;
}

.faq-item {
  margin-bottom: 15px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  transition: 0.3s;
}

.faq-question {
  background: #f0f4ff;
  padding: 14px 18px;
  cursor: pointer;
  font-weight: 600;
  color: #1a3a8f;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.faq-answer {
  padding: 0 20px;
  max-height: 0;
  overflow: hidden;
  transition: all 0.3s ease;
  background: white;
}

.faq-item.active .faq-answer {
  max-height: 500px;
  padding: 15px 20px;
}

.contact-section {
  background: #e8f4ff;
  border-radius: 10px;
  padding: 25px;
  margin-top: 40px;
  border-left: 5px solid #1a3a8f;
}
</style>

<div class="page-header">
  <h1><i class="fas fa-question-circle"></i> Bantuan SIDOKU</h1>
</div>

<div class="help-container">

  <h4 style="color:#1a3a8f; margin-bottom:15px; font-weight:600;">
    Panduan Penggunaan Sistem
  </h4>

  <p>
    Sistem Informasi Dokumen Kependudukan Online (SIDOKU) Kabupaten Tuban
    digunakan untuk membantu proses pengajuan dokumen kependudukan secara digital,
    sehingga lebih cepat, transparan, dan efisien.
  </p>

  <ul>
    <li>Login menggunakan akun desa yang telah terdaftar</li>
    <li>Pilih menu <b>Layanan</b> untuk mengajukan permohonan</li>
    <li>Isi formulir sesuai data sebenarnya</li>
    <li>Unggah dokumen persyaratan dengan lengkap</li>
    <li>Pantau status melalui menu <b>Riwayat Pengajuan</b></li>
  </ul>

  <hr style="margin:30px 0;">

  <h4 style="color:#1a3a8f; font-weight:600;">Pertanyaan Umum (FAQ)</h4>

  <div class="faq-item">
    <div class="faq-question">
      <span>Bagaimana cara mengajukan permohonan?</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="faq-answer">
      Login → Pilih Layanan → Lengkapi Data → Upload Berkas → Klik Kirim Pengajuan.
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      <span>Apa arti status pengajuan?</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="faq-answer">
      <ul>
        <li><b>Menunggu</b> → Pengajuan belum diproses admin</li>
        <li><b>Diproses</b> → Sedang dalam tahap verifikasi</li>
        <li><b>Diterima</b> → Pengajuan telah disetujui</li>
        <li><b>Ditolak</b> → Perlu perbaikan atau dokumen kurang lengkap</li>
      </ul>
    </div>
  </div>

  <div class="faq-item">
    <div class="faq-question">
      <span>Berapa lama proses verifikasi?</span>
      <i class="fas fa-chevron-down"></i>
    </div>
    <div class="faq-answer">
      Proses verifikasi dilakukan sesuai antrean dan kelengkapan dokumen.
      Estimasi waktu 1–3 hari kerja.
    </div>
  </div>

  <div class="contact-section">
    <h5 style="color:#1a3a8f; font-weight:600;">Hubungi Kami</h5>
    <p><b>Telepon:</b> (0356) 321654</p>
    <p><b>Email:</b> bantuan@sidoku-tuban.go.id</p>
    <p><b>Alamat:</b> Jl. Dr. Wahidin Sudirohusodo No.123, Tuban</p>
  </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach((item) => {
    item.querySelector(".faq-question").addEventListener("click", () => {
      item.classList.toggle("active");

      faqItems.forEach((other) => {
        if (other !== item) {
          other.classList.remove("active");
        }
      });
    });
  });
});
</script>

<?= $this->endSection(); ?>