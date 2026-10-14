<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    mysqli_query($koneksi, "INSERT INTO user
        (nama, username, password, role)
        VALUES ('$nama', '$username', '$password', 'kasir')
    ");

    header("Location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kasir - KopiKita</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <style>
        body {
            background: #f6f3f1;
        }

        .navbar {
            background: #4b2e1f;
        }

        .navbar-brand,
        .navbar-text {
            color: white !important;
        }

        .content {
            min-height: calc(100vh - 56px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .form-box {
            width: 500px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .form-box h3 {
            color: #4b2e1f;
        }

        .btn-simpan {
            background: #4b2e1f;
            color: white;
        }

        .btn-simpan:hover {
            background: #6b4430;
            color: white;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container-fluid px-4">

        <span class="navbar-brand fw-bold">
            KopiKita
        </span>

        <span class="navbar-text">
            <?= htmlspecialchars($_SESSION['nama']); ?> | Admin
        </span>

    </div>
</nav>

<div class="content">

    <div class="form-box">

        <h3 class="text-center mb-2">
            Tambah Kasir
        </h3>

        <p class="text-muted text-center mb-4">
            Tambahkan akun kasir baru
        </p>

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Nama Kasir
                </label>

                <input type="text"
                       name="nama"
                       class="form-control"
                       placeholder="Masukkan nama kasir"
                       required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Username
                </label>

                <input type="text"
                       name="username"
                       class="form-control"
                       placeholder="Masukkan username"
                       required>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Masukkan password"
                       required>

            </div>

            <div class="d-flex justify-content-between">

                <a href="user.php" class="btn btn-secondary">
                    Kembali
                </a>

                <button type="submit"
                        name="simpan"
                        class="btn btn-simpan">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>