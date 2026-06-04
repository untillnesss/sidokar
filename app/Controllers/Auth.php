<?php


namespace App\Controllers;

use App\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends BaseController
{
    /* ======================================================
        LOGIN
    ====================================================== */

    public function login()
    {
        
        return view('auth/login');
    }

    public function processLogin()
    {
        $model   = new UserModel();
        $session = session();

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        $user = $model
            ->where('username', $username)
            ->where('role', $role)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Username, password atau role salah!');
        }

        // Generate OTP
        $otp         = (string) rand(100000, 999999);
        $otp_expired = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $model->update($user['id'], [
            'otp_code'    => $otp,
            'otp_expired' => $otp_expired
        ]);

        $session->set([
            'temp_user_id' => $user['id'],
            'otp_email'    => $user['email']
        ]);

        $this->sendEmail($user['email'], $user['nama_lengkap'] ?? '', $otp, 'otp');

        return redirect()->to('/verifikasi_otp');
    }

    /* ======================================================
        VERIFIKASI OTP
    ====================================================== */

    public function index()
    {
        return view('auth/verifikasi_otp');
    }

    public function verify()
    {
        $model   = new UserModel();
        $session = session();

        $otpInput = trim($this->request->getPost('otp'));
        $userId   = $session->get('temp_user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Session OTP tidak ditemukan.');
        }

        // Ambil user berdasarkan ID dulu (bukan langsung cocokkan OTP)
        $user = $model->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }

        // Validasi OTP manual (lebih aman)
        if (
            $user['otp_code'] !== $otpInput ||
            strtotime($user['otp_expired']) < time()
        ) {
            return redirect()->back()->with('error', 'OTP salah atau sudah expired.');
        }

        // Hapus OTP setelah berhasil
        $model->update($user['id'], [
            'otp_code'    => null,
            'otp_expired' => null
        ]);

        $session->remove(['temp_user_id', 'otp_email']);

        // Pastikan is_master integer
        $isMaster = (int) ($user['is_master'] ?? 0);

        // Set session login final
        $session->set([
            'id'        => $user['id'],
            'nama'      => $user['nama_lengkap'] ?? '',
            'username'  => $user['username'],
            'role'      => $user['role'],
            'is_master' => $isMaster,
            'avatar'    => $user['avatar'] ?? null, // ✅ TAMBAH INI
            'logged_in' => true
        ]);
        return redirect()->to('/dashboard');
    }

    /* ======================================================
        REGISTER
    ====================================================== */

    public function register()
    {
        $db = \Config\Database::connect();
        $kecamatan = $db->table('kecamatan')->get()->getResult();

        return view('auth/register', [
            'kecamatan' => $kecamatan
        ]);
    }

    public function processRegister()
    {
        $model = new UserModel();
        $db    = \Config\Database::connect();

        $role      = $this->request->getPost('role');
        $id_desa   = $this->request->getPost('id_desa');
        $kode_desa = null;

        if ($model->where('username', $this->request->getPost('username'))->first()) {
            return redirect()->back()->with('error', 'Username sudah digunakan.');
        }

        if ($model->where('email', $this->request->getPost('email'))->first()) {
            return redirect()->back()->with('error', 'Email sudah digunakan.');
        }

        if ($role == 'desa') {

            if (!$id_desa) {
                return redirect()->back()->with('error', 'Desa wajib dipilih.');
            }

            $desa = $db->table('desa')
                ->where('id_desa', $id_desa)
                ->get()
                ->getRow();

            if (!$desa) {
                return redirect()->back()->with('error', 'Desa tidak valid.');
            }

            $kode_desa = $desa->kode_desa;
        }

        $model->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'role'         => $role,
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'kode_desa'    => $kode_desa,
            'is_master'    => 0
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil.');
    }

    /* ======================================================
        GET DESA AJAX
    ====================================================== */

    public function getDesa($kode_kecamatan)
    {
        $db = \Config\Database::connect();

        $desa = $db->table('desa')
            ->where('kode_kecamatan', $kode_kecamatan)
            ->orderBy('nama_desa', 'ASC')
            ->get()
            ->getResult();

        return $this->response->setJSON($desa);
    }

    /* ======================================================
        LOGOUT
    ====================================================== */

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    /* ======================================================
        EMAIL FUNCTION
    ====================================================== */

    private function sendEmail($email, $nama, $data, $type = 'otp')
    {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dheasecond.part02@gmail.com';
            $mail->Password   = 'swrtaiqbngjzgufs';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('dheasecond.part02@gmail.com', 'Admin SIDOKAR');
            $mail->addAddress($email);
            $mail->isHTML(true);

            if ($type == 'otp') {

                $mail->Subject = 'Kode OTP Login SIDOKAR';
                $mail->Body = "
                    <h3>Halo $nama</h3>
                    <p>Kode OTP Anda:</p>
                    <h2>$data</h2>
                    <p>Berlaku 5 menit.</p>
                ";

            } elseif ($type == 'reset_link') {

                $mail->Subject = 'Reset Password SIDOKAR';
                $mail->Body = "
                    <h3>Halo $nama</h3>
                    <p>Klik tombol di bawah untuk reset password:</p>
                    <a href='$data' style='padding:10px 20px;background:#1565c0;color:white;text-decoration:none;border-radius:5px;'>
                        Reset Password
                    </a>
                ";
            }

            $mail->send();

        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
    }

    /* ======================================================
    FORGOT PASSWORD
====================================================== */

public function forgotPassword()
{
    return view('auth/forgot_password');
}

public function processForgotPassword()
{
    $model = new UserModel();

    $email = $this->request->getPost('email');

    $user = $model->where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Email tidak ditemukan.');
    }

    // generate token
    $token = bin2hex(random_bytes(32));

    $model->update($user['id'], [
        'reset_token' => $token,
        'token_expired' => date("Y-m-d H:i:s", strtotime("+1 hour"))
    ]);

    $link = base_url('/reset-password?token=' . $token);

    $this->sendEmail($email, $user['nama_lengkap'], $link, 'reset_link');

    return redirect()->back()->with('success', 'Link reset password sudah dikirim ke email.');
}


/* ======================================================
    RESET PASSWORD
====================================================== */

    public function resetPassword()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return redirect()->to('/login')->with('error', 'Token tidak valid.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function processResetPassword()
    {
        $model = new UserModel();

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $user = $model->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token tidak ditemukan.');
        }

        if (strtotime($user['token_expired']) < time()) {
            return redirect()->to('/login')->with('error', 'Token sudah expired.');
        }

        $model->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'token_expired' => null
        ]);

        return redirect()->to('/login')->with('success', 'Password berhasil direset.');
    }
}