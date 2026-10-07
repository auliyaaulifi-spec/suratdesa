<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =========================================================
   DATA DASHBOARD
========================================================= */

/* Jumlah seluruh masyarakat */
$query_masyarakat = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM masyarakat
");

$data_masyarakat = mysqli_fetch_assoc($query_masyarakat);
$total_masyarakat = $data_masyarakat["total"];


/* Jumlah seluruh pengajuan masuk */
$query_pengajuan = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pengajuan_surat
");

$data_pengajuan = mysqli_fetch_assoc($query_pengajuan);
$total_pengajuan = $data_pengajuan["total"];


/* Jumlah surat yang sedang diproses */
$query_diproses = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pengajuan_surat
    WHERE status_pengajuan = 'Diproses'
");

$data_diproses = mysqli_fetch_assoc($query_diproses);
$total_diproses = $data_diproses["total"];


/* Jumlah surat yang sudah selesai */
$query_selesai = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pengajuan_surat
    WHERE status_pengajuan = 'Selesai'
");

$data_selesai = mysqli_fetch_assoc($query_selesai);
$total_selesai = $data_selesai["total"];


/* =========================================================
   PENGAJUAN TERBARU
========================================================= */

$query_terbaru = mysqli_query($koneksi, "

    SELECT
        pengajuan_surat.id_pengajuan,
        masyarakat.nama_lengkap,
        jenis_surat.nama_jenis,
        pengajuan_surat.tanggal_pengajuan,
        pengajuan_surat.status_pengajuan

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat = masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat = jenis_surat.id_jenis_surat

    ORDER BY pengajuan_surat.id_pengajuan DESC

    LIMIT 10

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Sistem Surat Desa</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            color: #111111;
        }


        /* =========================================
           HEADER
        ========================================= */

        .header {
            width: 100%;
            height: 80px;

            background: #d9d9d9;

            display: flex;
            align-items: center;

            padding: 0 30px;
        }

        .logo {
            width: 50px;
            height: 50px;

            background: #ffffff;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-right: 15px;

            overflow: hidden;

            flex-shrink: 0;
        }

        .logo img {
            width: 45px;
            height: 45px;

            object-fit: contain;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
        }


        /* =========================================
           SIDEBAR
        ========================================= */

        .sidebar {
            position: fixed;

            left: 0;
            top: 80px;

            width: 240px;
            height: calc(100vh - 80px);

            background: #eeeeee;

            padding-top: 20px;
        }

        .sidebar a {
            display: block;

            width: 100%;

            padding: 14px 25px;

            text-decoration: none;

            color: #111111;

            font-size: 14px;

            font-weight: bold;
        }

        .sidebar a:hover {
            background: #dcdcdc;
        }


        /* =========================================
           CONTENT
        ========================================= */

        .content {
            margin-left: 240px;

            padding: 35px;
        }

        .content h1 {
            margin-top: 0;
            margin-bottom: 18px;

            font-size: 26px;
        }

        .welcome {
            font-size: 18px;

            margin-bottom: 38px;
        }


        /* =========================================
           DASHBOARD CARDS
        ========================================= */

        .cards {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 25px;

            margin-bottom: 45px;
        }

        .card {
            min-height: 168px;

            background: #d9d9d9;

            border-radius: 12px;

            padding: 28px 30px;
        }

        .card-title {
            font-size: 16px;

            font-weight: bold;

            margin-bottom: 28px;
        }

        .card-number {
            font-size: 42px;

            font-weight: bold;
        }


        /* =========================================
           PENGAJUAN TERBARU
        ========================================= */

        .section-title {
            font-size: 24px;

            font-weight: bold;

            margin-bottom: 20px;
        }

        .table-box {
            width: 100%;

            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            background: #ffffff;
        }

        th {
            background: #d9d9d9;

            padding: 16px 18px;

            text-align: left;

            font-size: 14px;

            font-weight: bold;
        }

        td {
            padding: 16px 18px;

            border-bottom: 1px solid #dddddd;

            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1100px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 700px) {

            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
                padding: 25px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .header-title {
                font-size: 16px;
            }

        }

    </style>

</head>


<body>


    <!-- =========================================
         HEADER
    ========================================= -->

    <div class="header">

        <div class="logo">

            <img
                src="../assets/logo_desa.png"
                alt="Logo Desa Sampiran"
            >

        </div>


        <div class="header-title">

            SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA

        </div>

    </div>



    <!-- =========================================
         SIDEBAR
    ========================================= -->

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



    <!-- =========================================
         CONTENT
    ========================================= -->

    <div class="content">


        <h1>
            DASHBOARD ADMIN
        </h1>


        <div class="welcome">

            Selamat Datang,
            <?php

            if (isset($_SESSION["nama_admin"])) {

                echo htmlspecialchars($_SESSION["nama_admin"]);

            } else {

                echo "Admin Desa";

            }

            ?>

        </div>



        <!-- =====================================
             DASHBOARD CARDS
        ===================================== -->

        <div class="cards">


            <!-- DATA MASYARAKAT -->

            <div class="card">

                <div class="card-title">
                    DATA MASYARAKAT
                </div>

                <div class="card-number">

                    <?php
                    echo $total_masyarakat;
                    ?>

                </div>

            </div>



            <!-- PENGAJUAN MASUK -->

            <div class="card">

                <div class="card-title">
                    PENGAJUAN MASUK
                </div>

                <div class="card-number">

                    <?php
                    echo $total_pengajuan;
                    ?>

                </div>

            </div>



            <!-- SURAT DIPROSES -->

            <div class="card">

                <div class="card-title">
                    SURAT DIPROSES
                </div>

                <div class="card-number">

                    <?php
                    echo $total_diproses;
                    ?>

                </div>

            </div>



            <!-- SURAT SELESAI -->

            <div class="card">

                <div class="card-title">
                    SURAT SELESAI
                </div>

                <div class="card-number">

                    <?php
                    echo $total_selesai;
                    ?>

                </div>

            </div>


        </div>



        <!-- =====================================
             PENGAJUAN TERBARU
        ===================================== -->

        <div class="section-title">

            Pengajuan Terbaru

        </div>


        <div class="table-box">

            <table>

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Jenis Surat
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    $no = 1;

                    if (mysqli_num_rows($query_terbaru) > 0) {

                        while ($row = mysqli_fetch_assoc($query_terbaru)) {

                    ?>

                            <tr>

                                <td>
                                    <?php
                                    echo str_pad($no, 2, "0", STR_PAD_LEFT);
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row["nama_lengkap"] ?? "-"
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row["nama_jenis"] ?? "-"
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php

                                    if (!empty($row["tanggal_pengajuan"])) {

                                        echo date(
                                            "d-m-Y",
                                            strtotime($row["tanggal_pengajuan"])
                                        );

                                    } else {

                                        echo "-";

                                    }

                                    ?>
                                </td>

                                <td>

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $row["status_pengajuan"]
                                        );
                                        ?>
                                    </strong>

                                </td>

                            </tr>

                    <?php

                            $no++;

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="5" style="text-align:center;">

                                Belum ada pengajuan surat.

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>


    </div>


</body>

</html>