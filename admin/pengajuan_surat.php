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


/* =========================
   QUERY DATA PENGAJUAN
========================= */

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
        masyarakat.nama_lengkap LIKE '%$keyword%'
        OR masyarakat.nik LIKE '%$keyword%'
        OR jenis_surat.nama_jenis LIKE '%$keyword%'

    ORDER BY pengajuan_surat.id_pengajuan DESC
";

$result = mysqli_query($koneksi, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Pengajuan Surat</title>

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
           STATUS
        ========================= */

        .status {
            display: inline-block;
            padding: 6px 10px;
            font-size: 11px;
            border: 1px solid #ccc;
            background: #f5f5f5;
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
        PENGAJUAN SURAT
    </h1>

    <p class="subtitle">
        Daftar pengajuan surat dari masyarakat
    </p>


    <!-- =========================
         SEARCH
    ========================= -->

    <form
        method="GET"
        class="search-box"
    >

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


    <!-- =========================
         TABLE
    ========================= -->

    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th>Jenis Surat</th>

                    <th>Tanggal</th>

                    <th>Status</th>

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
                        echo htmlspecialchars(
                            $row["tanggal_pengajuan"] ?? "-"
                        );
                        ?>
                    </td>

                    <td>

                        <span class="status">

                            <?php
                            echo htmlspecialchars(
                                $row["status_pengajuan"] ?? "-"
                            );
                            ?>

                        </span>

                    </td>

                    <td>

                        <a
                            href="detail_pengajuan.php?id=<?php echo $row["id_pengajuan"]; ?>"
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
                        Belum ada data pengajuan surat.
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