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
   VALIDASI ID PENGAJUAN
========================================================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID pengajuan tidak valid.");
}

$id_pengajuan = (int) $_GET['id'];


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
   AMBIL DATA PENGAJUAN SKTM
========================================================= */

$query = "

SELECT

    /* DATA PENGAJUAN */
    p.id_pengajuan,
    p.tanggal_pengajuan,
    p.status_pengajuan,

    /* DATA SKTM */
    s.nama_pemohon,
    s.nik AS nik_pemohon,
    s.nomor_kk AS nomor_kk_sktm,
    s.desil,
    s.alasan_tidak_mampu,
    s.keperluan,

    /* DATA MASYARAKAT */
    m.nama_lengkap,
    m.nik,
    m.no_kk,
    m.jenis_kelamin,
    m.tempat_lahir,
    m.tanggal_lahir,
    m.pekerjaan,
    m.agama,
    m.status_perkawinan,
    m.alamat,

    /* DATA KELUARGA */
    dk.nomor_kk AS nomor_kk_keluarga,
    dk.nama_kepala_keluarga,
    dk.status_kesejahteraan,
    dk.desil_dtks,
    dk.keterangan,

    /* DATA KEPALA KELUARGA */
    kk.nik AS nik_kepala_keluarga,
    kk.tempat_lahir AS tempat_lahir_kepala_keluarga,
    kk.tanggal_lahir AS tanggal_lahir_kepala_keluarga,
    kk.pekerjaan AS pekerjaan_kepala_keluarga,
    kk.alamat AS alamat_kepala_keluarga

FROM pengajuan_surat p

LEFT JOIN data_sktm s
    ON p.id_pengajuan = s.id_pengajuan

LEFT JOIN masyarakat m
    ON p.id_masyarakat = m.id_masyarakat

LEFT JOIN data_keluarga dk
    ON p.id_pengajuan = dk.id_pengajuan

LEFT JOIN masyarakat kk
    ON kk.no_kk = dk.nomor_kk
    AND kk.nama_lengkap = dk.nama_kepala_keluarga

WHERE p.id_pengajuan = ?

LIMIT 1

";


$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die(
        "Query gagal dipersiapkan:<br>" .
        e(mysqli_error($koneksi))
    );
}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_pengajuan
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die(
        "Query gagal dijalankan:<br>" .
        e(mysqli_error($koneksi))
    );
}


$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data pengajuan SKTM tidak ditemukan.");
}


/* =========================================================
   DATA PEMOHON
========================================================= */

$nama_pemohon =
    !empty($data['nama_pemohon'])
        ? $data['nama_pemohon']
        : ($data['nama_lengkap'] ?? "");


$nik_pemohon =
    !empty($data['nik_pemohon'])
        ? $data['nik_pemohon']
        : ($data['nik'] ?? "");


$nomor_kk =
    !empty($data['nomor_kk_sktm'])
        ? $data['nomor_kk_sktm']
        : ($data['no_kk'] ?? "");


$jenis_kelamin =
    $data['jenis_kelamin'] ?? "";


$agama =
    $data['agama'] ?? "";


$tempat_lahir =
    $data['tempat_lahir'] ?? "";


$tanggal_lahir =
    $data['tanggal_lahir'] ?? "";


$pekerjaan =
    $data['pekerjaan'] ?? "";


$status_perkawinan =
    $data['status_perkawinan'] ?? "";


$alamat =
    $data['alamat'] ?? "";


/* =========================================================
   DATA KEPALA KELUARGA
========================================================= */

$nama_kepala_keluarga =
    $data['nama_kepala_keluarga'] ?? "";


$nik_kepala_keluarga =
    $data['nik_kepala_keluarga'] ?? "";


$tempat_lahir_kepala =
    $data['tempat_lahir_kepala_keluarga'] ?? "";


$tanggal_lahir_kepala =
    $data['tanggal_lahir_kepala_keluarga'] ?? "";


$pekerjaan_kepala =
    $data['pekerjaan_kepala_keluarga'] ?? "";


$alamat_kepala =
    $data['alamat_kepala_keluarga'] ?? "";


/* =========================================================
   DATA SKTM
========================================================= */

$desil =
    $data['desil'] ?? "";


$keperluan =
    $data['keperluan'] ?? "";


$alasan =
    $data['alasan_tidak_mampu'] ?? "";


/* =========================================================
   DATA SURAT
========================================================= */

