<?php
session_start();

if (isset($_SESSION['id'])) {

    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: kasir/dashboard.php");
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - KopiKita</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        body {
            background-color: #f3ebe5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 400px;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .logo {
            text-align: center;
            color: #4b2e1f;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #8b6b5a;
            margin-bottom: 30px;
        }

        .form-label {
            color: #4b2e1f;
            font-weight: 600;
        }

        .form-control {
            padding: 11px;
            border-radius: 8px;
        }

        .btn-login {
            background-color: #4b2e1f;
            color: white;
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #6b4430;
            color: white;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="logo">
        ☕ KopiKita
    </div>

    <div class="subtitle">
        Login Sistem Penjualan
    </div>

    <?php
    if (isset($_GET['pesan'])) {

        if ($_GET['pesan'] == 'gagal') {
            echo '<div class="alert alert-danger">
                    Username atau password salah.
                  </div>';
        }

        if ($_GET['pesan'] == 'logout') {
            echo '<div class="alert alert-success">
                    Berhasil logout.
                  </div>';
        }
    }
    ?>

    <form action="proses_login.php" method="POST">

        <div class="mb-3">
            <label class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                required
            >
        </div>

        <div class="mb-4">
            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required
            >
        </div>

        <button type="submit" class="btn btn-login">
            Login
        </button>

    </form>

</div>

</body>
</html>
