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

if (
    !isset($_GET["id"]) ||
    empty($_GET["id"])
) {
    die("ID pengajuan tidak ditemukan.");
}


$id_pengajuan = mysqli_real_escape_string(
    $koneksi,
    $_GET["id"]
);


/* =========================================================
   AMBIL DATA PENGAJUAN
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

        jenis_surat.nama_jenis,
        jenis_surat.kode_jenis

    FROM pengajuan_surat

    LEFT JOIN masyarakat
        ON pengajuan_surat.id_masyarakat =
           masyarakat.id_masyarakat

    LEFT JOIN jenis_surat
        ON pengajuan_surat.id_jenis_surat =
           jenis_surat.id_jenis_surat

    WHERE
        pengajuan_surat.id_pengajuan =
        '$id_pengajuan'

    LIMIT 1
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


$data = mysqli_fetch_assoc(
    $result
);


if (!$data) {

    die(
        "Data pengajuan tidak ditemukan."
    );

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
   DATA AWAL
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

$keperluan =
    $data["keperluan"] ?? "";

$nama_jenis =
    $data["nama_jenis"] ?? "";

$kode_jenis =
    $data["kode_jenis"] ?? "";


/* =========================================================
   JUDUL SURAT
   ========================================================= */

$judul_surat =
    !empty($nama_jenis)
    ? strtoupper($nama_jenis)
    : "SURAT KETERANGAN DOMISILI";

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
    Buat Surat - Sistem Surat Desa
</title>


<style>

/* =========================================================
   RESET
   ========================================================= */

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    padding: 0;

    font-family:
        Arial,
        sans-serif;

    background: #f3f3f3;

    color: #222;

}


/* =========================================================
   HEADER ADMIN
   LOGO TETAP SEPERTI DASHBOARD
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
        8px
        0;

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

    max-width: 1150px;

    background: white;

    border: 1px solid #ccc;

    padding: 30px;

    border-radius: 5px;

}


.section-title {

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom: 1px solid #ddd;

}


/* =========================================================
   FORM ROW
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

    font-family:
        Arial,
        sans-serif;

    background: white;

}


input:focus,
select:focus,
textarea:focus {

    outline: none;

    border-color: #333;

}


textarea {

    min-height: 80px;

    resize: vertical;

}


/* =========================================================
   NOMOR SURAT
   ========================================================= */

.nomor-input {

    border: 2px solid #555;

    font-weight: bold;

}


/* =========================================================
   PREVIEW TITLE
   ========================================================= */

.preview-title {

    margin-top: 35px;

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 15px;

}


/* =========================================================
   SURAT PREVIEW
   ========================================================= */

.surat-preview {

    width: 210mm;

    min-height: 297mm;

    background: white;

    border: 1px solid #aaa;

    padding:
        15mm
        18mm
        18mm
        18mm;

    margin: 0 auto;

    font-family:
        "Times New Roman",
        Times,
        serif;

    color: #000;

}


/* =========================================================
   KOP SURAT
   ========================================================= */

.kop-surat {

    display: flex;

    align-items: center;

    padding-bottom: 9px;

    border-bottom: 3px solid #000;

}


/* =========================================================
   LAMBANG KABUPATEN CIREBON
   HANYA UNTUK BLANKO SURAT
   ========================================================= */

.logo-desa {

    width: 105px;

    height: 105px;

    object-fit: contain;

    flex-shrink: 0;

}


/* =========================================================
   TEKS KOP
   ========================================================= */

.kop-text {

    flex: 1;

    text-align: center;

    padding-right: 105px;

}


.kop-text .baris1 {

    font-size: 17px;

    font-weight: bold;

    line-height: 1.3;

}


.kop-text .baris2 {

    font-size: 19px;

    font-weight: bold;

    line-height: 1.3;

}


.kop-text .baris3 {

    font-size: 24px;

    font-weight: bold;

    line-height: 1.3;

}


.kop-text .alamat {

    font-size: 11px;

    margin-top: 5px;

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

    font-size: 17px;

    font-weight: bold;

    text-decoration: underline;

}


