<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";


/* =====================================================
   CEK ID PENGAJUAN
===================================================== */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: pengajuan_surat.php");
    exit;
}

$id_pengajuan = (int) $_GET["id"];


/* =====================================================
   PROSES VERIFIKASI
===================================================== */

if (isset($_POST["verifikasi"])) {

    $catatan = mysqli_real_escape_string(
        $koneksi,
        $_POST["catatan"] ?? ""
    );

    $id_admin = $_SESSION["id_admin"];

    $update = mysqli_query(
        $koneksi,
        "UPDATE pengajuan_surat
         SET
            id_admin = '$id_admin',
            status_pengajuan = 'Diproses',
            catatan = '$catatan'
         WHERE id_pengajuan = '$id_pengajuan'"
    );

    if ($update) {

        header(
            "Location: verifikasi_pengajuan.php?id=" .
            $id_pengajuan .
            "&pesan=verifikasi"
        );

        exit;

    } else {

        $pesan_error = "Data gagal diverifikasi.";

    }
}


/* =====================================================
   PROSES PENOLAKAN
===================================================== */

if (isset($_POST["tolak"])) {

    $catatan = mysqli_real_escape_string(
        $koneksi,
        $_POST["catatan"] ?? ""
    );

    $id_admin = $_SESSION["id_admin"];

    $update = mysqli_query(
        $koneksi,
        "UPDATE pengajuan_surat
         SET
            id_admin = '$id_admin',
            status_pengajuan = 'Ditolak',
            catatan = '$catatan'
         WHERE id_pengajuan = '$id_pengajuan'"
    );

    if ($update) {

        header(
            "Location: verifikasi_pengajuan.php?id=" .
            $id_pengajuan .
            "&pesan=ditolak"
        );

        exit;

    } else {

        $pesan_error = "Data gagal ditolak.";

    }
}


/* =====================================================
   AMBIL DATA PENGAJUAN
=====================================================

   SUMBER NO. KK:

   1. Untuk SKTM:
      data_sktm.nomor_kk

   2. Untuk SKU dan DOMISILI:
      pengajuan_surat.no_kk

   3. Jika keduanya kosong:
      masyarakat.no_kk

===================================================== */

$query = "
    SELECT

        pengajuan_surat.id_pengajuan,

        masyarakat.nama_lengkap,

        masyarakat.nik,

        masyarakat.alamat,

        masyarakat.no_hp,

        pengajuan_surat.no_kk AS no_kk_pengajuan,

        data_sktm.nomor_kk AS nomor_kk_sktm,

        jenis_surat.nama_jenis,

        pengajuan_surat.tanggal_pengajuan,

        pengajuan_surat.keperluan,

        pengajuan_surat.status_pengajuan,

        pengajuan_surat.catatan

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat

    LEFT JOIN data_sktm
        ON pengajuan_surat.id_pengajuan =
           data_sktm.id_pengajuan

    WHERE pengajuan_surat.id_pengajuan =
          '$id_pengajuan'

    LIMIT 1
";


$result = mysqli_query($koneksi, $query);


/* =====================================================
   CEK QUERY
===================================================== */

if (!$result) {

    die(
        "Query gagal dijalankan: "
        . mysqli_error($koneksi)
    );

}


/* =====================================================
   AMBIL DATA
===================================================== */

$data = mysqli_fetch_assoc($result);


/* =====================================================
   CEK DATA
===================================================== */

if (!$data) {

    echo "Data pengajuan surat tidak ditemukan.";
    exit;

}


/* =====================================================
   TENTUKAN NO. KK YANG DITAMPILKAN
=====================================================

   Prioritas:

   1. nomor_kk dari data_sktm
   2. no_kk dari pengajuan_surat
   3. no_kk dari masyarakat

===================================================== */

$no_kk = "";


/* -----------------------------------------------------
   PRIORITAS 1
   NO. KK SKTM
----------------------------------------------------- */

