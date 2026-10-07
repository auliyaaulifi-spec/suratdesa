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
   KONEKSI DATABASE
   ========================================================= */

include "../config/koneksi.php";


/* =========================================================
   CEK ID PENGAJUAN
   ========================================================= */

if (
    !isset($_POST["id_pengajuan"]) ||
    empty($_POST["id_pengajuan"])
) {
    die("ID pengajuan tidak ditemukan.");
}


/* =========================================================
   AMBIL DATA DARI FORM
   ========================================================= */

$id_pengajuan = $_POST["id_pengajuan"] ?? "";

$nomor_surat = $_POST["nomor_surat"] ?? "";

$nama_jenis = $_POST["nama_jenis"] ?? "";

$kode_jenis = $_POST["kode_jenis"] ?? "";

$nama_lengkap = $_POST["nama_lengkap"] ?? "";

$nik = $_POST["nik"] ?? "";

$jenis_kelamin = $_POST["jenis_kelamin"] ?? "";

$tempat_lahir = $_POST["tempat_lahir"] ?? "";

$tanggal_lahir = $_POST["tanggal_lahir"] ?? "";

$pekerjaan = $_POST["pekerjaan"] ?? "";

$agama = $_POST["agama"] ?? "";

$status_perkawinan = $_POST["status_perkawinan"] ?? "";

$alamat = $_POST["alamat"] ?? "";

$no_hp = $_POST["no_hp"] ?? "";

$keperluan = $_POST["keperluan"] ?? "";


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
   FORMAT TANGGAL LAHIR
   ========================================================= */

$tanggal_lahir_tampil = "";

if (!empty($tanggal_lahir)) {

    $timestamp = strtotime($tanggal_lahir);

    if ($timestamp !== false) {

        $tanggal_lahir_tampil =
            date("d-m-Y", $timestamp);

    } else {

        $tanggal_lahir_tampil =
            $tanggal_lahir;

    }
}


/* =========================================================
   TANGGAL SURAT
   ========================================================= */

$tanggal_surat = date("d-m-Y");


/* =========================================================
   JUDUL SURAT
   ========================================================= */

$judul_surat = "SURAT KETERANGAN DOMISILI";


/* =========================================================
   JIKA JENIS SURAT TERSEDIA
   GUNAKAN NAMA JENIS SURAT
   ========================================================= */

