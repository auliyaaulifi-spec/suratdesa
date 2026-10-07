<?php

session_start();

if (!isset($_SESSION["id_masyarakat"])) {
    header("Location: login_masyarakat.php");
    exit;
}

include "../config/koneksi.php";

$id_masyarakat = $_SESSION["id_masyarakat"];


// ===============================
// AMBIL DATA MASYARAKAT
// ===============================

$query = mysqli_query($koneksi, "
    SELECT *
    FROM masyarakat
    WHERE id_masyarakat = '$id_masyarakat'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data masyarakat tidak ditemukan.");
}

$pesan = "";


// ===============================
// PROSES SIMPAN DATA
// ===============================

if (isset($_POST["simpan"])) {

    $nik = mysqli_real_escape_string(
        $koneksi,
        $_POST["nik"]
    );

    $no_kk = mysqli_real_escape_string(
        $koneksi,
        $_POST["no_kk"]
    );

    $nama_lengkap = mysqli_real_escape_string(
        $koneksi,
        $_POST["nama_lengkap"]
    );

    $jenis_kelamin = mysqli_real_escape_string(
        $koneksi,
        $_POST["jenis_kelamin"]
    );

    $tempat_lahir = mysqli_real_escape_string(
        $koneksi,
        $_POST["tempat_lahir"]
    );

    $tanggal_lahir = mysqli_real_escape_string(
        $koneksi,
        $_POST["tanggal_lahir"]
    );

    $pekerjaan = mysqli_real_escape_string(
        $koneksi,
        $_POST["pekerjaan"]
    );

    $agama = mysqli_real_escape_string(
        $koneksi,
        $_POST["agama"]
    );

    $status_perkawinan = mysqli_real_escape_string(
        $koneksi,
        $_POST["status_perkawinan"]
    );

    $kewarganegaraan = mysqli_real_escape_string(
        $koneksi,
        $_POST["kewarganegaraan"]
    );

    $alamat = mysqli_real_escape_string(
        $koneksi,
        $_POST["alamat"]
    );

    $no_hp = mysqli_real_escape_string(
        $koneksi,
        $_POST["no_hp"]
    );


    // ===============================
    // UPDATE DATA
    // ===============================

    $update = mysqli_query($koneksi, "
        UPDATE masyarakat
        SET
            nik = '$nik',
            no_kk = '$no_kk',
            nama_lengkap = '$nama_lengkap',
            jenis_kelamin = '$jenis_kelamin',
            tempat_lahir = '$tempat_lahir',
            tanggal_lahir = '$tanggal_lahir',
            pekerjaan = '$pekerjaan',
            agama = '$agama',
            status_perkawinan = '$status_perkawinan',
            kewarganegaraan = '$kewarganegaraan',
            alamat = '$alamat',
            no_hp = '$no_hp'
        WHERE id_masyarakat = '$id_masyarakat'
    ");


    if ($update) {

        $pesan = "Profil berhasil diperbarui.";

        // Ambil ulang data setelah berhasil disimpan
        $query = mysqli_query($koneksi, "
            SELECT *
            FROM masyarakat
            WHERE id_masyarakat = '$id_masyarakat'
        ");

        $data = mysqli_fetch_assoc($query);

    } else {

        $pesan = "Profil gagal diperbarui.";

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Profil Masyarakat - Sistem Informasi Pelayanan Surat Menyurat Desa
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* ================= HEADER ================= */

        .header {
            height: 80px;
            background: #d9d9d9;
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .header img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .header-title {
            margin-left: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #222;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: 240px;
            height: calc(100vh - 80px);
            background: #eeeeee;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: #222;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #d5d5d5;
        }

        /* ================= CONTENT ================= */

        .content {
            margin-left: 240px;
            padding: 35px;
        }

        h1 {
            margin-top: 0;
            font-size: 28px;
        }

        .subtitle {
            margin-bottom: 25px;
            color: #555;
        }

        /* ================= PROFILE BOX ================= */

        .profile-box {
            background: white;
            padding: 30px;
            width: 100%;
            max-width: 900px;
            border-radius: 5px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            font-family: Arial, sans-serif;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #555;
        }

        /* ================= BUTTON ================= */

        .btn-simpan {
            padding: 12px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-simpan:hover {
            background: #555;
        }

        /* ================= PESAN ================= */

        .pesan {
            margin-bottom: 20px;
            padding: 12px;
            background: #eeeeee;
            border: 1px solid #ccc;
            color: #333;
            border-radius: 4px;
        }

    </style>

</head>

<body>


    <!-- ================= HEADER ================= -->

    <div class="header">

        <img
            src="../assets/logo_desa.png"
            alt="Logo Desa Sampiran"
        >

        <div class="header-title">
            SISTEM INFORMASI PELAYANAN SURAT MENYURAT DESA
        </div>

    </div>


    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar">

        <a href="dashboard.php">
            DASHBOARD
        </a>

        <a href="pengajuan_surat.php">
            PENGAJUAN SURAT
        </a>

        <a href="status_pengajuan.php">
            STATUS PENGAJUAN
        </a>

        <a href="riwayat_surat.php">
            RIWAYAT SURAT
        </a>

        <a href="profil.php">
            PROFIL
        </a>

        <a href="../logout.php">
            LOGOUT
        </a>

    </div>


    <!-- ================= CONTENT ================= -->

    <div class="content">

        <h1>
            PROFIL MASYARAKAT
        </h1>

        <div class="subtitle">
            Informasi data profil masyarakat.
        </div>


        <?php if ($pesan != "") { ?>

            <div class="pesan">
                <?php echo htmlspecialchars($pesan); ?>
            </div>

        <?php } ?>


        <div class="profile-box">

            <div class="section-title">
                DATA PROFIL
            </div>


            <form method="POST">


                <!-- NAMA LENGKAP -->

                <div class="form-group">

                    <label>
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        value="<?php echo htmlspecialchars($data["nama_lengkap"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- NIK -->

                <div class="form-group">

                    <label>
                        NIK
                    </label>

                    <input
                        type="text"
                        name="nik"
                        value="<?php echo htmlspecialchars($data["nik"] ?? ""); ?>"
                        maxlength="16"
                        required
                    >

                </div>


                <!-- NO KK -->

                <div class="form-group">

                    <label>
                        No. KK
                    </label>

                    <input
                        type="text"
                        name="no_kk"
                        value="<?php echo htmlspecialchars($data["no_kk"] ?? ""); ?>"
                        maxlength="16"
                        required
                    >

                </div>


                <!-- TEMPAT LAHIR -->

                <div class="form-group">

                    <label>
                        Tempat Lahir
                    </label>

                    <input
                        type="text"
                        name="tempat_lahir"
                        value="<?php echo htmlspecialchars($data["tempat_lahir"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- TANGGAL LAHIR -->

                <div class="form-group">

                    <label>
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="<?php echo htmlspecialchars($data["tanggal_lahir"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- JENIS KELAMIN -->

                <div class="form-group">

                    <label>
                        Jenis Kelamin
                    </label>

                    <select
                        name="jenis_kelamin"
                        required
                    >

                        <option value="">
                            -- Pilih Jenis Kelamin --
                        </option>

                        <option
                            value="Laki-laki"
                            <?php
                            if (($data["jenis_kelamin"] ?? "") == "Laki-laki") {
                                echo "selected";
                            }
                            ?>
                        >
                            Laki-laki
                        </option>

                        <option
                            value="Perempuan"
                            <?php
                            if (($data["jenis_kelamin"] ?? "") == "Perempuan") {
                                echo "selected";
                            }
                            ?>
                        >
                            Perempuan
                        </option>

                    </select>

                </div>


                <!-- PEKERJAAN -->

                <div class="form-group">

                    <label>
                        Pekerjaan
                    </label>

                    <input
                        type="text"
                        name="pekerjaan"
                        value="<?php echo htmlspecialchars($data["pekerjaan"] ?? ""); ?>"
                    >

                </div>


                <!-- AGAMA -->

                <div class="form-group">

                    <label>
                        Agama
                    </label>

                    <select
                        name="agama"
                        required
                    >

                        <option value="">
                            -- Pilih Agama --
                        </option>

                        <option
                            value="Islam"
                            <?php
                            if (($data["agama"] ?? "") == "Islam") {
                                echo "selected";
                            }
                            ?>
                        >
                            Islam
                        </option>

                        <option
                            value="Kristen"
                            <?php
                            if (($data["agama"] ?? "") == "Kristen") {
                                echo "selected";
                            }
                            ?>
                        >
                            Kristen
                        </option>

                        <option
                            value="Katolik"
                            <?php
                            if (($data["agama"] ?? "") == "Katolik") {
                                echo "selected";
                            }
                            ?>
                        >
                            Katolik
                        </option>

                        <option
                            value="Hindu"
                            <?php
                            if (($data["agama"] ?? "") == "Hindu") {
                                echo "selected";
                            }
                            ?>
                        >
                            Hindu
                        </option>

                        <option
                            value="Buddha"
                            <?php
                            if (($data["agama"] ?? "") == "Buddha") {
                                echo "selected";
                            }
                            ?>
                        >
                            Buddha
                        </option>

                        <option
                            value="Konghucu"
                            <?php
                            if (($data["agama"] ?? "") == "Konghucu") {
                                echo "selected";
                            }
                            ?>
                        >
                            Konghucu
                        </option>

                    </select>

                </div>


                <!-- STATUS PERKAWINAN -->

                <div class="form-group">

                    <label>
                        Status Perkawinan
                    </label>

                    <select
                        name="status_perkawinan"
                        required
                    >

                        <option value="">
                            -- Pilih Status --
                        </option>

                        <option
                            value="Belum Kawin"
                            <?php
                            if (($data["status_perkawinan"] ?? "") == "Belum Kawin") {
                                echo "selected";
                            }
                            ?>
                        >
                            Belum Kawin
                        </option>

                        <option
                            value="Kawin"
                            <?php
                            if (($data["status_perkawinan"] ?? "") == "Kawin") {
                                echo "selected";
                            }
                            ?>
                        >
                            Kawin
                        </option>

                        <option
                            value="Cerai Hidup"
                            <?php
                            if (($data["status_perkawinan"] ?? "") == "Cerai Hidup") {
                                echo "selected";
                            }
                            ?>
                        >
                            Cerai Hidup
                        </option>

                        <option
                            value="Cerai Mati"
                            <?php
                            if (($data["status_perkawinan"] ?? "") == "Cerai Mati") {
                                echo "selected";
                            }
                            ?>
                        >
                            Cerai Mati
                        </option>

                    </select>

                </div>


                <!-- KEWARGANEGARAAN -->

                <div class="form-group">

                    <label>
                        Kewarganegaraan
                    </label>

                    <input
                        type="text"
                        name="kewarganegaraan"
                        value="<?php echo htmlspecialchars($data["kewarganegaraan"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- ALAMAT -->

                <div class="form-group">

                    <label>
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        required
                    ><?php echo htmlspecialchars($data["alamat"] ?? ""); ?></textarea>

                </div>


                <!-- NO HP -->

                <div class="form-group">

                    <label>
                        No. HP
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        value="<?php echo htmlspecialchars($data["no_hp"] ?? ""); ?>"
                        maxlength="15"
                        required
                    >

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >
                    SIMPAN PERUBAHAN
                </button>


            </form>

        </div>

    </div>

</body>

</html>