if (
    isset($data["nomor_kk_sktm"]) &&
    trim($data["nomor_kk_sktm"]) !== ""
) {

    $no_kk = trim($data["nomor_kk_sktm"]);

}


/* -----------------------------------------------------
   PRIORITAS 2
   NO. KK DARI PENGAJUAN
   Untuk SKU dan Domisili
----------------------------------------------------- */

if (
    $no_kk === "" &&
    isset($data["no_kk_pengajuan"]) &&
    trim($data["no_kk_pengajuan"]) !== ""
) {

    $no_kk = trim($data["no_kk_pengajuan"]);

}


/* -----------------------------------------------------
   PRIORITAS 3
   NO. KK DARI DATA MASYARAKAT
----------------------------------------------------- */

if ($no_kk === "") {

    $query_kk_masyarakat = mysqli_query(
        $koneksi,
        "SELECT no_kk
         FROM masyarakat
         WHERE id_masyarakat = (
             SELECT id_masyarakat
             FROM pengajuan_surat
             WHERE id_pengajuan = '$id_pengajuan'
         )
         LIMIT 1"
    );

    if ($query_kk_masyarakat) {

        $data_kk_masyarakat =
            mysqli_fetch_assoc($query_kk_masyarakat);

        if (
            $data_kk_masyarakat &&
            isset($data_kk_masyarakat["no_kk"]) &&
            trim($data_kk_masyarakat["no_kk"]) !== ""
        ) {

            $no_kk =
                trim($data_kk_masyarakat["no_kk"]);

        }

    }

}


/* -----------------------------------------------------
   JIKA TETAP KOSONG
----------------------------------------------------- */

