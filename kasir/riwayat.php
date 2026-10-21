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

    <title>Riwayat Penjualan - KopiKita</title>

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

        .btn-brown {
            background: #4b2e1f;
            color: white;
        }

        .btn-brown:hover {
            background: #6b4430;
            color: white;
        }

        .table thead th {
            background: #4b2e1f;
            color: white;
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

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="transaksi.php">
                Transaksi
            </a>

            <a href="pelanggan.php">
                Data Pelanggan
            </a>

            <a href="riwayat.php" class="active">
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

                <div>
                    <h3 class="fw-bold mb-1" style="color: #4b2e1f;">
                        Riwayat Penjualan
                    </h3>

                    <p class="text-muted mb-0">
                        Daftar transaksi penjualan KopiKita.
                    </p>
                </div>

                <a href="transaksi.php" class="btn btn-brown">
                    + Transaksi Baru
                </a>

            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

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

                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= date(
                                            'd-m-Y',
                                            strtotime($data['tgl_penjualan'])
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $data['nama_pelanggan'] ?? 'Umum'
                                        ); ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format(
                                            $data['total_harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                    </td>

                                    <td>
                                        <a href="cetak_struk.php?id=<?= $data['id_penjualan']; ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-brown">
                                            Cetak Struk
                                        </a>
                                    </td>
                                </tr>

                            <?php
                                }
                            } else {
                            ?>

                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada riwayat penjualan.
                                    </td>
                                </tr>

                            <?php } ?>

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