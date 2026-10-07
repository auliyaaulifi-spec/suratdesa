<?php

session_start();

/* =========================================================
   CEK LOGIN ADMIN
   ========================================================= */

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}


/* =========================================================
   KONEKSI DATABASE
   ========================================================= */

include "../config/koneksi.php";


/* =========================================================
   PENCARIAN
   ========================================================= */

$keyword = "";

if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}


/* =========================================================
   AMBIL DATA PENGAJUAN
   STATUS DIPROSES
   ========================================================= */

$query = "
    SELECT
        pengajuan_surat.id_pengajuan,
        masyarakat.nama_lengkap,
        masyarakat.nik,
        jenis_surat.nama_jenis,
        pengajuan_surat.tanggal_pengajuan,
        pengajuan_surat.status_pengajuan

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat

    WHERE
        pengajuan_surat.status_pengajuan = 'Diproses'

        AND (
            masyarakat.nama_lengkap LIKE '%$keyword%'
            OR masyarakat.nik LIKE '%$keyword%'
            OR jenis_surat.nama_jenis LIKE '%$keyword%'
        )

    ORDER BY
        pengajuan_surat.id_pengajuan DESC
";


$result = mysqli_query($koneksi, $query);


/* =========================================================
   CEK QUERY
   ========================================================= */

if (!$result) {
    die(
        "Query gagal: " .
        mysqli_error($koneksi)
    );
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Cetak Surat - Sistem Surat Desa
    </title>


    <style>

        /* =========================
           RESET
           ========================= */

        * {
            box-sizing: border-box;
        }


        /* =========================
           BODY
           ========================= */

        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f5f5f5;

            color: #222;

        }


        /* =========================
           HEADER
           ========================= */

        .header {

            width: 100%;

            height: 80px;

            background: #d9d9d9;

            display: flex;

            align-items: center;

            padding-left: 20px;

            position: fixed;

            top: 0;

            left: 0;

            z-index: 100;

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

            width: 240px;

            height: calc(100vh - 80px);

            background: #eeeeee;

            position: fixed;

            top: 80px;

            left: 0;

            padding-top: 20px;

            overflow-y: auto;

        }


        .sidebar a {

            display: block;

            width: 100%;

            padding: 13px 25px;

            text-decoration: none;

            color: #222;

            font-size: 15px;

        }


        .sidebar a:hover {

            background: #d9d9d9;

        }


        /* =========================
           CONTENT
           ========================= */

        .content {

            margin-left: 240px;

            padding: 110px 40px 40px 40px;

            min-height: 100vh;

        }


        .content h1 {

            margin: 0 0 10px 0;

            font-size: 28px;

        }


        .subtitle {

            margin-bottom: 25px;

            color: #555;

        }


        /* =========================
           SEARCH
           ========================= */

        .search-box {

            display: flex;

            margin-bottom: 25px;

        }


        .search-box input {

            width: 350px;

            height: 40px;

            padding: 10px;

            border: 1px solid #bbb;

            border-radius: 5px 0 0 5px;

            font-size: 14px;

        }


        .search-box button {

            width: 80px;

            height: 40px;

            border: none;

            background: #333;

            color: white;

            border-radius: 0 5px 5px 0;

            cursor: pointer;

        }


        .search-box button:hover {

            background: #555;

        }


        /* =========================
           TABLE
           ========================= */

        table {

            width: 100%;

            border-collapse: collapse;

            background: white;

        }


        table th,
        table td {

            border: 1px solid #ccc;

            padding: 12px;

            text-align: left;

            font-size: 14px;

        }


        table th {

            background: #e5e5e5;

            font-weight: bold;

        }


        /* =========================
           BUTTON CETAK
           ========================= */

        .btn-cetak {

            display: inline-block;

            padding: 7px 12px;

            background: #333;

            color: white;

            text-decoration: none;

            border-radius: 4px;

            font-size: 13px;

        }


        .btn-cetak:hover {

            background: #555;

        }


        /* =========================
           DATA KOSONG
           ========================= */

        .kosong {

            text-align: center;

            color: #777;

            padding: 20px;

        }

    </style>

