```php
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

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("ID pengajuan tidak ditemukan.");
}

$id_pengajuan = (int) $_GET["id"];


/* =========================================================
   AMBIL DATA PENGAJUAN
   ========================================================= */

$query = "
    SELECT

        pengajuan_surat.id_pengajuan,
        pengajuan_surat.tanggal_pengajuan,
        pengajuan_surat.keperluan,
        pengajuan_surat.status_pengajuan,
        pengajuan_surat.catatan,

        masyarakat.id_masyarakat,
        masyarakat.nama_lengkap,
        masyarakat.nik,
        masyarakat.no_kk,
        masyarakat.jenis_kelamin,
        masyarakat.tempat_lahir,
        masyarakat.tanggal_lahir,
        masyarakat.pekerjaan,
        masyarakat.agama,
        masyarakat.status_perkawinan,
        masyarakat.alamat,
        masyarakat.no_hp,

        jenis_surat.id_jenis_surat,
        jenis_surat.nama_jenis,
        jenis_surat.kode_jenis,
        jenis_surat.deskripsi

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat

    WHERE pengajuan_surat.id_pengajuan =
          $id_pengajuan

    LIMIT 1
";


$result = mysqli_query($koneksi, $query);


if (!$result) {
    die(
        "Query gagal: " .
        mysqli_error($koneksi)
    );
}


$data = mysqli_fetch_assoc($result);


if (!$data) {
    die("Data pengajuan tidak ditemukan.");
}


/* =========================================================
   FUNGSI ESCAPE HTML
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
   DATA DASAR
   ========================================================= */

$nama_lengkap =
    $data["nama_lengkap"] ?? "";

$nik =
    $data["nik"] ?? "";

$no_kk =
    $data["no_kk"] ?? "";

$jenis_kelamin =
    $data["jenis_kelamin"] ?? "";

$tempat_lahir =
    $data["tempat_lahir"] ?? "";

$tanggal_lahir =
    $data["tanggal_lahir"] ?? "";

$pekerjaan =
    $data["pekerjaan"] ?? "";

$agama =
    $data["agama"] ?? "";

$status_perkawinan =
    $data["status_perkawinan"] ?? "";

$alamat =
    $data["alamat"] ?? "";

$no_hp =
    $data["no_hp"] ?? "";

$nama_jenis =
    $data["nama_jenis"] ?? "";

$kode_jenis =
    strtoupper(
        trim(
            $data["kode_jenis"] ?? ""
        )
    );

$deskripsi =
    $data["deskripsi"] ?? "";

$tanggal_pengajuan =
    $data["tanggal_pengajuan"] ?? "";

$keperluan =
    $data["keperluan"] ?? "";

$status_pengajuan =
    $data["status_pengajuan"] ?? "";

$catatan =
    $data["catatan"] ?? "";


/* =========================================================
   TENTUKAN FILE PEMBUATAN SURAT
   ========================================================= */

/*
   SKU menggunakan file khusus:

   buat_sku.php

   Surat selain SKU menggunakan:

   buat_surat.php
*/


if ($kode_jenis === "SKU") {

    $file_buat_surat =
        "buat_sku.php";

    $label_tombol =
        "BUAT SURAT SKU";

    $keterangan_pembuatan =
        "Pengajuan ini merupakan Surat Keterangan Usaha. Pilih menu untuk membuat atau mengunggah surat SKU.";

} else {

    $file_buat_surat =
        "buat_surat.php";

    $label_tombol =
        "BUAT SURAT";

    $keterangan_pembuatan =
        "Gunakan menu berikut untuk membuat surat berdasarkan data pengajuan.";

}


/* =========================================================
   LINK PEMBUATAN SURAT
   ========================================================= */

$link_buat_surat =
    $file_buat_surat .
    "?id=" .
    $id_pengajuan;

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
    Detail Cetak Surat
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

    font-family: Arial, sans-serif;

    background: #f5f5f5;

    color: #222;

}


/* =========================================================
   HEADER
   ========================================================= */

.header {

    width: 100%;

    height: 80px;

    background: #d9d9d9;

    display: flex;

    align-items: center;

    padding-left: 20px;

    position: fixed;

    top: 0;

    left: 0;

    z-index: 100;

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


/* =========================================================
   SIDEBAR
   ========================================================= */

.sidebar {

    width: 240px;

    height: calc(100vh - 80px);

    background: #eeeeee;

    position: fixed;

    top: 80px;

    left: 0;

    padding-top: 20px;

    overflow-y: auto;

}


.sidebar a {

    display: block;

    width: 100%;

    padding: 13px 25px;

    text-decoration: none;

    color: #222;

    font-size: 15px;

}


.sidebar a:hover {

    background: #d9d9d9;

}


/* =========================================================
   CONTENT
   ========================================================= */

.content {

    margin-left: 240px;

    padding: 110px 40px 50px 40px;

    min-height: 100vh;

}


.content h1 {

    margin: 0 0 10px 0;

    font-size: 28px;

}


.subtitle {

    margin-bottom: 25px;

    color: #555;

}


/* =========================================================
   BOX
   ========================================================= */

.box {

    background: white;

    border: 1px solid #ccc;

    padding: 25px;

    margin-bottom: 25px;

    border-radius: 5px;

}


.box-title {

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom: 1px solid #ddd;

}


/* =========================================================
   DETAIL
   ========================================================= */

.detail-row {

    display: flex;

    margin-bottom: 15px;

    font-size: 15px;

}


.detail-label {

    width: 210px;

    font-weight: bold;

}


.detail-value {

    flex: 1;

}


/* =========================================================
   STATUS
   ========================================================= */

.status {

    display: inline-block;

    padding: 6px 12px;

    background: #eeeeee;

    border-radius: 4px;

    font-size: 13px;

    font-weight: bold;

}


/* =========================================================
   FILE BOX
   ========================================================= */

.file-box {

    border: 1px dashed #aaa;

    padding: 25px;

    background: #fafafa;

}


.file-box p {

    margin-top: 0;

    color: #555;

    line-height: 1.6;

}


/* =========================================================
   INPUT FILE
   ========================================================= */

.file-input {

    margin-top: 15px;

    margin-bottom: 20px;

}


.file-input input[type="file"] {

    padding: 8px;

    width: 100%;

    border: 1px solid #bbb;

    background: white;

    border-radius: 4px;

}


/* =========================================================
   BUTTON AREA
   ========================================================= */

.button-area {

    margin-top: 25px;

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}


/* =========================================================
   BUTTON
   ========================================================= */

.btn {

    display: inline-block;

    padding: 10px 18px;

    border: none;

    border-radius: 4px;

    text-decoration: none;

    font-size: 14px;

    cursor: pointer;

}


/* =========================================================
   BUTTON BUAT SURAT
   ========================================================= */

.btn-buat {

    display: inline-block;

    padding: 12px 20px;

    background: #333;

    color: white !important;

    text-decoration: none;

    border-radius: 4px;

    font-size: 14px;

    font-weight: bold;

    cursor: pointer;

    margin-top: 5px;

}


.btn-buat:hover {

    background: #555;

}


/* =========================================================
   BUTTON UPLOAD
   ========================================================= */

.btn-upload {

    background: #333;

    color: white;

}


.btn-upload:hover {

    background: #555;

}


/* =========================================================
   BUTTON KEMBALI
   ========================================================= */

.btn-kembali {

    background: #777;

    color: white;

}


.btn-kembali:hover {

    background: #666;

}


/* =========================================================
   PEMISAH
   ========================================================= */

.pemisah {

    border: 0;

    border-top: 1px solid #ddd;

    margin: 25px 0;

}


/* =========================================================
   INFO KODE
   ========================================================= */

.kode-box {

    display: inline-block;

    padding: 6px 10px;

    background: #eeeeee;

    border: 1px solid #ccc;

    border-radius: 4px;

    font-weight: bold;

}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 800px) {

    .sidebar {

        width: 200px;

    }


    .content {

        margin-left: 200px;

        padding-left: 20px;

        padding-right: 20px;

    }


    .detail-row {

        flex-direction: column;

    }


    .detail-label {

        width: 100%;

        margin-bottom: 5px;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     HEADER
     ========================================================= -->

<div class="header">

    <img
        src="../assets/logo_desa.png"
        alt="Logo Desa Sampiran"
    >

    <div class="header-title">

        SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA

    </div>

</div>



<!-- =========================================================
     SIDEBAR
     ========================================================= -->

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



<!-- =========================================================
     CONTENT
     ========================================================= -->

<div class="content">


    <h1>
        CETAK SURAT
    </h1>


    <div class="subtitle">

        Detail pengajuan surat yang siap diproses.

    </div>



    <!-- =====================================================
         INFORMASI MASYARAKAT
         ===================================================== -->

    <div class="box">

        <div class="box-title">

            INFORMASI MASYARAKAT

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Nama Lengkap
            </div>

            <div class="detail-value">

                <?= e($nama_lengkap) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                NIK
            </div>

            <div class="detail-value">

                <?= e($nik) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Nomor KK
            </div>

            <div class="detail-value">

                <?= e($no_kk) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Jenis Kelamin
            </div>

            <div class="detail-value">

                <?= e($jenis_kelamin) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Tempat, Tanggal Lahir
            </div>

            <div class="detail-value">

                <?= e($tempat_lahir) ?>

                <?php if (!empty($tanggal_lahir)) { ?>

                    ,

                    <?= e($tanggal_lahir) ?>

                <?php } ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Pekerjaan
            </div>

            <div class="detail-value">

                <?= e($pekerjaan) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Agama
            </div>

            <div class="detail-value">

                <?= e($agama) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Status Perkawinan
            </div>

            <div class="detail-value">

                <?= e($status_perkawinan) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Alamat
            </div>

            <div class="detail-value">

                <?= nl2br(e($alamat)) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                No. HP
            </div>

            <div class="detail-value">

                <?= e($no_hp) ?>

            </div>

        </div>

    </div>



    <!-- =====================================================
         INFORMASI PENGAJUAN
         ===================================================== -->

    <div class="box">

        <div class="box-title">

            INFORMASI PENGAJUAN

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Nomor Pengajuan
            </div>

            <div class="detail-value">

                <?= e($id_pengajuan) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Jenis Surat
            </div>

            <div class="detail-value">

                <?= e($nama_jenis) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Kode Surat
            </div>

            <div class="detail-value">

                <span class="kode-box">

                    <?= e($kode_jenis) ?>

                </span>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Deskripsi
            </div>

            <div class="detail-value">

                <?= e($deskripsi) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Tanggal Pengajuan
            </div>

            <div class="detail-value">

                <?= e($tanggal_pengajuan) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Keperluan
            </div>

            <div class="detail-value">

                <?= e($keperluan) ?>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Status
            </div>

            <div class="detail-value">

                <span class="status">

                    <?= e($status_pengajuan) ?>

                </span>

            </div>

        </div>


        <div class="detail-row">

            <div class="detail-label">
                Catatan
            </div>

            <div class="detail-value">

                <?php

                if (!empty($catatan)) {

                    echo nl2br(
                        e($catatan)
                    );

                } else {

                    echo "-";

                }

                ?>

            </div>

        </div>

    </div>



    <!-- =====================================================
         PEMBUATAN SURAT
         ===================================================== -->

    <div class="box">

        <div class="box-title">

            PEMBUATAN SURAT

        </div>


        <div class="file-box">


            <!-- =================================================
                 KETERANGAN SESUAI JENIS SURAT
                 ================================================= -->

            <p>

                <?= e($keterangan_pembuatan) ?>

            </p>



            <!-- =================================================
                 TOMBOL PEMBUATAN SURAT
                 ================================================= -->

            <a
                href="<?= e($link_buat_surat) ?>"
                class="btn-buat"
            >

                <?= e($label_tombol) ?>

            </a>



            <!-- =================================================
                 PEMISAH
                 ================================================= -->

            <hr class="pemisah">



            <!-- =================================================
                 UPLOAD PDF
                 ================================================= -->

            <p>

                Jika surat sudah dibuat dalam bentuk PDF,
                admin juga dapat mengunggah file surat
                melalui menu berikut.

            </p>


            <form
                action="upload_surat.php"
                method="POST"
                enctype="multipart/form-data"
            >


                <input
                    type="hidden"
                    name="id_pengajuan"
                    value="<?= e($id_pengajuan) ?>"
                >


                <div class="file-input">

                    <input
                        type="file"
                        name="file_surat"
                        accept=".pdf,application/pdf"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-upload"
                >

                    UPLOAD SURAT PDF

                </button>


            </form>


        </div>

    </div>



    <!-- =====================================================
         TOMBOL KEMBALI
         ===================================================== -->

    <div class="button-area">

        <a
            href="cetak_surat.php"
            class="btn btn-kembali"
        >

            KEMBALI

        </a>

    </div>


</div>


</body>

</html>
```
