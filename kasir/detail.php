<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id_penjualan = $_GET['id'];

$query_penjualan = mysqli_query($koneksi, "
    SELECT penjualan.*, pelanggan.nama_pelanggan
    FROM penjualan
    LEFT JOIN pelanggan
    ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    WHERE penjualan.id_penjualan = '$id_penjualan'
");

$data_penjualan = mysqli_fetch_assoc($query_penjualan);

if (!$data_penjualan) {
    header("Location: riwayat.php");
    exit;
}

$query_detail = mysqli_query($koneksi, "
    SELECT detailpenjualan.*, produk.nama_produk, produk.harga
    FROM detailpenjualan
    JOIN produk
    ON detailpenjualan.id_produk = produk.id_produk
    WHERE detailpenjualan.id_penjualan = '$id_penjualan'
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Transaksi</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <style>
        body {
            background-color: #f6f3f1;
        }

        .navbar {
            background-color: #4b2e1f;
        }

        .sidebar {
            min-height: 100vh;
            background-color: white;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #4b2e1f;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #4b2e1f;
            color: white;
        }

        .content {
            padding: 30px;
        }

        .table thead {
            background-color: #4b2e1f;
            color: white;
        }

        .btn-brown {
            background-color: #4b2e1f;
            color: white;
        }

        .btn-brown:hover {
            background-color: #3a2217;
            color: white;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            color: #4b2e1f;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
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

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar">

            <h5 class="mb-4">Menu Kasir</h5>

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="transaksi.php">
                Transaksi
            </a>

            <a href="riwayat.php" class="active">
                Riwayat Penjualan
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </div>


        <!-- CONTENT -->
        <div class="col-md-10 content">

            <h3 class="mb-4">Detail Transaksi</h3>


            <!-- INFORMASI TRANSAKSI -->
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <strong>ID Transaksi</strong>
                            <p>
                                #<?= $data_penjualan['id_penjualan']; ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <strong>Tanggal</strong>
                            <p>
                                <?= date(
                                    'd-m-Y',
                                    strtotime($data_penjualan['tgl_penjualan'])
                                ); ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <strong>Pelanggan</strong>
                            <p>
                                <?= $data_penjualan['nama_pelanggan']
                                    ? htmlspecialchars($data_penjualan['nama_pelanggan'])
                                    : 'Umum'; ?>
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- DETAIL PRODUK -->
            <div class="card shadow-sm">

                <div class="card-body">

                    <h5 class="mb-3">
                        Produk yang Dibeli
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php
                            $no = 1;

                            while ($data = mysqli_fetch_assoc($query_detail)) {
                            ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['nama_produk']); ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format(
                                            $data['harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= $data['jumlah_produk']; ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format(
                                            $data['subtotal'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- TOTAL -->
                    <div class="text-end mt-3">

                        <span class="total">
                            Total:
                            Rp <?= number_format(
                                $data_penjualan['total_harga'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </span>

                    </div>


                    <!-- TOMBOL -->
                    <div class="mt-4">

                        <a href="riwayat.php"
                           class="btn btn-secondary">
                            Kembali
                        </a>

                        <a href="cetak_struk.php?id=<?= $data_penjualan['id_penjualan']; ?>"
                           target="_blank"
                           class="btn btn-brown">
                            Cetak Struk
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="../js/bootstrap.bundle.min.js"></script>

</body>
</html>