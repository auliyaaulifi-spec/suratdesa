<?php

session_start();

include "../config/koneksi.php";

$pesan = "";

if (isset($_POST["login"])) {

    $username = mysqli_real_escape_string(
        $koneksi,
        $_POST["username"]
    );

    $password = mysqli_real_escape_string(
        $koneksi,
        $_POST["password"]
    );

    $query = "
        SELECT *
        FROM masyarakat
        WHERE username = '$username'
        AND password = '$password'
        AND status_akun = 'aktif'
    ";

    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {

        $data = mysqli_fetch_assoc($result);

        $_SESSION["id_masyarakat"] = $data["id_masyarakat"];

        $_SESSION["nama_masyarakat"] = $data["nama_lengkap"];

        $_SESSION["username_masyarakat"] = $data["username"];

        header("Location: dashboard.php");
        exit;

    } else {

        $pesan = "Username atau password salah!";

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Login Masyarakat - Sistem Surat Desa
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {

            margin: 0;

            font-family: Arial, Helvetica, sans-serif;

            background: #f5f5f5;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;
        }

        .login-box {

            width: 420px;

            background: white;

            border: 1px solid #ccc;

            padding: 35px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .logo {

            text-align: center;

            margin-bottom: 15px;
        }

        .logo img {

            width: 75px;

            height: 75px;

            object-fit: contain;
        }

        .judul {

            text-align: center;

            font-size: 18px;

            font-weight: bold;

            margin-bottom: 25px;

            line-height: 1.4;
        }

        h2 {

            text-align: center;

            font-size: 22px;

            margin-bottom: 25px;
        }

        .form-group {

            margin-bottom: 18px;
        }

        label {

            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: bold;
        }

        input {

            width: 100%;

            height: 42px;

            padding: 10px;

            border: 1px solid #bbb;

            border-radius: 3px;

            font-size: 14px;
        }

        .btn-login {

            width: 100%;

            height: 42px;

            background: #333;

            color: white;

            border: none;

            border-radius: 3px;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;
        }

        .btn-login:hover {

            background: #555;
        }

        .pesan {

            background: #f8d7da;

            color: #842029;

            border: 1px solid #f5c2c7;

            padding: 10px;

            margin-bottom: 18px;

            text-align: center;

            font-size: 13px;
        }

        .keterangan {

            text-align: center;

            margin-top: 20px;

            font-size: 13px;

            color: #666;
        }

    </style>

</head>

<body>


<div class="login-box">


    <div class="logo">

        <img
            src="../assets/logo_desa.png"
            alt="Logo Desa Sampiran"
        >

    </div>


    <div class="judul">

        SISTEM INFORMASI PELAYANAN
        SURAT MENYURAT DESA

    </div>


    <h2>

        LOGIN MASYARAKAT

    </h2>


    <?php if ($pesan != "") { ?>

        <div class="pesan">

            <?php
            echo htmlspecialchars($pesan);
            ?>

        </div>

    <?php } ?>


    <form method="POST">


        <div class="form-group">

            <label>
                Username
            </label>

            <input
                type="text"
                name="username"
                placeholder="Masukkan username"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

        </div>


        <button
            type="submit"
            name="login"
            class="btn-login"
        >

            LOGIN

        </button>


    </form>


    <div class="keterangan">

        Login khusus masyarakat Desa Sampiran

    </div>


</div>


</body>

</html>