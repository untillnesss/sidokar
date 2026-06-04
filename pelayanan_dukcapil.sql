-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2026 pada 14.19
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pelayanan_dukcapil`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anak`
--

CREATE TABLE `anak` (
  `id_anak` int(11) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `jk_anak` enum('laki-laki','perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jam_lahir` time DEFAULT NULL,
  `berat_bayi` decimal(4,1) DEFAULT NULL,
  `panjang_bayi` decimal(4,1) DEFAULT NULL,
  `id_permohonan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anak`
--

INSERT INTO `anak` (`id_anak`, `nama_anak`, `anak_ke`, `jk_anak`, `tempat_lahir`, `tgl_lahir`, `jam_lahir`, `berat_bayi`, `panjang_bayi`, `id_permohonan`) VALUES
(15, 'vh', NULL, 'perempuan', 'TUBAN', '2025-09-02', '18:00:00', 3.0, 44.0, NULL),
(25, 'sulis', NULL, 'perempuan', 'TUBAN', '2025-10-01', '12:11:00', 3.0, 44.0, NULL),
(28, 'Dio', NULL, 'laki-laki', 'Malang', '2025-10-02', '09:41:00', 3.8, 50.0, 25),
(30, 'ajeng', NULL, 'perempuan', 'MOJOKERTO', '2025-10-01', '14:20:00', 3.0, 55.0, 27),
(37, 'kangen band', 1, 'perempuan', 'Tuban', '2026-03-01', '11:40:00', 3.0, 50.0, 34),
(39, 'khanzaaaaa', 1, 'perempuan', 'semarang', '2025-01-01', '20:06:00', 3.2, 51.0, 36),
(40, 'ramadhani', 2, 'perempuan', 'surabaya', '2026-02-03', '09:03:00', 3.6, 49.0, 37),
(42, 'Nanda', 1, 'laki-laki', 'Tuban', '2026-04-01', '20:42:00', 3.5, 49.9, 39),
(43, 'DHEA', 2, 'perempuan', 'Tuban', '2026-04-04', '22:06:00', 3.0, 50.0, 40),
(44, 'angel', 2, 'perempuan', 'Tuban', '2026-04-30', '19:33:00', 3.0, 44.0, 41),
(46, 'Amalia', 1, 'perempuan', 'Tuban', '2026-05-01', '21:41:00', 3.0, 45.0, 43),
(49, 'DHEA', 1, 'laki-laki', 'Tuban', '2026-05-01', '21:25:00', 1.0, 0.8, NULL),
(50, 'DHEA', 1, 'perempuan', 'Tuban', '2026-05-01', '21:36:00', 1.0, 1.0, 48),
(51, 'ramadhania', 1, 'laki-laki', 'Tuban', '2026-05-01', '19:24:00', 1.0, 1.0, 49);

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_kk`
--

CREATE TABLE `anggota_kk` (
  `id_anggota` int(11) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `field_diubah` varchar(50) DEFAULT NULL,
  `nilai_lama` varchar(100) DEFAULT NULL,
  `nilai_baru` varchar(100) DEFAULT NULL,
  `dasar_perubahan` varchar(100) DEFAULT NULL,
  `file_dokumen` varchar(255) DEFAULT NULL,
  `shdk` enum('Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Mertua','Famili Lain') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota_kk`
--

INSERT INTO `anggota_kk` (`id_anggota`, `id_pengajuan`, `nama`, `nik`, `field_diubah`, `nilai_lama`, `nilai_baru`, `dasar_perubahan`, `file_dokumen`, `shdk`) VALUES
(7, 3, 'ADI', '6577665367577790', NULL, NULL, NULL, NULL, NULL, 'Kepala Keluarga'),
(8, 3, 'DINI', '6577665367577787', NULL, NULL, NULL, NULL, NULL, 'Istri'),
(16, 5, 'AZIZ', '0987654321234545', NULL, NULL, NULL, NULL, NULL, 'Kepala Keluarga'),
(17, 4, 'SISIIIII', '6577665367575545', '', '', '', '', NULL, 'Anak'),
(18, 2, 'JONO', '0987654321234343', 'Nama', 'jono', 'joko', 'akta', NULL, 'Kepala Keluarga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_pindah`
--

CREATE TABLE `anggota_pindah` (
  `id_anggota` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `nama_anggota` varchar(100) NOT NULL,
  `nik_anggota` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota_pindah`
--

INSERT INTO `anggota_pindah` (`id_anggota`, `id_pengajuan`, `nama_anggota`, `nik_anggota`) VALUES
(12, 8, 'Desember', '9765436789087567'),
(15, 10, 'Ramadhani', '5654558798967674'),
(26, 11, 'ayu aulia', '1321313131331331'),
(31, 19, 'dwika', '5643232455576785'),
(32, 19, 'rama', '6434556789098868'),
(45, 12, 'dwika', '7675785785785757'),
(46, 24, 'jokooooo', '9879797878789698'),
(47, 18, 'gghghj', '8698686575787578'),
(48, 21, 'ramadha', '5675656756756756');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bantuan_faq`
--

CREATE TABLE `bantuan_faq` (
  `id` int(11) NOT NULL,
  `pertanyaan` text DEFAULT NULL,
  `jawaban` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bantuan_faq`
--

INSERT INTO `bantuan_faq` (`id`, `pertanyaan`, `jawaban`) VALUES
(2, 'Bagaimana cara mengajukan permohonan?', 'Login → Pilih Layanan → Lengkapi Data → Upload Berkas → Klik Kirim Pengajuan.'),
(3, 'Apa arti status pengajuan?', '<b>Pengajuan</b>  → Pengajuan belum diproses admin\r\n\r\n<b>Proses</b>  → Sedang dalam tahap verifikasi\r\n\r\n<b>Revisi</b> → Perlu perbaikan atau dokumen kurang lengkap\r\n\r\n<b>Selesai</b>  →  Pengajuan telah disetujui dan hasil pengajuan telah diunggah'),
(4, 'Berapa lama proses verifikasi?', 'Proses verifikasi dilakukan sesuai antrean dan kelengkapan dokumen.\r\nEstimasi waktu 1–3 hari kerja.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bantuan_konten`
--

CREATE TABLE `bantuan_konten` (
  `id` int(11) NOT NULL,
  `panduan` text DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bantuan_konten`
--

INSERT INTO `bantuan_konten` (`id`, `panduan`, `telepon`, `email`, `alamat`, `updated_at`) VALUES
(1, 'Sistem Informasi Dokumen Administrasi Rakyat (Si Dokar) Kabupaten Tuban digunakan untuk membantu proses pengajuan dokumen kependudukan secara digital, sehingga lebih cepat, transparan, dan efisien\r\n\r\n    1. Login menggunakan akun desa yang telah terdaftar\r\n    2. Pilih menu Layanan untuk mengajukan permohonan\r\n    3. Isi formulir sesuai data sebenarnya\r\n    4. Unggah dokumen persyaratan dengan lengkap\r\n    5. Pantau status melalui menu layanan pada kolom status', '(0356) 321654', 'bantuan@sidokar-tuban.go.id', 'Jl. Teuku Umar Tuban', '2026-04-19 03:51:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_dukung`
--

CREATE TABLE `data_dukung` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_dukung`
--

INSERT INTO `data_dukung` (`id`, `kategori`, `judul`, `isi`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'akta-kelahiran', 'Pendaftaran Kelahiran WNI Dalam Wilayah NKRI', 'Surat keterangan kelahiran dari desa/dokter/bidan/RS/perwakilan RI/lembaga sosial\r\nBukti riwayat kelahiran dari orang tua/keluarga atau saksi\r\nKartu Keluarga\r\nBerita acara laporan kelahiran / surat kelahiran dari desa\r\nSPTJM dengan 2 saksi jika tidak memiliki syarat huruf a\r\nSPTJM pertimbangan tertentu dengan 2 saksi', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-04-19 21:59:44'),
(3, 'akta-kematian', 'Pendaftaran Kematian Dalam Wilayah NKRI', 'a. Surat keterangan kematian dari desa/RS/perwakilan RI/lembaga sosial\r\nb. Bukti riwayat kematian dari keluarga/saksi\r\nc. Kartu Keluarga\r\nd. Berita acara laporan kematian / surat kematian dari desa\r\ne. SPTJM dengan 2 saksi jika tidak memiliki syarat huruf a\r\nf. SPTJM pertimbangan tertentu dengan 2 saksi', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-25 22:48:09'),
(4, 'kartu-keluarga', 'KK Baru Karena Membentuk Keluarga Baru', 'a. Kartu Keluarga lama\r\nb. Buku nikah/akta perkawinan/perceraian\r\nc. SPTJM jika belum tercatat', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-25 22:48:09'),
(5, 'kartu-keluarga', 'KK Hilang / Rusak', 'a. Surat kehilangan dari kepolisian\r\nb. KTP-el\r\nc. KITAP (untuk WNA)', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-25 22:48:09'),
(6, 'kartu-identitas-anak', 'Penerbitan KIA Baru', 'a. Kutipan Akta Kelahiran\r\nb. KK orang tua\r\nc. KTP-el orang tua\r\nd. Pas foto anak (5-17 tahun)', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-25 22:48:09'),
(7, 'pindah', 'Persyaratan Pindah Datang dan Pindah Keluar', 'SKPWNI (untuk pindah datang)\r\nKartu Keluarga\r\nKTP-el (Jika sudah diatas 17 tahun)\r\nSurat Pernyataan Perubahan Elemen Data (jika ada)\r\nSurat Pernyataan Alamat (jika kontrak)\r\nSurat Tidak Keberatan Numpang KK (jika masuk KK orang lain)', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-28 17:39:32'),
(8, 'akta-nikah', 'Persyaratan Akta Nikah', 'Akta Kelahiran suami dan istri\r\nb. Surat Keterangan dari Desa\r\nc. KTP suami dan istri\r\nd. Akta Perkawinan orang tua\r\ne. Surat perceraian (bagi yang pernah menikah)\r\nf. Surat keterangan belum pernah menikah\r\ng. Pas foto berdampingan ukuran 4x6', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-28 17:35:40'),
(9, 'akta-cerai', 'Persyaratan Akta Cerai', 'Salinan penetapan Pengadilan Negeri\r\nAkta perkawinan asli\r\nKTP suami dan istri\r\nKartu Keluarga', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram', '2026-02-25 22:48:09', '2026-02-28 17:57:27'),
(10, 'akta-kelahiran', 'Pendaftaran Kelahiran WNA', 'a. Surat keterangan kelahiran dari desa/dokter/bidan/RS/perwakilan RI/lembaga sosial\r\nb. Bukti riwayat kelahiran dari orang tua/keluarga atau saksi\r\nc. Dokumen Perjalanan\r\nd. KTP-el orang tua atau identitas lain/visa\r\ne. SPTJM dengan 2 saksi jika tidak memiliki syarat huruf a\r\nf. SPTJM pertimbangan tertentu dengan 2 saksi', 'Ketentuan:\r\n- Berkas upload format JPG\r\n- Maksimal 400 KB\r\n- Dicetak kertas A4 80 gram.', '2026-02-28 17:47:31', '2026-02-28 17:47:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `desa`
--

CREATE TABLE `desa` (
  `id_desa` int(11) NOT NULL,
  `kode_desa` varchar(15) NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `kode_kecamatan` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `desa`
--

INSERT INTO `desa` (`id_desa`, `kode_desa`, `nama_desa`, `kode_kecamatan`) VALUES
(0, '678123', 'deso', '237890'),
(1, '3523151001', 'Gedongombo', '352315'),
(2, '3523151002', 'Karang', '352315'),
(3, '3523152001', 'Bejagung', '352315'),
(4, '3523152002', 'Bektiharjo', '352315'),
(5, '3523152003', 'Boto', '352315'),
(6, '3523152004', 'Genaharjo', '352315'),
(7, '3523152005', 'Gesing', '352315'),
(8, '3523152006', 'Jadi', '352315'),
(9, '3523152007', 'Kowang', '352315'),
(10, '3523152008', 'Ngino', '352315'),
(11, '3523152009', 'Penambangan', '352315'),
(12, '3523152010', 'Prunggahan Kulon', '352315'),
(13, '3523152011', 'Prunggahan Wetan', '352315'),
(14, '3523152012', 'Sambongrejo', '352315'),
(15, '3523152013', 'Semanding', '352315'),
(16, '3523152014', 'Tegalagung', '352315'),
(17, '3523152015', 'Tunah', '352315'),
(18, '35230401', 'Bancar', '352304'),
(19, '35230402', 'Banjarejo', '352304'),
(20, '35230403', 'Bogorejo', '352304'),
(21, '35230404', 'Boncong', '352304'),
(22, '35230405', 'Bulujowo', '352304'),
(23, '35230406', 'Bulumeduro', '352304'),
(24, '35230407', 'Cingklung', '352304'),
(25, '35230408', 'Jatisari', '352304'),
(26, '35230409', 'Karangrejo', '352304'),
(27, '35230410', 'Kayen', '352304'),
(28, '35230411', 'Latsari', '352304'),
(29, '35230412', 'Margosuko', '352304'),
(30, '35230413', 'Ngampelrejo', '352304'),
(31, '35230414', 'Ngujuran', '352304'),
(32, '35230415', 'Pugoh', '352304'),
(33, '35230416', 'Sembungin', '352304'),
(34, '35230417', 'Siding', '352304'),
(35, '35230418', 'Sidomulyo', '352304'),
(36, '35230419', 'Sukoharjo', '352304'),
(37, '35230420', 'Sukolilo', '352304'),
(38, '35230421', 'Sumberan', '352304'),
(39, '35230422', 'Tengger Kulon', '352304'),
(40, '35230423', 'Tergambang', '352304'),
(41, '35230424', 'Tlogoagung', '352304'),
(42, '35230301', 'Bangilan', '352303'),
(43, '35230302', 'Banjarworo', '352303'),
(44, '35230303', 'Bate', '352303'),
(45, '35230304', 'Kablukan', '352303'),
(46, '35230305', 'Kedungharjo', '352303'),
(47, '35230306', 'Kedungjambangan', '352303'),
(48, '35230307', 'Kedungmulyo', '352303'),
(49, '35230308', 'Klakeh', '352303'),
(50, '35230309', 'Kumpulrejo', '352303'),
(51, '35230310', 'Ngrojo', '352303'),
(52, '35230311', 'Sidodadi', '352303'),
(53, '35230312', 'Sidokumpul', '352303'),
(54, '35230313', 'Sidotentrem', '352303'),
(55, '35230314', 'Weden', '352303'),
(56, '35232001', 'Banyubang', '352320'),
(57, '35232002', 'Dahor', '352320'),
(58, '35232003', 'Dermawuharjo', '352320'),
(59, '35232004', 'Gesikan', '352320'),
(60, '35232005', 'Grabagan', '352320'),
(61, '35232006', 'Menyunyur', '352320'),
(62, '35232007', 'Ngandong', '352320'),
(63, '35232008', 'Ngarum', '352320'),
(64, '35232009', 'Ngrejeng', '352320'),
(65, '35232010', 'Pakis', '352320'),
(66, '35232011', 'Waleran', '352320'),
(67, '3523082016', 'Gaji', '352308'),
(68, '3523082001', 'Gemulung', '352308'),
(69, '3523082004', 'Hargoretno', '352308'),
(70, '3523082010', 'Jarorejo', '352308'),
(71, '3523082013', 'Karanglo', '352308'),
(72, '3523082012', 'Kasiman', '352308'),
(73, '3523082009', 'Kedungrejo', '352308'),
(74, '3523082003', 'Margomulyo', '352308'),
(75, '3523082007', 'Margorejo', '352308'),
(76, '3523082011', 'Mliwang', '352308'),
(77, '3523082017', 'Padasan', '352308'),
(78, '3523082005', 'Sidonganti', '352308'),
(79, '3523082014', 'Sumberarum', '352308'),
(80, '3523082008', 'Temayang', '352308'),
(81, '3523082006', 'Tenggerwetan', '352308'),
(82, '3523082015', 'Trantang', '352308'),
(83, '3523082002', 'Wolutengah', '352308'),
(84, '3523160001', 'Sumurgung', '352316'),
(85, '3523160002', 'Sugiharjo', '352316'),
(86, '3523160003', 'Mondokan', '352316'),
(87, '3523160004', 'Perbon', '352316'),
(88, '3523160005', 'Kembangbilo', '352316'),
(89, '3523160006', 'Karangsari', '352316'),
(90, '3523160007', 'Latsari', '352316'),
(91, '3523160008', 'Kingking', '352316'),
(92, '3523160009', 'Ronggomulyo', '352316'),
(93, '3523160010', 'Sidorejo', '352316'),
(94, '3523160011', 'Doromukti', '352316'),
(95, '3523130001', 'Bogorejo', '352313'),
(96, '3523130002', 'Borehbangle', '352313'),
(97, '3523130003', 'Kapu', '352313'),
(98, '3523130004', 'Mandirejo', '352313'),
(99, '3523130005', 'Pongpongan', '352313'),
(100, '3523130006', 'Sambonggede', '352313'),
(101, '3523130007', 'Sembungrejo', '352313'),
(102, '3523130008', 'Sendanghaji', '352313'),
(103, '3523130009', 'Senori', '352313'),
(104, '3523130010', 'Sugihan', '352313'),
(105, '3523130011', 'Sumber', '352313'),
(106, '3523130012', 'Sumberejo', '352313'),
(107, '3523130013', 'Tahulu', '352313'),
(108, '3523130014', 'Tegalrejo', '352313'),
(109, '3523130015', 'Temandang', '352313'),
(110, '3523130016', 'Tlogowaru', '352313'),
(111, '3523130017', 'Tobo', '352313'),
(112, '3523130018', 'Tuwiri Kulon', '352313'),
(113, '3523130019', 'Tuwiri Wetan', '352313'),
(151, '3523180001', 'Cendoro', '352318'),
(152, '3523180002', 'Cepokorejo', '352318'),
(153, '3523180003', 'Dawung', '352318'),
(154, '3523180004', 'Gesikharjo', '352318'),
(155, '3523180005', 'Glodog', '352318'),
(156, '3523180006', 'Karangagung', '352318'),
(157, '3523180007', 'Ketambul', '352318'),
(158, '3523180008', 'Kradenan', '352318'),
(159, '3523180009', 'Leran Kulon', '352318'),
(160, '3523180010', 'Leran Wetan', '352318'),
(161, '3523180011', 'Ngimbang', '352318'),
(162, '3523180012', 'Palang', '352318'),
(163, '3523180013', 'Panyuran', '352318'),
(164, '3523180014', 'Pliwetan', '352318'),
(165, '3523180015', 'Pucangan', '352318'),
(166, '3523180016', 'Sumurgung', '352318'),
(167, '3523180017', 'Tasikmadu', '352318'),
(168, '3523180018', 'Tegalbang', '352318'),
(169, '3523180019', 'Wangun', '352318'),
(170, '3523020001', 'Kebonharjo', '352302'),
(171, '3523020002', 'Wangi', '352302'),
(172, '3523020003', 'Ketodan', '352302'),
(173, '3523020004', 'Karangtengah', '352302'),
(174, '3523020005', 'Bader', '352302'),
(175, '3523020006', 'Paseyan', '352302'),
(176, '3523020007', 'Besowo', '352302'),
(177, '3523020008', 'Jombok', '352302'),
(178, '3523020009', 'Sadang', '352302'),
(179, '3523020010', 'Wotsogo', '352302'),
(180, '3523020011', 'Ngepon', '352302'),
(181, '3523020012', 'Sugihan', '352302'),
(182, '3523020013', 'Sidomulyo', '352302'),
(183, '3523020014', 'Kedungmakam', '352302'),
(184, '3523020015', 'Demit', '352302'),
(185, '3523020016', 'Jatiklabang', '352302'),
(186, '3523020017', 'Sekaran', '352302'),
(187, '3523020018', 'Dingil', '352302'),
(260, '3523140001', 'Kebonagung', '352314'),
(261, '3523140002', 'Pekuwon', '352314'),
(262, '3523140003', 'Rengel', '352314'),
(263, '3523140004', 'Maibit', '352314'),
(264, '3523140005', 'Bulurejo', '352314'),
(265, '3523140006', 'Karangtinoto', '352314'),
(266, '3523140007', 'Sawahan', '352314'),
(267, '3523140008', 'Kanorejo', '352314'),
(268, '3523140009', 'Tambakrejo', '352314'),
(269, '3523140010', 'Ngadirejo', '352314'),
(270, '3523140011', 'Campurejo', '352314'),
(271, '3523140012', 'Banjaragung', '352314'),
(272, '3523140013', 'Banjararum', '352314'),
(273, '3523140014', 'Prambonwetan', '352314'),
(274, '3523140015', 'Punggulrejo', '352314'),
(275, '3523140016', 'Sumberejo', '352314'),
(276, '3523190001', 'Banjar', '352319'),
(277, '3523190002', 'Bunut', '352319'),
(278, '3523190003', 'Compreng', '352319'),
(279, '3523190004', 'Kedungharjo', '352319'),
(280, '3523190005', 'Kujung', '352319'),
(281, '3523190006', 'Minohorejo', '352319'),
(282, '3523190007', 'Mlangi', '352319'),
(283, '3523190008', 'Mrutuk', '352319'),
(284, '3523190009', 'Ngadipuro', '352319'),
(285, '3523190010', 'Ngadirejo', '352319'),
(286, '3523190011', 'Patihan', '352319'),
(287, '3523190012', 'Simorejo', '352319'),
(288, '3523190013', 'Sumberejo', '352319'),
(289, '3523190014', 'Tegalrejo', '352319'),
(290, '3523190015', 'Tegalsari', '352319'),
(291, '3523190016', 'Widang', '352319'),
(292, '3523050001', 'Banyuurip', '352305'),
(293, '3523050002', 'Jatisari', '352305'),
(294, '3523050003', 'Kaligede', '352305'),
(295, '3523050004', 'Katerban', '352305'),
(296, '3523050005', 'Leran', '352305'),
(297, '3523050006', 'Medalem', '352305'),
(298, '3523050007', 'Rayung', '352305'),
(299, '3523050008', 'Sendang', '352305'),
(300, '3523050009', 'Sidoharjo', '352305'),
(301, '3523050010', 'Wanglukulon', '352305'),
(302, '3523050011', 'Wangluwetan', '352305'),
(303, '3523050012', 'Wonosari', '352305'),
(304, '3523070001', 'Binangun', '352307'),
(305, '3523070002', 'Lajo Kidul', '352307'),
(306, '3523070003', 'Lajo Lor', '352307'),
(307, '3523070004', 'Kedungjambe', '352307'),
(308, '3523070005', 'Mergosari', '352307'),
(309, '3523070006', 'Mulyoagung', '352307'),
(310, '3523070007', 'Mulyorejo', '352307'),
(311, '3523070008', 'Saringembat', '352307'),
(312, '3523070009', 'Tanggir', '352307'),
(313, '3523070010', 'Tanjungrejo', '352307'),
(314, '3523070011', 'Tingkis', '352307'),
(315, '3523070012', 'Tunggulrejo', '352307'),
(316, '3523092001', 'Sembung', '352309'),
(317, '3523092002', 'Kemlaten', '352309'),
(318, '3523092003', 'Sukorejo', '352309'),
(319, '3523092004', 'Ngawun', '352309'),
(320, '3523092005', 'Mergoasri', '352309'),
(321, '3523092006', 'Brangkal', '352309'),
(322, '3523092007', 'Wukirharjo', '352309'),
(323, '3523092008', 'Cengkong', '352309'),
(324, '3523092009', 'Dagangan', '352309'),
(325, '3523092010', 'Kumpulrejo', '352309'),
(326, '3523092011', 'Margorejo', '352309'),
(327, '3523092012', 'Mojomalang', '352309'),
(328, '3523092013', 'Suciharjo', '352309'),
(329, '3523092014', 'Sendangrejo', '352309'),
(330, '3523092015', 'Selogabus', '352309'),
(331, '3523092016', 'Sugihwaras', '352309'),
(332, '3523092017', 'Pacing', '352309'),
(333, '3523092018', 'Parangbatu', '352309'),
(334, '3523172001', 'Trutup', '352317'),
(335, '3523172002', 'Kesamben', '352317'),
(336, '3523172003', 'Kepohagung', '352317'),
(337, '3523172004', 'Kedungrojo', '352317'),
(338, '3523172005', 'Sumurjalak', '352317'),
(339, '3523172006', 'Sembungrejo', '352317'),
(340, '3523172007', 'Sumberagung', '352317'),
(341, '3523172008', 'Cangkring', '352317'),
(342, '3523172009', 'Plumpang', '352317'),
(343, '3523172010', 'Plandirejo', '352317'),
(344, '3523172011', 'Ngrayung', '352317'),
(345, '3523172012', 'Bandungrejo', '352317'),
(346, '3523172013', 'Jatimulyo', '352317'),
(347, '3523172014', 'Klotok', '352317'),
(348, '3523172015', 'Magersari', '352317'),
(349, '3523172016', 'Kebomlati', '352317'),
(350, '3523172017', 'Penidon', '352317'),
(351, '3523172018', 'Kedungsoko', '352317'),
(352, '3523010001', 'Jlodro', '352301'),
(353, '3523010002', 'Sokogunung', '352301'),
(354, '3523010003', 'Jamprong', '352301'),
(355, '3523010004', 'Tawaran', '352301'),
(356, '3523010005', 'Sidomukti', '352301'),
(357, '3523010006', 'Bendonglateng', '352301'),
(358, '3523010007', 'Sidohasri', '352301'),
(359, '3523010008', 'Sidorejo', '352301'),
(360, '3523010009', 'Sokogrenjeng', '352301'),
(361, '3523060001', 'Dikir', '352306'),
(362, '3523060002', 'Ngulahan', '352306'),
(363, '3523060003', 'Plajan', '352306'),
(364, '3523060004', 'Mander', '352306'),
(365, '3523060005', 'Belikanget', '352306'),
(366, '3523060006', 'Cokrowati', '352306'),
(367, '3523060007', 'Pulogede', '352306'),
(368, '3523060008', 'Gadon', '352306'),
(369, '3523060009', 'Sotang', '352306'),
(370, '3523060010', 'Pabeyan', '352306'),
(371, '3523060011', 'Klutuk', '352306'),
(372, '3523060012', 'Tambakboyo', '352306'),
(373, '3523060013', 'Dasin', '352306'),
(374, '3523100001', 'Bringin', '352310'),
(375, '3523100002', 'Guwoterus', '352310'),
(376, '3523100003', 'Jetak', '352310'),
(377, '3523100004', 'Maindu', '352310'),
(378, '3523100005', 'Manjung', '352310'),
(379, '3523100006', 'Montongsekar', '352310'),
(380, '3523100007', 'Nguluhan', '352310'),
(381, '3523100008', 'Pakel', '352310'),
(382, '3523100009', 'Pucangan', '352310'),
(383, '3523100010', 'Sumurgung', '352310'),
(384, '3523100011', 'Talangkembar', '352310'),
(385, '3523100012', 'Talun', '352310'),
(386, '3523100013', 'Tanggulangin', '352310'),
(387, '3523110001', 'Bangunrejo', '352311'),
(388, '3523110002', 'Cekalang', '352311'),
(389, '3523110003', 'Glagahsari', '352311'),
(390, '3523110004', 'Gununganyar', '352311'),
(391, '3523110005', 'Jati', '352311'),
(392, '3523110006', 'Jegulo', '352311'),
(393, '3523110007', 'Kendalrejo', '352311'),
(394, '3523110008', 'Kenongosari', '352311'),
(395, '3523110009', 'Klumpit', '352311'),
(396, '3523110010', 'Menilo', '352311'),
(397, '3523110011', 'Mentoro', '352311'),
(398, '3523110012', 'Mojoagung', '352311'),
(399, '3523110013', 'Nguruan', '352311'),
(400, '3523110014', 'Pandanagung', '352311'),
(401, '3523110015', 'Pandanwangi', '352311'),
(402, '3523110016', 'Prambon Tergayang', '352311'),
(403, '3523110017', 'Rahayu', '352311'),
(404, '3523110018', 'Sandingrowo', '352311'),
(405, '3523110019', 'Simo', '352311'),
(406, '3523110020', 'Sokosari', '352311'),
(407, '3523110021', 'Sumurcinde', '352311'),
(408, '3523110022', 'Tluwe', '352311'),
(409, '3523110023', 'Wadung', '352311'),
(410, '3523122001', 'Karangasem', '352312'),
(411, '3523122002', 'Socorejo', '352312'),
(412, '3523122003', 'Temaji', '352312'),
(413, '3523122004', 'Purworejo', '352312'),
(414, '3523122005', 'Tasikharjo', '352312'),
(415, '3523122006', 'Sumurgeneng', '352312'),
(416, '3523122007', 'Suwalan', '352312'),
(417, '3523122008', 'Remen', '352312'),
(418, '3523122009', 'Beji', '352312'),
(419, '3523122010', 'Wadung', '352312'),
(420, '3523122011', 'Rawasan', '352312'),
(421, '3523122012', 'Mentoso', '352312'),
(422, '3523122013', 'Jenggolo', '352312'),
(423, '3523122014', 'Kaliuntu', '352312'),
(424, '3523122015', 'Sekardadi', '352312'),
(425, '3523122016', 'Jenu', '352312'),
(426, '3523122017', 'Sugihwaras', '352312'),
(427, '3523060014', 'Kenanti', '352306'),
(428, '3523060015', 'Sawir', '352306'),
(429, '3523060016', 'Sobontoro', '352306'),
(430, '3523060017', 'Merkawang', '352306'),
(431, '3523060018', 'Glondonggede', '352306'),
(432, '3523160012', 'Sidomulyo', '352316'),
(433, '3523160013', 'Kutorejo', '352316'),
(434, '3523160014', 'Kebonsari', '352316'),
(435, '3523160015', 'Sendangharjo', '352316'),
(436, '3523160016', 'Baturetno', '352316'),
(437, '3523160017', 'Sukolilo', '352316'),
(438, '456789', 'baru', '123456'),
(439, '901234', 'valid', '345678');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `jenis_dokumen` varchar(50) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `ukuran_file` int(11) NOT NULL,
  `tipe_file` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `id_permohonan`, `jenis_dokumen`, `nama_file`, `path_file`, `ukuran_file`, `tipe_file`, `uploaded_at`) VALUES
(121, 25, 'KTP Ayah', 'TTD PKK.jpg', 'uploads/1760579511_TTD PKK.jpg', 112567, 'image/jpeg', '2025-10-16 01:51:51'),
(123, 25, 'Kartu Keluarga', 'belakang.jpg', 'uploads/1760579511_belakang.jpg', 107355, 'image/jpeg', '2025-10-16 01:51:51'),
(124, 25, 'KTP Saksi 1', 'depan.jpg', 'uploads/1760579511_depan.jpg', 128401, 'image/jpeg', '2025-10-16 01:51:51'),
(125, 25, 'KTP Saksi 2', 'CP.jpg', 'uploads/1760579511_CP.jpg', 186984, 'image/jpeg', '2025-10-16 01:51:51'),
(126, 25, 'Surat Keterangan Lahir', 'WhatsApp Image 2025-07-18 at 13.52.27_8f279220.jpg', 'uploads/1760579511_WhatsApp Image 2025-07-18 at 13.52.27_8f279220.jpg', 74459, 'image/jpeg', '2025-10-16 01:51:51'),
(127, 25, 'Surat dari Desa', 'WhatsApp Image 2024-12-26 at 10.55.33_c7e6b34e.jpg', 'uploads/1760579511_WhatsApp Image 2024-12-26 at 10.55.33_c7e6b34e.jpg', 79087, 'image/jpeg', '2025-10-16 01:51:51'),
(128, 25, 'KTP Pelapor', 'Waktu.jpg', 'uploads/1760579511_Waktu.jpg', 300075, 'image/jpeg', '2025-10-16 01:51:51'),
(129, 25, 'F1.02', 'WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 'uploads/68f04fb7b7677_WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 115327, 'image/jpeg', '2025-10-16 01:51:51'),
(130, 25, 'F1.02', '7 kebiasaan.jpg', 'uploads/68f04fb7b8894_7 kebiasaan.jpg', 134576, 'image/jpeg', '2025-10-16 01:51:51'),
(131, 25, 'F2.01', 'cici.jpg', 'uploads/68f04fb7b99b4_cici.jpg', 172455, 'image/jpeg', '2025-10-16 01:51:51'),
(132, 25, 'F2.01', 'WhatsApp Image 2025-01-14 at 13.47.46_de5254d4.jpg', 'uploads/68f04fb7ba873_WhatsApp Image 2025-01-14 at 13.47.46_de5254d4.jpg', 36462, 'image/jpeg', '2025-10-16 01:51:51'),
(143, 27, 'KTP Ayah', 'Gambar1.jpg', 'uploads/1760941317_Gambar1.jpg', 4265, 'image/jpeg', '2025-10-20 06:21:57'),
(144, 27, 'KTP Ibu', 'Waktu.jpg', 'uploads/1760941317_Waktu.jpg', 300075, 'image/jpeg', '2025-10-20 06:21:57'),
(145, 27, 'Kartu Keluarga', 'TTD.jpg', 'uploads/1760941317_TTD.jpg', 86998, 'image/jpeg', '2025-10-20 06:21:57'),
(146, 27, 'KTP Saksi 1', 'Pas Foto Berwarna (1).jpg', 'uploads/1760941317_Pas Foto Berwarna (1).jpg', 50406, 'image/jpeg', '2025-10-20 06:21:57'),
(147, 27, 'KTP Saksi 2', '9 Ags.jpg', 'uploads/1760941317_9 Ags.jpg', 110426, 'image/jpeg', '2025-10-20 06:21:57'),
(148, 27, 'Surat Keterangan Lahir', '15 kerja bakti.jpg', 'uploads/1760941317_15 kerja bakti.jpg', 129117, 'image/jpeg', '2025-10-20 06:21:57'),
(149, 27, 'Surat dari Desa', 'background frame cute for education.jpeg.jpg', 'uploads/1760941317_background frame cute for education.jpeg.jpg', 65280, 'image/jpeg', '2025-10-20 06:21:57'),
(150, 27, 'KTP Pelapor', 'WhatsApp Image 2025-07-18 at 13.52.27_71cb35f8.jpg', 'uploads/1760941317_WhatsApp Image 2025-07-18 at 13.52.27_71cb35f8.jpg', 84771, 'image/jpeg', '2025-10-20 06:21:57'),
(151, 27, 'F1.02', 'PROMES.jpg', 'uploads/68f5d50552a67_PROMES.jpg', 149135, 'image/jpeg', '2025-10-20 06:21:57'),
(152, 27, 'F1.02', 'code.jpg', 'uploads/68f5d505537a0_code.jpg', 52924, 'image/jpeg', '2025-10-20 06:21:57'),
(153, 27, 'F1.02', 'images.jpg', 'uploads/68f5d5055486f_images.jpg', 4187, 'image/jpeg', '2025-10-20 06:21:57'),
(154, 27, 'F1.02', 'WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 'uploads/68f5d5055544a_WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 115327, 'image/jpeg', '2025-10-20 06:21:57'),
(155, 27, 'F1.02', '7 kebiasaan.jpg', 'uploads/68f5d505560e5_7 kebiasaan.jpg', 134576, 'image/jpeg', '2025-10-20 06:21:57'),
(156, 27, 'F2.01', 'PROMES.jpg', 'uploads/68f5d50556e04_PROMES.jpg', 149135, 'image/jpeg', '2025-10-20 06:21:57'),
(157, 27, 'F2.01', 'code.jpg', 'uploads/68f5d50557c67_code.jpg', 52924, 'image/jpeg', '2025-10-20 06:21:57'),
(158, 27, 'F2.01', 'images.jpg', 'uploads/68f5d505590b4_images.jpg', 4187, 'image/jpeg', '2025-10-20 06:21:57'),
(159, 27, 'F2.01', 'WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 'uploads/68f5d50559e64_WhatsApp Image 2025-04-28 at 15.10.53_d29d711f.jpg', 115327, 'image/jpeg', '2025-10-20 06:21:57'),
(160, 27, 'F2.01', '7 kebiasaan.jpg', 'uploads/68f5d5055aa1a_7 kebiasaan.jpg', 134576, 'image/jpeg', '2025-10-20 06:21:57'),
(161, 27, 'KTP Ayah', 'WhatsApp Image 2025-09-27 at 11.37.22 (1).jpeg', 'uploads/1761019826_WhatsApp Image 2025-09-27 at 11.37.22 (1).jpeg', 125776, 'image/jpeg', '2025-10-21 04:10:26'),
(162, 27, 'KTP Ibu', 'Gambar1.jpg', 'uploads/1761025265_Gambar1.jpg', 4265, 'image/jpeg', '2025-10-21 05:41:05'),
(206, 34, 'KTP Ibu', 'erd new.drawio.png', 'uploads/dokumen/1774409451_erd_new.drawio.png', 299673, 'image/png', '2026-03-24 20:30:51'),
(208, 34, 'KTP Saksi 2', 'WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 'uploads/dokumen/1774409451_WhatsApp_Image_2026-01-16_at_17.37.10.jpeg', 103055, 'image/jpeg', '2026-03-24 20:30:51'),
(212, 34, 'KTP Ayah', 'Waktu.jpg', 'uploads/dokumen/1774414919_Waktu.jpg', 300075, 'image/jpeg', '2026-03-24 22:01:59'),
(216, 34, 'KTP Saksi 1', 'sihana new.jpg', 'uploads/dokumen/1774415287_sihana_new.jpg', 75799, 'image/jpeg', '2026-03-24 22:08:07'),
(235, 34, 'Surat Lahir Desa', 'Waktu.jpg', 'uploads/dokumen/1774416988_Waktu.jpg', 300075, 'image/jpeg', '2026-03-24 22:36:28'),
(236, 34, 'Surat Lahir RS', 'TTD PKK.jpg', 'uploads/dokumen/1774416988_TTD_PKK.jpg', 112567, 'image/jpeg', '2026-03-24 22:36:28'),
(238, 34, 'Formulir F1.02', 'belakang.jpg', 'uploads/dokumen/1774417016_belakang.jpg', 107355, 'image/jpeg', '2026-03-24 22:36:56'),
(247, 36, 'KTP Ayah', 'WhatsApp Image 2025-12-28 at 10.17.46 (1).jpeg', 'uploads/dokumen/1774444540_WhatsApp_Image_2025-12-28_at_10.17.46_(1).jpeg', 83277, 'image/jpeg', '2026-03-25 06:15:40'),
(248, 36, 'KTP Ibu', 'ABSEN 8 AGS.jpg', 'uploads/dokumen/1774444540_ABSEN_8_AGS.jpg', 134726, 'image/jpeg', '2026-03-25 06:15:40'),
(249, 36, 'KTP Saksi 1', 'Gambar1.jpg', 'uploads/dokumen/1774444540_Gambar1.jpg', 4265, 'image/jpeg', '2026-03-25 06:15:40'),
(250, 36, 'KTP Saksi 2', '7 kebiasaan.jpg', 'uploads/dokumen/1774444540_7_kebiasaan.jpg', 134576, 'image/jpeg', '2026-03-25 06:15:40'),
(251, 36, 'Surat Lahir Desa', '15 senam.jpg', 'uploads/dokumen/1774444540_15_senam.jpg', 136770, 'image/jpeg', '2026-03-25 06:15:40'),
(252, 36, 'Surat Lahir RS', 'images.jpg', 'uploads/dokumen/1774444540_images.jpg', 4187, 'image/jpeg', '2026-03-25 06:15:40'),
(253, 36, 'Formulir F1.02', 'CP.jpg', 'uploads/dokumen/1774444540_CP.jpg', 186984, 'image/jpeg', '2026-03-25 06:15:40'),
(254, 37, 'KTP Ayah', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1774530132_Diagram_Tanpa_Judul_(1).jpg', 176696, 'image/jpeg', '2026-03-26 06:02:12'),
(255, 37, 'KTP Ibu', 'WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 'uploads/dokumen/1774530132_WhatsApp_Image_2026-02-01_at_14.36.34.jpeg', 179131, 'image/jpeg', '2026-03-26 06:02:12'),
(256, 37, 'KTP Saksi 1', 'WhatsApp Image 2025-12-28 at 10.17.47.jpeg', 'uploads/dokumen/1774530132_WhatsApp_Image_2025-12-28_at_10.17.47.jpeg', 70278, 'image/jpeg', '2026-03-26 06:02:12'),
(257, 37, 'KTP Saksi 2', 'TTD PKK.jpg', 'uploads/dokumen/1774530132_TTD_PKK.jpg', 112567, 'image/jpeg', '2026-03-26 06:02:12'),
(258, 37, 'Surat Lahir Desa', 'KERJA BAKTI 8 AGS.jpg', 'uploads/dokumen/1774530132_KERJA_BAKTI_8_AGS.jpg', 132185, 'image/jpeg', '2026-03-26 06:02:12'),
(259, 37, 'Surat Lahir RS', 'Waktu.jpg', 'uploads/dokumen/1774530132_Waktu.jpg', 300075, 'image/jpeg', '2026-03-26 06:02:12'),
(260, 37, 'Formulir F1.02', '15 senam.jpg', 'uploads/dokumen/1774530132_15_senam.jpg', 136770, 'image/jpeg', '2026-03-26 06:02:12'),
(272, 39, 'KTP Ayah', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1776606351_Diagram Tanpa Judul (1).jpg', 176696, 'image/jpeg', '2026-04-19 13:45:51'),
(273, 39, 'KTP Ibu', 'WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 'uploads/dokumen/1776606351_WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 179131, 'image/jpeg', '2026-04-19 13:45:51'),
(274, 39, 'KTP Saksi 1', 'WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 'uploads/dokumen/1776606351_WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 103055, 'image/jpeg', '2026-04-19 13:45:51'),
(275, 39, 'KTP Saksi 2', 'pengesahan kampus.jpeg', 'uploads/dokumen/1776606351_pengesahan kampus.jpeg', 85062, 'image/jpeg', '2026-04-19 13:45:51'),
(276, 39, 'Surat Lahir Desa', 'WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 'uploads/dokumen/1776606351_WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 75655, 'image/jpeg', '2026-04-19 13:45:51'),
(277, 39, 'Surat Lahir RS', 'WhatsApp Image 2026-01-16 at 17.37.15.jpeg', 'uploads/dokumen/1776606351_WhatsApp Image 2026-01-16 at 17.37.15.jpeg', 90098, 'image/jpeg', '2026-04-19 13:45:51'),
(278, 39, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 'uploads/dokumen/1776606351_69e4dc8f7ae97_WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 106090, 'image/jpeg', '2026-04-19 13:45:51'),
(279, 39, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 'uploads/dokumen/1776606351_69e4dc8f7c22b_WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 103055, 'image/jpeg', '2026-04-19 13:45:51'),
(280, 40, 'KTP Ayah', 'WhatsApp Image 2026-01-16 at 17.36.34.jpeg', 'uploads/dokumen/1776607739_WhatsApp Image 2026-01-16 at 17.36.34.jpeg', 87914, 'image/jpeg', '2026-04-19 14:08:59'),
(281, 40, 'KTP Ibu', 'WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 'uploads/dokumen/1776607739_WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 179131, 'image/jpeg', '2026-04-19 14:08:59'),
(282, 40, 'KTP Saksi 1', 'Pengesahan dinas.jpeg', 'uploads/dokumen/1776607739_Pengesahan dinas.jpeg', 76222, 'image/jpeg', '2026-04-19 14:08:59'),
(283, 40, 'KTP Saksi 2', 'WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 'uploads/dokumen/1776607739_WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 179131, 'image/jpeg', '2026-04-19 14:08:59'),
(284, 40, 'Surat Lahir Desa', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1776607739_Diagram Tanpa Judul (1).jpg', 176696, 'image/jpeg', '2026-04-19 14:08:59'),
(285, 40, 'Surat Lahir RS', 'WhatsApp Image 2026-01-16 at 17.37.20.jpeg', 'uploads/dokumen/1776607739_WhatsApp Image 2026-01-16 at 17.37.20.jpeg', 50800, 'image/jpeg', '2026-04-19 14:08:59'),
(286, 40, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 'uploads/dokumen/1776607739_69e4e1fb93dd4_WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 106090, 'image/jpeg', '2026-04-19 14:08:59'),
(287, 40, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 'uploads/dokumen/1776607739_69e4e1fb94fa9_WhatsApp Image 2026-01-16 at 17.37.10.jpeg', 103055, 'image/jpeg', '2026-04-19 14:08:59'),
(289, 41, 'KTP Ayah', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1777980857_Diagram Tanpa Judul (1).jpg', 176696, 'image/jpeg', '2026-05-05 11:34:17'),
(290, 41, 'KTP Ibu', 'Unknown pfp.jpg', 'uploads/dokumen/1777980857_Unknown pfp.jpg', 11567, 'image/jpeg', '2026-05-05 11:34:17'),
(291, 41, 'KTP Saksi 1', 'WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 'uploads/dokumen/1777980857_WhatsApp Image 2026-02-01 at 14.36.34.jpeg', 179131, 'image/jpeg', '2026-05-05 11:34:17'),
(292, 41, 'KTP Saksi 2', 'WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 'uploads/dokumen/1777980857_WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 75655, 'image/jpeg', '2026-05-05 11:34:17'),
(293, 41, 'Surat Lahir Desa', 'WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 'uploads/dokumen/1777980857_WhatsApp Image 2026-01-16 at 17.37.11.jpeg', 106090, 'image/jpeg', '2026-05-05 11:34:17'),
(294, 41, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.15.jpeg', 'uploads/dokumen/1777980857_69f9d5b9c3c51_WhatsApp Image 2026-01-16 at 17.37.15.jpeg', 90098, 'image/jpeg', '2026-05-05 11:34:17'),
(295, 41, 'Formulir F1.02', 'WhatsApp Image 2026-01-16 at 17.37.14.jpeg', 'uploads/dokumen/1777980857_69f9d5b9c4d23_WhatsApp Image 2026-01-16 at 17.37.14.jpeg', 94246, 'image/jpeg', '2026-05-05 11:34:17'),
(296, 43, 'KTP Ayah', 'SUWANTO.jpeg', 'uploads/dokumen/1779111895_SUWANTO.jpeg', 83193, 'image/jpeg', '2026-05-18 13:44:55'),
(297, 43, 'KTP Ibu', 'TITIN.jpeg', 'uploads/dokumen/1779111895_TITIN.jpeg', 152015, 'image/jpeg', '2026-05-18 13:44:55'),
(298, 43, 'KTP Saksi 1', 'RIZA.jpeg', 'uploads/dokumen/1779111895_RIZA.jpeg', 115658, 'image/jpeg', '2026-05-18 13:44:55'),
(299, 43, 'KTP Saksi 2', 'Unknown pfp.jpg', 'uploads/dokumen/1779111895_Unknown pfp.jpg', 11567, 'image/jpeg', '2026-05-18 13:44:55'),
(300, 43, 'Surat Lahir Desa', 'download.jpg', 'uploads/dokumen/1779111895_download.jpg', 153641, 'image/jpeg', '2026-05-18 13:44:55'),
(313, 48, 'KTP Ayah', 'SUWANTO.jpeg', 'uploads/dokumen/1779115071_SUWANTO.jpeg', 83193, 'image/jpeg', '2026-05-18 14:37:51'),
(314, 48, 'KTP Ibu', 'TITIN.jpeg', 'uploads/dokumen/1779115071_TITIN.jpeg', 152015, 'image/jpeg', '2026-05-18 14:37:51'),
(315, 48, 'KTP Saksi 2', 'ANTON.jpeg', 'uploads/dokumen/1779115071_ANTON.jpeg', 110087, 'image/jpeg', '2026-05-18 14:37:51'),
(316, 48, 'Surat Lahir Desa', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1779115071_Diagram Tanpa Judul (1).jpg', 176696, 'image/jpeg', '2026-05-18 14:37:51'),
(317, 48, 'Surat Lahir RS', 'WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 'uploads/dokumen/1779115071_WhatsApp Image 2026-01-16 at 17.37.21.jpeg', 75655, 'image/jpeg', '2026-05-18 14:37:51'),
(318, 49, 'KTP Ayah', 'ANTON.jpeg', 'uploads/dokumen/1779193532_ANTON.jpeg', 110087, 'image/jpeg', '2026-05-19 12:25:32'),
(319, 49, 'KTP Ibu', 'TITIN.jpeg', 'uploads/dokumen/1779193532_TITIN.jpeg', 152015, 'image/jpeg', '2026-05-19 12:25:32'),
(320, 49, 'KTP Saksi 1', 'download.jpg', 'uploads/dokumen/1779193532_download.jpg', 153641, 'image/jpeg', '2026-05-19 12:25:32'),
(321, 49, 'KTP Saksi 2', 'Diagram Tanpa Judul (1).jpg', 'uploads/dokumen/1779193532_Diagram Tanpa Judul (1).jpg', 176696, 'image/jpeg', '2026-05-19 12:25:32'),
(322, 49, 'Surat Lahir Desa', 'WhatsApp Image 2026-01-16 at 17.37.14.jpeg', 'uploads/dokumen/1779193532_WhatsApp Image 2026-01-16 at 17.37.14.jpeg', 94246, 'image/jpeg', '2026-05-19 12:25:32'),
(323, 49, 'Surat Lahir RS', 'sihana new.jpg', 'uploads/dokumen/1779193532_sihana new.jpg', 75799, 'image/jpeg', '2026-05-19 12:25:32'),
(324, 49, 'Formulir F1.02', 'SUWANTO.jpeg', 'uploads/dokumen/1779193532_6a0c56bced21d_SUWANTO.jpeg', 83193, 'image/jpeg', '2026-05-19 12:25:32'),
(326, 43, 'Surat Lahir RS', 'ANTON.jpeg', 'uploads/dokumen/1779197729_ANTON.jpeg', 110087, 'image/jpeg', '2026-05-19 13:35:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_akta_cerai`
--

CREATE TABLE `dokumen_akta_cerai` (
  `id_dokumen` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `jenis_dokumen` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_akta_cerai`
--

INSERT INTO `dokumen_akta_cerai` (`id_dokumen`, `id_permohonan`, `jenis_dokumen`, `file_path`, `created_at`) VALUES
(2, 1, 'pn', '1775566917_efc8691da4e885864713.jpeg', '2026-04-07 20:01:57'),
(3, 1, 'ktp_perempuan', '1775566917_08250d8dc56a02ec5b49.jpeg', '2026-04-07 20:01:57'),
(4, 1, 'ktp_laki', '1775566917_a61f847c790591bf2b19.jpeg', '2026-04-07 20:01:57'),
(5, 1, 'kk', '1775566917_558cb96424b78b321598.jpeg', '2026-04-07 20:01:57'),
(6, 1, 'akta_perkawinan', '1775566917_7d87152e78e52c0fdc4c.jpg', '2026-04-07 20:01:57'),
(7, 1, 'f201', '1775572458_768d35fc67f6d19a39c1.jpg', '2026-04-07 21:34:18'),
(8, 1, 'f201', '1775573686_39558afcdd8c6f239746.jpg', '2026-04-07 21:54:46'),
(22, 5, 'pn', '1775657071_84a3ae7882a349293f68.jpeg', '2026-04-08 21:04:31'),
(23, 5, 'ktp_perempuan', '1775657071_5e4697874b93a16044d9.jpeg', '2026-04-08 21:04:31'),
(24, 5, 'ktp_laki', '1775657071_db63ae1d6b52c364aa2f.jpg', '2026-04-08 21:04:31'),
(26, 5, 'akta_perkawinan', '1775657071_a2586f90d5ab0f3a64fd.jpg', '2026-04-08 21:04:31'),
(27, 5, 'f201', '1775657071_248dc7d9bae64c530cad.jpeg', '2026-04-08 21:04:31'),
(28, 5, 'kk', '1775657093_1d9cd90ab9333e7bf089.jpeg', '2026-04-08 21:04:53'),
(29, 6, 'pn', '1775657501_7911419cbaf59da843f9.jpg', '2026-04-08 21:11:41'),
(30, 6, 'ktp_perempuan', '1775657501_e852a1f4ca0420c18132.jpg', '2026-04-08 21:11:41'),
(31, 6, 'ktp_laki', '1775657501_441663b16191b20a7fb6.jpeg', '2026-04-08 21:11:41'),
(32, 6, 'kk', '1775657501_7741638fe8a336e3addf.jpg', '2026-04-08 21:11:41'),
(33, 6, 'akta_perkawinan', '1775657501_e9a38d604e6d1149fc31.jpg', '2026-04-08 21:11:41'),
(34, 6, 'f201', '1775657501_58345cc1e10a48bf0766.jpeg', '2026-04-08 21:11:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_akta_kematian`
--

CREATE TABLE `dokumen_akta_kematian` (
  `id_dokumen` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `jenis_dokumen` varchar(100) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_akta_kematian`
--

INSERT INTO `dokumen_akta_kematian` (`id_dokumen`, `id_permohonan`, `jenis_dokumen`, `file_dokumen`, `created_at`) VALUES
(10, 2, 'Surat Kematian Desa', '1776002872_d8b001a8307649aec0ce.jpg', '2026-04-12 21:07:52'),
(11, 2, 'Surat Kematian Instansi', '1776002872_3b6a30b77ec557b24a3b.jpeg', '2026-04-12 21:07:52'),
(12, 2, 'KTP Pelapor', '1776002872_d3f3787aa5cf78ac8b6e.jpeg', '2026-04-12 21:07:52'),
(13, 2, 'KTP Saksi 1', '1776002872_b26d38bce8ccc498163f.jpeg', '2026-04-12 21:07:52'),
(14, 2, 'KTP Saksi 2', '1776002872_508540b4a00f15952d91.jpeg', '2026-04-12 21:07:52'),
(15, 2, 'KK Jenazah', '1776002872_69ac734491a4352fbc45.jpeg', '2026-04-12 21:07:53'),
(16, 2, 'KTP Jenazah', '1776002873_760a4db87590ecb15343.jpg', '2026-04-12 21:07:53'),
(17, 2, 'F2.01', '1776002873_0177d477d411e689e05f.jpeg', '2026-04-12 21:07:53'),
(18, 2, 'F2.01', '1776002873_5ad874e6ae5cc18b9399.jpeg', '2026-04-12 21:07:53'),
(30, 4, 'Surat Kematian Desa', '1776006774_4adffa5d722d6f855806.jpeg', '2026-04-12 22:12:54'),
(31, 4, 'Surat Kematian Instansi', '1776006774_1e7d0770de6388950bd3.jpg', '2026-04-12 22:12:54'),
(32, 4, 'KTP Pelapor', '1776006774_292d0fac4ef14b8e5a81.jpeg', '2026-04-12 22:12:54'),
(33, 4, 'KTP Saksi 1', '1776006774_c04a0c93f8ec563c436e.jpeg', '2026-04-12 22:12:54'),
(34, 4, 'KTP Saksi 2', '1776006774_fd71001234cbc301590f.jpeg', '2026-04-12 22:12:54'),
(35, 4, 'KK Jenazah', '1776006774_9add747fec286a6c4d26.jpg', '2026-04-12 22:12:54'),
(36, 4, 'KTP Jenazah', '1776006774_ce12fbdaddd6fa1cfd43.jpg', '2026-04-12 22:12:54'),
(37, 4, 'F2.01', '1776006774_34431736658a4d429acb.jpg', '2026-04-12 22:12:54'),
(48, 6, 'Surat Kematian Desa', '1779199378_2d2ce0cb68bd6cd113ed.jpeg', '2026-05-19 21:02:58'),
(49, 6, 'Surat Kematian Instansi', '1779199378_7b7e3a6b5b1e80488304.jpeg', '2026-05-19 21:02:58'),
(50, 6, 'KTP Pelapor', '1779199378_38ec592ad8e57615d344.jpeg', '2026-05-19 21:02:58'),
(51, 6, 'KTP Saksi 1', '1779199378_9190b56dde44d1d742cf.jpeg', '2026-05-19 21:02:58'),
(52, 6, 'KTP Saksi 2', '1779199378_2fd7b6db04b2850db8d9.jpeg', '2026-05-19 21:02:58'),
(53, 6, 'KK Jenazah', '1779199378_d78c64f5de78b024d1d7.jpeg', '2026-05-19 21:02:58'),
(54, 6, 'KTP Jenazah', '1779199378_d1ef3cc981e0d212c51d.jpg', '2026-05-19 21:02:58'),
(55, 6, 'F2.01', '1779199378_e206228c04535a3c5366.jpeg', '2026-05-19 21:02:58'),
(56, 6, 'F2.01', '1779199378_d6ea0e7958d783a745c0.jpeg', '2026-05-19 21:02:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_akta_nikah`
--

CREATE TABLE `dokumen_akta_nikah` (
  `id_dokumen` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `jenis_dokumen` varchar(100) NOT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_akta_nikah`
--

INSERT INTO `dokumen_akta_nikah` (`id_dokumen`, `id_permohonan`, `jenis_dokumen`, `file`, `created_at`) VALUES
(1, 1, 'KK', '1775830555_56c29c4f111f959034ba.jpg', '2026-04-10 21:15:55'),
(2, 1, 'KTP Laki-laki', '1775830555_c42fa6bdc0034dce8b9f.jpg', '2026-04-10 21:15:55'),
(4, 1, 'Akta Lahir Laki', '1775830555_3862f6ef4935775ab71d.jpeg', '2026-04-10 21:15:55'),
(5, 1, 'Akta Lahir Perempuan', '1775830555_a954bdc4784a49baa6e7.jpeg', '2026-04-10 21:15:55'),
(6, 1, 'Surat Desa', '1775830555_3fa75a85c2a04a36df91.jpg', '2026-04-10 21:15:55'),
(7, 1, 'KTP Saksi', '1775830555_1f1abe8d204e865944a6.jpg', '2026-04-10 21:15:55'),
(8, 1, 'Pas Foto', '1775830555_75ea8f01df5f42814458.jpg', '2026-04-10 21:15:55'),
(9, 1, 'F1.02', '1775830555_482abe6fdec12f71ce12.jpg', '2026-04-10 21:15:55'),
(10, 1, 'KTP Perempuan', '1775835105_1a8b0f62af775d5bd68e.jpeg', '2026-04-10 22:31:45'),
(12, 3, 'KK', '1775838123_edab0d6d5390ca18020b.jpg', '2026-04-10 23:22:03'),
(13, 3, 'KTP Laki-laki', '1775838123_bce917d948e3c140a2e5.jpeg', '2026-04-10 23:22:03'),
(14, 3, 'KTP Perempuan', '1775838123_f75b21e38d9a2bc40036.jpeg', '2026-04-10 23:22:03'),
(15, 3, 'Akta Lahir Laki', '1775838123_92328339f17512cec484.jpg', '2026-04-10 23:22:03'),
(16, 3, 'Akta Lahir Perempuan', '1775838123_03bd917fab97beaf2fb3.jpg', '2026-04-10 23:22:03'),
(17, 3, 'Surat Desa', '1775838123_2ae5263e343050f95f50.jpeg', '2026-04-10 23:22:03'),
(18, 3, 'KTP Saksi', '1775838123_92c5fcb6d5806a231919.jpg', '2026-04-10 23:22:03'),
(19, 3, 'Pas Foto', '1775838123_3ea1efa118d7b305c31e.jpg', '2026-04-10 23:22:03'),
(20, 3, 'F1.02', '1775838123_71f1ef7202e007ca497a.jpeg', '2026-04-10 23:22:03'),
(21, 3, 'F1.02', '1775838123_5de5e7f0d49b4f47695d.jpeg', '2026-04-10 23:22:03'),
(22, 4, 'KK', '1775838816_deb3e73c76e55e46b610.jpeg', '2026-04-10 23:33:36'),
(23, 4, 'KTP Laki-laki', '1775838816_22e9fa36be823980643f.jpg', '2026-04-10 23:33:36'),
(24, 4, 'KTP Perempuan', '1775838816_65c8fbe98563d84ed6b7.jpeg', '2026-04-10 23:33:36'),
(25, 4, 'Akta Lahir Laki', '1775838816_bed9336bd13be71b0304.jpeg', '2026-04-10 23:33:36'),
(26, 4, 'Akta Lahir Perempuan', '1775838816_db9eb64f897ac0e08750.jpeg', '2026-04-10 23:33:36'),
(27, 4, 'Surat Desa', '1775838816_c208bace45bdc2b7e2c4.jpg', '2026-04-10 23:33:36'),
(28, 4, 'KTP Saksi', '1775838816_d9d6d8af1cd6b6580e97.jpeg', '2026-04-10 23:33:36'),
(29, 4, 'Pas Foto', '1775838816_ce7bb05674cd599d8526.jpeg', '2026-04-10 23:33:36'),
(30, 4, 'F1.02', '1775838816_28115d4e8502c7cc863b.jpeg', '2026-04-10 23:33:36'),
(31, 5, 'KK', '1775839173_7fee9c33ff6c41d4bc64.jpeg', '2026-04-10 23:39:33'),
(32, 5, 'KTP Laki-laki', '1775839173_a991eaa97f185ca3c1ba.jpg', '2026-04-10 23:39:33'),
(33, 5, 'KTP Perempuan', '1775839173_a0d0edd76d55de037ad2.jpeg', '2026-04-10 23:39:33'),
(34, 5, 'Akta Lahir Laki', '1775839173_0b54197afa8774192cbb.jpeg', '2026-04-10 23:39:33'),
(35, 5, 'Akta Lahir Perempuan', '1775839173_ed5c77b8a1e558758a40.jpeg', '2026-04-10 23:39:33'),
(36, 5, 'Surat Desa', '1775839173_bb1f3d3083eaabc631c9.jpeg', '2026-04-10 23:39:33'),
(37, 5, 'KTP Saksi', '1775839173_250570279b1eb87fc5d3.jpeg', '2026-04-10 23:39:33'),
(38, 5, 'Pas Foto', '1775839173_9b51cff27022d53368a9.jpeg', '2026-04-10 23:39:33'),
(39, 5, 'F1.02', '1775839173_8beade8b6e971e1415c2.jpeg', '2026-04-10 23:39:33'),
(40, 6, 'KK', '1775839314_98dd9c974bab1a03eb47.jpeg', '2026-04-10 23:41:54'),
(41, 6, 'KTP Laki-laki', '1775839314_9b117b470d6ab53591ba.jpg', '2026-04-10 23:41:54'),
(42, 6, 'KTP Perempuan', '1775839314_6733f02e7faee2cda4a3.jpeg', '2026-04-10 23:41:54'),
(43, 6, 'Akta Lahir Laki', '1775839314_1882b2399397f1fd4def.jpeg', '2026-04-10 23:41:54'),
(44, 6, 'Akta Lahir Perempuan', '1775839314_15d0c3dee3753c7f0fca.jpeg', '2026-04-10 23:41:54'),
(45, 6, 'Surat Desa', '1775839314_3baf3c4bf6733aec3a1d.jpeg', '2026-04-10 23:41:54'),
(46, 6, 'KTP Saksi', '1775839314_bf2eae0532ad45505f46.jpeg', '2026-04-10 23:41:54'),
(47, 6, 'Pas Foto', '1775839314_7bdb7ed6f8e951c6b1a6.jpg', '2026-04-10 23:41:54'),
(48, 6, 'F1.02', '1775839314_8248c135d6df950ab08d.jpeg', '2026-04-10 23:41:54'),
(49, 6, 'F1.02', '1775839346_6550eb45ffe76cc671aa.jpg', '2026-04-10 23:42:26'),
(50, 7, 'KK', '1775915218_f69c487b058ed434a7f6.jpeg', '2026-04-11 20:46:58'),
(51, 7, 'KTP Laki-laki', '1775915218_9d79f6891165cfa565da.jpeg', '2026-04-11 20:46:58'),
(52, 7, 'KTP Perempuan', '1775915218_f041e3d0114854afa75c.jpeg', '2026-04-11 20:46:58'),
(53, 7, 'Akta Lahir Laki', '1775915218_2438ebb719f5e8e4ac2c.jpeg', '2026-04-11 20:46:58'),
(54, 7, 'Akta Lahir Perempuan', '1775915218_4a045fd27facaed28621.jpeg', '2026-04-11 20:46:58'),
(55, 7, 'Surat Desa', '1775915218_84e09f49f43b12407cf8.jpeg', '2026-04-11 20:46:58'),
(56, 7, 'KTP Saksi', '1775915218_7fb004fbd5f3b7635fd3.jpeg', '2026-04-11 20:46:58'),
(57, 7, 'Pas Foto', '1775915218_0c006b1b45f19d46f6a4.jpeg', '2026-04-11 20:46:58'),
(59, 7, 'F1.02', '1775915387_f48ab809e8933f7f98c8.jpeg', '2026-04-11 20:49:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_kk`
--

CREATE TABLE `dokumen_kk` (
  `id_dokumen` int(11) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `jenis_dokumen` varchar(50) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_kk`
--

INSERT INTO `dokumen_kk` (`id_dokumen`, `id_pengajuan`, `jenis_dokumen`, `nama_file`) VALUES
(2, 2, 'F1.02', '1776178019_9a5d1cdb1727a0e8aefb.jpeg'),
(3, 2, 'F1.06', '1776178019_b56f4c151b8ebb4780a9.jpg'),
(5, 2, 'KK', '1776179535_860c38573fab70694f79.jpeg'),
(6, 3, 'F1.02', '1776179824_533144ad849e15e424c3.jpeg'),
(7, 3, 'F1.06', '1776179824_461295aa4d680bf5293b.jpg'),
(8, 4, 'KK', '1776268435_ef64abe7bfff23a12fa5.jpeg'),
(9, 4, 'F1.02', '1776268435_fbc068789944eac155a5.jpeg'),
(10, 4, 'F1.06', '1776268435_62853da2bda313e5b08b.jpeg'),
(11, 5, 'F1.02', '1776268509_0060341c596c3489b91e.jpeg'),
(12, 5, 'F1.02', '1776268509_91a4fd9b02ace71b7618.jpeg'),
(13, 5, 'F1.06', '1776268509_a41c77c624e0f879d810.jpeg'),
(14, 5, 'F1.06', '1776268509_02863ae38085f243bbbb.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_pindah`
--

CREATE TABLE `dokumen_pindah` (
  `id_dokumen` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `jenis_dokumen` varchar(50) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `tanggal_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen_pindah`
--

INSERT INTO `dokumen_pindah` (`id_dokumen`, `id_pengajuan`, `jenis_dokumen`, `nama_file`, `tanggal_upload`) VALUES
(50, 8, 'surat_asal', 'uploads/1764211525_New_flow.jpg', '2025-11-27 09:45:25'),
(51, 8, 'surat_tujuan', 'uploads/1764211525_Diagram_Tanpa_Judul.jpg', '2025-11-27 09:45:25'),
(52, 8, 'kk_asli', 'uploads/1764211525_9_Ags.jpg', '2025-11-27 09:45:25'),
(53, 8, 'ktp_pindah', 'uploads/1764211525_Gambar1.jpg', '2025-11-27 09:45:25'),
(54, 8, 'form_f102', 'uploads/1764211525_Diagram_Tanpa_Judul.jpg', '2025-11-27 09:45:25'),
(55, 8, 'form_f102', 'uploads/1764211525_alur_penelitian.jpg', '2025-11-27 09:45:25'),
(56, 8, 'form_f106', 'uploads/1764211525_Pas_Foto_Berwarna__1_.jpg', '2025-11-27 09:45:25'),
(67, 10, 'surat_asal', 'uploads/1768401592_sihana_new.jpg', '2026-01-14 21:39:52'),
(68, 10, 'ktp_pindah', 'uploads/1768401592_flowchart.jpg', '2026-01-14 21:39:52'),
(69, 10, 'form_f106', 'uploads/1768401592_New_flow.jpg', '2026-01-14 21:39:52'),
(70, 10, 'form_f106', 'uploads/1768401592_flowchart.jpg', '2026-01-14 21:39:52'),
(71, 11, 'KTP', '1774708771_52b578d4fa7d6ff0f56e.jpeg', '2026-03-28 21:39:31'),
(73, 11, 'Surat Desa', '1774708771_4ff9807cfe1909ad5657.jpeg', '2026-03-28 21:39:32'),
(74, 11, 'F1.02', '1774708772_20f1a1c46af9d59ee5bf.jpeg', '2026-03-28 21:39:32'),
(75, 11, 'F1.02', '1774708772_9783b1159c253989431f.jpeg', '2026-03-28 21:39:32'),
(76, 11, 'F1.06', '1774708772_42cc83f617096cffc298.jpeg', '2026-03-28 21:39:32'),
(77, 12, 'KK', '1774790590_b1a13b31e5a676e69600.jpg', '2026-03-29 20:23:10'),
(78, 12, 'Surat Desa', '1774790590_b10ec755806b31f2e962.jpeg', '2026-03-29 20:23:10'),
(79, 12, 'F1.02', '1774790590_2e4e537bbb3332c0e0e9.jpg', '2026-03-29 20:23:10'),
(80, 12, 'F1.02', '1774790590_9135b0c18fe783a6a68e.jpg', '2026-03-29 20:23:10'),
(81, 12, 'F1.06', '1774790590_7e9e37b13fb4e1f2f115.jpg', '2026-03-29 20:23:10'),
(82, 12, 'F1.06', '1774790590_8d4c13327f52fcdf54fd.jpg', '2026-03-29 20:23:10'),
(102, 18, 'KTP', '1774962919_9518b2be72e4c8886bd7.jpeg', '2026-03-31 20:15:19'),
(104, 18, 'Surat Desa', '1774962919_12c510022cbd613d0036.jpeg', '2026-03-31 20:15:19'),
(105, 18, 'F1.02', '1774962919_eca490c1c24266453874.jpeg', '2026-03-31 20:15:19'),
(107, 18, 'F1.06', '1774968227_7a45513b7977491e500d.jpeg', '2026-03-31 21:43:47'),
(108, 11, 'KK', '1775224432_e1242892544763088edb.jpg', '2026-04-03 20:53:52'),
(109, 19, 'KTP', '1775225789_df1450c857cbc8d0f288.jpeg', '2026-04-03 21:16:29'),
(110, 19, 'KK', '1775225789_1e7083d3ad8b86bde3cb.jpeg', '2026-04-03 21:16:29'),
(111, 19, 'Surat Desa', '1775225789_cbe3a94f6c1b1eed4287.jpeg', '2026-04-03 21:16:29'),
(112, 19, 'F1.02', '1775225789_0b2f79a8d3d0392a7e0a.jpeg', '2026-04-03 21:16:29'),
(114, 19, 'F1.06', '1775225789_c8e521f78ac29f9ec8b9.jpg', '2026-04-03 21:16:29'),
(121, 21, 'KK', '1775308528_4d22fd33839267f5be71.jpg', '2026-04-04 20:15:28'),
(122, 21, 'Surat Desa', '1775308528_8c0a13bf474b537c1c4f.jpeg', '2026-04-04 20:15:28'),
(123, 21, 'F1.02', '1775308528_b8c15e740b2992b077ab.jpeg', '2026-04-04 20:15:28'),
(124, 21, 'F1.06', '1775308528_068a1babdf143b78b767.jpeg', '2026-04-04 20:15:28'),
(135, 24, 'Surat Desa', '1775309367_334841668c589b31d2d6.jpeg', '2026-04-04 20:29:27'),
(136, 24, 'F1.02', '1775309367_d114b0eed609190edf9e.jpeg', '2026-04-04 20:29:27'),
(137, 24, 'F1.02', '1775309367_d6261250fba3b6d27e6e.jpeg', '2026-04-04 20:29:27'),
(138, 24, 'F1.06', '1775309367_ea491e8ff3b002737927.jpg', '2026-04-04 20:29:27'),
(139, 24, 'KK', '1775309388_fa3de5fc204a4fd3823d.jpg', '2026-04-04 20:29:48'),
(140, 18, 'KK', '1778386827_4d38c6493483ab8a4aa6.jpeg', '2026-05-10 11:20:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `download_files`
--

CREATE TABLE `download_files` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `path_file` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `download_files`
--

INSERT INTO `download_files` (`id`, `nama_file`, `judul`, `deskripsi`, `path_file`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'F1.06.pdf', 'Formulir Perubahan Data Pindah Datang dan Keluar', 'Digunakan untuk membuat SKPWNI', 'uploads/downloads/1776576133_156e3ed3a7f1e15b0fa7.pdf', 12, '2026-02-28 12:56:40', '2026-04-19 12:22:13'),
(3, 'Form Perkawinan.pdf', 'Formulir Akta Perkawinan', 'Formulir Pembuatan AKta Perkawinan', 'uploads/downloads/1776576215_7c484510d366561f868c.pdf', 12, '2026-02-28 14:01:16', '2026-04-19 12:23:35'),
(4, 'F1.02.pdf', 'Formulir Pembuatan KK', 'Formulir untuk Pembuatan KK dan Perubahan Lemen Data', 'uploads/downloads/1776576236_a0210d542a7b3342faca.pdf', 12, '2026-02-28 14:03:09', '2026-04-19 12:23:56'),
(5, 'F2.01.pdf', 'Formulir Pembuatan Akta Kelahiran dan Kematian', 'Formulir Pengajuan Akta kelahiran dan Akta Kematian ', 'uploads/downloads/1776576266_49729783746179b6dc91.pdf', 12, '2026-02-28 14:09:42', '2026-04-19 12:24:26'),
(6, 'Akta Perceraian.pdf', 'Formulir Akta Perceraian', 'Formulir Pengajuan Akta Perceraian untuk Non - Muslim', 'uploads/downloads/1776576293_e1a34b04d6eb7f2cb36a.pdf', 12, '2026-02-28 14:10:59', '2026-04-19 12:24:53'),
(7, 'Formulir KIA.pdf', 'Formulir KIA', 'Formulir Pembuatan KIA', 'uploads/downloads/1776576316_04a406be519242ac4cf5.pdf', 12, '2026-02-28 14:18:08', '2026-04-19 12:25:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_permohonan` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `uploaded_by` varchar(50) DEFAULT 'admin',
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_permohonan`, `nama_file`, `path_file`, `uploaded_by`, `uploaded_at`) VALUES
(1, 27, '2057-Article Text-5321-1-10-20220928.pdf', 'uploads/hasil_1761026975_2057-Article Text-5321-1-10-20220928.pdf', 'admin', '2025-10-21 13:09:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_layanan`
--

CREATE TABLE `hasil_layanan` (
  `id_hasil` int(11) NOT NULL,
  `jenis_layanan` varchar(50) NOT NULL,
  `id_ref` int(11) NOT NULL,
  `file_hasil` varchar(255) NOT NULL,
  `nama_file_asli` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `jenis_file` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_layanan`
--

INSERT INTO `hasil_layanan` (`id_hasil`, `jenis_layanan`, `id_ref`, `file_hasil`, `nama_file_asli`, `uploaded_by`, `created_at`, `jenis_file`) VALUES
(4, 'akta_kelahiran', 36, '1774450030_Bukti_Persetujuan_Pembimbing.pdf', 'Bukti Persetujuan Pembimbing.pdf', 1, '2026-03-25 14:47:10', NULL),
(7, 'pindah', 18, 'uploads/hasil_pindah/1775217373_af85987b7d3577319016.pdf', 'acitivity diagram.pdf', 12, '2026-04-03 18:56:13', NULL),
(8, 'pindah', 19, 'uploads/hasil_pindah/1775226592_0ed2bc98a3c95388a412.pdf', 'TA SIB.pdf', 12, '2026-04-03 21:29:52', NULL),
(9, 'pindah', 19, 'uploads/hasil_pindah/1775306734_85d605093b2654d30b87.pdf', 'Pengesahan dinas.pdf', 12, '2026-04-04 19:14:11', 'SKPWNI'),
(10, 'pindah', 19, 'uploads/hasil_pindah/1775304851_9d3777083b0bc7a0de47.pdf', 'acitivity diagram.pdf', 12, '2026-04-04 19:14:11', 'KK_BARU'),
(11, 'pindah', 24, 'uploads/hasil_pindah/1775311704_cbe8f1a3023f704254f8.pdf', 'Form kawin dan cerai.pdf', 12, '2026-04-04 21:08:24', 'SKPWNI'),
(12, 'pindah', 24, 'uploads/hasil_pindah/1775311704_4813c83dfbc1f13a3284.pdf', '1774358786_4ebc24f78bafe8f8a7f4.pdf', 12, '2026-04-04 21:08:24', 'KK_BARU'),
(13, 'akta_cerai', 1, '1778241173_ec45300414bb7c163183.pdf', '1774531239_Laporan_Tugas_MPPL_FIX_DHEA,_CICI,_WILDA,_ROTIP,_HUSNI (1).pdf', NULL, '2026-04-08 20:02:45', NULL),
(14, 'akta_cerai', 5, '1775657208_7a67b4474742d05dc318.pdf', '314-323 (1).pdf', NULL, '2026-04-08 21:06:15', NULL),
(15, 'akta_nikah', 6, '1778153208_a2439f7f44f800c180a5.pdf', '1774443883_Big_Book_Kelompok_Kelas_2 (1).pdf', NULL, '2026-05-07 18:26:48', NULL),
(16, 'akta_nikah', 7, '1775915440_ad63688d000b8a6ce734.pdf', 'Bukti Persetujuan Pembimbing.pdf', NULL, '2026-04-11 20:50:17', NULL),
(18, 'akta_kematian', 2, '1776005943_ff8ab89fcc49fe6f2335.pdf', '2057-Article Text-5321-1-10-20220928.pdf', NULL, '2026-04-12 21:30:34', NULL),
(19, 'akta_cerai', 2, '1776005772_1a01d2a471b84cf99e68.pdf', 'Big Book Kelompok Kelas 2.pdf', NULL, '2026-04-12 21:56:12', NULL),
(20, 'kk', 3, '1776270083_01f2bc9c3773d5a3ac32.pdf', '4.+Artikel+Firman.pdf', NULL, '2026-04-15 23:21:23', 'pdf'),
(21, 'akta_nikah', 5, '1776265338_8bc3eb45ba3be43eebda.pdf', 'TA SIB_removed.pdf', NULL, '2026-04-15 22:02:18', NULL),
(22, 'kk', 2, '1776266371_a0a38db00b4028440458.pdf', 'TA SIB_removed.pdf', NULL, '2026-04-15 22:19:31', 'pdf'),
(23, 'kk', 4, '1776270107_5d877d24ba0e0d656fb6.pdf', 'KHS SEMESTER 6.pdf', NULL, '2026-04-15 23:21:47', 'pdf'),
(27, 'akta_kelahiran', 37, '1777979587_1069_Keluar.pdf', '1069_Keluar.pdf', 12, '2026-05-05 18:13:07', NULL),
(29, 'akta_kematian', 3, '1778062042_cb755d976281f2e9aad9.pdf', 'BERITA ACARA BIMBINGAN SKRIPSI.pdf', NULL, '2026-05-06 17:07:22', NULL),
(30, 'akta_kematian', 3, '1778068014_31a85832311e5dda8641.pdf', '1069_Keluar (2).pdf', NULL, '2026-05-06 18:46:54', NULL),
(31, 'akta_kematian', 5, '1778068287_b1c1511235a6bebd4e4f.pdf', '1069_Keluar.pdf', NULL, '2026-05-06 18:51:27', NULL),
(32, 'akta_kematian', 5, '1778068457_6f856f49f89991cfc453.pdf', '1774443883_Big_Book_Kelompok_Kelas_2 (1).pdf', NULL, '2026-05-06 18:54:17', NULL),
(33, 'pindah', 19, 'uploads/hasil_pindah/1778385840_27c77073b8bf04a67217.pdf', '1069_Keluar.pdf', 12, '2026-05-10 11:04:00', 'SKPWNI'),
(34, 'pindah', 19, 'uploads/hasil_pindah/1778385840_efa3578148b464e95c92.pdf', '2026.pdf', 12, '2026-05-10 11:04:00', 'KK_BARU'),
(35, 'akta_kelahiran', 25, '1778417707_1069_Keluar_(1).pdf', '1069_Keluar (1).pdf', 12, '2026-05-10 19:55:07', NULL),
(36, 'akta_kelahiran', 41, '1778419328_2026.pdf', '2026.pdf', 12, '2026-05-10 20:22:08', NULL),
(37, 'akta_kelahiran', 34, '1779197073_F1.06.pdf', 'F1.06.pdf', 1, '2026-05-19 20:24:33', NULL),
(38, 'akta_kelahiran', 43, '1779197776_1774443883_Big_Book_Kelompok_Kelas_2_(1).pdf', '1774443883_Big_Book_Kelompok_Kelas_2 (1).pdf', 1, '2026-05-19 20:36:16', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pindah`
--

CREATE TABLE `hasil_pindah` (
  `id_hasil` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `file_hasil` varchar(255) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id_kecamatan` int(11) NOT NULL,
  `kode_kecamatan` varchar(10) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kecamatan`
--

INSERT INTO `kecamatan` (`id_kecamatan`, `kode_kecamatan`, `nama_kecamatan`) VALUES
(1, '352304', 'Bancar'),
(2, '352303', 'Bangilan'),
(3, '352320', 'Grabagan'),
(4, '352302', 'Jatirogo'),
(5, '352312', 'Jenu'),
(6, '352301', 'Kenduruan'),
(7, '352308', 'Kerek'),
(8, '352313', 'Merakurak'),
(9, '352310', 'Montong'),
(10, '352318', 'Palang'),
(13, '352314', 'Rengel'),
(14, '352315', 'Semanding'),
(15, '352305', 'Senori'),
(16, '352307', 'Singgahan'),
(17, '352311', 'Soko'),
(18, '352306', 'Tambakboyo'),
(19, '352316', 'Tuban'),
(20, '352319', 'Widang'),
(25, '352309', 'Parengan'),
(26, '352317', 'Plumpang'),
(27, '123456', 'coba'),
(28, '345678', 'uji'),
(29, '237890', 'camat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orangtua`
--

CREATE TABLE `orangtua` (
  `id_orangtua` int(11) NOT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nik_ayah` char(16) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `nik_ibu` char(16) DEFAULT NULL,
  `id_permohonan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orangtua`
--

INSERT INTO `orangtua` (`id_orangtua`, `nama_ayah`, `nik_ayah`, `nama_ibu`, `nik_ibu`, `id_permohonan`) VALUES
(15, 'dedy', '4546567685542323', 'sob', '7653543547687899', NULL),
(25, 'ty', '4546567685542323', 'zoni', '2346678798078564', NULL),
(28, 'SUPRI', '6743436589754346', 'WIWIK', '1324789808097785', 25),
(30, 'dedy', '4546567685542323', 'lusi', '2346678798078564', 27),
(37, 'kajdksajkdsan', '6756756454343234', 'siti', '5436524134141431', 34),
(39, 'joko', '3253252353242486', 'setia', '7987555343557097', 36),
(40, 'Supri', '3253252353242243', 'Yani', '5436524134141546', 37),
(42, 'Nanang', '6563534246465698', 'Naza', '6763534246465698', 39),
(43, 'rama', '6765674647658758', 'siti', '6765674647658788', 40),
(44, 'gghfgb', '6765674647658758', 'Naza', '5436524134141431', 41),
(46, 'Dwika', '6567464646535989', 'Ramadhani', '6567464646535768', 43),
(50, 'rama', '6765674647658758', 'siti', '7654246798766788', 48),
(51, 'kajdksajkdsan', '6756756454343234', 'siti', '9976442335465767', 49);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelapor`
--

CREATE TABLE `pelapor` (
  `id_pelapor` int(11) NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `nik_pelapor` char(16) NOT NULL,
  `alamat_pelapor` text DEFAULT NULL,
  `id_permohonan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelapor`
--

INSERT INTO `pelapor` (`id_pelapor`, `nama_pelapor`, `nik_pelapor`, `alamat_pelapor`, `id_permohonan`) VALUES
(20, 'DHEA AMALIA DWIKA', '0897656445332455', 'gf', NULL),
(30, 'DHEA AMALIA', '1223456576843221', 'bbe', NULL),
(34, 'Dicky Diffie', '4654452447687953', 'jogja', 25),
(36, 'DHEA AMALIA DWIKA RAMADHAN', '2343454556678778', 'hg', 27),
(43, 'edi', '9864213456789009', 'hgh', 34),
(45, 'anammmmm', '9876544243565767', 'semanding', 36),
(46, 'dhea amalia', '9876544243565645', 'soko', 37),
(48, 'Nanang', '6563534246466565', 'Tuban', 39),
(49, 'rama', '6765674647658758', 'Tuban', 40),
(50, 'v', '9864213456789009', 'ngino', 41),
(52, 'Dhea', '6567464646535343', 'semanding', 43),
(55, 'rama', '6765674647658758', 'Tuban', NULL),
(56, 'rama', '6765674647658758', 'Tuban', 48),
(57, 'doddy', '9864213456789009', 'hgh', 49);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_akta_cerai`
--

CREATE TABLE `pengajuan_akta_cerai` (
  `id_permohonan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `kode_desa` varchar(20) DEFAULT NULL,
  `nama_perempuan` varchar(100) NOT NULL,
  `nik_perempuan` varchar(20) NOT NULL,
  `nama_laki` varchar(100) NOT NULL,
  `nik_laki` varchar(20) NOT NULL,
  `tanggal_cerai` date NOT NULL,
  `tempat_cerai` varchar(100) NOT NULL,
  `nomor_akta_perkawinan` varchar(100) NOT NULL,
  `tanggal_perkawinan` date NOT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') DEFAULT 'Pengajuan',
  `catatan_revisi` text DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL,
  `diproses_oleh` varchar(100) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `tanggal_pengajuan` datetime DEFAULT current_timestamp(),
  `tanggal_proses` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_akta_cerai`
--

INSERT INTO `pengajuan_akta_cerai` (`id_permohonan`, `id_user`, `kode_desa`, `nama_perempuan`, `nik_perempuan`, `nama_laki`, `nik_laki`, `tanggal_cerai`, `tempat_cerai`, `nomor_akta_perkawinan`, `tanggal_perkawinan`, `status`, `catatan_revisi`, `catatan_pengembalian`, `catatan_penolakan`, `diproses_oleh`, `updated_by`, `tanggal_pengajuan`, `tanggal_proses`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, NULL, '456789', 'VIONA', '5353552235657879', 'RAMAd', '1234567890098765', '2026-04-01', 'TUBAN', '12-AC-20', '2023-01-31', 'Pengembalian', NULL, 'kj', NULL, NULL, NULL, '2026-04-08 19:14:36', NULL, NULL, '2026-04-07 13:01:57', '2026-05-09 20:20:57'),
(5, NULL, NULL, 'new', '5353552235657656', 'olddd', '1234567890098656', '2026-02-10', 'TUBAN', '12-AC-25656', '2025-06-17', 'Selesai', NULL, NULL, NULL, NULL, NULL, '2026-04-08 21:05:36', NULL, NULL, '2026-04-08 21:04:31', '2026-04-08 21:06:15'),
(6, NULL, '456789', 'SOFI', '6756756746746098', 'JONATHAN', '1234567890098343', '2026-04-02', 'JOMBANG', '22-BGGG-6767', '2020-01-06', 'Pengajuan', NULL, NULL, NULL, NULL, NULL, '2026-05-21 21:03:37', NULL, NULL, '2026-04-08 21:11:41', '2026-05-21 21:08:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_akta_kematian`
--

CREATE TABLE `pengajuan_akta_kematian` (
  `id_permohonan` int(11) NOT NULL,
  `no_permohonan` varchar(30) NOT NULL,
  `nik_jenazah` varchar(16) NOT NULL,
  `nama_jenazah` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_kematian` date NOT NULL,
  `jam_kematian` time DEFAULT NULL,
  `tempat_kematian` varchar(150) DEFAULT NULL,
  `sebab_kematian` varchar(150) DEFAULT NULL,
  `nama_pelapor` varchar(100) DEFAULT NULL,
  `nik_pelapor` varchar(16) DEFAULT NULL,
  `hubungan_pelapor` varchar(50) DEFAULT NULL,
  `nama_saksi_1` varchar(100) DEFAULT NULL,
  `nik_saksi_1` varchar(16) DEFAULT NULL,
  `nama_saksi_2` varchar(100) DEFAULT NULL,
  `nik_saksi_2` varchar(16) DEFAULT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') DEFAULT 'Pengajuan',
  `catatan_revisi` text DEFAULT NULL,
  `kode_desa` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_akta_kematian`
--

INSERT INTO `pengajuan_akta_kematian` (`id_permohonan`, `no_permohonan`, `nik_jenazah`, `nama_jenazah`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `tanggal_kematian`, `jam_kematian`, `tempat_kematian`, `sebab_kematian`, `nama_pelapor`, `nik_pelapor`, `hubungan_pelapor`, `nama_saksi_1`, `nik_saksi_1`, `nama_saksi_2`, `nik_saksi_2`, `status`, `catatan_revisi`, `kode_desa`, `created_at`, `updated_at`, `catatan_pengembalian`, `catatan_penolakan`) VALUES
(2, 'AKM-20260412210752', '7346289171971434', 'DIDIK', 'Perempuan', 'Mojokerto', '2017-01-02', '2026-04-07', '19:06:00', 'RS', 'KECELAKAAN', 'rama', '4366456352252545', 'ANAK', 'JOKO', '9786757564534245', 'GUGH', '9786757564534089', 'Selesai', NULL, NULL, '2026-04-12 21:07:52', '2026-04-12 21:30:34', NULL, NULL),
(4, 'AKM-20260412221254', '7346289171971768', 'RATIH', 'Perempuan', 'Tuban', '2026-04-02', '2026-04-08', '23:12:00', 'RS', 'SAKIT', 'A', '4366456352252508', 'SUAMI', 'GJHGJ', '9786757564534245', 'GUGH', '9786757564534089', 'Proses', NULL, NULL, '2026-04-12 22:12:54', '2026-05-12 20:39:28', NULL, NULL),
(6, 'AKM-20260519210258', '7346289171971868', 'SEMI', 'Laki-laki', 'Tuban', '2026-05-01', '2026-05-08', '23:03:00', 'RS', 'SAKIT', 'Nanang', '9864213456789087', 'ANAK', 'JOKO', '9786757564534245', 'BIARA', '9786757564534089', 'Proses', NULL, '456789', '2026-05-19 21:02:58', '2026-05-19 21:13:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_akta_nikah`
--

CREATE TABLE `pengajuan_akta_nikah` (
  `id_permohonan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_laki_laki` varchar(100) NOT NULL,
  `nik_laki_laki` char(16) NOT NULL,
  `nama_perempuan` varchar(100) NOT NULL,
  `nik_perempuan` char(16) NOT NULL,
  `agama` varchar(50) NOT NULL,
  `tempat_pernikahan` enum('Gereja','Pura','Vihara','Klenteng','Lainnya') NOT NULL,
  `tanggal_perkawinan` date NOT NULL,
  `nama_pemuka_agama` varchar(100) NOT NULL,
  `nama_saksi_1` varchar(100) NOT NULL,
  `nama_saksi_2` varchar(100) NOT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') NOT NULL DEFAULT 'Pengajuan',
  `catatan_penolakan` text DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kode_desa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_akta_nikah`
--

INSERT INTO `pengajuan_akta_nikah` (`id_permohonan`, `id_user`, `nama_laki_laki`, `nik_laki_laki`, `nama_perempuan`, `nik_perempuan`, `agama`, `tempat_pernikahan`, `tanggal_perkawinan`, `nama_pemuka_agama`, `nama_saksi_1`, `nama_saksi_2`, `status`, `catatan_penolakan`, `catatan_pengembalian`, `catatan`, `created_at`, `updated_at`, `kode_desa`) VALUES
(5, NULL, 'YONOooo', '6564674674535242', 'SOFI', '5353552235657464', 'KHATOLIK', 'Gereja', '2026-04-03', 'ROMO', 'SUSTER', 'BIARA', 'Revisi', NULL, NULL, 'hkj', '2026-04-10 23:39:33', '2026-05-21 20:25:18', NULL),
(6, NULL, 'jekiiiiii', '6567564646535456', 'cen', '5353552235657888', 'BUDHA', 'Gereja', '2026-04-01', 'BIKSU', 'DONI', 'JOKO', 'Selesai', NULL, NULL, NULL, '2026-04-10 23:41:54', '2026-05-07 18:26:48', '456789'),
(7, NULL, 'LAGU', '6564674674535435', 'SOFA', '5353552235657609', 'BUDHA', 'Vihara', '2026-04-01', 'ROMO', 'SUSTER', 'BIARA', 'Pengajuan', NULL, 'gfh', NULL, '2026-04-11 20:46:58', '2026-05-21 20:58:08', '456789');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_kia`
--

CREATE TABLE `pengajuan_kia` (
  `id_kia` int(11) NOT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `nik_ayah` char(16) NOT NULL,
  `alamat_desa` varchar(100) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `nik_anak` char(16) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `foto_anak` varchar(255) DEFAULT NULL,
  `file_kk` varchar(255) DEFAULT NULL,
  `file_akta` varchar(255) DEFAULT NULL,
  `file_f102` varchar(255) NOT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') NOT NULL DEFAULT 'Pengajuan',
  `catatan` text DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL,
  `hasil_pdf` varchar(255) DEFAULT NULL,
  `terakhir_upload` datetime DEFAULT NULL,
  `tanggal_pengajuan` datetime DEFAULT current_timestamp(),
  `kode_desa` varchar(20) NOT NULL,
  `notif_read` tinyint(1) DEFAULT 0,
  `jenis_pengajuan` enum('baru','sudah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_kia`
--

INSERT INTO `pengajuan_kia` (`id_kia`, `nama_ayah`, `nik_ayah`, `alamat_desa`, `nama_anak`, `nik_anak`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `foto_anak`, `file_kk`, `file_akta`, `file_f102`, `status`, `catatan`, `catatan_pengembalian`, `catatan_penolakan`, `hasil_pdf`, `terakhir_upload`, `tanggal_pengajuan`, `kode_desa`, `notif_read`, `jenis_pengajuan`) VALUES
(2, 'ADI', '3253252353242414', '', 'DHEA', '8984983274638498', 'P', 'MJK', '2026-01-06', '', 'uploads/kia/kk/kia_6977603cc1cdb.jpeg', 'uploads/kia/akta/kia_6977603cc2832.jpeg', '[\"uploads/f102/1769434321219.jpg\",\"uploads/f102/1769434321233.jpg\"]', 'Selesai', NULL, NULL, NULL, 'kia_2_1769518637.pdf', '2026-01-27 19:57:17', '2026-01-26 19:38:20', '', 0, 'baru'),
(4, 'SDF', '4636726253221515', '', 'DON', '9897756565766587', 'P', 'HGHJ', '2025-06-10', '', 'uploads/kia/kk/kia_6978bdc867189.jpeg', 'uploads/kia/akta/kia_6978bdc8676eb.jpeg', 'uploads/kia/f102/kia_6978bdc867c21.jpeg|uploads/kia/f102/kia_6978bdc867f5e.jpeg|uploads/kia/f102/kia_6978bdc8682ec.jpg', 'Proses', NULL, NULL, NULL, NULL, NULL, '2026-01-27 20:29:44', '456789', 0, 'baru'),
(6, 'efw', '6865734632521412', '', 'we', '1234123543575488', 'P', 'sdfdf', '2025-07-08', '', 'uploads/kia/kk/kia_697b6034d0db5.jpeg', 'uploads/kia/akta/kia_697b6034d1468.jpeg', 'uploads/kia/f102/kia_697b6034d1bd5.jpeg|uploads/kia/f102/kia_697b6034d2059.jpeg', 'Proses', NULL, NULL, NULL, NULL, NULL, '2026-01-29 20:27:16', '456789', 1, 'baru'),
(10, 'A', '6576575768787999', '', 'AISYAH', '3455236726217808', 'L', 'Tuban', '2025-02-20', NULL, '1775393168_a748cd2c4950b86f0e85.pdf', '1775393168_d0cd6f03d3ca5c867b79.pdf', '1775393168_7904d79163a87e09f3a9.jpeg,1775482414_2853d8ec99ce4bd203ec.jpg', 'Selesai', NULL, NULL, NULL, 'uploads/kia/1775482913_54e16080143628c6d944.pdf', NULL, '2026-04-05 19:46:08', '456789', 0, 'sudah'),
(11, 'andika', '6756756454343288', '', 'kangen', '', 'L', 'Tuban', '2026-04-01', NULL, NULL, NULL, '1775395007_d2b9e40451a595f0f6bb.jpeg,1775395059_48ebd11c49ba5e518926.jpg', 'Pengajuan', NULL, NULL, NULL, NULL, NULL, '2026-04-05 20:16:47', 'ADMIN', 0, 'baru'),
(12, 'ADI SATRIO', '6756756454343234', '', 'DHE', '3455236726216565', 'P', 'Tuban', '2021-01-06', '1775485122_330aaceedaa95b124a41.jpg', '1775485122_31f9d66a547bf73e6c65.pdf', '1775485122_ab87607e537b81dce2bc.pdf', '1775485122_fb7a8e315ecdbf37e6ff.jpeg', 'Selesai', NULL, NULL, NULL, 'uploads/kia/1778383848_776c9ee6289f6ace78da.pdf', NULL, '2026-04-06 21:18:43', '456789', 0, 'sudah'),
(13, 'RUDI', '6576575768781342', '', 'AISYAH', '', 'P', 'semarang', '2026-04-01', NULL, NULL, NULL, '1775485197_0c7df00599aba06090bd.jpeg,1775485197_0c43de942fbacaa3698a.jpeg', 'Pengajuan', NULL, NULL, NULL, NULL, NULL, '2026-04-06 21:19:57', 'ADMIN', 0, 'baru'),
(14, 'DANU', '6756756454343234', '', 'DHEA', NULL, 'P', 'Mojokerto', '2026-05-01', NULL, NULL, NULL, '1778592576_baa3d44d614511091e87.jpeg', 'Revisi', 'hkh', NULL, NULL, NULL, NULL, '2026-05-12 20:29:36', '456789', 0, 'baru'),
(15, 'DONO', '6756756454343234', '', 'AMAL', '3455236726217987', 'P', 'Tuban', '2026-05-01', NULL, '1779372768_9e4301021c49faf098e9.pdf', '1779372768_8739f401b35ffe778539.pdf', '1779372768_cc38c08fe9a2281084de.jpeg,1779372768_7668fd08b12c30985aab.jpeg', 'Proses', NULL, NULL, NULL, NULL, NULL, '2026-05-21 21:12:48', '456789', 0, 'sudah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_kk`
--

CREATE TABLE `pengajuan_kk` (
  `id_pengajuan` int(11) NOT NULL,
  `kode_desa` varchar(20) DEFAULT NULL,
  `jenis_pengajuan` enum('baru','perubahan') DEFAULT NULL,
  `nama_kepala` varchar(100) DEFAULT NULL,
  `nik_kepala` varchar(20) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') DEFAULT 'Pengajuan',
  `catatan_revisi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_kk`
--

INSERT INTO `pengajuan_kk` (`id_pengajuan`, `kode_desa`, `jenis_pengajuan`, `nama_kepala`, `nik_kepala`, `no_kk`, `alamat`, `status`, `catatan_revisi`, `created_at`, `updated_at`, `catatan_pengembalian`, `catatan_penolakan`) VALUES
(2, NULL, 'perubahan', 'jono', '0987654321234343', '7678676875645465', 'tbn', 'Proses', NULL, '2026-04-14 14:46:59', '2026-05-19 21:37:03', NULL, NULL),
(3, '456789', 'baru', 'ADIiii', '6577665367577790', '', 'SMD', 'Revisi', 'er', '2026-04-14 15:17:04', '2026-05-19 21:37:23', 'dfs', NULL),
(4, '456789', 'perubahan', 'DENI', '6577665367577545', '6577665367577434', 'SFS', 'Pengajuan', 'ESG', '2026-04-15 15:53:55', '2026-05-19 21:38:10', 'ds', 'af'),
(5, NULL, 'baru', 'AZIIIIIIIIIIIZ', '0987654321234545', '', 'DS', 'Proses', NULL, '2026-04-15 15:55:09', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_pindah`
--

CREATE TABLE `pengajuan_pindah` (
  `id_pengajuan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_pindah` enum('datang','keluar') NOT NULL,
  `kategori_pindah` enum('Kepala Keluarga','Sebagian Anggota Keluarga','Anggota Keluarga','Seluruh Keluarga') NOT NULL,
  `jenis_tujuan` enum('Antar Desa','Antar Kecamatan','Antar Kabupaten/Kota') NOT NULL,
  `alamat_asal` text NOT NULL,
  `alamat_tujuan` text NOT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') NOT NULL DEFAULT 'Pengajuan',
  `catatan_revisi` text DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL,
  `hasil_file` varchar(255) DEFAULT NULL,
  `tanggal_pengajuan` datetime DEFAULT current_timestamp(),
  `kode_desa` varchar(20) DEFAULT NULL,
  `notif_read` enum('0','1') DEFAULT '0',
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_pindah`
--

INSERT INTO `pengajuan_pindah` (`id_pengajuan`, `nama_pemohon`, `jenis_pindah`, `kategori_pindah`, `jenis_tujuan`, `alamat_asal`, `alamat_tujuan`, `alasan`, `status`, `catatan_revisi`, `catatan_pengembalian`, `catatan_penolakan`, `hasil_file`, `tanggal_pengajuan`, `kode_desa`, `notif_read`, `id_user`) VALUES
(8, 'Dwika', 'keluar', 'Kepala Keluarga', 'Antar Desa', 'bjh', 'gyrty', 'hjg', 'Proses', NULL, NULL, NULL, 'uploads/hasil_pengajuan/HASIL_8_1764214315.pdf', '2025-11-27 09:45:25', '', '0', NULL),
(10, 'hvgh', 'datang', 'Kepala Keluarga', 'Antar Kabupaten/Kota', 'vh', 'vhv', 'dtd', 'Pengajuan', NULL, NULL, NULL, NULL, '2026-01-14 21:39:52', '', '0', NULL),
(11, 'riza aaaa', 'keluar', 'Kepala Keluarga', 'Antar Kabupaten/Kota', 'ggdf', 'nvn', 'cvvx', 'Proses', NULL, NULL, NULL, NULL, '2026-03-28 21:39:31', '456789', '0', NULL),
(12, 'amalia', 'datang', 'Kepala Keluarga', 'Antar Desa', 'jombang', 'nganjuk', 'nikah', 'Pengajuan', NULL, NULL, NULL, NULL, '2026-03-29 20:23:10', NULL, '0', NULL),
(18, 'dhea ramadhaniihjhjk', 'datang', 'Kepala Keluarga', 'Antar Desa', 'bj', 'vjhjh', 'hgjh', 'Pengajuan', 'ghds', NULL, NULL, NULL, '2026-03-31 20:15:19', NULL, '0', NULL),
(19, 'dhea amalia d . r', 'datang', 'Sebagian Anggota Keluarga', 'Antar Desa', 'semanding', 'tuban', 'kerja', 'Selesai', 'rev', NULL, NULL, NULL, '2026-04-03 21:16:29', '456789', '0', NULL),
(21, 'dwika', 'datang', 'Kepala Keluarga', 'Antar Kabupaten/Kota', 'bsdh', 'sdjhjsd', 'ghghgh', 'Revisi', 'hgjh', NULL, NULL, NULL, '2026-04-04 20:15:28', '456789', '0', NULL),
(24, 'della', 'datang', 'Kepala Keluarga', 'Antar Kabupaten/Kota', 'dsf', 'dfsf', 'fsf', 'Selesai', 'bdjsf', 'qdw', 'jj', NULL, '2026-04-04 20:29:27', '456789', '0', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan`
--

CREATE TABLE `permohonan` (
  `id_permohonan` int(11) NOT NULL,
  `id_pelapor` int(11) NOT NULL,
  `id_anak` int(11) NOT NULL,
  `id_orangtua` int(11) NOT NULL,
  `id_saksi1` int(11) NOT NULL,
  `id_saksi2` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('Pengajuan','Proses','Revisi','Selesai','Pengembalian') DEFAULT NULL,
  `catatan_revisi` text DEFAULT NULL,
  `catatan_pengembalian` text DEFAULT NULL,
  `catatan_penolakan` text DEFAULT NULL,
  `tanggal_pengembalian` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan`
--

INSERT INTO `permohonan` (`id_permohonan`, `id_pelapor`, `id_anak`, `id_orangtua`, `id_saksi1`, `id_saksi2`, `created_at`, `kode_desa`, `updated_at`, `status`, `catatan_revisi`, `catatan_pengembalian`, `catatan_penolakan`, `tanggal_pengembalian`) VALUES
(25, 34, 28, 28, 51, 52, '2025-10-16 01:51:51', '901234', '2026-05-10 12:55:07', 'Selesai', NULL, NULL, NULL, NULL),
(27, 36, 30, 30, 55, 56, '2025-10-20 06:21:57', '901234', '2026-05-05 11:18:19', 'Pengajuan', NULL, NULL, NULL, NULL),
(34, 43, 37, 37, 69, 70, '2026-03-24 20:30:51', '456789', '2026-05-19 13:24:48', 'Pengembalian', NULL, 'as', NULL, NULL),
(36, 0, 39, 0, 0, 0, '2026-03-25 06:15:40', NULL, '2026-05-05 11:17:47', 'Proses', NULL, NULL, NULL, NULL),
(37, 0, 40, 0, 0, 0, '2026-03-26 06:02:12', '456789', '2026-05-06 10:10:51', 'Pengembalian', NULL, 'gh', NULL, NULL),
(39, 0, 42, 0, 0, 0, '2026-04-19 13:45:51', '3523172005', '2026-05-05 11:43:56', 'Revisi', 'asf', NULL, NULL, NULL),
(40, 0, 43, 0, 0, 0, '2026-04-19 14:08:59', '456789', '2026-05-13 04:13:05', 'Revisi', 'rty', NULL, NULL, NULL),
(41, 0, 44, 0, 0, 0, '2026-05-05 11:34:17', '456789', '2026-05-10 13:22:08', 'Selesai', NULL, NULL, NULL, NULL),
(43, 0, 46, 0, 0, 0, '2026-05-18 13:44:55', NULL, '2026-05-19 13:36:16', 'Selesai', NULL, NULL, 'surat RS belum ada', NULL),
(48, 0, 50, 0, 0, 0, '2026-05-18 14:37:51', NULL, '2026-05-18 14:42:10', 'Revisi', 'kurang f1.02', NULL, NULL, NULL),
(49, 0, 51, 0, 0, 0, '2026-05-19 12:25:32', NULL, '2026-05-19 13:15:09', 'Proses', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `saksi`
--

CREATE TABLE `saksi` (
  `id_saksi` int(11) NOT NULL,
  `nama_saksi` varchar(100) DEFAULT NULL,
  `nik_saksi` char(16) DEFAULT NULL,
  `id_permohonan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `saksi`
--

INSERT INTO `saksi` (`id_saksi`, `nama_saksi`, `nik_saksi`, `id_permohonan`) VALUES
(45, 'dokter', '8786755334345465', NULL),
(46, 'hasan', '2342453564657667', NULL),
(51, 'RORO', '3456587098978656', 25),
(52, 'SISI', '7986765646586898', 25),
(55, 'RORO', '4546567766755334', 27),
(56, 'SISI', '9008866443435677', 27),
(69, '', '', 34),
(70, '', '', 34),
(73, '', '', 36),
(74, '', '', 36),
(75, 'Anis', '7687575764674897', 37),
(76, 'Nisa', '8765563537646757', 37),
(79, 'yuni', '3454113432525454', 39),
(80, 'puspi', '4653656758757868', 39),
(81, 'didik', '7687575764674545', 40),
(82, 'dini', '3434566880906756', 40),
(83, 'yuni', '3454113432525454', 41),
(84, 'fdgvvvvvg', '1324679809807969', 41),
(87, 'Doni', '6567464646535323', 43),
(88, 'Dini', '6567464646535767', 43),
(91, 'didik', '7687575764674545', 48),
(92, 'puspi', '3434566880906756', 48),
(93, 'knakhfjkshj', '7687575764674654', 49),
(94, 'vb   bvbvb', '1324679809807969', 49);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expired` datetime DEFAULT NULL,
  `role` enum('desa','admin') NOT NULL,
  `is_master` tinyint(1) NOT NULL DEFAULT 0,
  `kode_desa` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expired` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `avatar`, `username`, `password`, `email`, `reset_token`, `token_expired`, `role`, `is_master`, `kode_desa`, `created_at`, `otp_code`, `otp_expired`) VALUES
(5, 'asal', 'default.png', 'palsu', '$2y$10$41zfis2v9Sxc6R9nKAN8DekWgPSJ4lPJmW0ZV8IS.tRa8/Qh.sLou', 'dhearamadhani2115@gmail.com', NULL, NULL, 'desa', 0, '456789', '2025-09-25 02:33:56', NULL, NULL),
(10, 'dukcapil', NULL, 'admin', '$2y$10$7Ryg6HAODkx926wAs1Sgg.bZZxFJGfCzl/XLgTIHMGV6mtm2AU3JC', 'rizaayurp22@gmail.com', NULL, NULL, 'admin', 0, NULL, '2025-10-13 01:42:15', NULL, NULL),
(12, 'Dhea', '1776432411_21617c6909d9cd4518c3.jpg', 'Ayu', '$2y$10$JlYMLAZTIuvK0kolL9uHDuUClLfuyDkXWLXdzK9LzXujhsxtQLWtS', 'wijiwilujeng212@gmail.com', NULL, NULL, 'admin', 1, NULL, '2026-02-26 14:04:21', NULL, NULL),
(14, 'Adelia Putri', NULL, 'Puput', '$2y$10$wyJyplPDqqMp6WZyRVZMcOT.9CAzzDiOobtAdr21cENpgHg9xdAni', 'ramadhani.dhea2002@gmail.com', NULL, NULL, 'desa', 0, '3523172005', '2026-04-19 12:47:40', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anak`
--
ALTER TABLE `anak`
  ADD PRIMARY KEY (`id_anak`),
  ADD KEY `fk_anak_permohonan_new` (`id_permohonan`);

--
-- Indeks untuk tabel `anggota_kk`
--
ALTER TABLE `anggota_kk`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `anggota_pindah`
--
ALTER TABLE `anggota_pindah`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `bantuan_faq`
--
ALTER TABLE `bantuan_faq`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bantuan_konten`
--
ALTER TABLE `bantuan_konten`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_dukung`
--
ALTER TABLE `data_dukung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `desa`
--
ALTER TABLE `desa`
  ADD PRIMARY KEY (`id_desa`),
  ADD UNIQUE KEY `kode_desa` (`kode_desa`),
  ADD KEY `desa_ibfk_1` (`kode_kecamatan`);

--
-- Indeks untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `fk_dokumen_permohonan` (`id_permohonan`);

--
-- Indeks untuk tabel `dokumen_akta_cerai`
--
ALTER TABLE `dokumen_akta_cerai`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `fk_dokumen_akta_cerai` (`id_permohonan`);

--
-- Indeks untuk tabel `dokumen_akta_kematian`
--
ALTER TABLE `dokumen_akta_kematian`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_permohonan` (`id_permohonan`);

--
-- Indeks untuk tabel `dokumen_akta_nikah`
--
ALTER TABLE `dokumen_akta_nikah`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indeks untuk tabel `dokumen_kk`
--
ALTER TABLE `dokumen_kk`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `dokumen_pindah`
--
ALTER TABLE `dokumen_pindah`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `download_files`
--
ALTER TABLE `download_files`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_permohonan` (`id_permohonan`);

--
-- Indeks untuk tabel `hasil_layanan`
--
ALTER TABLE `hasil_layanan`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `idx_jenis_layanan` (`jenis_layanan`),
  ADD KEY `idx_id_ref` (`id_ref`);

--
-- Indeks untuk tabel `hasil_pindah`
--
ALTER TABLE `hasil_pindah`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`id_kecamatan`),
  ADD UNIQUE KEY `kode_kecamatan` (`kode_kecamatan`);

--
-- Indeks untuk tabel `orangtua`
--
ALTER TABLE `orangtua`
  ADD PRIMARY KEY (`id_orangtua`),
  ADD KEY `fk_orangtua_permohonan_new` (`id_permohonan`);

--
-- Indeks untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  ADD PRIMARY KEY (`id_pelapor`),
  ADD KEY `fk_pelapor_permohonan_new` (`id_permohonan`);

--
-- Indeks untuk tabel `pengajuan_akta_cerai`
--
ALTER TABLE `pengajuan_akta_cerai`
  ADD PRIMARY KEY (`id_permohonan`);

--
-- Indeks untuk tabel `pengajuan_akta_kematian`
--
ALTER TABLE `pengajuan_akta_kematian`
  ADD PRIMARY KEY (`id_permohonan`);

--
-- Indeks untuk tabel `pengajuan_akta_nikah`
--
ALTER TABLE `pengajuan_akta_nikah`
  ADD PRIMARY KEY (`id_permohonan`);

--
-- Indeks untuk tabel `pengajuan_kia`
--
ALTER TABLE `pengajuan_kia`
  ADD PRIMARY KEY (`id_kia`);

--
-- Indeks untuk tabel `pengajuan_kk`
--
ALTER TABLE `pengajuan_kk`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `pengajuan_pindah`
--
ALTER TABLE `pengajuan_pindah`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `permohonan`
--
ALTER TABLE `permohonan`
  ADD PRIMARY KEY (`id_permohonan`),
  ADD KEY `fk_permohonan_anak` (`id_anak`),
  ADD KEY `fk_permohonan_orangtua` (`id_orangtua`),
  ADD KEY `fk_permohonan_pelapor` (`id_pelapor`),
  ADD KEY `fk_permohonan_saksi1` (`id_saksi1`),
  ADD KEY `fk_permohonan_saksi2` (`id_saksi2`);

--
-- Indeks untuk tabel `saksi`
--
ALTER TABLE `saksi`
  ADD PRIMARY KEY (`id_saksi`),
  ADD KEY `fk_saksi_permohonan_new` (`id_permohonan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_kode_desa` (`kode_desa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anak`
--
ALTER TABLE `anak`
  MODIFY `id_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `anggota_kk`
--
ALTER TABLE `anggota_kk`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `anggota_pindah`
--
ALTER TABLE `anggota_pindah`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `bantuan_faq`
--
ALTER TABLE `bantuan_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `bantuan_konten`
--
ALTER TABLE `bantuan_konten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_dukung`
--
ALTER TABLE `data_dukung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `desa`
--
ALTER TABLE `desa`
  MODIFY `id_desa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98733;

--
-- AUTO_INCREMENT untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;

--
-- AUTO_INCREMENT untuk tabel `dokumen_akta_cerai`
--
ALTER TABLE `dokumen_akta_cerai`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `dokumen_akta_kematian`
--
ALTER TABLE `dokumen_akta_kematian`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `dokumen_akta_nikah`
--
ALTER TABLE `dokumen_akta_nikah`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `dokumen_kk`
--
ALTER TABLE `dokumen_kk`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `dokumen_pindah`
--
ALTER TABLE `dokumen_pindah`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT untuk tabel `download_files`
--
ALTER TABLE `download_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hasil_layanan`
--
ALTER TABLE `hasil_layanan`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `hasil_pindah`
--
ALTER TABLE `hasil_pindah`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `id_kecamatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `orangtua`
--
ALTER TABLE `orangtua`
  MODIFY `id_orangtua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  MODIFY `id_pelapor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_akta_cerai`
--
ALTER TABLE `pengajuan_akta_cerai`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_akta_kematian`
--
ALTER TABLE `pengajuan_akta_kematian`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_akta_nikah`
--
ALTER TABLE `pengajuan_akta_nikah`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_kia`
--
ALTER TABLE `pengajuan_kia`
  MODIFY `id_kia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_kk`
--
ALTER TABLE `pengajuan_kk`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_pindah`
--
ALTER TABLE `pengajuan_pindah`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `saksi`
--
ALTER TABLE `saksi`
  MODIFY `id_saksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anak`
--
ALTER TABLE `anak`
  ADD CONSTRAINT `fk_anak_permohonan_new` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `anggota_kk`
--
ALTER TABLE `anggota_kk`
  ADD CONSTRAINT `anggota_kk_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_kk` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `anggota_pindah`
--
ALTER TABLE `anggota_pindah`
  ADD CONSTRAINT `anggota_pindah_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pindah` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `desa`
--
ALTER TABLE `desa`
  ADD CONSTRAINT `desa_ibfk_1` FOREIGN KEY (`kode_kecamatan`) REFERENCES `kecamatan` (`kode_kecamatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_dokumen_permohonan` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen_akta_cerai`
--
ALTER TABLE `dokumen_akta_cerai`
  ADD CONSTRAINT `fk_dokumen_akta_cerai` FOREIGN KEY (`id_permohonan`) REFERENCES `pengajuan_akta_cerai` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen_akta_kematian`
--
ALTER TABLE `dokumen_akta_kematian`
  ADD CONSTRAINT `dokumen_akta_kematian_ibfk_1` FOREIGN KEY (`id_permohonan`) REFERENCES `pengajuan_akta_kematian` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen_kk`
--
ALTER TABLE `dokumen_kk`
  ADD CONSTRAINT `dokumen_kk_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_kk` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen_pindah`
--
ALTER TABLE `dokumen_pindah`
  ADD CONSTRAINT `dokumen_pindah_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pindah` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil_pindah`
--
ALTER TABLE `hasil_pindah`
  ADD CONSTRAINT `hasil_pindah_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pindah` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orangtua`
--
ALTER TABLE `orangtua`
  ADD CONSTRAINT `fk_orangtua_permohonan` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orangtua_permohonan_new` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pelapor`
--
ALTER TABLE `pelapor`
  ADD CONSTRAINT `fk_pelapor_permohonan` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pelapor_permohonan_new` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `saksi`
--
ALTER TABLE `saksi`
  ADD CONSTRAINT `fk_saksi_permohonan` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_saksi_permohonan_new` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_desa` FOREIGN KEY (`kode_desa`) REFERENCES `desa` (`kode_desa`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
