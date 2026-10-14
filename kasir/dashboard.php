<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

$produk = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM produk"
))['total'];

$pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM pelanggan"
))['total'];

$penjualan = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM penjualan"
))['total'];

$omzet = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COALESCE(SUM(total_harga),0) AS total FROM penjualan"
))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Kasir - KopiKita</title>

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

        .sidebar {
            min-height: calc(100vh - 56px);
            background: white;
            border-right: 1px solid #ddd;
            padding: 20px 10px;
        }

        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 5px;
            color: #4b2e1f;
            text-decoration: none;
            border-radius: 7px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #4b2e1f;
            color: white;
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .omzet {
            background: #4b2e1f;
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
            <?= htmlspecialchars($_SESSION['nama']); ?> | Kasir
        </span>

    </div>

</nav>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 sidebar">

            <a href="dashboard.php" class="active">
                Dashboard
            </a>

            <a href="transaksi.php">
                Transaksi
            </a>

            <a href="pelanggan.php">
                Data Pelanggan
            </a>

            <a href="riwayat.php">
                Riwayat Penjualan
            </a>

            <a href="../logout.php"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </div>

        <div class="col-md-10 content">

            <h3>
                Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>
            </h3>

            <p class="text-muted mb-4">
                Kelola transaksi penjualan kopi
            </p>

            <div class="row">

                <div class="col-md-4 mb-4">

                    <div class="card p-4">

                        <p class="text-muted mb-1">
                            Total Produk
                        </p>

                        <h3>
                            <?= $produk; ?>
                        </h3>

                    </div>

                </div>

                <div class="col-md-4 mb-4">

                    <div class="card p-4">

                        <p class="text-muted mb-1">
                            Total Pelanggan
                        </p>

                        <h3>
                            <?= $pelanggan; ?>
                        </h3>

                    </div>

                </div>

                <div class="col-md-4 mb-4">

                    <div class="card p-4">

                        <p class="text-muted mb-1">
                            Total Transaksi
                        </p>

                        <h3>
                            <?= $penjualan; ?>
                        </h3>

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="card omzet p-4">

                        <p class="mb-1">
                            Total Omzet
                        </p>

                        <h2>
                            Rp <?= number_format(
                                $omzet,
                                0,
                                ',',
                                '.'
                            ); ?>
                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>