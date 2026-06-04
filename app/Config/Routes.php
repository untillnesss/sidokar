<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

$routes->get('test-detail/(:num)', function($id){
    return 'ID: ' . $id;
});
$routes->get('cek-controller', 'AktaKelahiran::index');

// ======================================================
// DEFAULT
// ======================================================
$routes->get('/', 'Home::index');
$routes->get('test', 'Test::index');

// ======================================================
// AUTH
// ======================================================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::processForgotPassword');
$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password', 'Auth::processResetPassword');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');
$routes->get('get-desa/(:any)', 'Auth::getDesa/$1');

// ======================================================
// OTP
// ======================================================
$routes->get('verifikasi_otp', 'Otp::index');
$routes->post('verifikasi_otp', 'Otp::verify');

// ======================================================
// DASHBOARD
// ======================================================
$routes->get('dashboard', 'Dashboard::index');
$routes->get('layanan', 'Layanan::index');
$routes->get('bantuan', 'Bantuan::index');

// ======================================================
// AKTA KELAHIRAN (🔥 TARUH DI ATAS BIAR AMAN)
// ======================================================
$routes->group('akta-kelahiran', function($routes){

    $routes->get('/', 'AktaKelahiran::index');
    $routes->get('create', 'AktaKelahiran::create');
    $routes->post('store', 'AktaKelahiran::store');
    $routes->get('edit/(:num)', 'AktaKelahiran::edit/$1');
    $routes->post('update/(:num)', 'AktaKelahiran::update/$1');
    $routes->get('delete/(:num)', 'AktaKelahiran::delete/$1');
    // 🔥 DETAIL (WAJIB ADA DI SINI)
    $routes->get('detail/(:any)', 'AktaKelahiran::detail/$1');
    // status & hasil
    $routes->get('status/(:num)/(:any)', 'AktaKelahiran::updateStatus/$1/$2');
    $routes->post('upload-hasil/(:num)', 'AktaKelahiran::uploadHasil/$1');
    $routes->get('download/(:num)', 'AktaKelahiran::download/$1');
});

// ======================================================
// DATA DUKUNG (🔥 TARUH DI BAWAH BIAR GAK NANGKEP SEMUA)
// ======================================================
$routes->group('data-dukung', function($routes){

    // 🔥 JADIKAN MENU SEBAGAI HALAMAN UTAMA
    $routes->get('/', 'DataDukung::menu');
    $routes->get('list', 'DataDukung::index');
    $routes->get('create', 'DataDukung::create');
    $routes->post('store', 'DataDukung::store');
    $routes->get('edit/(:num)', 'DataDukung::edit/$1');
    $routes->post('update/(:num)', 'DataDukung::update/$1');
    $routes->get('delete/(:num)', 'DataDukung::delete/$1');
    $routes->get('(:segment)', 'DataDukung::kategori/$1');
});
// ======================================================
// ADMIN
// ======================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes){

    // data dukung
    $routes->get('data-dukung', 'DataDukung::index');
    $routes->get('data-dukung/create', 'DataDukung::create');
    $routes->post('data-dukung/store', 'DataDukung::store');
    $routes->get('data-dukung/edit/(:num)', 'DataDukung::edit/$1');
    $routes->post('data-dukung/update/(:num)', 'DataDukung::update/$1');
    $routes->get('data-dukung/delete/(:num)', 'DataDukung::delete/$1');

    // download
    $routes->get('download', 'Download::index');
    $routes->get('download/create', 'Download::create');
    $routes->post('download/store', 'Download::store');
    $routes->get('download/delete/(:num)', 'Download::delete/$1');
    $routes->get('download/edit/(:num)', 'Download::edit/$1');
    $routes->post('download/update/(:num)', 'Download::update/$1');
});

// ======================================================
// USER MANAGEMENT
// ======================================================
$routes->get('user-management', 'UserManagement::index');
$routes->post('user-management/update/(:num)', 'UserManagement::update/$1');
$routes->get('user-management/delete/(:num)', 'UserManagement::delete/$1');

// ======================================================
// DOWNLOAD PUBLIC
// ======================================================
$routes->get('download-files', 'Download::publicList');
$routes->get('download-file/(:num)', 'Download::download/$1');
$routes->get('lihat-file/(:num)', 'Download::lihat/$1');

$routes->get('pindah', 'Pindah::index');
$routes->get('pindah/tambah', 'Pindah::tambah');
$routes->post('pindah/simpan', 'Pindah::simpan');
$routes->get('pindah/detail/(:num)', 'Pindah::detail/$1');

