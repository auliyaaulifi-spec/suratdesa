
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
   VALIDASI REQUEST
========================================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Permintaan tidak valid.");
}


/* =========================================================
   AMBIL ID PENGAJUAN
========================================================= */

$id_pengajuan = isset($_POST['id_pengajuan'])
    ? (int) $_POST['id_pengajuan']
    : 0;


if ($id_pengajuan <= 0) {
    die("ID pengajuan tidak valid.");
}


/* =========================================================
   CEK DATA PENGAJUAN
========================================================= */

$queryCek = "
    SELECT id_pengajuan
    FROM pengajuan_surat
    WHERE id_pengajuan = ?
    LIMIT 1
";


$stmtCek = mysqli_prepare(
    $koneksi,
    $queryCek
);


if (!$stmtCek) {
    die(
        "Query pengecekan pengajuan gagal: " .
        mysqli_error($koneksi)
    );
}


mysqli_stmt_bind_param(
    $stmtCek,
    "i",
    $id_pengajuan
);


mysqli_stmt_execute(
    $stmtCek
);


$resultCek =
    mysqli_stmt_get_result(
        $stmtCek
    );


if (!$resultCek || mysqli_num_rows($resultCek) === 0) {

    mysqli_stmt_close($stmtCek);

    die("Data pengajuan tidak ditemukan.");

}


mysqli_stmt_close($stmtCek);


/* =========================================================
   CEK FILE UPLOAD
========================================================= */

if (!isset($_FILES['file_surat'])) {
    die("File PDF belum dipilih.");
}


if (
    $_FILES['file_surat']['error']
    !== UPLOAD_ERR_OK
) {

    die(
        "Terjadi kesalahan saat mengupload file. " .
        "Kode error: " .
        $_FILES['file_surat']['error']
    );
}


/* =========================================================
   DATA FILE
========================================================= */

$file = $_FILES['file_surat'];


$nama_file_asli =
    $file['name'];


$tmp_file =
    $file['tmp_name'];


$ukuran_file =
    $file['size'];


/* =========================================================
   BATAS UKURAN FILE
   MAKSIMAL 5 MB
========================================================= */

$maksimal_ukuran =
    5 * 1024 * 1024;


if ($ukuran_file > $maksimal_ukuran) {

    die(
        "Ukuran file terlalu besar. " .
        "Maksimal ukuran file adalah 5 MB."
    );

}


/* =========================================================
   CEK EKSTENSI FILE
========================================================= */

$ekstensi =
    strtolower(
        pathinfo(
            $nama_file_asli,
            PATHINFO_EXTENSION
        )
    );


if ($ekstensi !== "pdf") {

    die(
        "File yang diperbolehkan hanya PDF."
    );

}


/* =========================================================
   CEK MIME TYPE
========================================================= */

$finfo =
    finfo_open(
        FILEINFO_MIME_TYPE
    );


if (!$finfo) {

    die(
        "Sistem tidak dapat memeriksa tipe file."
    );

}


$mime =
    finfo_file(
        $finfo,
        $tmp_file
    );


finfo_close(
    $finfo
);


if ($mime !== "application/pdf") {

    die(
        "File yang diupload bukan file PDF yang valid."
    );

}


/* =========================================================
   FOLDER UPLOAD
========================================================= */

$folder_upload =
    __DIR__ .
    "/../uploads/sktm/";


/* =========================================================
   BUAT FOLDER JIKA BELUM ADA
========================================================= */

if (!is_dir($folder_upload)) {

    if (
        !mkdir(
            $folder_upload,
            0777,
            true
        )
    ) {

        die(
            "Folder upload PDF tidak dapat dibuat."
        );

    }

}


/* =========================================================
   CEK FOLDER BISA DITULIS
========================================================= */

if (!is_writable($folder_upload)) {

    die(
        "Folder upload PDF tidak dapat ditulis."
    );

}


/* =========================================================
   NAMA FILE BARU
========================================================= */

$nama_file_baru =
    "SKTM_" .
    $id_pengajuan .
    "_" .
    date("YmdHis") .
    ".pdf";


$path_file =
    $folder_upload .
    $nama_file_baru;


/* =========================================================
   PINDAHKAN FILE KE FOLDER
========================================================= */

if (
    !move_uploaded_file(
        $tmp_file,
        $path_file
    )
) {

    die(
        "File PDF gagal disimpan ke server."
    );

}


