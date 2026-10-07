
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
   CEK METHOD POST
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak valid.");
}


/* =========================================================
   FUNGSI ESCAPE
   ========================================================= */

function e($value)
{
    return htmlspecialchars(
        $value ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}


/* =========================================================
   DATA SURAT
   ========================================================= */

$id_pengajuan =
    $_POST["id_pengajuan"] ?? "";

$nama_jenis =
    $_POST["nama_jenis"]
    ?? "Surat Keterangan Usaha";

$kode_jenis =
    $_POST["kode_jenis"]
    ?? "SKU";

$nomor_surat =
    $_POST["nomor_surat"]
    ?? "";

$tanggal_surat =
    $_POST["tanggal_surat"]
    ?? date("Y-m-d");


/* =========================================================
   DATA PEMOHON
   ========================================================= */

$nama_lengkap =
    $_POST["nama_lengkap"]
    ?? "";

$nik =
    $_POST["nik"]
    ?? "";

$tempat_lahir =
    $_POST["tempat_lahir"]
    ?? "";

$tanggal_lahir =
    $_POST["tanggal_lahir"]
    ?? "";

$jenis_kelamin =
    $_POST["jenis_kelamin"]
    ?? "";

$agama =
    $_POST["agama"]
    ?? "";

$status_perkawinan =
    $_POST["status_perkawinan"]
    ?? "";

$pekerjaan =
    $_POST["pekerjaan"]
    ?? "";

$kewarganegaraan =
    $_POST["kewarganegaraan"]
    ?? "INDONESIA";

$alamat =
    $_POST["alamat"]
    ?? "";


/* =========================================================
   DATA USAHA
   ========================================================= */

$nama_pemilik =
    $_POST["nama_pemilik"]
    ?? "";

$nama_usaha =
    $_POST["nama_usaha"]
    ?? "";

$jenis_usaha =
    $_POST["jenis_usaha"]
    ?? "";

$bidang_usaha =
    $_POST["bidang_usaha"]
    ?? "";

$alamat_usaha =
    $_POST["alamat_usaha"]
    ?? "";


/*
   Jika bidang usaha kosong,
   gunakan jenis usaha.
*/

if (
    empty(trim($bidang_usaha))
) {

    $bidang_usaha =
        $jenis_usaha;

}


/* =========================================================
   PENANDATANGAN
   ========================================================= */

$jabatan_penandatangan =
    $_POST["jabatan_penandatangan"]
    ?? "Sekretaris Desa Sampiran";

$nama_penandatangan =
    $_POST["nama_penandatangan"]
    ?? "SITI SUGIYANTI";


/* =========================================================
   FORMAT TANGGAL INDONESIA
   ========================================================= */

function formatTanggalIndonesia($tanggal)
{

    if (empty($tanggal)) {
        return "";
    }

    $bulan = [

        1 => "Januari",
        2 => "Februari",
        3 => "Maret",
        4 => "April",
        5 => "Mei",
        6 => "Juni",
        7 => "Juli",
        8 => "Agustus",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Desember"

    ];

    $timestamp =
        strtotime($tanggal);

    if (!$timestamp) {
        return $tanggal;
    }

    return
        date("d", $timestamp)
        . " "
        . $bulan[
            (int)date("m", $timestamp)
        ]
        . " "
        . date("Y", $timestamp);

}


$tanggal_lahir_format =
    formatTanggalIndonesia(
        $tanggal_lahir
    );


$tanggal_surat_format =
    formatTanggalIndonesia(
        $tanggal_surat
    );

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
    Surat Keterangan Usaha
</title>


<style>

/* =========================================================
   RESET
   ========================================================= */

* {
    box-sizing: border-box;
}


/* =========================================================
   BODY
   ========================================================= */

body {

    margin: 0;

    padding: 0;

    background: #e5e5e5;

    color: #000;

    font-family:
        "Times New Roman",
        Times,
        serif;

}


/* =========================================================
   SURAT A4
   ========================================================= */

.surat {

    width: 210mm;

    min-height: 297mm;

    margin: 20px auto;

    padding:
        20mm
        20mm
        20mm
        25mm;

    background: #fff;

}


/* =========================================================
   KOP SURAT
   ========================================================= */

.kop {

    display: flex;

    align-items: center;

    padding-bottom: 8px;

    border-bottom: 3px solid #000;

}


.logo {

    width: 90px;

    height: 90px;

    object-fit: contain;

    margin-right: 15px;

    flex-shrink: 0;

}


.kop-text {

    flex: 1;

    text-align: center;

    line-height: 1.2;

}


.kop-text .baris1 {

    font-size: 16px;

    font-weight: bold;

}


.kop-text .baris2 {

    font-size: 18px;

    font-weight: bold;

}


.kop-text .baris3 {

    font-size: 22px;

    font-weight: bold;

}


.kop-text .alamat {

    margin-top: 4px;

    font-size: 10px;

    line-height: 1.4;

}


/* =========================================================
   JUDUL SURAT
   ========================================================= */

.judul {

    text-align: center;

    margin-top: 22px;

    margin-bottom: 22px;

}


.judul h2 {

    margin: 0;

    font-size: 17px;

    font-weight: bold;

    text-decoration: underline;

}


.nomor {

    margin-top: 5px;

    font-size: 12px;

}


/* =========================================================
   ISI
   ========================================================= */

.isi {

    font-size: 12px;

    line-height: 1.55;

    text-align: justify;

}


.isi p {

    margin-top: 0;

    margin-bottom: 12px;

}


/* =========================================================
   DATA
   ========================================================= */

.data {

    margin:
        8px
        0
        14px
        20px;

}


.data-row {

    display: flex;

    margin-bottom: 4px;

}


.data-label {

    width: 150px;

    flex-shrink: 0;

}


.data-titik {

    width: 18px;

    flex-shrink: 0;

}


.data-value {

    flex: 1;

}


/* =========================================================
   KETERANGAN USAHA
   ========================================================= */

.keterangan-usaha {

    margin-top: 15px;

    text-align: justify;

}


.keterangan-usaha strong {

    font-weight: bold;

}


/* =========================================================
   BLOK DATA USAHA
   ========================================================= */

.data-usaha {

    margin:
        10px
        0
        15px
        20px;

}


.nama-usaha {

    font-weight: bold;

    text-decoration: underline;

}


/* =========================================================
   PENUTUP
   ========================================================= */

.penutup {

    margin-top: 18px;

    text-align: justify;

}


/* =========================================================
   TANDA TANGAN
   ========================================================= */

.ttd {

    width: 260px;

    margin-left: auto;

    margin-top: 35px;

    text-align: center;

    font-size: 12px;

}


.ttd-space {

    height: 65px;

}


.ttd-name {

    font-weight: bold;

    text-decoration: underline;

}


/* =========================================================
   TOMBOL
   ========================================================= */

.tombol {

    width: 210mm;

    margin: 20px auto;

    text-align: center;

}


.btn {

    display: inline-block;

    padding: 10px 18px;

    margin: 0 4px;

    border: none;

    border-radius: 4px;

    cursor: pointer;

    font-family: Arial, sans-serif;

    font-size: 14px;

}


.btn-print {

    background: #333;

    color: white;

}


.btn-back {

    background: #777;

    color: white;

    text-decoration: none;

}


/* =========================================================
   PRINT
   ========================================================= */

@media print {

    body {

        background: white;

    }


    .surat {

        width: auto;

        min-height: auto;

        margin: 0;

        padding: 0;

    }


    .tombol {

        display: none;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     SURAT
     ===================================================== -->

<div class="surat">


    <!-- =================================================
         KOP
         ================================================= -->

    <div class="kop">


        <img
            src="../assets/Lambang_Kabupaten_Cirebon.gif"
            class="logo"
            alt="Lambang Kabupaten Cirebon"
        >


        <div class="kop-text">


            <div class="baris1">

                PEMERINTAH KABUPATEN CIREBON

            </div>


            <div class="baris2">

                KECAMATAN TALUN

            </div>


            <div class="baris3">

                DESA SAMPIRAN

            </div>


            <div class="alamat">

                Jl. Raya Sampiran No. 01
                Telp. 087824115800
                Kecamatan Talun,
                Kabupaten Cirebon,
                Provinsi Jawa Barat 45171

            </div>


        </div>


    </div>



    <!-- =================================================
         JUDUL
         ================================================= -->

    <div class="judul">


        <h2>

            SURAT KETERANGAN USAHA

        </h2>


        <div class="nomor">

            Nomor:
            <?php
            echo e($nomor_surat);
            ?>

        </div>


    </div>



    <!-- =================================================
         ISI SURAT
         ================================================= -->

    <div class="isi">


        <p>

            Yang bertanda tangan di bawah ini
            menerangkan bahwa:

        </p>



        <!-- =================================================
             DATA PENANDATANGAN
             ================================================= -->

        <div class="data">


            <div class="data-row">

                <div class="data-label">
                    Nama
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $nama_penandatangan
                    );
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Jabatan
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $jabatan_penandatangan
                    );
                    ?>

                </div>

            </div>


        </div>



        <p>

            Dengan ini menerangkan bahwa:

        </p>



        <!-- =================================================
             DATA PEMOHON
             ================================================= -->

        <div class="data">


            <div class="data-row">

                <div class="data-label">
                    NIK
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e($nik);
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Nama
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $nama_lengkap
                    );
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Tempat / Tgl. Lahir
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php

                    echo e(
                        $tempat_lahir
                    );

                    if (
                        !empty(
                            $tanggal_lahir_format
                        )
                    ) {

                        echo ", " .
                            e(
                                $tanggal_lahir_format
                            );

                    }

                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Jenis Kelamin
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $jenis_kelamin
                    );
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Alamat
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php

                    echo nl2br(
                        e($alamat)
                    );

                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Agama
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e($agama);
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Status Perkawinan
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $status_perkawinan
                    );
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Pekerjaan
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e($pekerjaan);
                    ?>

                </div>

            </div>


            <div class="data-row">

                <div class="data-label">
                    Kewarganegaraan
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $kewarganegaraan
                    );
                    ?>

                </div>

            </div>


        </div>



        <!-- =================================================
             DATA USAHA
             ================================================= -->

        <p>

            Yang bersangkutan benar mempunyai usaha
            dengan keterangan sebagai berikut:

        </p>


        <div class="data-usaha">


            <!-- NAMA USAHA -->

            <div class="data-row">

                <div class="data-label">
                    Nama Usaha
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value nama-usaha">

                    <?php
                    echo e(
                        $nama_usaha
                    );
                    ?>

                </div>

            </div>


            <!-- JENIS USAHA -->

            <div class="data-row">

                <div class="data-label">
                    Jenis Usaha
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $jenis_usaha
                    );
                    ?>

                </div>

            </div>


            <!-- BIDANG USAHA -->

            <div class="data-row">

                <div class="data-label">
                    Bidang Usaha
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo e(
                        $bidang_usaha
                    );
                    ?>

                </div>

            </div>


            <!-- ALAMAT USAHA -->

            <div class="data-row">

                <div class="data-label">
                    Alamat Usaha
                </div>

                <div class="data-titik">
                    :
                </div>

                <div class="data-value">

                    <?php
                    echo nl2br(
                        e($alamat_usaha)
                    );
                    ?>

                </div>

            </div>


        </div>



        <!-- =================================================
             KETERANGAN USAHA
             ================================================= -->

        <div class="keterangan-usaha">

            Orang tersebut di atas adalah benar
            penduduk Desa Sampiran, Kecamatan Talun,
            Kabupaten Cirebon dan benar mempunyai
            usaha dengan nama

            <strong>

                <?php
                echo e(
                    $nama_usaha
                );
                ?>

            </strong>

            yang bergerak di bidang

            <strong>

                <?php
                echo e(
                    $bidang_usaha
                );
                ?>

            </strong>

            dan beralamat di

            <strong>

                <?php
                echo e(
                    $alamat_usaha
                );
                ?>

            </strong>

            .

        </div>



        <!-- =================================================
             PENUTUP
             ================================================= -->

        <div class="penutup">

            Demikian Surat Keterangan Usaha ini
            dibuat dengan sebenarnya untuk
            dipergunakan sebagaimana mestinya
            dan kepada yang berkepentingan agar
            menjadi tahu serta mohon maklum adanya.

        </div>


    </div>



    <!-- =================================================
         TANDA TANGAN
         ================================================= -->

    <div class="ttd">


        Sampiran,
        <?php
        echo e(
            $tanggal_surat_format
        );
        ?>


        <br><br>


        <?php
        echo e(
            $jabatan_penandatangan
        );
        ?>


        <div class="ttd-space"></div>


        <div class="ttd-name">

            <?php
            echo e(
                $nama_penandatangan
            );
            ?>

        </div>


    </div>


</div>



<!-- =====================================================
     TOMBOL
     ===================================================== -->

<div class="tombol">


    <button
        type="button"
        class="btn btn-print"
        onclick="window.print()"
    >

        CETAK / SIMPAN PDF

    </button>


    <a
        href="javascript:history.back()"
        class="btn btn-back"
    >

        KEMBALI

    </a>


</div>


</body>

</html>
```
