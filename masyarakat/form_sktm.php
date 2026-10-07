<?php

session_start();

if (!isset($_SESSION['id_masyarakat'])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$id_masyarakat = $_SESSION['id_masyarakat'];


/* =========================================================
   AMBIL DATA MASYARAKAT
========================================================= */

$query_masyarakat = mysqli_query(
    $koneksi,
    "SELECT *
     FROM masyarakat
     WHERE id_masyarakat = '$id_masyarakat'"
);

$data_masyarakat = mysqli_fetch_assoc($query_masyarakat);

if (!$data_masyarakat) {
    die("Data masyarakat tidak ditemukan.");
}


/* =========================================================
   DATA OTOMATIS DARI PROFIL
========================================================= */

$nama_pemohon = $data_masyarakat['nama_lengkap'] ?? '';
$nik_pemohon  = $data_masyarakat['nik'] ?? '';


/* =========================================================
   PROSES PENGAJUAN
========================================================= */

if (isset($_POST['ajukan'])) {

    /* -----------------------------------------
       DATA FORM
    ----------------------------------------- */

    $nomor_kk = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['nomor_kk'] ?? '')
    );

    $desil = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['desil'] ?? '')
    );

    $alasan_tidak_mampu = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['alasan_tidak_mampu'] ?? '')
    );

    $keperluan = mysqli_real_escape_string(
        $koneksi,
        trim($_POST['keperluan'] ?? '')
    );

    $tanggal = date('Y-m-d');


    /* -----------------------------------------
       VALIDASI
    ----------------------------------------- */

    if (
        empty($nomor_kk) ||
        empty($desil) ||
        empty($alasan_tidak_mampu) ||
        empty($keperluan)
    ) {

        $error = "Mohon lengkapi seluruh data pengajuan SKTM.";

    } else {

        /* -----------------------------------------
           INSERT PENGAJUAN SURAT
        ----------------------------------------- */

        $query_pengajuan = mysqli_query(
            $koneksi,
            "INSERT INTO pengajuan_surat
            (
                id_masyarakat,
                id_jenis_surat,
                tanggal_pengajuan,
                keperluan,
                status_pengajuan
            )
            VALUES
            (
                '$id_masyarakat',
                '3',
                '$tanggal',
                '$keperluan',
                'Menunggu'
            )"
        );


        if ($query_pengajuan) {

            $id_pengajuan = mysqli_insert_id($koneksi);


            /* -----------------------------------------
               INSERT DATA SKTM
            ----------------------------------------- */

            $query_sktm = mysqli_query(
                $koneksi,
                "INSERT INTO data_sktm
                (
                    id_pengajuan,
                    nama_pemohon,
                    nik,
                    nomor_kk,
                    desil,
                    alasan_tidak_mampu,
                    keperluan
                )
                VALUES
                (
                    '$id_pengajuan',
                    '$nama_pemohon',
                    '$nik_pemohon',
                    '$nomor_kk',
                    '$desil',
                    '$alasan_tidak_mampu',
                    '$keperluan'
                )"
            );


            if ($query_sktm) {

                header(
                    "Location: status_pengajuan.php"
                );

                exit;

            } else {

                $error =
                    "Data SKTM gagal disimpan. Error: "
                    . mysqli_error($koneksi);
            }

        } else {

            $error =
                "Pengajuan surat gagal disimpan. Error: "
                . mysqli_error($koneksi);
        }
    }
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
        Form Pengajuan SKTM
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            padding: 0;

            font-family: Arial, sans-serif;

            background: #f5f5f5;

            color: #222;

        }


        /* =================================================
           HEADER
        ================================================= */

        .header {

            height: 80px;

            background: #d9d9d9;

            display: flex;

            align-items: center;

            padding-left: 25px;

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


        /* =================================================
           SIDEBAR
        ================================================= */

        .sidebar {

            position: fixed;

            top: 80px;

            left: 0;

            width: 240px;

            height: calc(100vh - 80px);

            background: #eeeeee;

            padding-top: 20px;

            border-right: 1px solid #ddd;

        }


        .sidebar a {

            display: block;

            padding: 15px 20px;

            text-decoration: none;

            color: #222;

            font-size: 14px;

        }


        .sidebar a:hover {

            background: #d5d5d5;

        }


        /* =================================================
           CONTENT
        ================================================= */

        .content {

            margin-left: 240px;

            padding: 40px;

            min-height: calc(100vh - 80px);

        }


        .content h1 {

            margin: 0 0 10px 0;

            font-size: 26px;

        }


        .subtitle {

            margin-bottom: 25px;

            color: #666;

            font-size: 14px;

        }


        /* =================================================
           FORM BOX
        ================================================= */

        .form-box {

            width: 850px;

            max-width: 100%;

            background: white;

            border: 1px solid #ddd;

            padding: 30px;

            box-shadow:
                0 1px 4px rgba(0,0,0,0.08);

        }


        /* =================================================
           FORM GROUP
        ================================================= */

        .form-group {

            margin-bottom: 20px;

        }


        .form-group label {

            display: block;

            margin-bottom: 8px;

            font-size: 14px;

            font-weight: bold;

            color: #333;

        }


        .form-group input,
        .form-group textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 4px;

            font-family: Arial, sans-serif;

            font-size: 14px;

            background: white;

        }


        .form-group input:focus,
        .form-group textarea:focus {

            outline: none;

            border-color: #777;

        }


        .form-group input[readonly] {

            background: #f1f1f1;

            color: #555;

            cursor: not-allowed;

        }


        .form-group textarea {

            min-height: 100px;

            resize: vertical;

            line-height: 1.5;

        }


        /* =================================================
           PETUNJUK
        ================================================= */

        .help-text {

            display: block;

            margin-top: 6px;

            color: #777;

            font-size: 12px;

            line-height: 1.4;

        }


        /* =================================================
           ERROR
        ================================================= */

        .error {

            margin-bottom: 20px;

            padding: 12px 15px;

            background: #f8d7da;

            color: #842029;

            border: 1px solid #f5c2c7;

            border-radius: 4px;

            font-size: 14px;

        }


        /* =================================================
           BUTTON
        ================================================= */

        .button-area {

            margin-top: 25px;

        }


        .button {

            background: #333;

            color: white;

            border: none;

            padding: 12px 24px;

            border-radius: 4px;

            cursor: pointer;

            font-size: 14px;

        }


        .button:hover {

            background: #555;

        }


        .button-back {

            display: inline-block;

            margin-left: 8px;

            padding: 12px 24px;

            background: #777;

            color: white;

            text-decoration: none;

            border-radius: 4px;

            font-size: 14px;

        }


        .button-back:hover {

            background: #555;

        }


        /* =================================================
           RESPONSIVE
        ================================================= */

        @media (max-width: 900px) {

            .sidebar {

                width: 200px;

            }


            .content {

                margin-left: 200px;

                padding: 25px;

            }


            .form-box {

                width: 100%;

            }

        }


        @media (max-width: 650px) {

            .header-title {

                font-size: 15px;

            }


            .sidebar {

                position: static;

                width: 100%;

                height: auto;

            }


            .content {

                margin-left: 0;

                padding: 20px;

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

        SISTEM INFORMASI PELAYANAN
        SURAT MENYURAT DESA

    </div>

</div>


<!-- =====================================================
     SIDEBAR
===================================================== -->

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


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="content">

    <h1>
        FORM PENGAJUAN SURAT KETERANGAN TIDAK MAMPU
    </h1>

    <div class="subtitle">

        Silakan lengkapi data berikut untuk mengajukan
        Surat Keterangan Tidak Mampu (SKTM).

    </div>


    <?php if (isset($error)) { ?>

        <div class="error">

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php } ?>


    <div class="form-box">

        <form
            method="POST"
            action=""
        >


            <!-- =================================================
                 NAMA LENGKAP
            ================================================= -->

            <div class="form-group">

                <label>
                    NAMA LENGKAP
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $nama_pemohon
                        );
                    ?>"
                    readonly
                >

            </div>


            <!-- =================================================
                 NIK
            ================================================= -->

            <div class="form-group">

                <label>
                    NIK
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $nik_pemohon
                        );
                    ?>"
                    readonly
                >

            </div>


            <!-- =================================================
                 NOMOR KK
            ================================================= -->

            <div class="form-group">

                <label>
                    NOMOR KARTU KELUARGA
                </label>

                <input
                    type="text"
                    name="nomor_kk"
                    maxlength="16"
                    inputmode="numeric"
                    placeholder="Masukkan Nomor KK"
                    value="<?php
                        echo htmlspecialchars(
                            $_POST['nomor_kk'] ?? ''
                        );
                    ?>"
                    required
                >

                <span class="help-text">

                    Masukkan Nomor KK sebanyak 16 digit.

                </span>

            </div>


            <!-- =================================================
                 DESIL DTSEN
            ================================================= -->

            <div class="form-group">

                <label>
                    DESIL DTSEN
                </label>

                <input
                    type="text"
                    name="desil"
                    maxlength="20"
                    inputmode="numeric"
                    placeholder="Masukkan Desil DTSEN"
                    value="<?php
                        echo htmlspecialchars(
                            $_POST['desil'] ?? ''
                        );
                    ?>"
                    required
                >

                <span class="help-text">

                    Contoh Desil DTSEN:
                    <strong>
                        1, 2, 3, 4, 5, 6, 7, 8, 9, atau 10
                    </strong>.
                    Silakan masukkan sesuai dengan data yang dimiliki.

                </span>

            </div>


            <!-- =================================================
                 ALASAN TIDAK MAMPU
            ================================================= -->

            <div class="form-group">

                <label>
                    ALASAN TIDAK MAMPU
                </label>

                <textarea
                    name="alasan_tidak_mampu"
                    placeholder="Contoh: Tidak memiliki penghasilan tetap dan membutuhkan bantuan pendidikan."
                    required
                ><?php
                    echo htmlspecialchars(
                        $_POST['alasan_tidak_mampu'] ?? ''
                    );
                ?></textarea>

                <span class="help-text">

                    Jelaskan secara singkat kondisi atau alasan
                    yang menyebabkan Anda membutuhkan SKTM.

                </span>

            </div>


            <!-- =================================================
                 KEPERLUAN
            ================================================= -->

            <div class="form-group">

                <label>
                    SURAT DIPERLUKAN UNTUK
                </label>

                <textarea
                    name="keperluan"
                    placeholder="Contoh: Untuk pengajuan bantuan pendidikan."
                    required
                ><?php
                    echo htmlspecialchars(
                        $_POST['keperluan'] ?? ''
                    );
                ?></textarea>

                <span class="help-text">

                    Tuliskan tujuan penggunaan Surat Keterangan
                    Tidak Mampu.

                </span>

            </div>


            <!-- =================================================
                 BUTTON
            ================================================= -->

            <div class="button-area">

                <button
                    type="submit"
                    name="ajukan"
                    class="button"
                >
                    AJUKAN SURAT
                </button>

                <a
                    href="pengajuan_surat.php"
                    class="button-back"
                >
                    KEMBALI
                </a>

            </div>


        </form>

    </div>

</div>

</body>

</html>