$nomor_surat =
    "460/___/Puskesos/" . date("m/Y");


$tanggal_surat =
    date("Y-m-d");


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
   BACA LOGO PUSKESOS
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
   BACA LOGO KABUPATEN CIREBON
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
    Pembuatan Surat SKTM
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
   CONTAINER
========================================================= */

.container {

    max-width: 1200px;

    margin: auto;
}


/* =========================================================
   JUDUL HALAMAN
========================================================= */

.page-title {

    background: #ffffff;

    padding: 20px;

    margin-bottom: 20px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.08);
}


.page-title h2 {

    margin: 0;

    font-size: 24px;
}


.page-title p {

    margin: 8px 0 0;

    color: #666;
}


/* =========================================================
   CARD
========================================================= */

.card {

    background: #ffffff;

    padding: 25px;

    margin-bottom: 30px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.08);
}


.card h3 {

    margin-top: 0;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom: 2px solid #222;

    font-size: 18px;
}


/* =========================================================
   GRID
========================================================= */

.grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 16px;
}


.full {

    grid-column: 1 / -1;
}


/* =========================================================
   FORM
========================================================= */

.form-group {

    display: flex;

    flex-direction: column;

    gap: 6px;
}


.form-group label {

    font-weight: bold;

    font-size: 14px;
}


.form-group input,
.form-group select,
.form-group textarea {

    width: 100%;

    padding: 10px 12px;

    border: 1px solid #ccc;

    border-radius: 5px;

    font-size: 14px;

    font-family: Arial, sans-serif;
}


.form-group textarea {

    min-height: 80px;

    resize: vertical;
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

    padding: 12px 20px;

    border-radius: 5px;

    cursor: pointer;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;
}


.btn-pdf {

    background: #222;

    color: white;
}


.btn-back {

    background: #777;

    color: white;
}


/* =========================================================
   UPLOAD PDF
========================================================= */

.upload-card {

    background: #ffffff;

    padding: 25px;

    margin-top: 30px;

    margin-bottom: 30px;

    border-radius: 8px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.08);
}


.upload-card h3 {

    margin-top: 0;

    margin-bottom: 10px;

    padding-bottom: 10px;

    border-bottom: 2px solid #222;

    font-size: 18px;
}


.upload-info {

    color: #666;

    font-size: 14px;

    margin-bottom: 15px;

    line-height: 1.5;
}


.btn-upload {

    background: #198754;

    color: white;
}


/* =========================================================
   JUDUL PREVIEW
========================================================= */

.preview-title {

    text-align: center;

    margin: 35px 0 15px;

    font-weight: bold;

    font-size: 20px;
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
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .grid {

        grid-template-columns: 1fr;
    }

    .full {

        grid-column: auto;
    }

    .surat {

        width: 100%;

        min-height: auto;

        padding: 20px;
    }

}


/* =========================================================
   PRINT
========================================================= */

