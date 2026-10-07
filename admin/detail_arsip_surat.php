<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";


/* =========================
   CEK ID SURAT
========================= */

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("ID surat tidak ditemukan.");
}

$id_surat = mysqli_real_escape_string($koneksi, $_GET["id"]);


/* =========================
   SIMPAN NOMOR SURAT
========================= */

if (isset($_POST["simpan_nomor"])) {

    $id_nomor_surat = mysqli_real_escape_string(
        $koneksi,
        $_POST["id_nomor_surat"]
    );

    if ($id_nomor_surat == "") {

        $pesan = "Silakan pilih nomor surat.";

    } else {

        $query_update = "
            UPDATE surat
            SET id_nomor_surat = '$id_nomor_surat'
            WHERE id_surat = '$id_surat'
        ";

        if (mysqli_query($koneksi, $query_update)) {

            $pesan = "Nomor surat berhasil disimpan.";

        } else {

            $pesan = "Nomor surat gagal disimpan.";

        }
    }
}


/* =========================
   AMBIL DATA SURAT
========================= */

$query = "
    SELECT
        surat.*,

        pengajuan_surat.id_pengajuan,
        pengajuan_surat.tanggal_pengajuan,
        pengajuan_surat.keperluan,

        masyarakat.nama_lengkap,
        masyarakat.nik,
        masyarakat.no_kk,
        masyarakat.alamat,
        masyarakat.no_hp,

        jenis_surat.nama_jenis,

        nomor_surat.nomor_surat,
        nomor_surat.nomor_urut,
        nomor_surat.tahun,
        nomor_surat.keterangan

    FROM surat

    LEFT JOIN pengajuan_surat
        ON surat.id_pengajuan = pengajuan_surat.id_pengajuan

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat = masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat = jenis_surat.id_jenis_surat

    LEFT JOIN nomor_surat
        ON surat.id_nomor_surat = nomor_surat.id_nomor_surat

    WHERE surat.id_surat = '$id_surat'
";

$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data surat tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);


/* =========================
   AMBIL DAFTAR NOMOR SURAT
========================= */

$query_nomor = "
    SELECT *
    FROM nomor_surat
    ORDER BY tahun DESC, nomor_urut ASC
";

