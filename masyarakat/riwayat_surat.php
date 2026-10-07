
<?php

session_start();

if (!isset($_SESSION["id_masyarakat"])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$id_masyarakat = $_SESSION["id_masyarakat"];


/* =========================================================
   AMBIL DATA RIWAYAT SURAT
========================================================= */

$query = mysqli_query($koneksi, "

    SELECT

        pengajuan_surat.id_pengajuan,

        jenis_surat.nama_jenis,

        pengajuan_surat.tanggal_pengajuan,

        pengajuan_surat.status_pengajuan,

        /*
         * PDF dari SKTM
         * disimpan di pengajuan_surat.file_surat
         */
        pengajuan_surat.file_surat AS file_surat_pengajuan,

        /*
         * PDF SKU / Domisili / surat lainnya
         * disimpan di surat.file_surat
         */
        surat.file_surat AS file_surat_surat

    FROM pengajuan_surat


    LEFT JOIN jenis_surat

        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat


    LEFT JOIN surat

        ON pengajuan_surat.id_pengajuan =
           surat.id_pengajuan


    WHERE

        pengajuan_surat.id_masyarakat =
        '$id_masyarakat'


    ORDER BY

        pengajuan_surat.id_pengajuan DESC

");


/* =========================================================
   CEK QUERY
========================================================= */

if (!$query) {

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
        Riwayat Surat - Sistem Informasi Pelayanan Surat Menyurat Desa
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family:
                Arial,
                sans-serif;

            background: #f5f5f5;
        }


        /* =================================================
           HEADER
        ================================================= */

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


        /* =================================================
           SIDEBAR
        ================================================= */

        .sidebar {

            position: fixed;

            top: 80px;

            left: 0;

            width: 240px;

            height:
                calc(100vh - 80px);

            background: #eeeeee;

            padding-top: 20px;
        }


        .sidebar a {

            display: block;

            padding:
                15px
                20px;

            text-decoration: none;

            color: #222;

            font-size: 15px;
        }


        .sidebar a:hover {

            background: #d5d5d5;
        }


        /* =================================================
           CONTENT
        ================================================= */

        .content {

            margin-left: 240px;

            padding: 35px;
        }


        h1 {

            margin-top: 0;

            font-size: 28px;
        }


        .subtitle {

            margin-bottom: 25px;

            color: #555;
        }


        /* =================================================
           TABLE BOX
        ================================================= */

        .table-box {

            background: white;

            padding: 25px;

            border-radius: 5px;

            box-shadow:
                0 1px 4px
                rgba(
                    0,
                    0,
                    0,
                    0.08
                );
        }


        /* =================================================
           TABLE
        ================================================= */

        table {

            width: 100%;

            border-collapse: collapse;
        }


        th {

            background: #e5e5e5;

            padding: 12px;

            text-align: left;

            border:
                1px
                solid
                #ccc;
        }


        td {

            padding: 12px;

            border:
                1px
                solid
                #ccc;
        }


        /* =================================================
           BUTTON DOWNLOAD
        ================================================= */

        .btn-download {

            display: inline-block;

            padding:
                8px
                12px;

            background: #333;

            color: white;

            text-decoration: none;

            border-radius: 4px;

            font-size: 13px;
        }


        .btn-download:hover {

            background: #555;
        }


        /* =================================================
           TIDAK ADA AKSI
        ================================================= */

        .no-action {

            color: #888;
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
        RIWAYAT SURAT
    </h1>


    <div class="subtitle">

        Daftar riwayat pengajuan surat yang telah dilakukan.

    </div>


    <div class="table-box">


        <table>


            <thead>

                <tr>

                    <th>
                        No
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
                    mysqli_num_rows($query) > 0
                ) {


                    while (
                        $row =
                        mysqli_fetch_assoc($query)
                    ) {


                        /* =================================
                           TENTUKAN FILE PDF
                        ================================= */


                        $file_pdf = "";


                        /*
                         * PRIORITAS:
                         *
                         * 1. pengajuan_surat.file_surat
                         *    untuk SKTM
                         *
                         * 2. surat.file_surat
                         *    untuk SKU / Domisili
                         */


                        if (
                            !empty(
                                $row[
                                    "file_surat_pengajuan"
                                ]
                            )
                        ) {


                            $file_pdf =
                                $row[
                                    "file_surat_pengajuan"
                                ];


                        } elseif (
                            !empty(
                                $row[
                                    "file_surat_surat"
                                ]
                            )
                        ) {


                            $file_pdf =
                                $row[
                                    "file_surat_surat"
                                ];

                        }


                        /* =================================
                           TENTUKAN URL PDF
                        ================================= */


                        $url_pdf = "";


                        if (
                            !empty($file_pdf)
                        ) {


                            /*
                             * File SKTM disimpan:
                             *
                             * uploads/sktm/nama.pdf
                             *
                             * sedangkan SKU/Domisili:
                             *
                             * uploads/nama.pdf
                             *
                             */


                            if (
                                strpos(
                                    $file_pdf,
                                    "uploads/"
                                ) === 0
                            ) {


                                /*
                                 * SKTM
                                 *
                                 * file_pdf:
                                 * uploads/sktm/xxxxx.pdf
                                 *
                                 * URL:
                                 * ../uploads/sktm/xxxxx.pdf
                                 */

                                $url_pdf =
                                    "../" .
                                    $file_pdf;


                            } else {


                                /*
                                 * SKU / DOMISILI
                                 *
                                 * file_pdf:
                                 * nama_file.pdf
                                 *
                                 * URL:
                                 * ../uploads/nama_file.pdf
                                 */

                                $url_pdf =
                                    "../uploads/" .
                                    $file_pdf;

                            }

                        }

                ?>


                        <tr>


                            <!-- NO -->

                            <td>

                                <?php
                                echo $no++;
                                ?>

                            </td>


                            <!-- NO PENGAJUAN -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row[
                                        "id_pengajuan"
                                    ]
                                );
                                ?>

                            </td>


                            <!-- JENIS SURAT -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "nama_jenis"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- TANGGAL -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "tanggal_pengajuan"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- STATUS -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "status_pengajuan"
                                    ]
                                );

                                ?>

                            </td>


                            <!-- AKSI -->

                            <td>


                                <?php

                                if (
                                    $row[
                                        "status_pengajuan"
                                    ]
                                    ===
                                    "Selesai"
                                    &&
                                    !empty(
                                        $url_pdf
                                    )
                                ) {

                                ?>


                                    <a
                                        class="btn-download"
                                        href="<?php
                                            echo htmlspecialchars(
                                                $url_pdf
                                            );
                                        ?>"
                                        target="_blank"
                                    >

                                        UNDUH SURAT

                                    </a>


                                <?php

                                } else {

                                ?>


                                    <span class="no-action">

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
                            style="text-align:center;"
                        >

                            Belum ada riwayat surat.

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
```