.judul-surat p {

    margin-top: 6px;

    font-size: 14px;

}


/* =========================================================
   ISI SURAT
   ========================================================= */

.isi-surat {

    font-size: 14px;

    line-height: 1.7;

    text-align: justify;

}


.data-surat {

    margin-top: 15px;

    margin-bottom: 22px;

}


.data-row {

    display: flex;

    margin-bottom: 5px;

    line-height: 1.5;

}


.data-label {

    width: 180px;

    flex-shrink: 0;

}


.data-titik {

    width: 20px;

    flex-shrink: 0;

}


.data-value {

    flex: 1;

}


/* =========================================================
   PENUTUP
   ========================================================= */

.penutup {

    margin-top: 20px;

    line-height: 1.7;

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

    font-size: 14px;

    line-height: 1.5;

}


.ttd-space {

    height: 75px;

}


.ttd-name {

    font-weight: bold;

    text-decoration: underline;

}


/* =========================================================
   BUTTON
   ========================================================= */

.button-area {

    margin-top: 25px;

    display: flex;

    gap: 10px;

}


.btn {

    border: none;

    border-radius: 4px;

    padding:
        11px
        20px;

    text-decoration: none;

    cursor: pointer;

    font-size: 14px;

    display: inline-block;

}


.btn-pdf {

    background: #333;

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
   RESPONSIVE
   ========================================================= */

@media (max-width: 1100px) {

    .surat-preview {

        width: 100%;

        min-height: auto;

    }

}


@media (max-width: 900px) {

    .sidebar {

        width: 200px;

    }


    .content {

        margin-left: 200px;

        padding-left: 20px;

        padding-right: 20px;

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
     HEADER ADMIN
     TETAP LOGO DESA SEPERTI DASHBOARD
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
        BUAT SURAT
    </h1>


    <div class="subtitle">

        Periksa dan edit data surat sebelum dibuat menjadi PDF.

    </div>



    <!-- =====================================================
         FORM
         ===================================================== -->

    <form
        action="cetak_pdf.php"
        method="POST"
        target="_blank"
    >


        <input
            type="hidden"
            name="id_pengajuan"
            value="<?php echo e($id_pengajuan); ?>"
        >


        <div class="form-container">


            <div class="section-title">

                DATA SURAT

            </div>



            <!-- =================================================
                 NOMOR SURAT
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Nomor Surat
                    </label>


                    <input
                        type="text"
                        name="nomor_surat"
                        id="nomor_surat"
                        class="nomor-input"
                        value=""
                        placeholder="Contoh: 470/001/DS/2026"
                        required
                    >


                </div>


            </div>



            <!-- =================================================
                 JENIS DAN KODE SURAT
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Jenis Surat
                    </label>


                    <input
                        type="text"
                        name="nama_jenis"
                        value="<?php echo e($nama_jenis); ?>"
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
                    >


                </div>


            </div>



            <!-- =================================================
                 NAMA DAN NIK
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Nama Lengkap
                    </label>


                    <input
                        type="text"
                        name="nama_lengkap"
                        id="nama_lengkap"
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
                        id="nik"
                        value="<?php echo e($nik); ?>"
                        required
                    >


                </div>


            </div>



            <!-- =================================================
                 JENIS KELAMIN, TEMPAT, TANGGAL LAHIR
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Jenis Kelamin
                    </label>


                    <select
                        name="jenis_kelamin"
                        id="jenis_kelamin"
                    >


                        <option value="">
                            -- Pilih --
                        </option>


                        <option
                            value="Laki-laki"
                            <?php

                            if (
                                $jenis_kelamin
                                ===
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
                                $jenis_kelamin
                                ===
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
                        Tempat Lahir
                    </label>


                    <input
                        type="text"
                        name="tempat_lahir"
                        id="tempat_lahir"
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
                        id="tanggal_lahir"
                        value="<?php echo e($tanggal_lahir); ?>"
                    >


                </div>


            </div>



            <!-- =================================================
                 PEKERJAAN AGAMA STATUS
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Pekerjaan
                    </label>


                    <input
                        type="text"
                        name="pekerjaan"
                        id="pekerjaan"
                        value="<?php echo e($pekerjaan); ?>"
                    >


                </div>



                <div class="form-group">


                    <label>
                        Agama
                    </label>


                    <select
                        name="agama"
                        id="agama"
                    >


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
                                    $agama
                                    ===
                                    $agama_item
                                )
                                ?
                                "selected"
                                :
                                "";


                            echo
                            "<option
                                value='" .
                                e($agama_item) .
                                "' $selected
                            >" .
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
                        id="status_perkawinan"
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
                                    $status_perkawinan
                                    ===
                                    $status_item
                                )
                                ?
                                "selected"
                                :
                                "";


                            echo
                            "<option
                                value='" .
                                e($status_item) .
                                "' $selected
                            >" .
                                e($status_item) .
                            "</option>";

                        }

                        ?>


                    </select>


                </div>


            </div>



            <!-- =================================================
                 ALAMAT
                 ================================================= -->

            <div class="form-row">


                <div class="form-group full">


                    <label>
                        Alamat
                    </label>


                    <textarea
                        name="alamat"
                        id="alamat"
                    ><?php echo e($alamat); ?></textarea>


                </div>


            </div>



            <!-- =================================================
                 NO HP DAN KEPERLUAN
                 ================================================= -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        No. HP
                    </label>


                    <input
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        value="<?php echo e($no_hp); ?>"
                    >


                </div>



                <div class="form-group">


                    <label>
                        Keperluan
                    </label>


                    <input
                        type="text"
                        name="keperluan"
                        id="keperluan"
                        value="<?php echo e($keperluan); ?>"
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


                <!-- =================================================
                     KOP SURAT
                     ================================================= -->

                <div class="kop-surat">


                    <!--
                        KHUSUS BLANKO SURAT:
                        LAMBANG KABUPATEN CIREBON
                    -->

                    <img
                        src="../assets/Lambang_Kabupaten_Cirebon.gif"
                        class="logo-desa"
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

                            Jl. Ir. Soekarno (Jl. Benjaran),
                            Kecamatan Talun, Kabupaten Cirebon,
                            Provinsi Jawa Barat 45171

                        </div>


                    </div>


                </div>



                <!-- =================================================
                     JUDUL SURAT
                     ================================================= -->

                <div class="judul-surat">


                    <h2>

                        <?php
                        echo e($judul_surat);
                        ?>

                    </h2>


                    <p>

                        Nomor:

                        <span
                            id="preview_nomor_surat"
                        >

                            -

                        </span>

                    </p>


                </div>



                <!-- =================================================
                     ISI SURAT
                     ================================================= -->

                <div class="isi-surat">


                    <p>

                        Yang bertanda tangan di bawah ini,
                        Kepala Desa Sampiran, Kecamatan Talun,
                        Kabupaten Cirebon, dengan ini menerangkan
                        bahwa:

                    </p>



                    <div class="data-surat">


                        <!-- NAMA -->

                        <div class="data-row">


                            <div class="data-label">
                                Nama
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_nama"
                            >

                                <?php
                                echo e(
                                    $nama_lengkap
                                );
                                ?>

                            </div>


                        </div>



                        <!-- NIK -->

                        <div class="data-row">


                            <div class="data-label">
                                NIK
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_nik"
                            >

                                <?php
                                echo e($nik);
                                ?>

                            </div>


                        </div>



                        <!-- JENIS KELAMIN -->

                        <div class="data-row">


                            <div class="data-label">
                                Jenis Kelamin
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_jenis_kelamin"
                            >

                                <?php
                                echo e(
                                    $jenis_kelamin
                                );
                                ?>

                            </div>


                        </div>



                        <!-- TEMPAT TANGGAL LAHIR -->

                        <div class="data-row">


                            <div class="data-label">
                                Tempat/Tanggal Lahir
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_ttl"
                            >

                                <?php

                                echo e(
                                    $tempat_lahir
                                );


                                if (
                                    !empty(
                                        $tanggal_lahir
                                    )
                                ) {

                                    $tanggal_preview =
                                        strtotime(
                                            $tanggal_lahir
                                        );


                                    if (
                                        $tanggal_preview
                                        !== false
                                    ) {

                                        echo ", " .
                                            date(
                                                "d-m-Y",
                                                $tanggal_preview
                                            );

                                    }

                                }

                                ?>

                            </div>


                        </div>



                        <!-- PEKERJAAN -->

                        <div class="data-row">


                            <div class="data-label">
                                Pekerjaan
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_pekerjaan"
                            >

                                <?php
                                echo e(
                                    $pekerjaan
                                );
                                ?>

                            </div>


                        </div>



                        <!-- AGAMA -->

                        <div class="data-row">


                            <div class="data-label">
                                Agama
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_agama"
                            >

                                <?php
                                echo e(
                                    $agama
                                );
                                ?>

                            </div>


                        </div>



                        <!-- STATUS PERKAWINAN -->

                        <div class="data-row">


                            <div class="data-label">
                                Status Perkawinan
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_status"
                            >

                                <?php
                                echo e(
                                    $status_perkawinan
                                );
                                ?>

                            </div>


                        </div>



                        <!-- ALAMAT -->

                        <div class="data-row">


                            <div class="data-label">
                                Alamat
                            </div>


                            <div class="data-titik">
                                :
                            </div>


                            <div
                                class="data-value"
                                id="preview_alamat"
                            >

                                <?php
                                echo nl2br(
                                    e($alamat)
                                );
                                ?>

                            </div>


                        </div>


                    </div>



                    <!-- =================================================
                         PENUTUP SURAT
                         ================================================= -->

                    <div class="penutup">


                        Berdasarkan data dan keterangan yang
                        terdapat pada administrasi Pemerintah
                        Desa Sampiran, Kecamatan Talun,
                        Kabupaten Cirebon, dengan ini menerangkan
                        bahwa nama tersebut di atas benar
                        merupakan penduduk yang berdomisili
                        di wilayah Desa Sampiran.
                        <br><br>
                        Demikian surat keterangan ini dibuat
                        dengan sebenarnya agar dapat
                        dipergunakan sebagaimana mestinya sesuai
                        dengan keperluan yang telah disampaikan
                        kepada Pemerintah Desa Sampiran, 
                        dan dapat menjadi bahan administrasi
                        sesuai dengan keperluan yang
                        bersangkutan.


                    </div>


                </div>



                <!-- =================================================
                     TANDA TANGAN
                     ================================================= -->

                <div class="ttd">


                    Sampiran,

                    <?php
                    echo date("d-m-Y");
                    ?>


                    <br>


                    Kepala Desa Sampiran


                    <div class="ttd-space"></div>


                    <div class="ttd-name">

                        ______________________________

                    </div>


                </div>


            </div>



            <!-- =================================================
                 BUTTON
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


</div>



<!-- =========================================================
     JAVASCRIPT PREVIEW
     ========================================================= -->

<script>


document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =====================================================
           AMBIL ELEMENT
           ===================================================== */

        const nomorSurat =
            document.getElementById(
                "nomor_surat"
            );


        const previewNomor =
            document.getElementById(
                "preview_nomor_surat"
            );


        const nama =
            document.getElementById(
                "nama_lengkap"
            );


        const previewNama =
            document.getElementById(
                "preview_nama"
            );


        const nik =
            document.getElementById(
                "nik"
            );


        const previewNik =
            document.getElementById(
                "preview_nik"
            );


        const jenisKelamin =
            document.getElementById(
                "jenis_kelamin"
            );


        const previewJenisKelamin =
            document.getElementById(
                "preview_jenis_kelamin"
            );


        const tempatLahir =
            document.getElementById(
                "tempat_lahir"
            );


        const tanggalLahir =
            document.getElementById(
                "tanggal_lahir"
            );


        const previewTTL =
            document.getElementById(
                "preview_ttl"
            );


        const pekerjaan =
            document.getElementById(
                "pekerjaan"
            );


        const previewPekerjaan =
            document.getElementById(
                "preview_pekerjaan"
            );


        const agama =
            document.getElementById(
                "agama"
            );


        const previewAgama =
            document.getElementById(
                "preview_agama"
            );


        const status =
            document.getElementById(
                "status_perkawinan"
            );


        const previewStatus =
            document.getElementById(
                "preview_status"
            );


        const alamat =
            document.getElementById(
                "alamat"
            );


        const previewAlamat =
            document.getElementById(
                "preview_alamat"
            );



        /* =====================================================
           UPDATE NOMOR SURAT
           ===================================================== */

        function updateNomor()
        {

            if (
                nomorSurat.value.trim()
                ===
                ""
            ) {

                previewNomor.textContent =
                    "-";

            } else {

                previewNomor.textContent =
                    nomorSurat.value;

            }

        }



        /* =====================================================
           UPDATE NAMA
           ===================================================== */

        function updateNama()
        {

            previewNama.textContent =
                nama.value;

        }



        /* =====================================================
           UPDATE NIK
           ===================================================== */

        function updateNik()
        {

            previewNik.textContent =
                nik.value;

        }



        /* =====================================================
           UPDATE JENIS KELAMIN
           ===================================================== */

        function updateJenisKelamin()
        {

            previewJenisKelamin.textContent =
                jenisKelamin.value;

        }



        /* =====================================================
           UPDATE TEMPAT TANGGAL LAHIR
           ===================================================== */

        function updateTTL()
        {

            let hasil =
                tempatLahir.value;


            if (
                tanggalLahir.value
                !==
                ""
            ) {

                const tanggal =
                    new Date(
                        tanggalLahir.value
                        + "T00:00:00"
                    );


                const hari =
                    String(
                        tanggal.getDate()
                    ).padStart(2, "0");


                const bulan =
                    String(
                        tanggal.getMonth() + 1
                    ).padStart(2, "0");


                const tahun =
                    tanggal.getFullYear();


                if (
                    hasil !== ""
                ) {

                    hasil +=
                        ", ";

                }


                hasil +=
                    hari +
                    "-" +
                    bulan +
                    "-" +
                    tahun;

            }


            previewTTL.textContent =
                hasil;

        }



        /* =====================================================
           UPDATE PEKERJAAN
           ===================================================== */

        function updatePekerjaan()
        {

            previewPekerjaan.textContent =
                pekerjaan.value;

        }



        /* =====================================================
           UPDATE AGAMA
           ===================================================== */

        function updateAgama()
        {

            previewAgama.textContent =
                agama.value;

        }



        /* =====================================================
           UPDATE STATUS
           ===================================================== */

        function updateStatus()
        {

            previewStatus.textContent =
                status.value;

        }



        /* =====================================================
           UPDATE ALAMAT
           ===================================================== */

        function updateAlamat()
        {

            previewAlamat.innerHTML =
                alamat.value
                    .replace(
                        /\n/g,
                        "<br>"
                    );

        }



        /* =====================================================
           EVENT INPUT
           ===================================================== */

        nomorSurat.addEventListener(
            "input",
            updateNomor
        );


        nama.addEventListener(
            "input",
            updateNama
        );


        nik.addEventListener(
            "input",
            updateNik
        );


        jenisKelamin.addEventListener(
            "change",
            updateJenisKelamin
        );


        tempatLahir.addEventListener(
            "input",
            updateTTL
        );


        tanggalLahir.addEventListener(
            "change",
            updateTTL
        );


        pekerjaan.addEventListener(
            "input",
            updatePekerjaan
        );


        agama.addEventListener(
            "change",
            updateAgama
        );


        status.addEventListener(
            "change",
            updateStatus
        );


        alamat.addEventListener(
            "input",
            updateAlamat
        );



        /* =====================================================
           UPDATE AWAL
           ===================================================== */

        updateNomor();

        updateNama();

        updateNik();

        updateJenisKelamin();

        updateTTL();

        updatePekerjaan();

        updateAgama();

        updateStatus();

        updateAlamat();


    }
);

</script>


</body>

</html>
```
