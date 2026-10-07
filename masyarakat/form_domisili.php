
<?php

session_start();

if (!isset($_SESSION["id_masyarakat"])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$id_masyarakat = $_SESSION["id_masyarakat"];


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


$pesan = "";


/* =========================
   PROSES PENGAJUAN
========================= */

if (isset($_POST["ajukan"])) {

    $no_kk = trim($_POST["no_kk"]);

    $keperluan = mysqli_real_escape_string(
        $koneksi,
        trim($_POST["keperluan"])
    );


    /* =========================
       VALIDASI NO. KK
    ========================= */

    if (!preg_match('/^[0-9]{16}$/', $no_kk)) {

        $pesan = "Nomor KK harus terdiri dari 16 digit angka.";

    } else {

        $no_kk = mysqli_real_escape_string(
            $koneksi,
            $no_kk
        );

        $tanggal_pengajuan = date("Y-m-d");

        $status_pengajuan = "Menunggu";


        /*
         * id_jenis_surat = 1
         * berdasarkan data jenis surat Domisili
         */

        $id_jenis_surat = 1;


        /* =========================
           SIMPAN PENGAJUAN
        ========================= */

        $query = mysqli_query(
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
                '$id_jenis_surat',
                '$tanggal_pengajuan',
                '$keperluan',
                '$status_pengajuan'
            )"
        );


        if ($query) {

            header("Location: status_pengajuan.php");
            exit;

        } else {

            $pesan = "Pengajuan surat gagal disimpan: "
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
        Form Pengajuan Domisili
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .header {
            height: 80px;
            background: #d9d9d9;
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .header img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .header-title {
            margin-left: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #222;
        }

        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: 240px;
            height: calc(100vh - 80px);
            background: #eeeeee;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: #222;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #d5d5d5;
        }

        .content {
            margin-left: 240px;
            padding: 35px;
        }

        h1 {
            margin-top: 0;
            font-size: 28px;
        }

        .subtitle {
            margin-bottom: 25px;
            color: #555;
        }

        .form-box {
            background: white;
            padding: 30px;
            max-width: 900px;
            border-radius: 5px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            font-family: Arial, sans-serif;
        }

        input[readonly] {
            background: #eeeeee;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group small {
            display: block;
            margin-top: 6px;
            color: #666;
            font-size: 12px;
        }

        .btn {
            padding: 12px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #555;
        }

        .pesan {
            margin-bottom: 20px;
            padding: 12px;
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            border-radius: 4px;
            max-width: 900px;
        }

        .info {
            margin-bottom: 20px;
            padding: 14px;
            background: #f1f5f9;
            border-left: 4px solid #333;
            color: #444;
            max-width: 900px;
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

    <div class="header-title">
        SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA
    </div>

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
        FORM PENGAJUAN SURAT KETERANGAN DOMISILI
    </h1>

    <div class="subtitle">
        Silakan lengkapi data pengajuan surat.
    </div>


    <?php if ($pesan != "") { ?>

        <div class="pesan">
            <?php echo htmlspecialchars($pesan); ?>
        </div>

    <?php } ?>


    <div class="info">

        <strong>Perhatian:</strong><br>

        Silakan masukkan nomor KK sesuai dengan
        Kartu Keluarga yang sebenarnya.

    </div>


    <div class="form-box">

        <form method="POST">


            <!-- NAMA LENGKAP -->

            <div class="form-group">

                <label>
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $data_masyarakat["nama_lengkap"]
                        );
                    ?>"
                    readonly
                >

            </div>


            <!-- NIK -->

            <div class="form-group">

                <label>
                    NIK
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $data_masyarakat["nik"]
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
                        echo isset($_POST["no_kk"])
                            ? htmlspecialchars($_POST["no_kk"])
                            : "";
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


            <!-- ALAMAT -->

            <div class="form-group">

                <label>
                    Alamat
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $data_masyarakat["alamat"]
                        );
                    ?>"
                    readonly
                >

            </div>


            <!-- KEPERLUAN -->

            <div class="form-group">

                <label>
                    Diperlukan Untuk
                </label>

                <textarea
                    name="keperluan"
                    placeholder="Contoh: Untuk keperluan administrasi kependudukan"
                    required
                ><?php
                    echo isset($_POST["keperluan"])
                        ? htmlspecialchars($_POST["keperluan"])
                        : "";
                ?></textarea>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="ajukan"
                class="btn"
            >
                AJUKAN SURAT
            </button>


        </form>

    </div>

</div>

</body>

</html>
```
