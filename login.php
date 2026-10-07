<?php

session_start();

include "config/koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM admin 
         WHERE username='$username' 
         AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data_admin = mysqli_fetch_assoc($query);

        $_SESSION["id_admin"] = $data_admin["id_admin"];
        $_SESSION["nama_admin"] = $data_admin["nama_admin"];
        $_SESSION["username"] = $data_admin["username"];

        header("Location: admin/dashboard.php");
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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Surat Desa</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Kotak utama login */
        .login-container {
            width: 420px;
            height: 550px;

            background-color: #d9d9d9;

            display: flex;
            flex-direction: column;
            align-items: center;

            padding-top: 30px;
        }

        /* Logo Desa */
        .logo {
            width: 85px;
            height: 85px;
            object-fit: contain;

            background-color: #ffffff;
            border-radius: 10px;

            padding: 5px;

            margin-bottom: 15px;
        }

        /* Judul sistem */
        .judul {
            width: 360px;

            text-align: center;

            font-size: 21px;
            font-weight: bold;
            line-height: 1.2;

            margin-bottom: 28px;
        }

        /* Judul LOGIN */
        .login-title {
            font-size: 26px;
            font-weight: bold;

            margin-bottom: 30px;
        }

        /* Form */
        .form-login {
            width: 365px;
        }

        /* Label */
        .form-login label {
            display: block;

            font-size: 16px;
            font-weight: bold;

            margin-bottom: 7px;
        }

        /* Input */
        .form-login input {
            width: 100%;
            height: 48px;

            border: none;
            border-radius: 9px;

            background-color: #f5f5f5;

            padding: 0 12px;

            font-size: 15px;
            font-weight: bold;

            outline: none;

            margin-bottom: 17px;
        }

        .form-login input::placeholder {
            color: #111111;
            opacity: 1;
        }

        /* Tombol Login */
        .login-button {
            width: 100%;
            height: 48px;

            border: none;
            border-radius: 9px;

            background-color: #f5f5f5;

            font-size: 18px;
            font-weight: bold;

            cursor: pointer;

            margin-top: 28px;
        }

        .login-button:hover {
            background-color: #eeeeee;
        }

        /* Pesan error */
        .pesan {
            width: 365px;

            margin-top: 15px;

            text-align: center;

            font-size: 14px;
            font-weight: bold;
        }

    </style>

</head>

<body>

    <div class="login-container">

        <!-- Logo Desa Sampiran -->
        <img
            src="logo_desa.png"
            alt="Logo Desa Sampiran"
            class="logo"
        >

        <!-- Judul Sistem -->
        <h1 class="judul">
            SISTEM INFORMASI PELAYANAN<br>
            SURAT MENYURAT DESA
        </h1>

        <!-- Judul Login -->
        <h2 class="login-title">
            LOGIN
        </h2>

        <!-- Form Login -->
        <form
            class="form-login"
            method="POST"
            action=""
        >

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                placeholder="masukan username"
                required
            >

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="masukan password"
                required
            >

            <button
                type="submit"
                class="login-button"
            >
                LOGIN
            </button>

        </form>

        <?php if ($pesan != "") { ?>

            <div class="pesan">
                <?php echo $pesan; ?>
            </div>

        <?php } ?>

    </div>

</body>

</html>