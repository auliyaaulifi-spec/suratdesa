<?php

session_start();

if (!isset($_SESSION["id_masyarakat"])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$nama_masyarakat = $_SESSION["nama_masyarakat"];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Dashboard Masyarakat - Sistem Surat Desa</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            position: fixed;
            top: 0;
            left: 0;

            width: 100%;
            height: 80px;

            background: #e5e5e5;

            display: flex;
            align-items: center;

            padding-left: 20px;

            border-bottom: 1px solid #ccc;

            z-index: 10;
        }

        .header img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .header-title {
            margin-left: 15px;

            font-size: 20px;
            font-weight: bold;

            color: #222;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;

            top: 80px;
            left: 0;

            width: 240px;
            height: calc(100vh - 80px);

            background: #eeeeee;

            padding-top: 20px;

            border-right: 1px solid #ccc;
        }

        .sidebar a {
            display: block;

            padding: 13px 20px;

            color: #222;

            text-decoration: none;

            font-size: 14px;
        }

        .sidebar a:hover {
            background: #dcdcdc;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            margin-left: 240px;

            padding: 110px 35px 40px 35px;
        }

        .content h1 {
            margin-top: 0;

            font-size: 28px;
        }

        .welcome {
            margin-bottom: 30px;

            color: #555;

            font-size: 16px;
        }

        /* =========================
           CARDS
        ========================= */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;

            margin-bottom: 30px;
        }

        .card {
            background: white;

            border: 1px solid #ccc;

            padding: 25px;

            min-height: 150px;

            text-decoration: none;

            color: #222;

            display: flex;

            flex-direction: column;

            justify-content: center;

            transition: 0.2s;
        }

        .card:hover {
            background: #eeeeee;
        }

        .card-title {
            font-size: 18px;

            font-weight: bold;

            margin-bottom: 10px;
        }

        .card-text {
            color: #666;

            font-size: 14px;

            line-height: 1.5;
        }

        /* =========================
           INFORMASI
        ========================= */

        .info-box {
            background: white;

            border: 1px solid #ccc;

            padding: 25px;
        }

        .info-box h2 {
            margin-top: 0;

            font-size: 20px;
        }

        .info-box p {
            color: #555;

            line-height: 1.6;
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
     SIDEBAR
========================= -->

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


<!-- =========================
     CONTENT
========================= -->

<div class="content">

    <h1>
        DASHBOARD MASYARAKAT
    </h1>

    <div class="welcome">

        Selamat Datang,
        <strong>
            <?php
            echo htmlspecialchars(
                $nama_masyarakat
            );
            ?>
        </strong>

    </div>


    <!-- =========================
         CARDS
    ========================= -->

    <div class="cards">


        <a
            href="pengajuan_surat.php"
            class="card"
        >

            <div class="card-title">
                AJUKAN SURAT
            </div>

            <div class="card-text">

                Ajukan surat administrasi desa
                secara online.

            </div>

        </a>


        <a
            href="status_pengajuan.php"
            class="card"
        >

            <div class="card-title">
                STATUS PENGAJUAN
            </div>

            <div class="card-text">

                Lihat status pengajuan surat
                yang sedang diproses.

            </div>

        </a>


        <a
            href="riwayat_surat.php"
            class="card"
        >

            <div class="card-title">
                RIWAYAT SURAT
            </div>

            <div class="card-text">

                Lihat riwayat surat yang
                pernah diajukan.

            </div>

        </a>


        <a
            href="profil.php"
            class="card"
        >

            <div class="card-title">
                PROFIL
            </div>

            <div class="card-text">

                Lihat dan kelola informasi
                profil masyarakat.

            </div>

        </a>


    </div>


    <!-- =========================
         INFORMASI
    ========================= -->

    <div class="info-box">

        <h2>
            INFORMASI
        </h2>

        <p>

            Gunakan menu <strong>AJUKAN SURAT</strong>
            untuk mengajukan pelayanan surat
            administrasi desa.

        </p>

        <p>

            Setelah pengajuan dikirim,
            Anda dapat memantau prosesnya
            melalui menu <strong>STATUS PENGAJUAN</strong>.

        </p>

        <p>

            Apabila surat telah selesai diproses,
            Anda dapat mengunduh surat melalui
            menu tersebut.

        </p>

    </div>

</div>

</body>

</html>