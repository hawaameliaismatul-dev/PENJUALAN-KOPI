<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telpon = $_POST['no_telpon'];

    mysqli_query($koneksi, "
        INSERT INTO pelanggan
        (nama_pelanggan, alamat, no_telpon)
        VALUES
        ('$nama', '$alamat', '$no_telpon')
    ");

    header("Location: pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pelanggan</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <style>
        body {
            background-color: #f6f3f1;
        }

        .navbar {
            background-color: #4b2e1f;
        }

        .form-box {
            max-width: 600px;
            margin: 40px auto;
        }

        .btn-brown {
            background-color: #4b2e1f;
            color: white;
        }

        .btn-brown:hover {
            background-color: #3a2217;
            color: white;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid">
        <span class="navbar-brand">
            Sistem Penjualan Kopi
        </span>
    </div>
</nav>

<div class="container">

    <div class="card shadow-sm form-box">

        <div class="card-body">

            <h4 class="mb-4">Tambah Pelanggan</h4>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nama Pelanggan
                    </label>

                    <input type="text"
                           name="nama_pelanggan"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea name="alamat"
                              class="form-control"
                              required></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        No. Telepon
                    </label>

                    <input type="text"
                           name="no_telpon"
                           class="form-control"
                           minlength="10"
                           maxlength="13"
                           pattern="[0-9]{10,13}"
                           title="Nomor telepon harus 10 sampai 13 angka"
                           required>

                </div>

                <a href="pelanggan.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

                <button type="submit"
                        name="simpan"
                        class="btn btn-brown">
                    Simpan
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>