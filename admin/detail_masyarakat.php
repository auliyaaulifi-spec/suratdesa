<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

if (!isset($_GET["id"])) {
    header("Location: data_masyarakat.php");
    exit;
}

$id = $_GET["id"];

$query = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE id_masyarakat = '$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data masyarakat tidak ditemukan.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Masyarakat</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .header {
            height: 80px;
            background: #d9d9d9;
            display: flex;
            align-items: center;
            padding-left: 25px;
        }

        .header img {
            width: 55px;
            height: 55px;
            object-fit: contain;
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
            padding: 14px 25px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #d9d9d9;
        }

        .content {
            margin-left: 240px;
            padding: 35px;
        }

        h1 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        .box {
            background: white;
            padding: 25px;
            border: 1px solid #ddd;
            max-width: 900px;
        }

        .box h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .data-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .label {
            width: 180px;
            font-weight: bold;
        }

        .value {
            flex: 1;
        }

        .back-button {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            font-size: 13px;
        }

        .back-button:hover {
            background: #555;
        }
    </style>
</head>

<body>

<div class="header">
    <img src="../assets/logo_desa.png" alt="Logo Desa Sampiran">
</div>

<div class="sidebar">

    <a href="dashboard.php">DASHBOARD</a>
    <a href="data_masyarakat.php">DATA MASYARAKAT</a>
    <a href="pengajuan_surat.php">PENGAJUAN SURAT</a>
    <a href="verifikasi_pengajuan.php">VERIFIKASI PENGAJUAN</a>
    <a href="cetak_surat.php">CETAK SURAT</a>
    <a href="arsip_surat.php">ARSIP SURAT</a>
    <a href="nomor_surat.php">NOMOR SURAT</a>
    <a href="profil.php">PROFIL</a>
    <a href="../logout.php">LOGOUT</a>

</div>

<div class="content">

    <h1>DETAIL MASYARAKAT</h1>

    <p class="subtitle">
        Informasi lengkap data masyarakat
    </p>

    <div class="box">

        <h2>DATA MASYARAKAT</h2>

        <div class="data-row">
            <div class="label">Nama Lengkap</div>
            <div class="value">
                <?php echo htmlspecialchars($data["nama_lengkap"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">NIK</div>
            <div class="value">
                <?php echo htmlspecialchars($data["nik"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">No. KK</div>
            <div class="value">
                <?php echo htmlspecialchars($data["no_kk"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">Alamat</div>
            <div class="value">
                <?php echo htmlspecialchars($data["alamat"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">No. HP</div>
            <div class="value">
                <?php echo htmlspecialchars($data["no_hp"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">Username</div>
            <div class="value">
                <?php echo htmlspecialchars($data["username"]); ?>
            </div>
        </div>

        <div class="data-row">
            <div class="label">Status Akun</div>
            <div class="value">
                <?php echo htmlspecialchars($data["status_akun"]); ?>
            </div>
        </div>

        <a href="data_masyarakat.php" class="back-button">
            KEMBALI
        </a>

    </div>

</div>

</body>
</html>