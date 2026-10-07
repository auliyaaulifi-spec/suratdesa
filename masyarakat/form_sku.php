
<?php

session_start();

if (!isset($_SESSION['id_masyarakat'])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$id_masyarakat = $_SESSION['id_masyarakat'];


/* =========================
   AMBIL DATA MASYARAKAT
========================= */

$query_masyarakat = mysqli_query(
    $koneksi,
    "SELECT *
     FROM masyarakat
     WHERE id_masyarakat = '$id_masyarakat'"
);

$data_masyarakat = mysqli_fetch_assoc($query_masyarakat);

if (!$data_masyarakat) {
    die("Data masyarakat tidak ditemukan.");
}


/* =========================
   PROSES PENGAJUAN
========================= */

$error = "";

if (isset($_POST['ajukan'])) {

    $nama_usaha = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_usaha'])
    );

    $jenis_usaha = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['jenis_usaha'])
    );

    $alamat_usaha = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['alamat_usaha'])
    );

    $nama_pemilik = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nama_pemilik'])
    );

    $no_kk = trim($_POST['no_kk']);

    $keperluan = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['keperluan'])
    );


    /* =========================
       VALIDASI NO. KK
    ========================= */

    if (!preg_match('/^[0-9]{16}$/', $no_kk)) {

        $error = "Nomor KK harus terdiri dari 16 digit angka.";

    } else {

        $no_kk = mysqli_real_escape_string(
            $koneksi,
            $no_kk
        );

        $tanggal = date('Y-m-d');

        /*
         * id_jenis_surat = 2
         * Surat Keterangan Usaha (SKU)
         */

        $query_pengajuan = mysqli_query(
            $koneksi,
            "INSERT INTO pengajuan_surat
            (
                id_masyarakat,
                no_kk,
                id_jenis_surat,
                tanggal_pengajuan,
                keperluan,
                status_pengajuan
            )
            VALUES
            (
                '$id_masyarakat',
                '$no_kk',
                '2',
                '$tanggal',
                '$keperluan',
                'Menunggu'
            )"
        );


        if ($query_pengajuan) {

            $id_pengajuan = mysqli_insert_id($koneksi);


            /* =========================
               SIMPAN DATA USAHA
            ========================= */

            $query_usaha = mysqli_query(
                $koneksi,
                "INSERT INTO data_usaha
                (
                    id_pengajuan,
                    nama_usaha,
                    jenis_usaha,
                    alamat_usaha,
                    nama_pemilik
                )
                VALUES
                (
                    '$id_pengajuan',
                    '$nama_usaha',
                    '$jenis_usaha',
                    '$alamat_usaha',
                    '$nama_pemilik'
                )"
            );


            if ($query_usaha) {

                header("Location: status_pengajuan.php");
                exit;

            } else {

                $error = "Data usaha gagal disimpan: "
                       . mysqli_error($koneksi);
            }

        } else {

            $error = "Pengajuan surat gagal disimpan: "
                   . mysqli_error($koneksi);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Form Pengajuan Surat Keterangan Usaha
    </title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffff;
        }

        .header {
            width: 100%;
            height: 80px;
            background: #1f4e79;
            color: white;
            display: flex;
            align-items: center;
            padding-left: 30px;
        }

        .header img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 15px;
        }

        .header h2 {
            font-size: 20px;
        }

        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: 240px;
            height: calc(100vh - 80px);
            background: #f4f4f4;
            border-right: 1px solid #ddd;
        }

        .sidebar a {
            display: block;
            width: 240px;
            padding: 16px 20px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #e5e5e5;
        }

        .content {
            margin-left: 240px;
            padding: 40px;
        }

        .content h1 {
            font-size: 26px;
            margin-bottom: 30px;
            color: #333;
        }

        .form-box {
            width: 850px;
            border: 1px solid #ddd;
            padding: 30px;
            background: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 90px;
            resize: vertical;
        }

        .form-group small {
            display: block;
            margin-top: 6px;
            color: #666;
            font-size: 12px;
        }

        .button {
            background: #1f4e79;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .button:hover {
            background: #163a5c;
        }

        .error {
            width: 850px;
            margin-bottom: 20px;
            padding: 12px;
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .info {
            width: 850px;
            margin-bottom: 20px;
            padding: 14px;
            background: #f1f5f9;
            border-left: 4px solid #1f4e79;
            color: #444;
            font-size: 14px;
        }

    </style>

</head>

<body>


<!-- HEADER -->

<div class="header">

    <img
        src="../assets/logo_desa.png"
        alt="Logo Desa Sampiran"
    >

    <h2>
        SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA
    </h2>

</div>


<!-- SIDEBAR -->

<div class="sidebar">

    <a href="dashboard.php">
        DASHBOARD
    </a>

    <a href="pengajuan_surat.php">
        PENGAJUAN SURAT
    </a>

    <a href="status_pengajuan.php">
        STATUS PENGAJUAN
    </a>

    <a href="riwayat_surat.php">
        RIWAYAT SURAT
    </a>

    <a href="profil.php">
        PROFIL
    </a>

    <a href="../logout.php">
        LOGOUT
    </a>

</div>


<!-- CONTENT -->

<div class="content">

    <h1>
        FORM PENGAJUAN SURAT KETERANGAN USAHA (SKU)
    </h1>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <div class="info">

        <strong>Perhatian:</strong><br>

        Silakan isi data usaha dan nomor KK sesuai
        dengan dokumen yang sebenarnya.

    </div>


    <div class="form-box">

        <form method="POST">


            <!-- NAMA USAHA -->

            <div class="form-group">

                <label>
                    NAMA USAHA
                </label>

                <input
                    type="text"
                    name="nama_usaha"
                    value="<?php
                        echo isset($_POST['nama_usaha'])
                            ? htmlspecialchars($_POST['nama_usaha'])
                            : '';
                    ?>"
                    placeholder="Masukkan nama usaha"
                    required
                >

            </div>


            <!-- JENIS USAHA -->

            <div class="form-group">

                <label>
                    JENIS USAHA
                </label>

                <input
                    type="text"
                    name="jenis_usaha"
                    value="<?php
                        echo isset($_POST['jenis_usaha'])
                            ? htmlspecialchars($_POST['jenis_usaha'])
                            : '';
                    ?>"
                    placeholder="Contoh: Warung sembako"
                    required
                >

            </div>


            <!-- ALAMAT USAHA -->

            <div class="form-group">

                <label>
                    ALAMAT USAHA
                </label>

                <textarea
                    name="alamat_usaha"
                    placeholder="Masukkan alamat lengkap usaha"
                    required
                ><?php
                    echo isset($_POST['alamat_usaha'])
                        ? htmlspecialchars($_POST['alamat_usaha'])
                        : '';
                ?></textarea>

            </div>


            <!-- NAMA PEMILIK -->

            <div class="form-group">

                <label>
                    NAMA PEMILIK
                </label>

                <input
                    type="text"
                    name="nama_pemilik"
                    value="<?php
                        echo htmlspecialchars(
                            $data_masyarakat['nama_lengkap']
                        );
                    ?>"
                    required
                >

            </div>


            <!-- NIK PEMILIK -->

            <div class="form-group">

                <label>
                    NIK PEMILIK
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $data_masyarakat['nik']
                        );
                    ?>"
                    readonly
                >

            </div>


            <!-- NO KK -->

            <div class="form-group">

                <label>
                    NO. KARTU KELUARGA (KK)
                </label>

                <input
                    type="text"
                    name="no_kk"
                    value="<?php
                        echo isset($_POST['no_kk'])
                            ? htmlspecialchars($_POST['no_kk'])
                            : '';
                    ?>"
                    maxlength="16"
                    minlength="16"
                    inputmode="numeric"
                    pattern="[0-9]{16}"
                    placeholder="Masukkan 16 digit nomor KK"
                    required
                >

                <small>
                    Masukkan nomor KK sesuai Kartu Keluarga.
                    Nomor KK harus terdiri dari 16 digit angka.
                </small>

            </div>


            <!-- KEPERLUAN -->

            <div class="form-group">

                <label>
                    DIPERLUKAN UNTUK
                </label>

                <textarea
                    name="keperluan"
                    placeholder="Contoh: Untuk pengajuan bantuan usaha"
                    required
                ><?php
                    echo isset($_POST['keperluan'])
                        ? htmlspecialchars($_POST['keperluan'])
                        : '';
                ?></textarea>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="ajukan"
                class="button"
            >
                AJUKAN SURAT
            </button>


        </form>

    </div>

</div>

</body>

</html>
```
