
<?php

session_start();

/* =========================================================
   CEK LOGIN ADMIN
========================================================= */

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../login.php");
    exit;
}


/* =========================================================
   KONEKSI DATABASE
========================================================= */

include "../config/koneksi.php";


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
   FUNGSI TANGGAL INDONESIA
========================================================= */

function tanggalIndonesia($tanggal)
{
    if (
        empty($tanggal) ||
        $tanggal == "0000-00-00"
    ) {
        return "-";
    }

    $bulan = [
        1  => "Januari",
        2  => "Februari",
        3  => "Maret",
        4  => "April",
        5  => "Mei",
        6  => "Juni",
        7  => "Juli",
        8  => "Agustus",
        9  => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Desember"
    ];

    $pecah = explode("-", $tanggal);

    if (count($pecah) != 3) {
        return $tanggal;
    }

    $tahun = $pecah[0];
    $bulanAngka = (int) $pecah[1];
    $hari = (int) $pecah[2];

    if (!isset($bulan[$bulanAngka])) {
        return $tanggal;
    }

    return $hari . " " .
        $bulan[$bulanAngka] . " " .
        $tahun;
}


/* =========================================================
   VALIDASI POST
========================================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Permintaan tidak valid.");
}


/* =========================================================
   AMBIL DATA DARI FORM
========================================================= */

$id_pengajuan =
    isset($_POST['id_pengajuan'])
        ? (int) $_POST['id_pengajuan']
        : 0;

if ($id_pengajuan <= 0) {
    die("ID pengajuan tidak valid.");
}


$nomor_surat =
    $_POST['nomor_surat']
    ?? "";


$tanggal_surat =
    $_POST['tanggal_surat']
    ?? date("Y-m-d");


/* =========================================================
   DATA PEMOHON
========================================================= */

$nama_pemohon =
    $_POST['nama_pemohon']
    ?? "";


$nik =
    $_POST['nik']
    ?? "";


$nomor_kk =
    $_POST['nomor_kk']
    ?? "";


$jenis_kelamin =
    $_POST['jenis_kelamin']
    ?? "";


$tempat_lahir =
    $_POST['tempat_lahir']
    ?? "";


$tanggal_lahir =
    $_POST['tanggal_lahir']
    ?? "";


$agama =
    $_POST['agama']
    ?? "";


$pekerjaan =
    $_POST['pekerjaan']
    ?? "";


$status_perkawinan =
    $_POST['status_perkawinan']
    ?? "";


$alamat =
    $_POST['alamat']
    ?? "";


/* =========================================================
   DATA KEPALA KELUARGA
========================================================= */

$nama_kepala_keluarga =
    $_POST['nama_kepala_keluarga']
    ?? "";


$nik_kepala_keluarga =
    $_POST['nik_kepala_keluarga']
    ?? "";


$tempat_lahir_kepala_keluarga =
    $_POST['tempat_lahir_kepala_keluarga']
    ?? "";


$tanggal_lahir_kepala_keluarga =
    $_POST['tanggal_lahir_kepala_keluarga']
    ?? "";


$pekerjaan_kepala_keluarga =
    $_POST['pekerjaan_kepala_keluarga']
    ?? "";


$alamat_kepala_keluarga =
    $_POST['alamat_kepala_keluarga']
    ?? "";


/* =========================================================
   DATA SKTM
========================================================= */

$desil =
    $_POST['desil']
    ?? "";


$keperluan =
    $_POST['keperluan']
    ?? "";


$alasan_tidak_mampu =
    $_POST['alasan_tidak_mampu']
    ?? "";


/* =========================================================
   LOGO
========================================================= */

$logoPuskesosPath =
    __DIR__ . "/../assets/puskesos.png";


$logoKabupatenPath =
    __DIR__ . "/../assets/Lambang_Kabupaten_Cirebon.gif";


$logoPuskesos = "";


$logoKabupaten = "";


/* =========================================================
   LOGO PUSKESOS
========================================================= */

if (file_exists($logoPuskesosPath)) {

    $isiLogoPuskesos =
        file_get_contents(
            $logoPuskesosPath
        );

    if ($isiLogoPuskesos !== false) {

        $logoPuskesos =
            "data:image/png;base64," .
            base64_encode(
                $isiLogoPuskesos
            );
    }
}


/* =========================================================
   LOGO KABUPATEN CIREBON
========================================================= */

if (file_exists($logoKabupatenPath)) {

    $isiLogoKabupaten =
        file_get_contents(
            $logoKabupatenPath
        );

    if ($isiLogoKabupaten !== false) {

        $logoKabupaten =
            "data:image/gif;base64," .
            base64_encode(
                $isiLogoKabupaten
            );
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
    Surat Keterangan Tidak Mampu
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

    padding: 20px;

    background: #eeeeee;

    font-family: Arial, sans-serif;

    color: #222;
}


/* =========================================================
   TOOLBAR
========================================================= */

.toolbar {

    max-width: 1200px;

    margin: 0 auto 20px;

    background: #ffffff;

    padding: 15px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.08);

    display: flex;

    gap: 10px;

    justify-content: center;

    flex-wrap: wrap;
}


