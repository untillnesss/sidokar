<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color:#0d3b66;">
        Profil Saya
    </h4>
    <small class="text-muted">
        Kelola informasi akun Anda dengan mudah
    </small>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<div class="d-flex justify-content-center">
    <div class="card p-4 shadow-sm" style="width:100%; max-width:600px; border-radius:12px;">
        
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">

            <div style="position:relative; width:70px; height:70px; margin-right:15px;">

                <img id="previewAvatar"
                     src="<?= base_url('uploads/avatar/' . ($user['avatar'] ?? 'default.png')) ?>" 
                     style="width:70px; height:70px; border-radius:50%; object-fit:cover;
                            box-shadow:0 2px 8px rgba(0,0,0,0.15);">

                <div class="dropdown" style="position:absolute; bottom:0; right:0;">
    
                <button class="btn p-0 border-0" data-bs-toggle="dropdown">
                    <div style="
                        background:#2563eb;
                        color:white;
                        width:26px;
                        height:26px;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:12px;">
                        <i class="fas fa-camera"></i>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <label class="dropdown-item" for="avatarInput" style="cursor:pointer;">
                            <i class="fas fa-edit me-2"></i> Edit Foto
                        </label>
                    </li>

                    <li>
                        <button type="button" class="dropdown-item text-danger" onclick="hapusFoto()">
                            <i class="fas fa-trash me-2"></i> Hapus Foto
                        </button>
                    </li>
                </ul>

            </div>

            </div>

            <div>
                <h5 class="mb-1 fw-semibold"><?= $user['nama_lengkap'] ?? '-' ?></h5>
                <small class="text-muted"><?= $user['email'] ?? '-' ?></small>
            </div>
        </div>

        <h6 class="mb-3 text-secondary">Informasi Akun</h6>

        <form id="formProfile" action="/profile/update" method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" 
                       class="form-control form-control-sm"
                       value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" 
                       class="form-control form-control-sm"
                       value="<?= $user['email'] ?? '' ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" 
                       class="form-control form-control-sm"
                       value="<?= $user['username'] ?? '' ?>" readonly>
            </div>

            <!-- 🔥 FILE INPUT -->
            <input type="file" name="avatar" id="avatarInput" accept=".jpg,.jpeg" hidden>

            <!-- 🔥 TAMBAHAN (WAJIB) -->
            <input type="hidden" name="avatar_crop" id="avatarCrop">

            <button class="btn btn-primary w-100 mt-2">Update Profil</button>
        </form>
    </div>
</div>

<!-- MODAL CROPPER -->
<div class="modal fade" id="cropModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Atur Foto Profil</h5>
      </div>

      <div class="modal-body">
        <img id="imageCrop" style="max-width:100%;">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" id="cropBtn">Simpan</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
let cropper;
const avatarInput = document.getElementById('avatarInput');
const imageCrop = document.getElementById('imageCrop');

// PILIH FILE
avatarInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    if (!['image/jpeg', 'image/jpg'].includes(file.type)) {
        Swal.fire('Error', 'Avatar harus JPG / JPEG', 'error');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        imageCrop.src = e.target.result;

        const modal = new bootstrap.Modal(document.getElementById('cropModal'));
        modal.show();

        if (cropper) cropper.destroy();

        cropper = new Cropper(imageCrop, {
            aspectRatio: 1,
            viewMode: 1,
        });
    };
    reader.readAsDataURL(file);
});

// CROPPING
document.getElementById('cropBtn').addEventListener('click', function() {
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300
    });

    const base64 = canvas.toDataURL('image/jpeg');

    document.getElementById('previewAvatar').src = base64;
    document.getElementById('avatarCrop').value = base64;

    bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
});

// KONFIRMASI SUBMIT
document.getElementById('formProfile').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan perubahan?',
        text: 'Pastikan data sudah benar',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});


// 🔥 PINDAH KE LUAR (WAJIB)
function hapusFoto() {
    Swal.fire({
        title: 'Hapus foto profil?',
        text: 'Foto akan kembali ke default',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/profile/delete-avatar';

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?= $this->endSection() ?>