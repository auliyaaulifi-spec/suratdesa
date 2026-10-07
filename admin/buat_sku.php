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

$id_pengajuan = mysqli_real_escape_string(
    $koneksi,
    $_GET["id"]
);


/* =========================================================
   AMBIL DATA PENGAJUAN + MASYARAKAT + DATA USAHA
   ========================================================= */

$query = "
    SELECT

        pengajuan_surat.id_pengajuan,
        pengajuan_surat.tanggal_pengajuan,
        pengajuan_surat.keperluan,
        pengajuan_surat.status_pengajuan,

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

        data_usaha.id_data_usaha,
        data_usaha.id_pengajuan AS usaha_id_pengajuan,
        data_usaha.nama_usaha,
        data_usaha.jenis_usaha,
        data_usaha.alamat_usaha,
        data_usaha.nama_pemilik,

        jenis_surat.nama_jenis,
        jenis_surat.kode_jenis

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat

    LEFT JOIN data_usaha
        ON pengajuan_surat.id_pengajuan =
           data_usaha.id_pengajuan

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat

    WHERE pengajuan_surat.id_pengajuan =
          '$id_pengajuan'

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
   DATA MASYARAKAT
   ========================================================= */

$nama_lengkap =
    $data["nama_lengkap"] ?? "";

$nik =
    $data["nik"] ?? "";

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


/* =========================================================
   DATA USAHA
   ========================================================= */

$nama_usaha =
    $data["nama_usaha"] ?? "";

$jenis_usaha =
    $data["jenis_usaha"] ?? "";

$alamat_usaha =
    $data["alamat_usaha"] ?? "";

$nama_pemilik =
    $data["nama_pemilik"] ?? "";


/* =========================================================
   DATA SURAT
   ========================================================= */

$nama_jenis =
    $data["nama_jenis"] ??
    "Surat Keterangan Usaha";

$kode_jenis =
    $data["kode_jenis"] ??
    "SKU";


/*
   Nomor surat dikosongkan
   agar dapat diisi admin.
*/

$nomor_surat = "";


/*
   Jabatan penandatangan
*/

$jabatan_penandatangan =
    "Sekretaris Desa Sampiran";


/*
   Nama penandatangan
*/

$nama_penandatangan =
    "SITI SUGIYANTI";


/*
   Tanggal surat
*/

$tanggal_surat =
    date("Y-m-d");

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
    Buat Surat Keterangan Usaha
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

    font-family: Arial, sans-serif;

    background: #f3f3f3;

    color: #222;

}


/* =========================================================
   HEADER
   ========================================================= */

.header {

    height: 80px;

    background: #d9d9d9;

    display: flex;

    align-items: center;

    padding-left: 20px;

    position: fixed;

    top: 0;

    left: 0;

    width: 100%;

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

}


/* =========================================================
   SIDEBAR
   ========================================================= */

.sidebar {

    width: 240px;

    height: calc(100vh - 80px);

    background: #eeeeee;

    position: fixed;

    left: 0;

    top: 80px;

    padding-top: 20px;

    overflow-y: auto;

}


.sidebar a {

    display: block;

    width: 100%;

    padding: 13px 25px;

    color: #222;

    text-decoration: none;

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

    margin: 0 0 8px 0;

    font-size: 28px;

}


.subtitle {

    color: #666;

    margin-bottom: 25px;

}


/* =========================================================
   FORM CONTAINER
   ========================================================= */

.form-container {

    max-width: 1100px;

    background: white;

    border: 1px solid #ccc;

    padding: 30px;

    border-radius: 5px;

}


/* =========================================================
   SECTION TITLE
   ========================================================= */

.section-title {

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom: 1px solid #ddd;

}


/* =========================================================
   FORM
   ========================================================= */

.form-row {

    display: flex;

    gap: 20px;

    margin-bottom: 16px;

}


.form-group {

    flex: 1;

}


.form-group.full {

    width: 100%;

}


label {

    display: block;

    font-weight: bold;

    font-size: 14px;

    margin-bottom: 7px;

}


input,
select,
textarea {

    width: 100%;

    padding: 10px;

    border: 1px solid #bbb;

    border-radius: 4px;

    font-size: 14px;

    font-family: Arial, sans-serif;

    background: white;

}


input:focus,
select:focus,
textarea:focus {

    outline: none;

    border-color: #555;

}


textarea {

    min-height: 80px;

    resize: vertical;

}


/* =========================================================
   KOTAK DATA USAHA
   ========================================================= */

.data-usaha-box {

    background: #fafafa;

    border: 1px solid #ccc;

    padding: 20px;

    border-radius: 5px;

    margin-bottom: 25px;

}


/* =========================================================
   PREVIEW
   ========================================================= */