$routes->get('pindah/edit/(:num)', 'Pindah::edit/$1');
$routes->post('pindah/update/(:num)', 'Pindah::update/$1');
$routes->get('pindah/delete/(:num)', 'Pindah::delete/$1');
$routes->post('pindah/update-status', 'Pindah::updateStatus');
$routes->get('pindah/edit-Hasil/(:num)', 'Pindah::editHasil/$1');
$routes->post('pindah/update-hasil/(:num)', 'Pindah::updateHasil/$1');

$routes->get('kia', 'Kia::index');
$routes->get('kia/form', 'Kia::form');
$routes->post('kia/simpan', 'Kia::simpan');
$routes->get('kia/edit/(:num)', 'Kia::edit/$1');
$routes->post('kia/update/(:num)', 'Kia::update/$1');
$routes->get('kia/delete/(:num)', 'Kia::delete/$1');

$routes->get('kia/detail/(:num)', 'Kia::detail/$1');
$routes->get('kia/getDesa/(:any)', 'Kia::getDesa/$1');
$routes->post('kia/update-status', 'Kia::updateStatus');
$routes->post('kia/update-hasil/(:num)', 'Kia::updateHasil/$1');

$routes->get('/akta-cerai', 'AktaCerai::index');
$routes->get('/akta-cerai/create', 'AktaCerai::create');
$routes->post('/akta-cerai/store', 'AktaCerai::store');
$routes->get('/akta-cerai/edit/(:num)', 'AktaCerai::edit/$1');
$routes->post('/akta-cerai/update/(:num)', 'AktaCerai::update/$1');
$routes->get('/akta-cerai/delete/(:num)', 'AktaCerai::delete/$1');
$routes->get('/akta-cerai/detail/(:num)', 'AktaCerai::detail/$1');
$routes->post('/akta-cerai/update-status', 'AktaCerai::updateStatus');
$routes->post('akta-cerai/update-hasil/(:num)', 'AktaCerai::updateHasil/$1');

// ======================
// AKTA NIKAH
// ======================
$routes->group('akta-nikah', function($routes){

    $routes->get('/', 'AktaNikah::index');
    $routes->get('create', 'AktaNikah::create');
    $routes->post('store', 'AktaNikah::store');
    $routes->get('detail/(:num)', 'AktaNikah::detail/$1');
    $routes->get('edit/(:num)', 'AktaNikah::edit/$1');
    $routes->post('update/(:num)', 'AktaNikah::update/$1');
    $routes->get('delete/(:num)', 'AktaNikah::delete/$1');
    $routes->post('update-status', 'AktaNikah::updateStatus');
    $routes->post('update-hasil/(:num)', 'AktaNikah::updateHasil/$1');
});
/*
|--------------------------------------------------------------------------
| ROUTES AKTA KEMATIAN
|--------------------------------------------------------------------------
*/

$routes->group('akta-kematian', function($routes) {

    $routes->get('/', 'AktaKematian::index');
    $routes->get('create', 'AktaKematian::create');
    $routes->post('store', 'AktaKematian::store');
    $routes->get('detail/(:num)', 'AktaKematian::detail/$1');
    $routes->get('edit/(:num)', 'AktaKematian::edit/$1');
    $routes->post('update/(:num)', 'AktaKematian::update/$1');
    $routes->get('delete/(:num)', 'AktaKematian::delete/$1');
    $routes->post('update-status', 'AktaKematian::updateStatus');
    $routes->post('update-hasil/(:num)', 'AktaKematian::updateHasil/$1');
    $routes->get('download/(:num)', 'AktaKematian::download/$1');
});

$routes->group('kartu-keluarga', function($routes){

    $routes->get('/', 'Kk::index');
    $routes->get('tambah', 'Kk::tambah');
    $routes->post('simpan', 'Kk::simpan');
    $routes->get('detail/(:num)', 'Kk::detail/$1');
    $routes->get('edit/(:num)', 'Kk::edit/$1');
    $routes->post('update/(:num)', 'Kk::update/$1');
    $routes->get('delete/(:num)', 'Kk::delete/$1');
    $routes->post('update-status', 'Kk::updateStatus');
    $routes->post('uploadHasil/(:num)', 'Kk::uploadHasil/$1');

    // FIX INI
    $routes->get('edit-hasil/(:num)', 'Kk::editHasil/$1');
    $routes->post('update-hasil/(:num)', 'Kk::updateHasil/$1');
});

$routes->get('/profile', 'Profile::index');
$routes->post('/profile/update', 'Profile::update');
$routes->post('/profile/delete-avatar', 'Profile::deleteAvatar');

