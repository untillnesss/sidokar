<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $db = Database::connect();

        $role       = session()->get('role');
        $kode_desa  = session()->get('kode_desa');
        $isMaster   = (int) session()->get('is_master');

        $bulan = $this->request->getGet('bulan'); // bisa null
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        $layanan = [
            'permohonan'        => 'created_at',
            'pengajuan_kia'     => 'tanggal_pengajuan',
            'pengajuan_pindah'  => 'tanggal_pengajuan',
        ];

        $statuses = [
            'Pengajuan',
            'Proses',
            'Revisi',
            'Selesai'
        ];

        $dataStatus = [];

        if (!empty($bulan)) {
            // Filter per bulan
            $startDate = sprintf('%04d-%02d-01 00:00:00', $tahun, $bulan);
            $endDate   = date('Y-m-t 23:59:59', strtotime($startDate));
        } else {
            // 🔥 Semua bulan dalam 1 tahun
            $startDate = $tahun . '-01-01 00:00:00';
            $endDate   = $tahun . '-12-31 23:59:59';
        }

        foreach ($statuses as $status) {

            $totalPerStatus = 0;

            foreach ($layanan as $tabel => $kolomTanggal) {

                $builder = $db->table($tabel)
                    ->where('status', $status)
                    ->where($kolomTanggal . ' >=', $startDate)
                    ->where($kolomTanggal . ' <=', $endDate);

                /*
                |--------------------------------------------------------------------------
                | FILTER AKSES
                |--------------------------------------------------------------------------
                */
                if ($role === 'desa') {

                    if (!empty($kode_desa)) {
                        $builder->where('kode_desa', $kode_desa);
                    } else {
                        $builder->where('1 = 0');
                    }
                }

                $totalPerStatus += $builder->countAllResults();
            }

            $dataStatus[] = $totalPerStatus;
        }
        $page  = (int) ($this->request->getGet('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $queryAktivitas = "

        -- AKTA KELAHIRAN
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'Akta Kelahiran' AS layanan,
            a.nama_anak AS nama,
            p.status,
            p.created_at AS waktu
        FROM permohonan p
        LEFT JOIN anak a ON a.id_anak = p.id_anak
        LEFT JOIN desa d ON d.kode_desa = p.kode_desa
        WHERE 
            DATE(p.created_at) = CURDATE()
            OR DATE(p.updated_at) = CURDATE()
            OR DATE(p.tanggal_pengembalian) = CURDATE()

        UNION ALL

        -- KIA
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'KIA' AS layanan,
            pk.nama_anak AS nama,
            pk.status,
            pk.tanggal_pengajuan AS waktu
        FROM pengajuan_kia pk
        LEFT JOIN desa d ON d.kode_desa = pk.kode_desa
        WHERE 
            DATE(pk.tanggal_pengajuan) = CURDATE()
            OR DATE(pk.terakhir_upload) = CURDATE()

        UNION ALL

        -- PINDAH
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            CONCAT('Pindah ', pp.jenis_pindah) AS layanan,
            MIN(ap.nama_anggota) AS nama,
            pp.status,
            pp.tanggal_pengajuan AS waktu
        FROM pengajuan_pindah pp
        LEFT JOIN anggota_pindah ap 
            ON ap.id_pengajuan = pp.id_pengajuan
        LEFT JOIN desa d 
            ON d.kode_desa = pp.kode_desa
        WHERE 
            DATE(pp.tanggal_pengajuan) = CURDATE()
        GROUP BY pp.id_pengajuan

        UNION ALL

        -- KK
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'Kartu Keluarga' AS layanan,
            pk.nama_kepala AS nama,
            pk.status,
            pk.created_at AS waktu
        FROM pengajuan_kk pk
        LEFT JOIN desa d ON d.kode_desa = pk.kode_desa
        WHERE 
            DATE(pk.created_at) = CURDATE()
            OR DATE(pk.updated_at) = CURDATE()

        UNION ALL

        -- AKTA NIKAH
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'Akta Nikah' AS layanan,
            CONCAT(pan.nama_laki_laki, ' & ', pan.nama_perempuan) AS nama,
            pan.status,
            pan.created_at AS waktu
        FROM pengajuan_akta_nikah pan
        LEFT JOIN desa d ON d.kode_desa = pan.kode_desa
        WHERE 
            DATE(pan.created_at) = CURDATE()
            OR DATE(pan.updated_at) = CURDATE()

        UNION ALL

        -- AKTA CERAI
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'Akta Cerai' AS layanan,
            CONCAT(pac.nama_laki, ' & ', pac.nama_perempuan) AS nama,
            pac.status,
            pac.created_at AS waktu
        FROM pengajuan_akta_cerai pac
        LEFT JOIN desa d ON d.kode_desa = pac.kode_desa
        WHERE 
            DATE(pac.created_at) = CURDATE()
            OR DATE(pac.updated_at) = CURDATE()
            OR DATE(pac.tanggal_proses) = CURDATE()
            OR DATE(pac.tanggal_selesai) = CURDATE()

        UNION ALL

        -- AKTA KEMATIAN
        SELECT 
            COALESCE(d.nama_desa, 'Admin Dukcapil') AS instansi,
            'Akta Kematian' AS layanan,
            pak.nama_jenazah AS nama,
            pak.status,
            pak.created_at AS waktu
        FROM pengajuan_akta_kematian pak
        LEFT JOIN desa d ON d.kode_desa = pak.kode_desa
        WHERE 
            DATE(pak.created_at) = CURDATE()
            OR DATE(pak.updated_at) = CURDATE()
    ";

       $filterDesa = "";

        if ($role === 'desa') {
            $filterDesa = "WHERE instansi = (
                SELECT nama_desa 
                FROM desa 
                WHERE kode_desa = '$kode_desa'
            )";
        }

        $totalAktivitas = count(
            $db->query("
                SELECT * FROM (
                    $queryAktivitas
                ) AS aktivitas
                $filterDesa
            ")->getResultArray()
        );
        $totalPages = ceil($totalAktivitas / $limit);

        $aktivitas = $db->query("
            SELECT * FROM (
                $queryAktivitas
            ) AS aktivitas
            $filterDesa
            ORDER BY waktu DESC
            LIMIT $limit OFFSET $offset
        ")->getResultArray();

                return view('dashboard', [
            'dataStatus' => $dataStatus,
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'is_master'  => $isMaster,
            'aktivitas'  => $aktivitas,
            'page'       => $page,
            'totalPages' => $totalPages
        ]);
    }
}