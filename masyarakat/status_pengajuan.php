```php
<?php

session_start();

include "../config/koneksi.php";


/* =========================
   CEK LOGIN MASYARAKAT
========================= */

if (!isset($_SESSION["id_masyarakat"])) {

    header("Location: ../login.php");

    exit;
}


$id_masyarakat =
    $_SESSION["id_masyarakat"];


/* =========================
   AMBIL DATA PENGAJUAN
========================= */

$id_masyarakat =
    mysqli_real_escape_string(
        $koneksi,
        $id_masyarakat
    );


$query = "

    SELECT

        pengajuan_surat.id_pengajuan,

        pengajuan_surat.tanggal_pengajuan,

        pengajuan_surat.status_pengajuan,

        /*
         * FILE SKTM
         * Disimpan langsung di
         * pengajuan_surat.file_surat
         */

        pengajuan_surat.file_surat
            AS file_surat_pengajuan,


        jenis_surat.nama_jenis,


        /*
         * FILE SURAT LAMA
         * SKU, Domisili, dan lainnya
         */

        surat.id_surat,

        surat.file_surat
            AS file_surat_surat,

        surat.status_surat


    FROM pengajuan_surat


    LEFT JOIN jenis_surat

        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat


    LEFT JOIN surat

        ON pengajuan_surat.id_pengajuan =
           surat.id_pengajuan


    WHERE pengajuan_surat.id_masyarakat =
          '$id_masyarakat'


    ORDER BY
        pengajuan_surat.id_pengajuan DESC

";


$result =
    mysqli_query(
        $koneksi,
        $query
    );


/* =========================
   CEK QUERY
========================= */

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


    <title>
        Status Pengajuan - Sistem Surat Desa
    </title>


    <style>


        * {

            box-sizing: border-box;

        }


        body {

            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

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

            height:
                calc(100vh - 80px);

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

            padding:
                110px
                35px
                40px
                35px;

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
           TABLE
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


        table {

            width: 100%;

            border-collapse: collapse;

        }


        table th,
        table td {

            border: 1px solid #ccc;

            padding: 12px;

            font-size: 14px;

            text-align: left;

        }


        table th {

            background: #eeeeee;

            font-weight: bold;

        }


        /* =========================
           STATUS
        ========================= */

        .status {

            display: inline-block;

            padding:
                6px
                10px;

            border-radius: 3px;

            font-size: 12px;

            font-weight: bold;

        }


        .menunggu {

            background: #fff3cd;

            color: #856404;

        }


        .diproses {

            background: #cfe2ff;

            color: #084298;

        }


        .selesai {

            background: #d1e7dd;

            color: #0f5132;

        }


        .ditolak {

            background: #f8d7da;

            color: #842029;

        }


        /* =========================
           BUTTON
        ========================= */

        .btn {

            display: inline-block;

            padding:
                8px
                13px;

            border-radius: 3px;

            text-decoration: none;

            font-size: 12px;

            font-weight: bold;

        }


        .btn-unduh {

            background: #333;

            color: white;

        }


        .btn-unduh:hover {

            background: #555;

        }


        .tidak-tersedia {

            color: #999;

            font-size: 12px;

        }


        /* =========================
           DETAIL
        ========================= */

        .detail-box {

            margin-top: 25px;

            padding: 20px;

            border: 1px solid #ccc;

            background: #fafafa;

        }


        .detail-box h3 {

            margin-top: 0;

            font-size: 18px;

        }


        .detail-row {

            display: grid;

            grid-template-columns:
                180px 1fr;

            margin-bottom: 10px;

        }


        .detail-label {

            font-weight: bold;

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {

                width: 200px;

            }


            .content {

                margin-left: 200px;

            }


            table {

                font-size: 12px;

            }


            table th,
            table td {

                padding: 8px;

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

        STATUS PENGAJUAN

    </h1>


    <div class="subjudul">

        Daftar status pengajuan surat Anda

    </div>


    <div class="box">


        <h2>

            DAFTAR PENGAJUAN

        </h2>


        <table>


            <thead>


                <tr>


                    <th>
                        No.
                    </th>


                    <th>
                        No. Pengajuan
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


                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {


                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {


                        /* =========================
                           SIAPKAN FILE SURAT
                        ========================= */

                        $url_surat = "";


                        /*
                         * PRIORITAS 1
                         *
                         * File SKTM yang baru
                         *
                         * pengajuan_surat.file_surat
                         */

                        if (
                            !empty(
                                $row[
                                    "file_surat_pengajuan"
                                ]
                            )
                        ) {


                            $file_pengajuan =
                                ltrim(
                                    $row[
                                        "file_surat_pengajuan"
                                    ],
                                    "/"
                                );


                            /*
                             * Nilai database SKTM:
                             *
                             * uploads/sktm/nama.pdf
                             *
                             * Maka cukup:
                             *
                             * ../uploads/sktm/nama.pdf
                             */

                            $url_surat =
                                "../" .
                                $file_pengajuan;


                        }


                        /*
                         * PRIORITAS 2
                         *
                         * File surat lama
                         *
                         * surat.file_surat
                         *
                         * Contoh:
                         * SKU_20.pdf
                         */

                        elseif (
                            !empty(
                                $row[
                                    "file_surat_surat"
                                ]
                            )
                        ) {


                            $file_lama =
                                ltrim(
                                    $row[
                                        "file_surat_surat"
                                    ],
                                    "/"
                                );


                            /*
                             * Surat lama disimpan
                             * langsung di folder:
                             *
                             * uploads/
                             */

                            $url_surat =
                                "../uploads/" .
                                $file_lama;

                        }


                ?>


                        <tr>


                            <!-- =====================
                                 NOMOR
                            ====================== -->

                            <td>

                                <?php

                                echo $no++;

                                ?>

                            </td>


                            <!-- =====================
                                 ID PENGAJUAN
                            ====================== -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "id_pengajuan"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- =====================
                                 JENIS SURAT
                            ====================== -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "nama_jenis"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- =====================
                                 TANGGAL
                            ====================== -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "tanggal_pengajuan"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- =====================
                                 STATUS
                            ====================== -->

                            <td>


                                <?php


                                if (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    == "Menunggu"
                                ) {


                                ?>

                                    <span
                                        class="status menunggu"
                                    >

                                        Menunggu

                                    </span>


                                <?php


                                } elseif (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    == "Diproses"
                                ) {


                                ?>

                                    <span
                                        class="status diproses"
                                    >

                                        Diproses

                                    </span>


                                <?php


                                } elseif (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    == "Selesai"
                                ) {


                                ?>

                                    <span
                                        class="status selesai"
                                    >

                                        Selesai

                                    </span>


                                <?php


                                } elseif (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    == "Ditolak"
                                ) {


                                ?>

                                    <span
                                        class="status ditolak"
                                    >

                                        Ditolak

                                    </span>


                                <?php


                                } else {


                                ?>

                                    <span
                                        class="status"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $row[
                                                "status_pengajuan"
                                            ]
                                        );

                                        ?>

                                    </span>


                                <?php

                                }


                                ?>


                            </td>


                            <!-- =====================
                                 AKSI
                            ====================== -->

                            <td>


                                <?php


                                /*
                                 * TOMBOL UNDUH
                                 *
                                 * Muncul apabila:
                                 *
                                 * 1. Status Selesai
                                 * 2. File PDF tersedia
                                 */

                                if (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    == "Selesai"
                                    &&
                                    !empty(
                                        $url_surat
                                    )
                                ) {


                                ?>


                                    <a
                                        href="<?= htmlspecialchars(
                                            $url_surat
                                        ) ?>"
                                        target="_blank"
                                        class="btn btn-unduh"
                                    >

                                        UNDUH SURAT

                                    </a>


                                <?php


                                } else {


                                ?>


                                    <span
                                        class="tidak-tersedia"
                                    >

                                        -

                                    </span>


                                <?php


                                }


                                ?>


                            </td>


                        </tr>


                <?php


                    }


                } else {


                ?>


                    <tr>


                        <td
                            colspan="6"
                            style="
                                text-align:center;
                            "
                        >

                            Belum ada pengajuan surat.


                        </td>


                    </tr>


                <?php

                }


                ?>


            </tbody>


        </table>


    </div>


    <!-- =========================
         INFORMASI
    ========================= -->

    <div class="detail-box">


        <h3>

            INFORMASI

        </h3>


        <div class="detail-row">


            <div class="detail-label">

                Menunggu

            </div>


            <div>

                Pengajuan sedang menunggu
                verifikasi admin.

            </div>


        </div>


        <div class="detail-row">


            <div class="detail-label">

                Diproses

            </div>


            <div>

                Pengajuan sedang diproses
                oleh pemerintah desa.

            </div>


        </div>


        <div class="detail-row">


            <div class="detail-label">

                Selesai

            </div>


            <div>

                Surat telah selesai dan
                dapat diunduh.

            </div>


        </div>


        <div class="detail-row">


            <div class="detail-label">

                Ditolak

            </div>


            <div>

                Pengajuan tidak dapat
                diproses.

            </div>


        </div>


    </div>


</div>


</body>


</html>
```
