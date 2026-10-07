<?php

session_start();

if (!isset($_SESSION["id_admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$pesan = "";

/* =========================
   SIMPAN NOMOR SURAT
========================= */
if (isset($_POST["simpan"])) {

    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST["nomor_surat"]);
    $nomor_urut = mysqli_real_escape_string($koneksi, $_POST["nomor_urut"]);
    $tahun = mysqli_real_escape_string($koneksi, $_POST["tahun"]);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST["keterangan"]);

    $query = "INSERT INTO nomor_surat
              (nomor_surat, nomor_urut, tahun, keterangan)
              VALUES
              ('$nomor_surat', '$nomor_urut', '$tahun', '$keterangan')";

    if (mysqli_query($koneksi, $query)) {
        $pesan = "Nomor surat berhasil disimpan.";
    } else {
        $pesan = "Nomor surat gagal disimpan.";
    }
}

/* =========================
   AMBIL DATA NOMOR SURAT
========================= */

$query_nomor = "SELECT * FROM nomor_surat
                ORDER BY id_nomor_surat DESC";

$result_nomor = mysqli_query($koneksi, $query_nomor);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Nomor Surat - Sistem Surat Desa</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            position: fixed;
            top: 0;
            left: 0;

            width: 100%;
            height: 80px;

            background: #e5e5e5;

            display: flex;
            align-items: center;

            padding-left: 20px;

            border-bottom: 1px solid #ccc;

            z-index: 10;
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

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;

            top: 80px;
            left: 0;

            width: 240px;
            height: calc(100vh - 80px);

            background: #eeeeee;

            padding-top: 20px;

            border-right: 1px solid #ccc;
        }

        .sidebar a {
            display: block;

            padding: 13px 20px;

            color: #222;

            text-decoration: none;

            font-size: 14px;
        }

        .sidebar a:hover {
            background: #dcdcdc;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            margin-left: 240px;

            padding: 110px 35px 40px 35px;
        }

        .content h1 {
            margin-top: 0;

            font-size: 28px;
        }

        .subjudul {
            margin-bottom: 25px;

            color: #555;
        }

        /* =========================
           PESAN
        ========================= */

        .pesan {
            background: #dff0d8;

            border: 1px solid #b8dca8;

            padding: 12px 15px;

            margin-bottom: 20px;

            width: 100%;

            color: #315b25;
        }

        /* =========================
           FORM
        ========================= */

        .box {
            background: white;

            border: 1px solid #ccc;

            padding: 25px;

            margin-bottom: 30px;
        }

        .box h2 {
            margin-top: 0;

            font-size: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-weight: bold;

            font-size: 14px;
        }

        .form-group input {
            width: 100%;

            height: 40px;

            padding: 8px 10px;

            border: 1px solid #bbb;

            border-radius: 3px;

            font-size: 14px;
        }

        .btn-simpan {
            background: #333;

            color: white;

            border: none;

            padding: 11px 22px;

            cursor: pointer;

            font-size: 14px;

            border-radius: 3px;
        }

        .btn-simpan:hover {
            background: #555;
        }

        /* =========================
           TABLE
        ========================= */

        table {
            width: 100%;

            border-collapse: collapse;

            background: white;
        }

        table th,
        table td {
            border: 1px solid #ccc;

            padding: 12px;

            text-align: left;

            font-size: 14px;
        }

        table th {
            background: #eeeeee;

            font-weight: bold;
        }

        .kosong {
            text-align: center;

            color: #777;
        }

    </style>

</head>

<body>

<!-- =========================
     HEADER
========================= -->

<div class="header">

    <img src="../assets/logo_desa.png" alt="Logo Desa Sampiran">

    <div class="header-title">
        SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA
    </div>

</div>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <a href="dashboard.php">DASHBOARD</a>

    <a href="data_masyarakat.php">DATA MASYARAKAT</a>

    <a href="pengajuan_surat.php">PENGAJUAN SURAT</a>

    <a href="verifikasi_pengajuan.php">VERIFIKASI PENGAJUAN</a>

    <a href="cetak_surat.php">CETAK SURAT</a>

    <a href="arsip_surat.php">ARSIP SURAT</a>

    <a href="nomor_surat.php">NOMOR SURAT</a>

    <a href="profil.php">PROFIL</a>

    <a href="../logout.php">LOGOUT</a>

</div>


<!-- =========================
     CONTENT
========================= -->

<div class="content">

    <h1>NOMOR SURAT</h1>

    <div class="subjudul">
        Kelola nomor surat yang digunakan dalam pelayanan administrasi desa
    </div>


    <?php if ($pesan != "") { ?>

        <div class="pesan">
            <?php echo htmlspecialchars($pesan); ?>
        </div>

    <?php } ?>


    <!-- FORM NOMOR SURAT -->

    <div class="box">

        <h2>NOMOR SURAT</h2>

        <form method="POST">

            <div class="form-group">

                <label>Nomor Surat</label>

                <input
                    type="text"
                    name="nomor_surat"
                    placeholder="Masukkan nomor surat"
                    required
                >

            </div>


            <div class="form-group">

                <label>Nomor Urut</label>

                <input
                    type="number"
                    name="nomor_urut"
                    placeholder="Masukkan nomor urut"
                    required
                >

            </div>


            <div class="form-group">

                <label>Tahun</label>

                <input
                    type="number"
                    name="tahun"
                    placeholder="Masukkan tahun"
                    value="<?php echo date('Y'); ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Keterangan</label>

                <input
                    type="text"
                    name="keterangan"
                    placeholder="Masukkan keterangan"
                    required
                >

            </div>


            <button
                type="submit"
                name="simpan"
                class="btn-simpan"
            >
                SIMPAN
            </button>

        </form>

    </div>


    <!-- DAFTAR NOMOR SURAT -->

    <div class="box">

        <h2>DAFTAR NOMOR SURAT</h2>

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nomor Surat</th>

                    <th>No. Urut</th>

                    <th>Tahun</th>

                    <th>Keterangan</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                if (mysqli_num_rows($result_nomor) > 0) {

                    while ($row = mysqli_fetch_assoc($result_nomor)) {

                ?>

                        <tr>

                            <td>
                                <?php echo $no++; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["nomor_surat"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["nomor_urut"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["tahun"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["keterangan"]); ?>
                            </td>

                        </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="5"
                            class="kosong"
                        >
                            Belum ada nomor surat.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>