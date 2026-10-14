<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$produk = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM produk"
))['total'];

$kasir = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM user WHERE role='kasir'"
))['total'];

$pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM pelanggan"
))['total'];

$penjualan = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM penjualan"
))['total'];

$omset = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COALESCE(SUM(total_harga),0) AS total FROM penjualan"
))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - KopiKita</title>

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

        .card h6 {
            color: #777;
        }

        .card h3 {
            color: #4b2e1f;
            font-weight: bold;
        }

        .omset {
            background: #4b2e1f;
        }

        .omset h6,
        .omset h3 {
            color: white;
        }
    </style>
</head>

<body>

<!-- Navbar -->
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


<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">

            <a href="dashboard.php" class="active">
                Dashboard
            </a>

            <a href="produk.php">
                Data Produk
            </a>

            <a href="user.php">
                Data Kasir
            </a>

            <a href="pelanggan.php">
                Data Pelanggan
            </a>

            <a href="penjualan.php">
                Data Penjualan
            </a>

            <a href="laporan.php">
                Laporan
            </a>

            <a href="../logout.php"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </div>


        <!-- Isi -->
        <div class="col-md-10 content">

            <h3 class="mb-1">Dashboard</h3>

            <p class="text-muted mb-4">
                Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?>
            </p>


            <div class="row g-4">

                <!-- Produk -->
                <div class="col-md-4">
                    <div class="card p-4">
                        <h6>Total Produk</h6>
                        <h3><?= $produk; ?></h3>
                    </div>
                </div>


                <!-- Kasir -->
                <div class="col-md-4">
                    <div class="card p-4">
                        <h6>Total Kasir</h6>
                        <h3><?= $kasir; ?></h3>
                    </div>
                </div>


                <!-- Pelanggan -->
                <div class="col-md-4">
                    <div class="card p-4">
                        <h6>Total Pelanggan</h6>
                        <h3><?= $pelanggan; ?></h3>
                    </div>
                </div>


                <!-- Penjualan -->
                <div class="col-md-6">
                    <div class="card p-4">
                        <h6>Total Penjualan</h6>
                        <h3><?= $penjualan; ?> Transaksi</h3>
                    </div>
                </div>


                <!-- Omset -->
                <div class="col-md-6">
                    <div class="card omset p-4">
                        <h6>Total Omzet</h6>
                        <h3>
                            Rp <?= number_format($omset, 0, ',', '.'); ?>
                        </h3>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>