$result_nomor = mysqli_query($koneksi, $query_nomor);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Detail Arsip Surat - Sistem Surat Desa</title>

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

        .subjudul {
            margin-bottom: 25px;

            color: #555;
        }


        /* =========================
           PESAN
        ========================= */

        .pesan {
            background: #dff0d8;

            border: 1px solid #b8dca8;

            padding: 12px 15px;

            margin-bottom: 20px;

            color: #315b25;
        }


        /* =========================
           BOX
        ========================= */

        .box {
            background: white;

            border: 1px solid #ccc;

            padding: 25px;

            margin-bottom: 25px;
        }

        .box h2 {
            margin-top: 0;

            font-size: 20px;

            margin-bottom: 20px;
        }


        /* =========================
           DETAIL
        ========================= */

        .detail {
            display: grid;

            grid-template-columns: 220px 1fr;

            row-gap: 12px;
        }

        .label {
            font-weight: bold;
        }


        /* =========================
           FORM NOMOR
        ========================= */

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-weight: bold;

            font-size: 14px;
        }

        .form-group select {
            width: 100%;

            height: 42px;

            padding: 8px 10px;

            border: 1px solid #bbb;

            border-radius: 3px;

            background: white;

            font-size: 14px;
        }


        /* =========================
           BUTTON
        ========================= */

        .button-group {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;

            padding: 10px 18px;

            margin-right: 8px;

            border: none;

            border-radius: 3px;

            text-decoration: none;

            cursor: pointer;

            font-size: 14px;
        }

        .btn-simpan {
            background: #333;

            color: white;
        }

        .btn-kembali {
            background: #777;

            color: white;
        }

        .btn-unduh {
            background: #333;

            color: white;
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

    <h1>DETAIL ARSIP SURAT</h1>

    <div class="subjudul">
        Informasi detail surat yang telah selesai
    </div>


    <?php if (isset($pesan)) { ?>

        <div class="pesan">
            <?php echo htmlspecialchars($pesan); ?>
        </div>

    <?php } ?>


    <!-- =========================
         INFORMASI MASYARAKAT
    ========================= -->

    <div class="box">

        <h2>INFORMASI MASYARAKAT</h2>

        <div class="detail">

            <div class="label">
                Nama Lengkap
            </div>

            <div>
                <?php echo htmlspecialchars($data["nama_lengkap"]); ?>
            </div>


            <div class="label">
                NIK
            </div>

            <div>
                <?php echo htmlspecialchars($data["nik"]); ?>
            </div>


            <div class="label">
                Nomor KK
            </div>

            <div>
                <?php echo htmlspecialchars($data["no_kk"]); ?>
            </div>


            <div class="label">
                Alamat
            </div>

            <div>
                <?php echo htmlspecialchars($data["alamat"]); ?>
            </div>


            <div class="label">
                No. HP
            </div>

            <div>
                <?php echo htmlspecialchars($data["no_hp"]); ?>
            </div>

        </div>

    </div>


    <!-- =========================
         INFORMASI SURAT
    ========================= -->

    <div class="box">

        <h2>INFORMASI SURAT</h2>

        <div class="detail">

            <div class="label">
                Jenis Surat
            </div>

            <div>
                <?php echo htmlspecialchars($data["nama_jenis"]); ?>
            </div>


            <div class="label">
                Tanggal Pengajuan
            </div>

            <div>
                <?php echo htmlspecialchars($data["tanggal_pengajuan"]); ?>
            </div>


            <div class="label">
                Tanggal Surat
            </div>

            <div>
                <?php echo htmlspecialchars($data["tanggal_surat"]); ?>
            </div>


            <div class="label">
                Tanggal Cetak
            </div>

            <div>
                <?php echo htmlspecialchars($data["tanggal_cetak"]); ?>
            </div>


            <div class="label">
                Status Surat
            </div>

            <div>
                <?php echo htmlspecialchars($data["status_surat"]); ?>
            </div>


            <div class="label">
                Nomor Surat Saat Ini
            </div>

            <div>

                <?php

                if (!empty($data["nomor_surat"])) {

                    echo htmlspecialchars($data["nomor_surat"]);

                } else {

                    echo "Belum memiliki nomor surat.";

                }

                ?>

            </div>

        </div>

    </div>


    <!-- =========================
         PILIH NOMOR SURAT
    ========================= -->

    <div class="box">

        <h2>NOMOR SURAT</h2>

        <form method="POST">

            <div class="form-group">

                <label>
                    Pilih Nomor Surat
                </label>

                <select
                    name="id_nomor_surat"
                    required
                >

                    <option value="">
                        -- Pilih Nomor Surat --
                    </option>


                    <?php

                    if (mysqli_num_rows($result_nomor) > 0) {

                        while ($nomor = mysqli_fetch_assoc($result_nomor)) {

                            $selected = "";

                            if (
                                $data["id_nomor_surat"] ==
                                $nomor["id_nomor_surat"]
                            ) {

                                $selected = "selected";

                            }

                    ?>

                            <option
                                value="<?php echo $nomor["id_nomor_surat"]; ?>"
                                <?php echo $selected; ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $nomor["nomor_surat"]
                                );
                                ?>

                                -

                                <?php
                                echo htmlspecialchars(
                                    $nomor["keterangan"]
                                );
                                ?>

                            </option>

                    <?php

                        }

                    }

                    ?>

                </select>

            </div>


            <button
                type="submit"
                name="simpan_nomor"
                class="btn btn-simpan"
            >
                SIMPAN NOMOR SURAT
            </button>

        </form>

    </div>


    <!-- =========================
         FILE SURAT
    ========================= -->

    <div class="box">

        <h2>FILE SURAT</h2>

        <div class="detail">

            <div class="label">
                Nama File
            </div>

            <div>
                <?php
                echo htmlspecialchars(
                    $data["file_surat"]
                );
                ?>
            </div>

        </div>


        <?php if (!empty($data["file_surat"])) { ?>

            <div class="button-group">

                <a
                    href="../uploads/<?php echo urlencode($data["file_surat"]); ?>"
                    target="_blank"
                    class="btn btn-unduh"
                >
                    UNDUH PDF
                </a>

            </div>

        <?php } ?>

    </div>


    <!-- =========================
         BUTTON KEMBALI
    ========================= -->

    <div class="button-group">

        <a
            href="arsip_surat.php"
            class="btn btn-kembali"
        >
            KEMBALI
        </a>

    </div>

</div>

</body>

</html>