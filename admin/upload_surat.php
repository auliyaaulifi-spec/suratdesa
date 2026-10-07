<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =========================
   CEK DATA
   ========================= */

if (!isset($_POST["id_pengajuan"]) || empty($_POST["id_pengajuan"])) {
    die("ID pengajuan tidak ditemukan.");
}

$id_pengajuan = $_POST["id_pengajuan"];
$id_admin = $_SESSION["id_admin"];

/* =========================
   CEK FILE
   ========================= */

if (!isset($_FILES["file_surat"])) {
    die("File surat belum dipilih.");
}

$file = $_FILES["file_surat"];

/* =========================
   CEK ERROR UPLOAD
   ========================= */

if ($file["error"] !== UPLOAD_ERR_OK) {
    die("Terjadi kesalahan saat mengunggah file.");
}

/* =========================
   CEK FORMAT FILE
   ========================= */

$nama_file = $file["name"];
$ukuran_file = $file["size"];
$file_tmp = $file["tmp_name"];

$ekstensi = strtolower(
    pathinfo($nama_file, PATHINFO_EXTENSION)
);

if ($ekstensi !== "pdf") {
    die("File yang diperbolehkan hanya PDF.");
}

/* =========================
   BATAS UKURAN FILE
   Maksimal 5 MB
   ========================= */

if ($ukuran_file > 5 * 1024 * 1024) {
    die("Ukuran file terlalu besar. Maksimal 5 MB.");
}

/* =========================
   CEK PENGAJUAN
   ========================= */

$query_pengajuan = "
    SELECT *
    FROM pengajuan_surat
    WHERE id_pengajuan = '$id_pengajuan'
";

$result_pengajuan = mysqli_query(
    $koneksi,
    $query_pengajuan
);

if (!$result_pengajuan) {
    die("Gagal mengambil data pengajuan.");
}

$data_pengajuan = mysqli_fetch_assoc(
    $result_pengajuan
);

if (!$data_pengajuan) {
    die("Data pengajuan tidak ditemukan.");
}

/* =========================
   BUAT NAMA FILE BARU
   ========================= */

$nama_file_baru =
    "surat_" .
    $id_pengajuan .
    "_" .
    time() .
    ".pdf";

/* =========================
   LOKASI UPLOAD
   ========================= */

$folder_upload = "../uploads/";

$lokasi_file =
    $folder_upload .
    $nama_file_baru;

/* =========================
   PINDAHKAN FILE
   ========================= */

if (!move_uploaded_file($file_tmp, $lokasi_file)) {
    die("File gagal disimpan.");
}

/* =========================
   TANGGAL SURAT
   ========================= */

$tanggal_surat = date("Y-m-d");

/* =========================
   SIMPAN DATA SURAT
   ========================= */

$query_surat = "
    INSERT INTO surat
    (
        id_pengajuan,
        id_admin,
        id_nomor_surat,
        tanggal_surat,
        file_surat,
        status_surat,
        tanggal_cetak
    )
    VALUES
    (
        '$id_pengajuan',
        '$id_admin',
        NULL,
        '$tanggal_surat',
        '$nama_file_baru',
        'Selesai',
        '$tanggal_surat'
    )
";

if (!mysqli_query($koneksi, $query_surat)) {

    /* Hapus file jika database gagal */

    if (file_exists($lokasi_file)) {
        unlink($lokasi_file);
    }

    die(
        "Data surat gagal disimpan: " .
        mysqli_error($koneksi)
    );
}

/* =========================
   UPDATE STATUS PENGAJUAN
   ========================= */

$query_update = "
    UPDATE pengajuan_surat
    SET status_pengajuan = 'Selesai'
    WHERE id_pengajuan = '$id_pengajuan'
";

if (!mysqli_query($koneksi, $query_update)) {

    die(
        "Status pengajuan gagal diperbarui: " .
        mysqli_error($koneksi)
    );
}

/* =========================
   KEMBALI KE CETAK SURAT
   ========================= */

header(
    "Location: cetak_surat.php?pesan=upload_berhasil"
);

exit;

?>