if (!empty($nama_jenis)) {

    $judul_surat = strtoupper(
        $nama_jenis
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
    <?php echo e($judul_surat); ?>
</title>


<style>

/* =========================================================
   HALAMAN A4
   ========================================================= */

@page {

    size: A4;

    margin: 15mm 18mm 18mm 18mm;

}


/* =========================================================
   RESET
   ========================================================= */

* {

    box-sizing: border-box;

}


body {

    margin: 0;

    padding: 0;

    background: #eeeeee;

    font-family:
        "Times New Roman",
        Times,
        serif;

    color: #000;

}


/* =========================================================
   KERTAS
   ========================================================= */

.kertas {

    width: 210mm;

    min-height: 297mm;

    margin: 20px auto;

    padding:
        15mm
        18mm
        18mm
        18mm;

    background: #fff;

}


/* =========================================================
   KOP SURAT
   ========================================================= */

.kop {

    display: flex;

    align-items: center;

    padding-bottom: 9px;

    border-bottom: 3px solid #000;

}


/* =========================================================
   LAMBANG KABUPATEN
   ========================================================= */

.logo {

    width: 105px;

    height: 105px;

    object-fit: contain;

    flex-shrink: 0;

}


/* =========================================================
   TEKS KOP
   ========================================================= */

.kop-tengah {

    flex: 1;

    text-align: center;

    padding-right: 105px;

}


.kabupaten {

    font-size: 17px;

    font-weight: bold;

    line-height: 1.3;

}


.kecamatan {

    font-size: 19px;

    font-weight: bold;

    line-height: 1.3;

}


.desa {

    font-size: 24px;

    font-weight: bold;

    line-height: 1.3;

}


.alamat {

    margin-top: 5px;

    font-size: 11px;

    line-height: 1.4;

}


/* =========================================================
   JUDUL SURAT
   ========================================================= */

.judul {

    text-align: center;

    margin-top: 25px;

    margin-bottom: 25px;

}


.judul h2 {

    margin: 0;

    font-size: 17px;

    font-weight: bold;

    text-decoration: underline;

}


.nomor {

    margin-top: 6px;

    font-size: 14px;

}


/* =========================================================
   ISI SURAT
   ========================================================= */

.isi {

    margin-top: 15px;

    font-size: 14px;

    line-height: 1.7;

    text-align: justify;

}


.data {

    margin-top: 15px;

    margin-bottom: 22px;

}


/* =========================================================
   BARIS DATA
   ========================================================= */

.data-row {

    display: flex;

    margin-bottom: 5px;

    line-height: 1.5;

}


.label {

    width: 180px;

    flex-shrink: 0;

}


.titik {

    width: 20px;

    flex-shrink: 0;

}


.nilai {

    flex: 1;

}


/* =========================================================
   PENUTUP
   ========================================================= */

.penutup {

    margin-top: 20px;

    text-align: justify;

    line-height: 1.7;

}


/* =========================================================
   TANDA TANGAN
   ========================================================= */

.ttd {

    width: 300px;

    margin-left: auto;

    margin-top: 45px;

    text-align: center;

    font-size: 14px;

    line-height: 1.5;

}


.ruang-ttd {

    height: 75px;

}


.nama-kades {

    font-weight: bold;

    text-decoration: underline;

}


/* =========================================================
   TOMBOL
   ========================================================= */

.tombol {

    width: 210mm;

    margin: 20px auto;

    display: flex;

    justify-content: center;

    gap: 10px;

}


button {

    padding: 10px 20px;

    border: none;

    border-radius: 4px;

    cursor: pointer;

    font-size: 14px;

}


.btn-print {

    background: #333;

    color: #fff;

}


.btn-kembali {

    background: #777;

    color: #fff;

}


/* =========================================================
   PRINT
   ========================================================= */

@media print {

    body {

        background: #fff;

    }


    .kertas {

        width: 210mm;

        min-height: 297mm;

        margin: 0;

        padding:
            15mm
            18mm
            18mm
            18mm;

    }


    .tombol {

        display: none;

    }

}

</style>

</head>


<body>


<div class="kertas">


    <!-- =====================================================
         KOP SURAT
         ===================================================== -->

    <div class="kop">


        <!-- LAMBANG KABUPATEN CIREBON -->

        <img
            src="../assets/Lambang_Kabupaten_Cirebon.gif"
            class="logo"
            alt="Lambang Kabupaten Cirebon"
        >


        <div class="kop-tengah">


            <div class="kabupaten">

                PEMERINTAH KABUPATEN CIREBON

            </div>


            <div class="kecamatan">

                KECAMATAN TALUN

            </div>


            <div class="desa">

                DESA SAMPIRAN

            </div>


            <div class="alamat">

                Jl. Ir. Soekarno (Jl. Benjaran),
                Kecamatan Talun, Kabupaten Cirebon,
                Provinsi Jawa Barat 45171

            </div>


        </div>


    </div>



    <!-- =====================================================
         JUDUL SURAT
         ===================================================== -->

    <div class="judul">


        <h2>

            <?php
            echo e($judul_surat);
            ?>

        </h2>


        <div class="nomor">

            Nomor:

            <?php

            if (!empty($nomor_surat)) {

                echo e($nomor_surat);

            } else {

                echo "______________________________";

            }

            ?>

        </div>


    </div>



    <!-- =====================================================
         ISI SURAT
         ===================================================== -->

    <div class="isi">


        <p>

            Yang bertanda tangan di bawah ini, Kepala Desa
            Sampiran, Kecamatan Talun, Kabupaten Cirebon,
            dengan ini menerangkan bahwa:

        </p>



        <!-- =================================================
             DATA PENDUDUK
             ================================================= -->

        <div class="data">


            <!-- NAMA -->

            <div class="data-row">

                <div class="label">

                    Nama

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e($nama_lengkap);
                    ?>

                </div>

            </div>



            <!-- NIK -->

            <div class="data-row">

                <div class="label">

                    NIK

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e($nik);
                    ?>

                </div>

            </div>



            <!-- JENIS KELAMIN -->

            <div class="data-row">

                <div class="label">

                    Jenis Kelamin

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e($jenis_kelamin);
                    ?>

                </div>

            </div>



            <!-- TEMPAT TANGGAL LAHIR -->

            <div class="data-row">

                <div class="label">

                    Tempat/Tanggal Lahir

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php

                    echo e($tempat_lahir);

                    if (
                        !empty(
                            $tanggal_lahir_tampil
                        )
                    ) {

                        echo ", " .
                            e(
                                $tanggal_lahir_tampil
                            );

                    }

                    ?>

                </div>

            </div>



            <!-- PEKERJAAN -->

            <div class="data-row">

                <div class="label">

                    Pekerjaan

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e($pekerjaan);
                    ?>

                </div>

            </div>



            <!-- AGAMA -->

            <div class="data-row">

                <div class="label">

                    Agama

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e($agama);
                    ?>

                </div>

            </div>



            <!-- STATUS PERKAWINAN -->

            <div class="data-row">

                <div class="label">

                    Status Perkawinan

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php
                    echo e(
                        $status_perkawinan
                    );
                    ?>

                </div>

            </div>



            <!-- ALAMAT -->

            <div class="data-row">

                <div class="label">

                    Alamat

                </div>

                <div class="titik">

                    :

                </div>

                <div class="nilai">

                    <?php

                    echo nl2br(
                        e($alamat)
                    );

                    ?>

                </div>

            </div>


        </div>



        <!-- =================================================
             KETERANGAN
             ================================================= -->

        <div class="penutup">

            Berdasarkan data dan keterangan yang ada pada
            Pemerintah Desa Sampiran, Kecamatan Talun,
            Kabupaten Cirebon, yang bersangkutan benar
            merupakan penduduk yang berdomisili di wilayah
            Desa Sampiran.

            <br><br>

            Surat keterangan ini dibuat atas permohonan yang
            bersangkutan untuk dapat dipergunakan sebagaimana
            mestinya sesuai dengan keperluan yang disampaikan
            kepada Pemerintah Desa Sampiran.

            <br><br>

            Demikian surat keterangan ini dibuat dengan
            sebenarnya agar dapat digunakan sebagaimana
            mestinya.

        </div>


    </div>



    <!-- =====================================================
         TANDA TANGAN
         ===================================================== -->

    <div class="ttd">


        Sampiran,

        <?php
        echo e($tanggal_surat);
        ?>


        <br>


        Kepala Desa Sampiran


        <div class="ruang-ttd"></div>


        <div class="nama-kades">

            ______________________________

        </div>


    </div>


</div>



<!-- =========================================================
     TOMBOL
     ========================================================= -->

<div class="tombol">


    <button
        type="button"
        class="btn-kembali"
        onclick="history.back()"
    >

        KEMBALI

    </button>


    <button
        type="button"
        class="btn-print"
        onclick="window.print()"
    >

        CETAK / SIMPAN PDF

    </button>


</div>


</body>

</html>