$routes->post('/bantuan/create', 'Bantuan::create');
$routes->post('/bantuan/update/(:num)', 'Bantuan::update/$1');
$routes->get('/bantuan/delete/(:num)', 'Bantuan::delete/$1');
$routes->post('/bantuan/update-konten', 'Bantuan::updateKonten');

$routes->post('akta-kelahiran/pengembalian/(:num)', 'AktaKelahiran::pengembalian/$1');
$routes->post('akta-kelahiran/setujui-pengembalian/(:num)', 'AktaKelahiran::setujuiPengembalian/$1');
$routes->post('akta-kelahiran/tolak-pengembalian/(:num)', 'AktaKelahiran::tolakPengembalian/$1');

$routes->post('akta-kematian/pengembalian/(:num)', 'AktaKematian::pengembalian/$1');
$routes->post('akta-kematian/tolak-pengembalian/(:num)', 'AktaKematian::tolakPengembalian/$1');
$routes->post('akta-kematian/setujui-pengembalian/(:num)', 'AktaKematian::setujuiPengembalian/$1');

$routes->post('kartu-keluarga/pengembalian/(:num)', 'Kk::pengembalian/$1');
$routes->post('kartu-keluarga/setujui-pengembalian/(:num)', 'Kk::setujuiPengembalian/$1');
$routes->post('kartu-keluarga/tolak-pengembalian/(:num)', 'Kk::tolakPengembalian/$1');

$routes->post('akta-nikah/pengembalian/(:num)', 'AktaNikah::pengembalian/$1');
$routes->post('akta-nikah/setujui-pengembalian/(:num)', 'AktaNikah::setujuiPengembalian/$1');
$routes->post('akta-nikah/tolak-pengembalian/(:num)', 'AktaNikah::tolakPengembalian/$1');

$routes->post('akta-cerai/pengembalian/(:num)', 'AktaCerai::pengembalian/$1');
$routes->post('akta-cerai/setujui-pengembalian/(:num)', 'AktaCerai::setujuiPengembalian/$1');
$routes->post('akta-cerai/tolak-pengembalian/(:num)', 'AktaCerai::tolakPengembalian/$1');

$routes->post('kia/pengembalian/(:num)', 'Kia::pengembalian/$1');
$routes->post('kia/setujui-pengembalian/(:num)', 'Kia::setujuiPengembalian/$1');
$routes->post('kia/tolak-pengembalian/(:num)', 'Kia::tolakPengembalian/$1');

$routes->post('pindah/pengembalian/(:num)', 'Pindah::pengembalian/$1');
$routes->post('pindah/setujui-pengembalian/(:num)', 'Pindah::setujuiPengembalian/$1');
$routes->post('pindah/tolak-pengembalian/(:num)', 'Pindah::tolakPengembalian/$1');

$routes->post('akta-kelahiran/tolakPengembalian/(:num)', 'AktaKelahiran::tolakPengembalian/$1');
$routes->get('akta-kelahiran/setujuiPengembalian/(:num)', 'AktaKelahiran::setujuiPengembalian/$1');

$routes->post('akta-kematian/setujui-pengajuan/(:num)', 'AktaKematian::setujuiPengajuan/$1');
$routes->post('akta-kematian/tolak-pengajuan/(:num)', 'AktaKematian::tolakPengajuan/$1');

$routes->get('kartu-keluarga/detail/(:num)', 'Kk::detail/$1');
$routes->post('kartu-keluarga/setujui/(:num)', 'Kk::setujui/$1');
$routes->post('kartu-keluarga/tolak/(:num)', 'Kk::tolak/$1');

$routes->post('akta-nikah/setujui/(:num)', 'AktaNikah::setujui/$1');
$routes->post('akta-nikah/tolak/(:num)', 'AktaNikah::tolak/$1');

// DETAIL
$routes->get('akta-cerai/detail/(:num)', 'AktaCerai::detail/$1');

// SETUJUI
$routes->post('akta-cerai/setujui/(:num)', 'AktaCerai::setujui/$1');

// TOLAK / REVISI
$routes->post('akta-cerai/tolak/(:num)', 'AktaCerai::tolak/$1');

// ================= KIA =================
$routes->post('kia/setujui/(:num)', 'Kia::setujui/$1');
$routes->post('kia/tolak/(:num)', 'Kia::tolak/$1');

$routes->post('pindah/setujui/(:num)', 'Pindah::setujui/$1');
$routes->post('pindah/tolak/(:num)', 'Pindah::tolak/$1');