.preview-title {

    margin-top: 35px;

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 15px;

}


.surat-preview {

    background: white;

    border: 1px solid #bbb;

    padding: 35px;

    max-width: 900px;

    margin: 0 auto;

    min-height: 1000px;

}


/* =========================================================
   KOP SURAT
   ========================================================= */

.kop-surat {

    display: flex;

    align-items: center;

    border-bottom: 3px solid #222;

    padding-bottom: 10px;

    margin-bottom: 5px;

}


.logo-kabupaten {

    width: 100px;

    height: 100px;

    object-fit: contain;

    flex-shrink: 0;

}


.kop-text {

    flex: 1;

    text-align: center;

}


.kop-text .baris1 {

    font-size: 17px;

    font-weight: bold;

}


.kop-text .baris2 {

    font-size: 19px;

    font-weight: bold;

}


.kop-text .baris3 {

    font-size: 23px;

    font-weight: bold;

}


.kop-text .alamat {

    font-size: 11px;

    margin-top: 4px;

    line-height: 1.4;

}


/* =========================================================
   JUDUL SURAT
   ========================================================= */

.judul-surat {

    text-align: center;

    margin-top: 25px;

    margin-bottom: 25px;

}


.judul-surat h2 {

    margin: 0;

    font-family:
        "Times New Roman",
        Times,
        serif;

    font-size: 18px;

    text-decoration: underline;

}


.nomor-surat-preview {

    margin-top: 6px;

    font-family:
        "Times New Roman",
        Times,
        serif;

    font-size: 14px;

}


/* =========================================================
   ISI SURAT
   ========================================================= */

.isi-surat {

    font-family:
        "Times New Roman",
        Times,
        serif;

    font-size: 14px;

    line-height: 1.6;

    text-align: justify;

}


.data-surat {

    margin-top: 15px;

    margin-bottom: 20px;

}


.data-row {

    display: flex;

    margin-bottom: 5px;

}


.data-label {

    width: 180px;

}


.data-titik {

    width: 20px;

}


.data-value {

    flex: 1;

}


/* =========================================================
   PARAGRAF USAHA
   ========================================================= */

.paragraf-usaha {

    margin-top: 15px;

    text-align: justify;

}


.nama-usaha-tebal {

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

    width: 300px;

    margin-left: auto;

    margin-top: 45px;

    text-align: center;

    font-family:
        "Times New Roman",
        Times,
        serif;

    font-size: 14px;

}


.ttd-space {

    height: 80px;

}


.ttd-name {

    font-weight: bold;

    text-decoration: underline;

}


.ttd-jabatan {

    margin-bottom: 5px;

}


/* =========================================================
   BUTTON
   ========================================================= */

.button-area {

    margin-top: 25px;

    display: flex;

    gap: 10px;

    flex-wrap: wrap;

}


.btn {

    border: none;

    border-radius: 4px;

    padding: 11px 20px;

    text-decoration: none;

    cursor: pointer;

    font-size: 14px;

    display: inline-block;

}


.btn-pdf {

    background: #333;

    color: white;

}


.btn-upload {

    background: #555;

    color: white;

}


.btn-kembali {

    background: #777;

    color: white;

}


.btn:hover {

    opacity: .85;

}


/* =========================================================
   UPLOAD PDF
   ========================================================= */

.upload-box {

    margin-top: 30px;

    padding: 20px;

    background: #fafafa;

    border: 1px solid #ccc;

    border-radius: 5px;

}


.upload-box-title {

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 10px;

}


.upload-description {

    color: #666;

    margin-top: 0;

    margin-bottom: 15px;

    line-height: 1.6;

}


.upload-input {

    margin-bottom: 15px;

}