@media print {

    body {

        background: white;

        padding: 0;
    }


    .page-title,
    .form-card,
    .upload-card,
    .preview-title {

        display: none !important;
    }


    .container {

        max-width: none;

        margin: 0;
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


<div class="container">


<!-- =====================================================
     JUDUL HALAMAN
===================================================== -->

<div class="page-title">

    <h2>
        Pembuatan Surat SKTM
    </h2>

    <p>
        Surat Keterangan Tidak Mampu
    </p>

</div>


<!-- =====================================================
     FORM DATA
===================================================== -->

<div class="card form-card">


<form
    method="POST"
    action="cetak_sktm.php"
    target="_blank"
>


<input
    type="hidden"
    name="id_pengajuan"
    value="<?= $id_pengajuan ?>"
>


<!-- =====================================================
     DATA SURAT
===================================================== -->

<h3>
    DATA SURAT
</h3>


<div class="grid">


    <div class="form-group">

        <label>
            Nomor Surat
        </label>

        <input
            type="text"
            name="nomor_surat"
            value="<?= e($nomor_surat) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Tanggal Surat
        </label>

        <input
            type="date"
            name="tanggal_surat"
            value="<?= e($tanggal_surat) ?>"
        >

    </div>


</div>


<!-- =====================================================
     DATA PEMOHON
===================================================== -->

<h3 style="margin-top:30px;">
    DATA PEMOHON
</h3>


<div class="grid">


    <div class="form-group">

        <label>
            Nama Lengkap
        </label>

        <input
            type="text"
            name="nama_pemohon"
            value="<?= e($nama_pemohon) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            NIK
        </label>

        <input
            type="text"
            name="nik"
            value="<?= e($nik_pemohon) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Nomor KK
        </label>

        <input
            type="text"
            name="nomor_kk"
            value="<?= e($nomor_kk) ?>"
        >

    </div>


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
                <?= $jenis_kelamin == "Laki-laki" ? "selected" : "" ?>
            >
                Laki-laki
            </option>

            <option
                value="Perempuan"
                <?= $jenis_kelamin == "Perempuan" ? "selected" : "" ?>
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
            value="<?= e($tempat_lahir) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Tanggal Lahir
        </label>

        <input
            type="date"
            name="tanggal_lahir"
            value="<?= e($tanggal_lahir) ?>"
        >

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

            $agamaList = [
                "Islam",
                "Kristen",
                "Katolik",
                "Hindu",
                "Buddha",
                "Konghucu"
            ];

            foreach ($agamaList as $agamaItem):

            ?>

                <option
                    value="<?= e($agamaItem) ?>"
                    <?= $agama == $agamaItem ? "selected" : "" ?>
                >
                    <?= e($agamaItem) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <div class="form-group">

        <label>
            Pekerjaan
        </label>

        <input
            type="text"
            name="pekerjaan"
            value="<?= e($pekerjaan) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Status Perkawinan
        </label>

        <select name="status_perkawinan">

            <option value="">
                -- Pilih --
            </option>

            <?php

            $statusList = [
                "Belum Kawin",
                "Kawin",
                "Cerai Hidup",
                "Cerai Mati"
            ];

            foreach ($statusList as $statusItem):

            ?>

                <option
                    value="<?= e($statusItem) ?>"
                    <?= $status_perkawinan == $statusItem ? "selected" : "" ?>
                >
                    <?= e($statusItem) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <div class="form-group full">

        <label>
            Alamat
        </label>

        <textarea name="alamat"><?= e($alamat) ?></textarea>

    </div>


</div>


<!-- =====================================================
     DATA KEPALA KELUARGA
===================================================== -->

<h3 style="margin-top:30px;">
    DATA KEPALA KELUARGA
</h3>


<div class="grid">


    <div class="form-group">

        <label>
            Nama Kepala Keluarga
        </label>

        <input
            type="text"
            name="nama_kepala_keluarga"
            value="<?= e($nama_kepala_keluarga) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            NIK Kepala Keluarga
        </label>

        <input
            type="text"
            name="nik_kepala_keluarga"
            value="<?= e($nik_kepala_keluarga) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Tempat Lahir Kepala Keluarga
        </label>

        <input
            type="text"
            name="tempat_lahir_kepala_keluarga"
            value="<?= e($tempat_lahir_kepala) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Tanggal Lahir Kepala Keluarga
        </label>

        <input
            type="date"
            name="tanggal_lahir_kepala_keluarga"
            value="<?= e($tanggal_lahir_kepala) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Pekerjaan Kepala Keluarga
        </label>

        <input
            type="text"
            name="pekerjaan_kepala_keluarga"
            value="<?= e($pekerjaan_kepala) ?>"
        >

    </div>


    <div class="form-group full">

        <label>
            Alamat Kepala Keluarga
        </label>

        <textarea name="alamat_kepala_keluarga"><?= e($alamat_kepala) ?></textarea>

    </div>


</div>


<!-- =====================================================
     DATA SKTM
===================================================== -->

<h3 style="margin-top:30px;">
    DATA KETERANGAN SKTM
</h3>


<div class="grid">


    <div class="form-group">

        <label>
            Desil DTSEN
        </label>

        <input
            type="text"
            name="desil"
            value="<?= e($desil) ?>"
        >

    </div>


    <div class="form-group">

        <label>
            Keperluan
        </label>

        <input
            type="text"
            name="keperluan"
            value="<?= e($keperluan) ?>"
        >

    </div>


    <div class="form-group full">

        <label>
            Alasan Tidak Mampu
        </label>

        <textarea
            name="alasan_tidak_mampu"
        ><?= e($alasan) ?></textarea>

    </div>


</div>


<!-- =====================================================
     BUTTON
===================================================== -->

<div class="button-area">


    <button
        type="submit"
        class="btn btn-pdf"
    >
        BUAT PDF
    </button>


    <a
        href="pengajuan_masuk.php"
        class="btn btn-back"
    >
        KEMBALI
    </a>


</div>


</form>

</div>


<!-- =====================================================
     PREVIEW
===================================================== -->

<div class="preview-title">

    PREVIEW BLANKO SURAT SKTM

</div>


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

        <span id="preview_nomor_surat">
            <?= e($nomor_surat) ?>
        </span>

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

                <span id="preview_nama_pemohon">
                    <?= e($nama_pemohon) ?>
                </span>

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

                <span id="preview_nik">
                    <?= e($nik_pemohon) ?>
                </span>

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

                <span id="preview_nomor_kk">
                    <?= e($nomor_kk) ?>
                </span>

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

                <span id="preview_tempat_lahir">
                    <?= e($tempat_lahir) ?>
                </span>

                ,

                <span id="preview_tanggal_lahir">
                    <?= e(
                        tanggalIndonesia(
                            $tanggal_lahir
                        )
                    ) ?>
                </span>

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

                <span id="preview_jenis_kelamin">
                    <?= e($jenis_kelamin) ?>
                </span>

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

                <span id="preview_agama">
                    <?= e($agama) ?>
                </span>

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

                <span id="preview_pekerjaan">
                    <?= e($pekerjaan) ?>
                </span>

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

                <span id="preview_status_perkawinan">
                    <?= e($status_perkawinan) ?>
                </span>

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

                <span id="preview_alamat">
                    <?= nl2br(e($alamat)) ?>
                </span>

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

                <span id="preview_nama_kepala">
                    <?= e($nama_kepala_keluarga) ?>
                </span>

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

                <span id="preview_nik_kepala">
                    <?= e($nik_kepala_keluarga) ?>
                </span>

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

                <span id="preview_tempat_lahir_kepala">
                    <?= e($tempat_lahir_kepala) ?>
                </span>

                ,

                <span id="preview_tanggal_lahir_kepala">
                    <?= e(
                        tanggalIndonesia(
                            $tanggal_lahir_kepala
                        )
                    ) ?>
                </span>

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

                <span id="preview_pekerjaan_kepala">
                    <?= e($pekerjaan_kepala) ?>
                </span>

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

                <span id="preview_alamat_kepala">
                    <?= nl2br(e($alamat_kepala)) ?>
                </span>

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

            <span id="preview_desil">
                <?= e($desil) ?>
            </span>

        </div>


    </div>


    <!-- =================================================
         KEPERLUAN
    ================================================== -->

    <div class="paragraf-tengah">

        Surat keterangan ini digunakan untuk:

        <strong>

            <span id="preview_keperluan">
                <?= e($keperluan) ?>
            </span>

        </strong>

    </div>


    <!-- =================================================
         BAGIAN KETERANGAN DIHAPUS
    ================================================= -->


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

        <span id="preview_tanggal_surat">

            <?= e(
                tanggalIndonesia(
                    $tanggal_surat
                )
            ) ?>

        </span>


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


<!-- =====================================================
     UPLOAD PDF SURAT YANG SUDAH JADI
===================================================== -->

<div class="upload-card no-print">

    <h3>
        UPLOAD PDF SURAT YANG SUDAH JADI
    </h3>

    <div class="upload-info">

        Silakan upload file PDF surat SKTM
        yang sudah dibuat.

        <br>

        File PDF akan disimpan dan dikaitkan
        dengan pengajuan ini.

    </div>


    <form
        action="upload_sktm.php"
        method="POST"
        enctype="multipart/form-data"
    >


        <input
            type="hidden"
            name="id_pengajuan"
            value="<?= $id_pengajuan ?>"
        >


        <div class="form-group">

            <label>
                File PDF Surat
            </label>


            <input
                type="file"
                name="file_surat"
                accept=".pdf,application/pdf"
                required
            >

        </div>


        <div class="button-area">

            <button
                type="submit"
                class="btn btn-upload"
            >
                UPLOAD & SIMPAN PDF
            </button>

        </div>


    </form>

</div>


</div>


<!-- =====================================================
     JAVASCRIPT LIVE PREVIEW
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


    /* =====================================================
       HELPER
    ===================================================== */

    function getInput(name) {

        return document.querySelector(
            '[name="' + name + '"]'
        );

    }


    function getPreview(id) {

        return document.getElementById(id);

    }


    /* =====================================================
       BIND TEXT
    ===================================================== */

    function bindText(
        inputName,
        previewId
    ) {

        const inp =
            getInput(inputName);

        const out =
            getPreview(previewId);


        if (!inp || !out) {
            return;
        }


        function update() {

            out.textContent =
                inp.value.trim() !== ""
                    ? inp.value
                    : "-";

        }


        inp.addEventListener(
            "input",
            update
        );


        inp.addEventListener(
            "change",
            update
        );

    }


    /* =====================================================
       FORMAT TANGGAL
    ===================================================== */

    function formatTanggal(value) {

        if (!value) {
            return "-";
        }


        const tanggal =
            new Date(
                value + "T00:00:00"
            );


        if (isNaN(tanggal.getTime())) {
            return "-";
        }


        const bulan = [

            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"

        ];


        return (

            tanggal.getDate() +
            " " +
            bulan[tanggal.getMonth()] +
            " " +
            tanggal.getFullYear()

        );

    }


    /* =====================================================
       BIND TANGGAL
    ===================================================== */

    function bindTanggal(
        inputName,
        previewId
    ) {

        const inp =
            getInput(inputName);

        const out =
            getPreview(previewId);


        if (!inp || !out) {
            return;
        }


        function update() {

            out.textContent =
                formatTanggal(
                    inp.value
                );

        }


        inp.addEventListener(
            "input",
            update
        );


        inp.addEventListener(
            "change",
            update
        );

    }


    /* =====================================================
       DATA SURAT
    ===================================================== */

    bindText(
        "nomor_surat",
        "preview_nomor_surat"
    );


    bindTanggal(
        "tanggal_surat",
        "preview_tanggal_surat"
    );


    /* =====================================================
       DATA PEMOHON
    ===================================================== */

    bindText(
        "nama_pemohon",
        "preview_nama_pemohon"
    );


    bindText(
        "nik",
        "preview_nik"
    );


    bindText(
        "nomor_kk",
        "preview_nomor_kk"
    );


    bindText(
        "tempat_lahir",
        "preview_tempat_lahir"
    );


    bindTanggal(
        "tanggal_lahir",
        "preview_tanggal_lahir"
    );


    bindText(
        "jenis_kelamin",
        "preview_jenis_kelamin"
    );


    bindText(
        "agama",
        "preview_agama"
    );


    bindText(
        "pekerjaan",
        "preview_pekerjaan"
    );


    bindText(
        "status_perkawinan",
        "preview_status_perkawinan"
    );


    /* =====================================================
       ALAMAT PEMOHON
    ===================================================== */

    const alamat =
        getInput("alamat");


    const previewAlamat =
        getPreview("preview_alamat");


    if (
        alamat &&
        previewAlamat
    ) {

        function updateAlamat() {

            previewAlamat.innerHTML =
                alamat.value.trim() !== ""
                    ? alamat.value.replace(
                        /\n/g,
                        "<br>"
                    )
                    : "-";

        }


        alamat.addEventListener(
            "input",
            updateAlamat
        );

    }


    /* =====================================================
       KEPALA KELUARGA
    ===================================================== */

    bindText(
        "nama_kepala_keluarga",
        "preview_nama_kepala"
    );


    bindText(
        "nik_kepala_keluarga",
        "preview_nik_kepala"
    );


    bindText(
        "tempat_lahir_kepala_keluarga",
        "preview_tempat_lahir_kepala"
    );


    bindTanggal(
        "tanggal_lahir_kepala_keluarga",
        "preview_tanggal_lahir_kepala"
    );


    bindText(
        "pekerjaan_kepala_keluarga",
        "preview_pekerjaan_kepala"
    );


    /* =====================================================
       ALAMAT KEPALA KELUARGA
    ===================================================== */

    const alamatKepala =
        getInput(
            "alamat_kepala_keluarga"
        );


    const previewAlamatKepala =
        getPreview(
            "preview_alamat_kepala"
        );


    if (
        alamatKepala &&
        previewAlamatKepala
    ) {

        function updateAlamatKepala() {

            previewAlamatKepala.innerHTML =
                alamatKepala.value.trim() !== ""
                    ? alamatKepala.value.replace(
                        /\n/g,
                        "<br>"
                    )
                    : "-";

        }


        alamatKepala.addEventListener(
            "input",
            updateAlamatKepala
        );

    }


    /* =====================================================
       DATA SKTM
    ===================================================== */

    bindText(
        "desil",
        "preview_desil"
    );


    bindText(
        "keperluan",
        "preview_keperluan"
    );


});

</script>


</body>

</html>