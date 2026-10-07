<?php

session_start();

if (!isset($_SESSION["id_masyarakat"])) {
    header("Location: login_masyarakat.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Pengajuan Surat - Sistem Informasi Pelayanan Surat Menyurat Desa
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
            margin-bottom: 30px;
            color: #555;
            font-size: 16px;
        }

        .cards {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .card {
            width: 280px;
            min-height: 180px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .card h2 {
            margin-top: 10px;
            font-size: 19px;
        }

        .card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        .btn:hover {
            background: #555;
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
            PENGAJUAN SURAT
        </h1>

        <div class="subtitle">
            Pilih Jenis Surat
        </div>


        <div class="cards">


            <!-- DOMISILI -->

            <div class="card">

                <h2>
                    SURAT KETERANGAN DOMISILI
                </h2>

                <p>
                    Pengajuan surat keterangan domisili masyarakat.
                </p>

                <a
                    href="form_domisili.php"
                    class="btn"
                >
                    AJUKAN
                </a>

            </div>


            <!-- SKU -->

            <div class="card">

                <h2>
                    SURAT KETERANGAN USAHA
                </h2>

                <p>
                    Pengajuan Surat Keterangan Usaha (SKU).
                </p>

                <a
                    href="form_sku.php"
                    class="btn"
                >
                    AJUKAN
                </a>

            </div>


            <!-- SKTM -->

            <div class="card">

                <h2>
                    SURAT KETERANGAN TIDAK MAMPU
                </h2>

                <p>
                    Pengajuan Surat Keterangan Tidak Mampu (SKTM).
                </p>

                <a
                    href="form_sktm.php"
                    class="btn"
                >
                    AJUKAN
                </a>

            </div>


        </div>

    </div>

</body>

</html>