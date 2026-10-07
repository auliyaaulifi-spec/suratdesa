
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
   AMBIL DATA PENGAJUAN
=====================================================

   SUMBER NOMOR KK:

   1. SKU dan DOMISILI
      -> pengajuan_surat.no_kk

   2. SKTM
      -> data_sktm.nomor_kk

   Jika nomor KK SKTM kosong,
   maka menggunakan pengajuan_surat.no_kk.

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
   TENTUKAN NOMOR KK
=====================================================

   Prioritas:

   1. Jika SKTM mempunyai nomor KK
      -> gunakan data_sktm.nomor_kk

   2. Jika tidak ada
      -> gunakan pengajuan_surat.no_kk

===================================================== */

$no_kk = "";


/* =====================================================
   NOMOR KK DARI PENGAJUAN
===================================================== */

if (
    isset($data["no_kk_pengajuan"]) &&
    trim($data["no_kk_pengajuan"]) != ""
) {

    $no_kk = trim($data["no_kk_pengajuan"]);

}


/* =====================================================
   NOMOR KK DARI SKTM
===================================================== */

if (
    isset($data["nomor_kk_sktm"]) &&
    trim($data["nomor_kk_sktm"]) != ""
) {

    $no_kk = trim($data["nomor_kk_sktm"]);

}


/* =====================================================
   JIKA TETAP KOSONG
===================================================== */

if ($no_kk == "") {

    $no_kk = "-";

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
        Detail Pengajuan Surat
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


        /* =====================================================
           HEADER
        ===================================================== */

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


        /* =====================================================
           SIDEBAR
        ===================================================== */

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


        /* =====================================================
           CONTENT
        ===================================================== */

        .content {

            margin-left: 240px;

            padding: 35px;

        }


        h1 {

            font-size: 25px;

            margin-bottom: 10px;

            color: #222;

        }


        .subtitle {

            color: #666;

            margin-bottom: 25px;

        }


        /* =====================================================
           BOX
        ===================================================== */

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

            color: #222;

        }


        /* =====================================================
           DATA ROW
        ===================================================== */

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


        /* =====================================================
           STATUS
        ===================================================== */

        .status {

            display: inline-block;

            padding: 7px 12px;

            border: 1px solid #ccc;

            background: #f5f5f5;

            font-size: 12px;

        }


        /* =====================================================
           BUTTON
        ===================================================== */

        .button-area {

            display: flex;

            gap: 10px;

            margin-top: 25px;

        }


        .btn {

            display: inline-block;

            padding: 10px 18px;

            text-decoration: none;

            font-size: 13px;

            border: none;

            cursor: pointer;

        }


        .btn-kembali {

            background: #777;

            color: white;

        }


        .btn-verifikasi {

            background: #333;

            color: white;

        }


        .btn:hover {

            opacity: 0.85;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

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
        DETAIL PENGAJUAN
    </h1>


    <p class="subtitle">

        Informasi lengkap pengajuan surat dari masyarakat

    </p>



    <!-- =================================================
         INFORMASI PENGAJUAN
    ================================================== -->

    <div class="box">


        <div class="box-title">

            INFORMASI PENGAJUAN

        </div>



        <!-- NAMA LENGKAP -->

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



        <!-- TANGGAL PENGAJUAN -->

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



        <!-- CATATAN -->

        <div class="data-row">

            <div class="label">
                Catatan
            </div>

            <div class="value">

                <?php

                $catatan = $data["catatan"] ?? "";

                if (trim($catatan) == "") {

                    echo "-";

                } else {

                    echo nl2br(
                        htmlspecialchars($catatan)
                    );

                }

                ?>

            </div>

        </div>



        <!-- =================================================
             BUTTON
        ================================================== -->

        <div class="button-area">


            <a
                href="pengajuan_surat.php"
                class="btn btn-kembali"
            >
                KEMBALI
            </a>


            <a
                href="verifikasi_pengajuan.php?id=<?php echo (int) $data["id_pengajuan"]; ?>"
                class="btn btn-verifikasi"
            >
                VERIFIKASI
            </a>


        </div>


    </div>


</div>


</body>

</html>
```
