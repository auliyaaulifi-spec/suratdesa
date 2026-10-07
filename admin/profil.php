<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id_admin = $_SESSION["id_admin"];

$pesan = "";
$error = "";

/* =========================
   PROSES UPDATE PROFIL
========================= */

if (isset($_POST["simpan"])) {

    $nama_admin = mysqli_real_escape_string(
        $koneksi,
        $_POST["nama_admin"]
    );

    $username = mysqli_real_escape_string(
        $koneksi,
        $_POST["username"]
    );

    $email = mysqli_real_escape_string(
        $koneksi,
        $_POST["email"]
    );

    $no_telepon = mysqli_real_escape_string(
        $koneksi,
        $_POST["no_telepon"]
    );

    $query_update = "
        UPDATE admin
        SET
            nama_admin = '$nama_admin',
            username = '$username',
            email = '$email',
            no_telepon = '$no_telepon'
        WHERE id_admin = '$id_admin'
    ";

    if (mysqli_query($koneksi, $query_update)) {

        $_SESSION["nama_admin"] = $nama_admin;
        $_SESSION["username"] = $username;

        $pesan = "Profil berhasil diperbarui.";

    } else {

        $error = "Profil gagal diperbarui.";
    }
}


/* =========================
   AMBIL DATA ADMIN
========================= */

$query_admin = "
    SELECT
        nama_admin,
        username,
        email,
        no_telepon
    FROM admin
    WHERE id_admin = '$id_admin'
";

$result_admin = mysqli_query(
    $koneksi,
    $query_admin
);

$data_admin = mysqli_fetch_assoc(
    $result_admin
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Profil Admin</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
            font-size: 14px;
        }


        /* =========================
           HEADER
        ========================= */

        .header {
            height: 80px;
            background: #ffffff;
            border-bottom: 1px solid #ddd;

            display: flex;
            align-items: center;

            padding: 0 30px;
        }

        .header img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            margin-right: 12px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;

            left: 0;
            top: 80px;

            width: 240px;
            height: calc(100vh - 80px);

            background: #ffffff;

            border-right: 1px solid #ddd;

            padding-top: 20px;
        }

        .sidebar a {
            display: block;

            text-decoration: none;

            color: #333;

            font-size: 14px;

            padding: 12px 30px;

            margin-bottom: 2px;
        }

        .sidebar a:hover {
            background: #f0f0f0;
        }


        /* =========================
           CONTENT
        ========================= */

        .content {

            margin-left: 240px;

            padding: 35px 40px;

            min-height: calc(100vh - 80px);
        }


        /* =========================
           JUDUL
        ========================= */

        .content h1 {

            font-size: 28px;

            font-weight: bold;

            margin-bottom: 8px;
        }

        .subtitle {

            font-size: 16px;

            color: #666;

            margin-bottom: 25px;
        }


        /* =========================
           BOX PROFIL
        ========================= */

        .box {

            background: #ffffff;

            border: 1px solid #ddd;

            border-radius: 6px;

            padding: 25px;

            max-width: 820px;
        }

        .box h2 {

            font-size: 20px;

            margin-bottom: 20px;

            font-weight: bold;
        }


        /* =========================
           FORM
        ========================= */

        .form-group {

            margin-bottom: 16px;
        }

        .form-group label {

            display: block;

            font-size: 14px;

            font-weight: bold;

            margin-bottom: 7px;
        }

        .form-group input {

            width: 100%;

            padding: 10px 12px;

            border: 1px solid #ccc;

            border-radius: 4px;

            font-size: 14px;

            outline: none;
        }

        .form-group input:focus {

            border-color: #888;
        }


        /* =========================
           BUTTON
        ========================= */

        .btn {

            display: inline-block;

            border: none;

            background: #333;

            color: #ffffff;

            padding: 10px 18px;

            border-radius: 4px;

            font-size: 14px;

            cursor: pointer;

            margin-top: 5px;
        }

        .btn:hover {

            background: #222;
        }


        /* =========================
           PESAN
        ========================= */

        .success {

            background: #e8f5e9;

            border: 1px solid #b7dfb9;

            color: #2e7d32;

            padding: 10px 12px;

            border-radius: 4px;

            font-size: 14px;

            margin-bottom: 18px;

            max-width: 820px;
        }

        .error {

            background: #ffebee;

            border: 1px solid #efb0b0;

            color: #c62828;

            padding: 10px 12px;

            border-radius: 4px;

            font-size: 14px;

            margin-bottom: 18px;

            max-width: 820px;
        }

    </style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<div class="header">

    <img
        src="../assets/logo_desa.png"
        alt="Logo Desa Sampiran"
    >

    <div class="header-title">
        SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA
    </div>

</div>


<!-- =========================
     SIDEBAR ADMIN
========================= -->

<div class="sidebar">

    <a href="dashboard.php">
        DASHBOARD
    </a>

    <a href="data_masyarakat.php">
        DATA MASYARAKAT
    </a>

    <a href="pengajuan_surat.php">
        PENGAJUAN SURAT
    </a>

    <a href="verifikasi_pengajuan.php">
        VERIFIKASI PENGAJUAN
    </a>

    <a href="cetak_surat.php">
        CETAK SURAT
    </a>

    <a href="arsip_surat.php">
        ARSIP SURAT
    </a>

    <a href="nomor_surat.php">
        NOMOR SURAT
    </a>

    <a href="profil.php">
        PROFIL
    </a>

    <a href="../logout.php">
        LOGOUT
    </a>

</div>


<!-- =========================
     CONTENT
========================= -->

<div class="content">

    <h1>PROFIL ADMIN</h1>

    <div class="subtitle">
        Informasi akun administrator
    </div>


    <?php if ($pesan != "") { ?>

        <div class="success">
            <?php echo $pesan; ?>
        </div>

    <?php } ?>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <div class="box">

        <h2>DATA PROFIL</h2>


        <form method="POST">


            <!-- NAMA ADMIN -->

            <div class="form-group">

                <label>
                    Nama Admin
                </label>

                <input
                    type="text"
                    name="nama_admin"
                    value="<?php
                        echo htmlspecialchars(
                            $data_admin["nama_admin"]
                        );
                    ?>"
                    placeholder="Masukkan nama admin"
                    required
                >

            </div>


            <!-- USERNAME -->

            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    value="<?php
                        echo htmlspecialchars(
                            $data_admin["username"]
                        );
                    ?>"
                    placeholder="Masukkan username"
                    required
                >

            </div>


            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?php
                        echo htmlspecialchars(
                            $data_admin["email"]
                        );
                    ?>"
                    placeholder="Masukkan email"
                >

            </div>


            <!-- NOMOR TELEPON -->

            <div class="form-group">

                <label>
                    No. Telepon
                </label>

                <input
                    type="text"
                    name="no_telepon"
                    value="<?php
                        echo htmlspecialchars(
                            $data_admin["no_telepon"]
                        );
                    ?>"
                    placeholder="Masukkan nomor telepon"
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="simpan"
                class="btn"
            >
                SIMPAN
            </button>


        </form>

    </div>

</div>


</body>

</html>