.btn {

    border: none;

    padding: 12px 20px;

    border-radius: 5px;

    cursor: pointer;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;
}


.btn-print {

    background: #222;

    color: #fff;
}


.btn-close {

    background: #777;

    color: #fff;
}


/* =========================================================
   KERTAS A4
========================================================= */

.surat {

    width: 210mm;

    min-height: 297mm;

    margin: 0 auto;

    padding:
        15mm
        18mm
        15mm
        18mm;

    background: #ffffff;

    box-shadow:
        0 3px 15px rgba(0,0,0,.15);

    color: #000;

    font-family:
        "Times New Roman",
        Times,
        serif;
}


/* =========================================================
   KOP SURAT
========================================================= */

.kop {

    display: flex;

    align-items: center;

    border-bottom: 2px solid #000;

    padding-bottom: 7px;

    margin-bottom: 4px;
}


.logo-kiri,
.logo-kanan {

    width: 70px;

    height: 70px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;
}


.logo-kiri img,
.logo-kanan img {

    width: 62px;

    height: 62px;

    object-fit: contain;

    display: block;
}


.kop-tengah {

    flex: 1;

    text-align: center;

    line-height: 1.15;
}


.judul-atas {

    font-size: 15px;

    font-weight: bold;
}


.judul-kop {

    font-size: 17px;

    font-weight: bold;
}


.desa {

    font-size: 17px;

    font-weight: bold;
}


.alamat-kop {

    font-size: 10.5px;

    margin-top: 3px;

    line-height: 1.3;
}


/* =========================================================
   JUDUL SURAT
========================================================= */

.judul-surat {

    text-align: center;

    margin-top: 18px;

    margin-bottom: 15px;
}


.judul-surat h2 {

    margin: 0;

    font-size: 16px;

    font-weight: bold;

    text-decoration: underline;
}


.nomor {

    margin-top: 4px;

    font-size: 12px;
}


/* =========================================================
   ISI
========================================================= */

.isi {

    font-size: 13px;

    line-height: 1.45;

    text-align: justify;
}


.pembuka {

    margin-bottom: 12px;
}


/* =========================================================
   DATA
========================================================= */

.data {

    margin-left: 25px;

    margin-bottom: 12px;
}


.row {

    display: flex;

    line-height: 1.45;

    margin-bottom: 1px;
}


.label {

    width: 170px;

    flex-shrink: 0;
}


.titik {

    width: 15px;

    flex-shrink: 0;
}


.nilai {

    flex: 1;

    word-break: normal;
}


.bold {

    font-weight: bold;
}


/* =========================================================
   PARAGRAF
========================================================= */

.paragraf {

    text-indent: 35px;

    margin-top: 12px;

    margin-bottom: 12px;

    text-align: justify;
}


.paragraf-tengah {

    text-align: center;

    margin-top: 15px;

    margin-bottom: 15px;
}


/* =========================================================
   DESIL
========================================================= */

.desil-box {

    display: flex;

    align-items: center;

    margin:
        10px
        0
        10px
        25px;
}


.desil-label {

    width: 170px;
}


.desil-value {

    width: 55px;

    height: 38px;

    border: 1px solid #000;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 16px;

    font-weight: bold;
}


/* =========================================================
   PENUTUP
========================================================= */

.penutup {

    margin-top: 15px;

    text-align: justify;
}


/* =========================================================
   TANDA TANGAN
========================================================= */

.ttd {

    display: flex;

    justify-content: space-between;

    margin-top: 35px;

    font-size: 12px;
}


.ttd-box {

    width: 45%;

    text-align: center;
}


.ttd-space {

    height: 75px;
}