if ($no_kk === "") {

    $no_kk = "-";

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Verifikasi Pengajuan
    </title>


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
            overflow-y: auto;
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
           BOX
        ========================= */

        .box {
            background: white;
            border: 1px solid #ddd;
            padding: 25px;
            max-width: 950px;
            margin-bottom: 20px;
        }

        .box-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }


        /* =========================
           DATA ROW
        ========================= */

        .data-row {
            display: flex;
            border-bottom: 1px solid #eeeeee;
            padding: 13px 0;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .label {
            width: 200px;
            min-width: 200px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        .value {
            flex: 1;
            font-size: 14px;
            color: #555;
            word-break: break-word;
        }


        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-block;
            padding: 7px 12px;
            border: 1px solid #ccc;
            background: #f5f5f5;
            font-size: 12px;
        }


        /* =========================
           CATATAN
        ========================= */

        .catatan {
            width: 100%;
            min-height: 100px;
            padding: 12px;
            border: 1px solid #ccc;
            resize: vertical;
            font-size: 13px;
        }


        /* =========================
           BUTTON
        ========================= */

        .button-area {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-verifikasi {
            background: #333;
            color: white;
        }

        .btn-tolak {
            background: #777;
            color: white;
        }

        .btn-kembali {
            background: #aaa;
            color: white;
        }

        .btn:hover {
            opacity: 0.85;
        }


        /* =========================
           PESAN
        ========================= */

        .pesan {
            background: #e8e8e8;
            border: 1px solid #ccc;
            padding: 12px 15px;
            margin-bottom: 20px;
            max-width: 950px;
            font-size: 13px;
        }

        .error {
            background: #f2f2f2;
            border: 1px solid #aaa;
            padding: 12px 15px;
            margin-bottom: 20px;
            max-width: 950px;
            font-size: 13px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 800px) {

            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
                padding: 20px;
            }

            .label {
                width: 160px;
                min-width: 160px;
            }

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

    <a href="verifikasi_pengajuan.php?id=<?php echo $id_pengajuan; ?>">
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
        VERIFIKASI PENGAJUAN
    </h1>

    <p class="subtitle">
        Detail pengajuan surat dari masyarakat
    </p>


    <?php if (
        isset($_GET["pesan"]) &&
        $_GET["pesan"] == "verifikasi"
    ) { ?>

        <div class="pesan">
            Pengajuan berhasil diverifikasi dan status menjadi Diproses.
        </div>

    <?php } ?>


    <?php if (
        isset($_GET["pesan"]) &&
        $_GET["pesan"] == "ditolak"
    ) { ?>

        <div class="pesan">
            Pengajuan berhasil ditolak.
        </div>

    <?php } ?>


    <?php if (isset($pesan_error)) { ?>

        <div class="error">

            <?php
            echo htmlspecialchars($pesan_error);
            ?>

        </div>

    <?php } ?>


    <!-- =========================
         INFORMASI PENGAJUAN
    ========================= -->

    <div class="box">

        <div class="box-title">
            INFORMASI PENGAJUAN
        </div>


        <!-- NAMA -->

        <div class="data-row">

            <div class="label">
                Nama Lengkap
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars(
                    $data["nama_lengkap"] ?? "-"
                );
                ?>

            </div>

        </div>


        <!-- NIK -->

        <div class="data-row">

            <div class="label">
                NIK
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars(
                    $data["nik"] ?? "-"
                );
                ?>

            </div>

        </div>


        <!-- NO KK -->

        <div class="data-row">

            <div class="label">
                No. KK
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars($no_kk);
                ?>

            </div>

        </div>


        <!-- NO HP -->

        <div class="data-row">

            <div class="label">
                No. HP
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars(
                    $data["no_hp"] ?? "-"
                );
                ?>

            </div>

        </div>


        <!-- JENIS SURAT -->

        <div class="data-row">

            <div class="label">
                Jenis Surat
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars(
                    $data["nama_jenis"] ?? "-"
                );
                ?>

            </div>

        </div>


        <!-- TANGGAL -->

        <div class="data-row">

            <div class="label">
                Tanggal Pengajuan
            </div>

            <div class="value">

                <?php
                echo htmlspecialchars(
                    $data["tanggal_pengajuan"] ?? "-"
                );
                ?>

            </div>

        </div>


        <!-- ALAMAT -->

        <div class="data-row">

            <div class="label">
                Alamat
            </div>

            <div class="value">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $data["alamat"] ?? "-"
                    )
                );
                ?>

            </div>

        </div>


        <!-- KEPERLUAN -->

        <div class="data-row">

            <div class="label">
                Keperluan
            </div>

            <div class="value">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $data["keperluan"] ?? "-"
                    )
                );
                ?>

            </div>

        </div>


        <!-- STATUS -->

        <div class="data-row">

            <div class="label">
                Status Pengajuan
            </div>

            <div class="value">

                <span class="status">

                    <?php
                    echo htmlspecialchars(
                        $data["status_pengajuan"] ?? "-"
                    );
                    ?>

                </span>

            </div>

        </div>


    </div>


    <!-- =========================
         CATATAN ADMIN
    ========================= -->

    <div class="box">

        <div class="box-title">
            CATATAN ADMIN
        </div>

        <form method="POST">

            <textarea
                name="catatan"
                class="catatan"
                placeholder="Masukkan catatan jika diperlukan..."
            ><?php
                echo htmlspecialchars(
                    $data["catatan"] ?? ""
                );
            ?></textarea>


            <div class="button-area">

                <a
                    href="pengajuan_surat.php"
                    class="btn btn-kembali"
                >
                    KEMBALI
                </a>


                <?php

                if (
                    $data["status_pengajuan"] == "Menunggu"
                ) {

                ?>

                    <button
                        type="submit"
                        name="tolak"
                        class="btn btn-tolak"
                    >
                        TOLAK
                    </button>


                    <button
                        type="submit"
                        name="verifikasi"
                        class="btn btn-verifikasi"
                    >
                        VERIFIKASI
                    </button>

                <?php

                }

                ?>

            </div>

        </form>

    </div>

</div>


</body>

</html>