</head>


<body>


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <div class="header">

        <img
            src="../assets/logo_desa.png"
            alt="Logo Desa Sampiran"
        >

        <div class="header-title">

            SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA

        </div>

    </div>



    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

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



    <!-- =====================================================
         CONTENT
         ===================================================== -->

    <div class="content">


        <h1>
            CETAK SURAT
        </h1>


        <div class="subtitle">

            Daftar surat yang siap diproses

        </div>



        <!-- =================================================
             SEARCH
             ================================================= -->

        <form
            method="GET"
            class="search-box"
        >

            <input
                type="text"
                name="keyword"
                placeholder="Cari Nama / NIK..."
                value="<?php
                    echo htmlspecialchars(
                        $keyword,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                ?>"
            >

            <button type="submit">
                CARI
            </button>

        </form>



        <!-- =================================================
             TABLE
             ================================================= -->

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

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php

                $no = 1;


                if (mysqli_num_rows($result) > 0) {


                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {


                        /* =================================================
                           TENTUKAN HALAMAN BERDASARKAN JENIS SURAT
                           ================================================= */

                        $nama_jenis_surat =
                            strtolower(
                                trim(
                                    $row["nama_jenis"] ?? ""
                                )
                            );


                        /*
                         * DEFAULT:
                         * Semua surat yang belum memiliki
                         * halaman khusus tetap menggunakan
                         * halaman Domisili/lama.
                         */

                        $url_cetak =
                            "detail_cetak_surat.php?id=" .
                            urlencode(
                                $row["id_pengajuan"]
                            );


                        /*
                         * SKU
                         *
                         * Jika nama jenis surat mengandung
                         * kata "usaha", arahkan ke buat_sku.php
                         */

                        if (
                            strpos(
                                $nama_jenis_surat,
                                "usaha"
                            ) !== false
                        ) {

                            $url_cetak =
                                "buat_sku.php?id=" .
                                urlencode(
                                    $row["id_pengajuan"]
                                );

                        }


                        /*
                         * SKTM
                         *
                         * Disiapkan untuk nanti.
                         * Jika file buat_sktm.php sudah dibuat,
                         * otomatis bisa digunakan.
                         */

                        elseif (
                            strpos(
                                $nama_jenis_surat,
                                "tidak mampu"
                            ) !== false
                        ) {

                            $url_cetak =
                                "buat_sktm.php?id=" .
                                urlencode(
                                    $row["id_pengajuan"]
                                );

                        }


                ?>


                        <tr>


                            <!-- NO -->

                            <td>

                                <?php
                                echo $no++;
                                ?>

                            </td>



                            <!-- NAMA -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["nama_lengkap"] ?? "",
                                    ENT_QUOTES,
                                    "UTF-8"
                                );

                                ?>

                            </td>



                            <!-- JENIS SURAT -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["nama_jenis"] ?? "",
                                    ENT_QUOTES,
                                    "UTF-8"
                                );

                                ?>

                            </td>



                            <!-- TANGGAL -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["tanggal_pengajuan"] ?? "",
                                    ENT_QUOTES,
                                    "UTF-8"
                                );

                                ?>

                            </td>



                            <!-- STATUS -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["status_pengajuan"] ?? "",
                                    ENT_QUOTES,
                                    "UTF-8"
                                );

                                ?>

                            </td>



                            <!-- AKSI -->

                            <td>

                                <a
                                    href="<?php
                                        echo htmlspecialchars(
                                            $url_cetak,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                    ?>"
                                    class="btn-cetak"
                                >

                                    CETAK

                                </a>

                            </td>


                        </tr>


                <?php

                    }


                } else {

                ?>


                    <tr>

                        <td
                            colspan="6"
                            class="kosong"
                        >

                            Belum ada surat yang siap diproses.

                        </td>

                    </tr>


                <?php

                }

                ?>


            </tbody>

        </table>


    </div>


</body>

</html>