.nama-ttd {

    font-weight: bold;

    text-decoration: underline;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    body {

        background: white;

        padding: 0;
    }


    .toolbar {

        display: none !important;
    }


    .surat {

        width: 210mm;

        min-height: 297mm;

        margin: 0;

        padding:
            15mm
            18mm
            15mm
            18mm;

        box-shadow: none;
    }


    @page {

        size: A4;

        margin: 0;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     TOOLBAR
===================================================== -->

<div class="toolbar">

    <button
        type="button"
        class="btn btn-print"
        onclick="window.print()"
    >
        CETAK / SIMPAN PDF
    </button>


    <button
        type="button"
        class="btn btn-close"
        onclick="window.close()"
    >
        TUTUP
    </button>

</div>


<!-- =====================================================
     SURAT
===================================================== -->

<div class="surat">


<!-- =====================================================
     KOP SURAT
===================================================== -->

<div class="kop">


    <div class="logo-kiri">

        <?php if (!empty($logoPuskesos)): ?>

            <img
                src="<?= $logoPuskesos ?>"
                alt="Logo Puskesos"
            >

        <?php endif; ?>

    </div>


    <div class="kop-tengah">

        <div class="judul-atas">
            SISTEM LAYANAN DAN RUJUKAN TERPADU
        </div>

        <div class="judul-kop">
            PUSAT KESEJAHTERAAN SOSIAL
        </div>

        <div class="desa">
            "MAKMUR MANDIRI"
        </div>

        <div class="alamat-kop">

            Sekretariat : Jalan Ir. Soekarno, No. 01,
            Desa Sampiran Kecamatan Talun,
            Kabupaten Cirebon

        </div>

    </div>


    <div class="logo-kanan">

        <?php if (!empty($logoKabupaten)): ?>

            <img
                src="<?= $logoKabupaten ?>"
                alt="Lambang Kabupaten Cirebon"
            >

        <?php endif; ?>

    </div>


</div>


<!-- =====================================================
     JUDUL SURAT
===================================================== -->

<div class="judul-surat">

    <h2>
        SURAT KETERANGAN
    </h2>


    <div class="nomor">

        Nomor :
        <?= e($nomor_surat) ?>

    </div>

</div>


<!-- =====================================================
     ISI SURAT
===================================================== -->

<div class="isi">


    <div class="pembuka">

        Yang bertandatangan di bawah ini Puskesos
        MAKMUR MANDIRI Desa Sampiran Kecamatan Talun
        Kabupaten Cirebon, menerangkan bahwa:

    </div>


    <!-- =================================================
         DATA PEMOHON
    ================================================== -->

    <div class="data">


        <div class="row">

            <div class="label">
                Nama Lengkap
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai bold">
                <?= e($nama_pemohon) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Nomor Induk Kependudukan
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($nik) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Nomor Kartu Keluarga
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($nomor_kk) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Tempat Tanggal Lahir
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">

                <?= e($tempat_lahir) ?>,
                <?= e(tanggalIndonesia($tanggal_lahir)) ?>

            </div>

        </div>


        <div class="row">

            <div class="label">
                Jenis Kelamin
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($jenis_kelamin) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Agama
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($agama) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Pekerjaan
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($pekerjaan) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Status Perkawinan
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($status_perkawinan) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Alamat
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= nl2br(e($alamat)) ?>
            </div>

        </div>


    </div>


    <!-- =================================================
         DATA KEPALA KELUARGA
    ================================================== -->

    <div class="data">


        <div class="row">

            <div class="label">
                Nama Kepala Keluarga
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai bold">
                <?= e($nama_kepala_keluarga) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                NIK
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($nik_kepala_keluarga) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Tempat Tanggal Lahir
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">

                <?= e($tempat_lahir_kepala_keluarga) ?>,
                <?= e(tanggalIndonesia($tanggal_lahir_kepala_keluarga)) ?>

            </div>

        </div>


        <div class="row">

            <div class="label">
                Pekerjaan
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= e($pekerjaan_kepala_keluarga) ?>
            </div>

        </div>


        <div class="row">

            <div class="label">
                Alamat
            </div>

            <div class="titik">
                :
            </div>

            <div class="nilai">
                <?= nl2br(e($alamat_kepala_keluarga)) ?>
            </div>

        </div>


    </div>


    <!-- =================================================
         PERNYATAAN
    ================================================== -->

    <div class="paragraf">

        Berdasarkan hasil verifikasi oleh fasilitator,
        bahwa orang tersebut di atas benar warga
        Desa Sampiran Kecamatan Talun yang pada saat
        ini kondisi ekonominya tidak mampu dan
        termasuk kategori keluarga miskin yang sudah
        terdapat dalam Data Tunggal Sosial Ekonomi
        Nasional (DTSEN). (Foto Copy KK dan KTP terlampir).

    </div>


    <!-- =================================================
         DESIL
    ================================================== -->

    <div class="desil-box">

        <div class="desil-label">
            Desil DTSEN
        </div>

        <div class="titik">
            :
        </div>

        <div class="desil-value">
            <?= e($desil) ?>
        </div>

    </div>


    <!-- =================================================
         KEPERLUAN
    ================================================== -->

    <div class="paragraf-tengah">

        Surat keterangan ini digunakan untuk:

        <strong>
            <?= e($keperluan) ?>
        </strong>

    </div>


    <!-- =================================================
         PENUTUP
    ================================================== -->

    <div class="penutup">

        Demikian surat keterangan ini dibuat untuk
        dipergunakan sebagaimana mestinya dan sebagai
        bahan pertimbangan lebih lanjut sesuai
        peraturan yang berlaku.

    </div>


</div>


<!-- =====================================================
     TANDA TANGAN
===================================================== -->

<div class="ttd">


    <div class="ttd-box">

        Mengetahui:

        <br>

        <strong>
            Pj. KEPALA DESA SAMPIRAN
        </strong>


        <div class="ttd-space"></div>


        <div class="nama-ttd">
            ........................................
        </div>

    </div>


    <div class="ttd-box">

        Cirebon,

        <?= e(tanggalIndonesia($tanggal_surat)) ?>


        <br><br>


        Puskesos Makmur Mandiri


        <br>


        <strong>
            Koordinator
        </strong>


        <div class="ttd-space"></div>


        <div class="nama-ttd">
            ........................................
        </div>

    </div>


</div>


</div>


<script>

/* =========================================================
   OTOMATIS BUKA DIALOG PRINT
========================================================= */

window.addEventListener(
    "load",
    function () {

        setTimeout(
            function () {
                window.print();
            },
            500
        );

    }
);

</script>


</body>

</html>
```