.upload-input input[type="file"] {

    width: 100%;

    padding: 8px;

    border: 1px solid #bbb;

    border-radius: 4px;

    background: white;

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
   RESPONSIVE
   ========================================================= */

@media (max-width: 900px) {

    .sidebar {

        width: 200px;

    }


    .content {

        margin-left: 200px;

    }


    .form-row {

        flex-direction: column;

        gap: 0;

    }


    .surat-preview {

        padding: 25px;

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
        BUAT SURAT KETERANGAN USAHA
    </h1>

    <div class="subtitle">

        Periksa dan edit data SKU sebelum dibuat menjadi PDF.

    </div>


    <!-- =====================================================
         FORM UTAMA BUAT PDF
         ===================================================== -->

    <form
        action="cetak_sku.php"
        method="POST"
        target="_blank"
    >

        <input
            type="hidden"
            name="id_pengajuan"
            value="<?php echo e($id_pengajuan); ?>"
        >


        <div class="form-container">


            <!-- =================================================
                 DATA SURAT
                 ================================================= -->

            <div class="section-title">

                DATA SURAT

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Jenis Surat
                    </label>

                    <input
                        type="text"
                        name="nama_jenis"
                        value="<?php echo e($nama_jenis); ?>"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>
                        Kode Surat
                    </label>

                    <input
                        type="text"
                        name="kode_jenis"
                        value="<?php echo e($kode_jenis); ?>"
                        readonly
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Nomor Surat
                    </label>

                    <input
                        type="text"
                        name="nomor_surat"
                        value="<?php echo e($nomor_surat); ?>"
                        placeholder="Contoh: 470/001/DS/2026"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Tanggal Surat
                    </label>

                    <input
                        type="date"
                        name="tanggal_surat"
                        value="<?php echo e($tanggal_surat); ?>"
                        required
                    >

                </div>

            </div>



            <!-- =================================================
                 DATA PEMOHON
                 ================================================= -->

            <div class="section-title">

                DATA PEMOHON

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        value="<?php echo e($nama_lengkap); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        NIK
                    </label>

                    <input
                        type="text"
                        name="nik"
                        value="<?php echo e($nik); ?>"
                        required
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Tempat Lahir
                    </label>

                    <input
                        type="text"
                        name="tempat_lahir"
                        value="<?php echo e($tempat_lahir); ?>"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="<?php echo e($tanggal_lahir); ?>"
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin">

                        <option value="">
                            -- Pilih --
                        </option>

                        <option
                            value="Laki-laki"
                            <?php
                            if (
                                $jenis_kelamin ==
                                "Laki-laki"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Laki-laki
                        </option>

                        <option
                            value="Perempuan"
                            <?php
                            if (
                                $jenis_kelamin ==
                                "Perempuan"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Perempuan
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Agama
                    </label>

                    <select name="agama">

                        <option value="">
                            -- Pilih --
                        </option>

                        <?php

                        $agama_list = [

                            "Islam",
                            "Kristen",
                            "Katolik",
                            "Hindu",
                            "Buddha",
                            "Konghucu"

                        ];

                        foreach (
                            $agama_list
                            as $agama_item
                        ) {

                            $selected =
                                (
                                    $agama ==
                                    $agama_item
                                )
                                ? "selected"
                                : "";

                            echo
                            "<option value=\"" .
                            e($agama_item) .
                            "\" $selected>" .
                            e($agama_item) .
                            "</option>";

                        }

                        ?>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Status Perkawinan
                    </label>

                    <select
                        name="status_perkawinan"
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        <?php

                        $status_list = [

                            "Belum Kawin",
                            "Kawin",
                            "Cerai Hidup",
                            "Cerai Mati"

                        ];

                        foreach (
                            $status_list
                            as $status_item
                        ) {

                            $selected =
                                (
                                    $status_perkawinan ==
                                    $status_item
                                )
                                ? "selected"
                                : "";

                            echo
                            "<option value=\"" .
                            e($status_item) .
                            "\" $selected>" .
                            e($status_item) .
                            "</option>";

                        }

                        ?>

                    </select>

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Pekerjaan
                    </label>

                    <input
                        type="text"
                        name="pekerjaan"
                        value="<?php echo e($pekerjaan); ?>"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Kewarganegaraan
                    </label>

                    <input
                        type="text"
                        name="kewarganegaraan"
                        value="INDONESIA"
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group full">

                    <label>
                        Alamat Pemohon
                    </label>

                    <textarea
                        name="alamat"
                    ><?php echo e($alamat); ?></textarea>

                </div>

            </div>



            <!-- =================================================
                 DATA USAHA
                 ================================================= -->

            <div class="section-title">

                DATA USAHA

            </div>


            <div class="data-usaha-box">


                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Nama Pemilik Usaha
                        </label>

                        <input
                            type="text"
                            name="nama_pemilik"
                            value="<?php echo e($nama_pemilik); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Nama Usaha
                        </label>

                        <input
                            type="text"
                            name="nama_usaha"
                            value="<?php echo e($nama_usaha); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Jenis Usaha
                        </label>

                        <input
                            type="text"
                            name="jenis_usaha"
                            value="<?php echo e($jenis_usaha); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Alamat Usaha
                        </label>

                        <textarea
                            name="alamat_usaha"
                            required
                        ><?php echo e($alamat_usaha); ?></textarea>

                    </div>

                </div>


            </div>



            <!-- =================================================
                 PENANDATANGAN
                 ================================================= -->

            <div class="section-title">

                PENANDATANGAN

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label>
                        Jabatan
                    </label>

                    <input
                        type="text"
                        name="jabatan_penandatangan"
                        value="<?php echo e(
                            $jabatan_penandatangan
                        ); ?>"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Nama Penandatangan
                    </label>

                    <input
                        type="text"
                        name="nama_penandatangan"
                        value="<?php echo e(
                            $nama_penandatangan
                        ); ?>"
                    >

                </div>

            </div>



            <!-- =================================================
                 PREVIEW SURAT
                 ================================================= -->

            <div class="preview-title">

                PREVIEW SURAT

            </div>


            <div class="surat-preview">


                <!-- KOP -->

                <div class="kop-surat">

                    <img
                        src="../assets/Lambang_Kabupaten_Cirebon.gif"
                        class="logo-kabupaten"
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



                <!-- JUDUL -->

                <div class="judul-surat">

                    <h2>

                        SURAT KETERANGAN USAHA

                    </h2>


                    <div class="nomor-surat-preview">

                        Nomor:

                        <?php

                        if (!empty($nomor_surat)) {

                            echo e($nomor_surat);

                        } else {

                            echo "____________________________";

                        }

                        ?>

                    </div>

                </div>



                <!-- ISI -->

                <div class="isi-surat">


                    <p>

                        Yang bertanda tangan di bawah ini
                        menerangkan bahwa:

                    </p>


                    <div class="data-surat">


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


                    <div class="data-surat">


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
                                echo e($nama_lengkap);
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
                                        $tanggal_lahir
                                    )
                                ) {

                                    echo ", " .
                                        date(
                                            "d-m-Y",
                                            strtotime(
                                                $tanggal_lahir
                                            )
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
                                echo e(
                                    $pekerjaan
                                );
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

                                INDONESIA

                            </div>

                        </div>

                    </div>



                    <!-- =================================================
                         KETERANGAN USAHA
                         ================================================= -->

                    <div class="paragraf-usaha">

                        Orang tersebut di atas adalah benar
                        penduduk Desa Sampiran, Kecamatan Talun,
                        Kabupaten Cirebon, dan yang bersangkutan
                        mempunyai usaha yang bergerak di bidang:

                        <strong>

                            <?php
                            echo e($jenis_usaha);
                            ?>

                        </strong>

                        dengan nama usaha:

                        <strong class="nama-usaha-tebal">

                            <?php
                            echo e($nama_usaha);
                            ?>

                        </strong>

                        yang beralamat di:

                        <strong>

                            <?php
                            echo e($alamat_usaha);
                            ?>

                        </strong>

                        .

                    </div>



                    <!-- PENUTUP -->

                    <div class="penutup">

                        Demikian Surat Keterangan Usaha ini
                        dibuat dengan sebenarnya untuk
                        dipergunakan sebagaimana mestinya
                        dan kepada yang berkepentingan agar
                        menjadi tahu serta mohon maklum adanya.

                    </div>


                </div>



                <!-- TANDA TANGAN -->

                <div class="ttd">

                    Sampiran,

                    <?php

                    echo date(
                        "d-m-Y",
                        strtotime(
                            $tanggal_surat
                        )
                    );

                    ?>


                    <br>


                    <div class="ttd-jabatan">

                        <?php
                        echo e(
                            $jabatan_penandatangan
                        );
                        ?>

                    </div>


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



            <!-- =================================================
                 TOMBOL BUAT PDF
                 ================================================= -->

            <div class="button-area">


                <a
                    href="cetak_surat.php"
                    class="btn btn-kembali"
                >

                    KEMBALI

                </a>


                <button
                    type="submit"
                    class="btn btn-pdf"
                >

                    BUAT PDF

                </button>


            </div>


        </div>

    </form>


    <!-- =====================================================
         UPLOAD SURAT PDF
         FORM TERPISAH DARI FORM BUAT PDF
         ===================================================== -->

    <div class="form-container upload-box">

        <div class="upload-box-title">

            UPLOAD SURAT PDF

        </div>


        <p class="upload-description">

            Jika surat SKU sudah dibuat dalam bentuk PDF,
            admin dapat mengunggah file tersebut untuk
            disimpan sebagai arsip surat pengajuan ini.

        </p>


        <form
            action="upload_surat.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- ID PENGAJUAN -->

            <input
                type="hidden"
                name="id_pengajuan"
                value="<?php echo e($id_pengajuan); ?>"
            >


            <!-- FILE PDF -->

            <div class="upload-input">

                <label>
                    Pilih File Surat PDF
                </label>

                <input
                    type="file"
                    name="file_surat"
                    accept=".pdf,application/pdf"
                    required
                >

            </div>


            <!-- TOMBOL UPLOAD -->

            <button
                type="submit"
                class="btn btn-upload"
            >

                UPLOAD SURAT PDF

            </button>

        </form>

    </div>


</div>


</body>

</html>