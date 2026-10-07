<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$keyword = "";

if (isset($_GET["keyword"])) {
    $keyword = $_GET["keyword"];
}

$query = "
    SELECT
        id_masyarakat,
        nik,
        no_kk,
        nama_lengkap,
        alamat,
        no_hp,
        status_akun,
        created_at
    FROM masyarakat
    WHERE
        nama_lengkap LIKE '%$keyword%'
        OR nik LIKE '%$keyword%'
    ORDER BY id_masyarakat DESC
";

$result = mysqli_query($koneksi, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Masyarakat</title>

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

        /* =========================
           HEADER
        ========================= */

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

        /* =========================
           CONTENT
        ========================= */

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

        /* =========================
           SEARCH
        ========================= */

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input {
            width: 350px;
            height: 40px;
            padding: 0 12px;
            border: 1px solid #ccc;
            background: white;
            font-size: 13px;
        }

        .search-box button {
            height: 40px;
            padding: 0 20px;
            border: none;
            background: #333;
            color: white;
            cursor: pointer;
            font-size: 13px;
        }

        .search-box button:hover {
            background: #555;
        }

        /* =========================
           TABLE
        ========================= */

        .table-box {
            background: white;
            border: 1px solid #ddd;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #eeeeee;
            padding: 14px 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
            font-size: 13px;
        }

        table td {
            padding: 14px 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        /* =========================
           BUTTON LIHAT
        ========================= */

        .btn-lihat {
            display: inline-block;
            padding: 7px 14px;
            background: #333;
            color: white;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-lihat:hover {
            background: #555;
        }

        /* =========================
           DATA KOSONG
        ========================= */

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
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

    <h1>
        DATA MASYARAKAT
    </h1>

    <p class="subtitle">
        Daftar data masyarakat
    </p>


    <!-- SEARCH -->

    <form method="GET" class="search-box">

        <input
            type="text"
            name="keyword"
            placeholder="Cari Nama / NIK..."
            value="<?php echo htmlspecialchars($keyword); ?>"
        >

        <button type="submit">
            CARI
        </button>

    </form>


    <!-- TABLE -->

    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama Lengkap</th>

                    <th>NIK</th>

                    <th>No. KK</th>

                    <th>Alamat</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <tr>

                    <td>
                        <?php
                        echo sprintf("%02d", $no);
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars($row["nama_lengkap"]);
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars($row["nik"]);
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars($row["no_kk"]);
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars($row["alamat"]);
                        ?>
                    </td>

                    <td>

                        <a
                            href="detail_masyarakat.php?id=<?php echo $row["id_masyarakat"]; ?>"
                            class="btn-lihat"
                        >
                            LIHAT
                        </a>

                    </td>

                </tr>

                <?php

                        $no++;

                    }

                } else {

                ?>

                <tr>

                    <td
                        colspan="6"
                        class="empty"
                    >
                        Belum ada data masyarakat.
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