<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
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

    <title>Data Penjualan - KopiKita</title>

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

        .table th {
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
            <?= htmlspecialchars($_SESSION['nama']); ?> | Admin
        </span>

    </div>
</nav>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 sidebar">

            <a href="dashboard.php">Dashboard</a>
            <a href="produk.php">Data Produk</a>
            <a href="user.php">Data Kasir</a>
            <a href="pelanggan.php">Data Pelanggan</a>

            <a href="penjualan.php" class="active">
                Data Penjualan
            </a>

            <a href="laporan.php">Laporan</a>

            <a href="../logout.php"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </div>

        <div class="col-md-10 content">

            <div class="mb-4">
                <h3>Data Penjualan</h3>

                <p class="text-muted">
                    Riwayat transaksi penjualan
                </p>
            </div>

            <div class="card p-4">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= date('d-m-Y', strtotime($data['tgl_penjualan'])); ?>
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

                                    <a href="penjualan_detail.php?id=<?= $data['id_penjualan']; ?>"
                                       class="btn btn-sm btn-info text-white">
                                        Detail
                                    </a>

                                    <a href="penjualan_hapus.php?id=<?= $data['id_penjualan']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        Hapus
                                    </a>

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

</body>
</html>