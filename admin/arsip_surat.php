```php
<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";


/* =========================================================
   SEARCH
========================================================= */

$keyword = "";

if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}

$keyword_sql = mysqli_real_escape_string(
    $koneksi,
    $keyword
);


/* =========================================================
   QUERY ARSIP
========================================================= */

$query = "

    SELECT

        pengajuan_surat.id_pengajuan,

        pengajuan_surat.tanggal_pengajuan,

        pengajuan_surat.file_surat
            AS file_surat_pengajuan,

        pengajuan_surat.status_pengajuan,

        surat.id_surat,

        surat.tanggal_surat,

        surat.file_surat
            AS file_surat_surat,

        surat.status_surat,

        surat.tanggal_cetak,

        masyarakat.nama_lengkap,

        jenis_surat.nama_jenis,

        nomor_surat.nomor_surat


    FROM pengajuan_surat


    LEFT JOIN surat

        ON pengajuan_surat.id_pengajuan =
           surat.id_pengajuan


    LEFT JOIN masyarakat

        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat


    LEFT JOIN jenis_surat

        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat


    LEFT JOIN nomor_surat

        ON surat.id_nomor_surat =
           nomor_surat.id_nomor_surat


    WHERE

        (

            surat.status_surat = 'Selesai'

            OR

            (
                pengajuan_surat.status_pengajuan = 'Selesai'
                AND pengajuan_surat.file_surat IS NOT NULL
                AND pengajuan_surat.file_surat != ''
            )

        )


        AND

        (

            masyarakat.nama_lengkap LIKE '%$keyword_sql%'

            OR nomor_surat.nomor_surat LIKE '%$keyword_sql%'

            OR jenis_surat.nama_jenis LIKE '%$keyword_sql%'

        )


    ORDER BY

        pengajuan_surat.id_pengajuan DESC

";


$result = mysqli_query(
    $koneksi,
    $query
);


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
        Arsip Surat - Sistem Surat Desa
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

            background: #f4f6f8;

            color: #222;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {

            width: 100%;

            height: 80px;

            background: #d9d9d9;

            display: flex;

            align-items: center;

            padding: 0 25px;

            position: fixed;

            top: 0;

            left: 0;

            z-index: 1000;

            box-shadow:
                0 2px 5px
                rgba(0,0,0,0.08);
        }


        .header img {

            width: 55px;

            height: 55px;

            object-fit: contain;

            background: white;

            padding: 3px;
        }


        .header-title {

            margin-left: 18px;

            font-size: 20px;

            font-weight: bold;

            letter-spacing: 0.3px;

            color: #222;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {

            width: 240px;

            height:
                calc(100vh - 80px);

            background: #eeeeee;

            position: fixed;

            top: 80px;

            left: 0;

            padding-top: 20px;

            overflow-y: auto;

            border-right:
                1px solid
                #ddd;
        }


        .sidebar a {

            display: block;

            width: 100%;

            padding:
                14px
                25px;

            text-decoration: none;

            color: #222;

            font-size: 15px;

            transition:
                background 0.2s,
                padding-left 0.2s;
        }


        .sidebar a:hover {

            background: #dcdcdc;

            padding-left: 30px;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .content {

            margin-left: 240px;

            padding:
                110px
                40px
                50px
                40px;

            min-height: 100vh;
        }


        .content h1 {

            margin:
                0
                0
                7px
                0;

            font-size: 29px;

            letter-spacing: 0.5px;
        }


        .subtitle {

            margin-bottom: 25px;

            color: #666;

            font-size: 15px;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .search-wrapper {

            background: white;

            padding: 18px;

            border:
                1px
                solid
                #ddd;

            border-radius: 8px;

            margin-bottom: 22px;

            box-shadow:
                0 1px 4px
                rgba(0,0,0,0.04);
        }


        .search-box {

            display: flex;

            max-width: 600px;
        }


        .search-box input {

            flex: 1;

            height: 42px;

            padding:
                0
                13px;

            border:
                1px
                solid
                #bbb;

            border-right: none;

            border-radius:
                6px
                0
                0
                6px;

            font-size: 14px;

            outline: none;
        }


        .search-box input:focus {

            border-color: #777;
        }


        .search-box button {

            width: 85px;

            height: 42px;

            border: none;

            background: #333;

            color: white;

            border-radius:
                0
                6px
                6px
                0;

            cursor: pointer;

            font-weight: bold;

            font-size: 13px;
        }


        .search-box button:hover {

            background: #555;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .table-card {

            background: white;

            border:
                1px
                solid
                #ddd;

            border-radius: 8px;

            overflow-x: auto;

            box-shadow:
                0 2px 7px
                rgba(0,0,0,0.05);
        }


        table {

            width: 100%;

            min-width: 900px;

            border-collapse: collapse;

            background: white;
        }


        table th {

            background: #e5e5e5;

            color: #222;

            font-size: 13px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.2px;

            padding:
                14px
                12px;

            border-bottom:
                1px
                solid
                #ccc;

            text-align: left;

            white-space: nowrap;
        }


        table td {

            padding:
                13px
                12px;

            border-bottom:
                1px
                solid
                #e5e5e5;

            font-size: 14px;

            vertical-align: middle;
        }


        table tbody tr:hover {

            background: #f8f8f8;
        }


        table tbody tr:last-child td {

            border-bottom: none;
        }


        /* =====================================================
           COLUMN
        ===================================================== */

        .col-no {

            width: 55px;

            text-align: center;
        }


        .col-nama {

            min-width: 170px;
        }


        .col-jenis {

            min-width: 220px;
        }


        .col-nomor {

            min-width: 130px;

            white-space: nowrap;
        }


        .col-tanggal {

            min-width: 120px;

            white-space: nowrap;
        }


        .col-status {

            width: 100px;

            text-align: center;
        }


        .col-aksi {

            min-width: 210px;

            white-space: nowrap;
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status-badge {

            display: inline-block;

            padding:
                6px
                11px;

            border-radius: 20px;

            background: #e8f5e9;

            color: #2e7d32;

            font-size: 12px;

            font-weight: bold;

            border:
                1px
                solid
                #c8e6c9;
        }


        /* =====================================================
           BUTTON
        ===================================================== */

        .btn {

            display: inline-block;

            padding:
                8px
                12px;

            border-radius: 5px;

            text-decoration: none;

            font-size: 12px;

            font-weight: bold;

            margin-right: 5px;

            transition:
                background 0.2s;
        }


        .btn-lihat {

            background: #333;

            color: white;
        }


        .btn-lihat:hover {

            background: #555;
        }


        .btn-download {

            background: #555;

            color: white;
        }


        .btn-download:hover {

            background: #333;
        }


        /* =====================================================
           TEXT MUTED
        ===================================================== */

        .text-muted {

            color: #999;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .kosong {

            text-align: center;

            color: #777;

            padding:
                35px
                20px;

            font-size: 14px;
        }


        /* =====================================================
           TABLE INFO
        ===================================================== */

        .table-info {

            padding:
                12px
                15px;

            background: #fafafa;

            border-top:
                1px
                solid
                #e5e5e5;

            color: #777;

            font-size: 13px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {

            .sidebar {

                width: 210px;
            }


            .content {

                margin-left: 210px;

                padding-left: 25px;

                padding-right: 25px;
            }


            .header-title {

                font-size: 16px;
            }

        }


        @media (max-width: 650px) {

            .header {

                height: 70px;

                padding-left: 15px;
            }


            .header img {

                width: 45px;

                height: 45px;
            }


            .header-title {

                font-size: 14px;

                margin-left: 10px;
            }


            .sidebar {

                top: 70px;

                width: 190px;

                height:
                    calc(100vh - 70px);
            }


            .content {

                margin-left: 190px;

                padding:
                    95px
                    15px
                    30px
                    15px;
            }


            .content h1 {

                font-size: 24px;
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
        ARSIP SURAT
    </h1>


    <div class="subtitle">

        Daftar arsip surat yang telah selesai

    </div>


    <!-- =================================================
         SEARCH
    ================================================== -->

    <div class="search-wrapper">


        <form
            method="GET"
            class="search-box"
        >


            <input
                type="text"
                name="keyword"
                placeholder="Cari nama, nomor surat, atau jenis surat..."
                value="<?php
                    echo htmlspecialchars(
                        $keyword
                    );
                ?>"
            >


            <button type="submit">

                CARI

            </button>


        </form>


    </div>


    <!-- =================================================
         TABLE
    ================================================== -->

    <div class="table-card">


        <table>


            <thead>

                <tr>

                    <th class="col-no">
                        No
                    </th>

                    <th class="col-nama">
                        Nama
                    </th>

                    <th class="col-jenis">
                        Jenis Surat
                    </th>

                    <th class="col-nomor">
                        Nomor Surat
                    </th>

                    <th class="col-tanggal">
                        Tanggal
                    </th>

                    <th class="col-status">
                        Status
                    </th>

                    <th class="col-aksi">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php

                $no = 1;

                $jumlah_arsip =
                    mysqli_num_rows(
                        $result
                    );


                if ($jumlah_arsip > 0) {


                    while (
                        $row =
                        mysqli_fetch_assoc(
                            $result
                        )
                    ) {


                        /* =====================================
                           FILE PDF
                        ===================================== */

                        $url_pdf = "";


                        /*
                         * SKTM
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


                            $url_pdf =
                                "../" .
                                $file_pengajuan;

                        }


                        /*
                         * SURAT LAMA
                         * SKU / DOMISILI
                         */

                        elseif (
                            !empty(
                                $row[
                                    "file_surat_surat"
                                ]
                            )
                        ) {


                            $file_surat =
                                ltrim(
                                    $row[
                                        "file_surat_surat"
                                    ],
                                    "/"
                                );


                            $url_pdf =
                                "../uploads/" .
                                $file_surat;

                        }


                        /* =====================================
                           STATUS
                        ===================================== */

                        $status_tampil = "Selesai";


                        if (
                            !empty(
                                $row[
                                    "status_surat"
                                ]
                            )
                        ) {

                            $status_tampil =
                                $row[
                                    "status_surat"
                                ];

                        }


                        if (
                            !empty(
                                $row[
                                    "status_pengajuan"
                                ]
                            )
                            &&
                            $row[
                                "status_pengajuan"
                            ] === "Selesai"
                        ) {

                            $status_tampil =
                                "Selesai";

                        }


                        /* =====================================
                           TANGGAL
                        ===================================== */

                        if (
                            !empty(
                                $row[
                                    "tanggal_surat"
                                ]
                            )
                        ) {

                            $tanggal =
                                $row[
                                    "tanggal_surat"
                                ];

                        } else {

                            $tanggal =
                                $row[
                                    "tanggal_pengajuan"
                                ]
                                ??
                                "";

                        }

                ?>


                        <tr>


                            <!-- NO -->

                            <td class="col-no">

                                <?php

                                echo $no++;

                                ?>

                            </td>


                            <!-- NAMA -->

                            <td class="col-nama">

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "nama_lengkap"
                                    ]
                                    ??
                                    "-"
                                );

                                ?>

                            </td>


                            <!-- JENIS SURAT -->

                            <td class="col-jenis">

                                <?php

                                echo htmlspecialchars(
                                    $row[
                                        "nama_jenis"
                                    ]
                                    ??
                                    "-"
                                );

                                ?>

                            </td>


                            <!-- NOMOR SURAT -->

                            <td class="col-nomor">

                                <?php

                                if (
                                    !empty(
                                        $row[
                                            "nomor_surat"
                                        ]
                                    )
                                ) {

                                    echo htmlspecialchars(
                                        $row[
                                            "nomor_surat"
                                        ]
                                    );

                                } else {

                                    echo '<span class="text-muted">-</span>';

                                }

                                ?>

                            </td>


                            <!-- TANGGAL -->

                            <td class="col-tanggal">

                                <?php

                                if (
                                    !empty(
                                        $tanggal
                                    )
                                ) {

                                    echo htmlspecialchars(
                                        $tanggal
                                    );

                                } else {

                                    echo '<span class="text-muted">-</span>';

                                }

                                ?>

                            </td>


                            <!-- STATUS -->

                            <td class="col-status">

                                <span class="status-badge">

                                    <?php

                                    echo htmlspecialchars(
                                        $status_tampil
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- AKSI -->

                            <td class="col-aksi">


                                <?php


                                /*
                                 * =================================================
                                 * LIHAT
                                 * =================================================
                                 *
                                 * SKU / DOMISILI
                                 * → detail_arsip_surat.php
                                 *
                                 * SKTM
                                 * → langsung membuka PDF
                                 */


                                if (
                                    !empty(
                                        $row[
                                            "id_surat"
                                        ]
                                    )
                                ) {


                                ?>

                                    <a
                                        href="detail_arsip_surat.php?id=<?php
                                            echo $row[
                                                "id_surat"
                                            ];
                                        ?>"
                                        class="btn btn-lihat"
                                    >

                                        LIHAT

                                    </a>


                                <?php


                                } elseif (
                                    !empty(
                                        $row[
                                            "file_surat_pengajuan"
                                        ]
                                    )
                                ) {


                                ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $url_pdf
                                        ) ?>"
                                        class="btn btn-lihat"
                                        target="_blank"
                                    >

                                        LIHAT

                                    </a>


                                <?php

                                }


                                /*
                                 * =================================================
                                 * UNDUH PDF
                                 * =================================================
                                 */

                                if (
                                    !empty(
                                        $url_pdf
                                    )
                                ) {


                                ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $url_pdf
                                        ) ?>"
                                        class="btn btn-download"
                                        target="_blank"
                                    >

                                        UNDUH PDF

                                    </a>


                                <?php

                                }


                                /*
                                 * =================================================
                                 * TIDAK ADA AKSI
                                 * =================================================
                                 */

                                if (
                                    empty(
                                        $url_pdf
                                    )
                                    &&
                                    empty(
                                        $row[
                                            "id_surat"
                                        ]
                                    )
                                ) {


                                ?>

                                    <span class="text-muted">
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
                            colspan="7"
                            class="kosong"
                        >

                            Belum ada arsip surat.

                        </td>

                    </tr>


                <?php

                }

                ?>


            </tbody>


        </table>


        <?php

        if ($jumlah_arsip > 0) {

        ?>

            <div class="table-info">

                Total arsip:
                <strong>
                    <?php
                    echo $jumlah_arsip;
                    ?>
                </strong>
                surat

            </div>

        <?php

        }

        ?>


    </div>


</div>


</body>

</html>
```
