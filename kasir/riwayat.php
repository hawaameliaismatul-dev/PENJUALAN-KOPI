<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT penjualan.*, pelanggan.nama_pelanggan
    FROM penjualan
    LEFT JOIN pelanggan
    ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    ORDER BY penjualan.id_penjualan DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Penjualan</title>

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

    </style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark">

    <div class="container-fluid">

        <span class="navbar-brand">
            Sistem Penjualan Kopi
        </span>

        <span class="text-white">
            Kasir:
            <?= htmlspecialchars($_SESSION['nama']); ?>
        </span>

    </div>

</nav>


<div class="container-fluid">

    <div class="row">


        <!-- SIDEBAR -->

        <div class="col-md-2 sidebar">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="transaksi.php" class="active">
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

        <!-- CONTENT -->

        <div class="col-md-10 content">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>
                    Riwayat Penjualan
                </h3>

                <a href="transaksi.php"
                   class="btn btn-brown">
                    + Transaksi Baru
                </a>

            </div>


            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php

                            $no = 1;

                            while ($data = mysqli_fetch_assoc($query)) {

                            ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'd-m-Y',
                                            strtotime($data['tgl_penjualan'])
                                        ); ?>
                                    </td>

                                    <td>

                                        <?php

                                        if ($data['nama_pelanggan']) {

                                            echo htmlspecialchars(
                                                $data['nama_pelanggan']
                                            );

                                        } else {

                                            echo "Umum";

                                        }

                                        ?>

                                    </td>

                                    <td>

                                        Rp
                                        <?= number_format(
                                            $data['total_harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </td>

                                    <td>

                                        <!-- TOMBOL CETAK -->

                                        <a href="cetak_struk.php?id=<?= $data['id_penjualan']; ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-brown">

                                            Cetak Struk

                                        </a>

                                    </td>

                                </tr>

                            <?php

                            }

                            ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>