/* =========================================================
   PATH FILE UNTUK DATABASE
========================================================= */

$file_database =
    "uploads/sktm/" .
    $nama_file_baru;


/* =========================================================
   UPDATE FILE SURAT
========================================================= */

$queryUpdateFile = "

    UPDATE pengajuan_surat

    SET file_surat = ?

    WHERE id_pengajuan = ?

";


$stmtFile =
    mysqli_prepare(
        $koneksi,
        $queryUpdateFile
    );


if (!$stmtFile) {

    if (file_exists($path_file)) {
        unlink($path_file);
    }

    die(
        "Query penyimpanan file gagal: " .
        mysqli_error($koneksi)
    );

}


mysqli_stmt_bind_param(
    $stmtFile,
    "si",
    $file_database,
    $id_pengajuan
);


if (
    !mysqli_stmt_execute(
        $stmtFile
    )
) {

    mysqli_stmt_close(
        $stmtFile
    );


    if (file_exists($path_file)) {
        unlink($path_file);
    }


    die(
        "File PDF gagal disimpan ke database."
    );

}


mysqli_stmt_close(
    $stmtFile
);


/* =========================================================
   UPDATE STATUS MENJADI SELESAI
========================================================= */

$queryStatus = "

    UPDATE pengajuan_surat

    SET status_pengajuan = 'Selesai'

    WHERE id_pengajuan = ?

";


$stmtStatus =
    mysqli_prepare(
        $koneksi,
        $queryStatus
    );


if (!$stmtStatus) {

    /*
       File sudah tersimpan.
       Status gagal diubah.
       Kita hentikan proses agar mudah diketahui.
    */

    die(
        "PDF berhasil disimpan, tetapi status " .
        "pengajuan gagal diubah menjadi Selesai: " .
        mysqli_error($koneksi)
    );

}


mysqli_stmt_bind_param(
    $stmtStatus,
    "i",
    $id_pengajuan
);


if (
    !mysqli_stmt_execute(
        $stmtStatus
    )
) {

    mysqli_stmt_close(
        $stmtStatus
    );


    die(
        "PDF berhasil disimpan, tetapi status " .
        "pengajuan gagal diubah menjadi Selesai."
    );

}


mysqli_stmt_close(
    $stmtStatus
);


/* =========================================================
   PROSES BERHASIL
========================================================= */

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
    Upload Berhasil
</title>


<style>

* {
    box-sizing: border-box;
}


body {

    margin: 0;

    padding: 20px;

    background: #f1f1f1;

    font-family: Arial, sans-serif;

    display: flex;

    align-items: center;

    justify-content: center;

    min-height: 100vh;
}


.box {

    width: 100%;

    max-width: 500px;

    background: #ffffff;

    padding: 35px;

    border-radius: 10px;

    text-align: center;

    box-shadow:
        0 4px 18px rgba(0,0,0,.12);
}


.icon {

    width: 70px;

    height: 70px;

    margin: 0 auto 20px;

    border-radius: 50%;

    background: #198754;

    color: #ffffff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 40px;

    font-weight: bold;
}


h2 {

    margin: 0 0 12px;

    color: #222;

}


p {

    margin: 8px 0;

    color: #555;

    line-height: 1.5;
}


.status {

    margin-top: 18px;

    padding: 12px;

    border-radius: 6px;

    background: #d1e7dd;

    color: #0f5132;

    font-weight: bold;
}


.loading {

    margin-top: 20px;

    font-size: 13px;

    color: #777;
}

</style>

</head>


<body>


<div class="box">


    <div class="icon">
        ✓
    </div>


    <h2>
        Surat Berhasil Disimpan
    </h2>


    <p>
        PDF surat SKTM berhasil di-upload.
    </p>


    <p>
        File surat telah disimpan ke sistem.
    </p>


    <div class="status">

        Status Pengajuan: SELESAI

    </div>


    <div class="loading">

        Halaman akan ditutup...

    </div>


</div>


<script>

/* =========================================================
   TUTUP HALAMAN
========================================================= */

setTimeout(function () {

    /*
       Coba tutup tab terlebih dahulu.
    */

    window.close();


    /*
       Jika browser tidak mengizinkan
       window.close(), arahkan ke halaman
       pengajuan surat.
    */

    setTimeout(function () {

        window.location.href =
            "pengajuan_surat.php";

    }, 800);


}, 1000);

</script>


</body>

</html>

<?php

exit;

?>

