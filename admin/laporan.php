<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-01');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

$query = mysqli_query($koneksi, "
    SELECT penjualan.*, pelanggan.nama_pelanggan
    FROM penjualan
    LEFT JOIN pelanggan
        ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    WHERE DATE(tgl_penjualan) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY tgl_penjualan DESC
");

$jumlah_transaksi = mysqli_num_rows($query);

$omzet_query = mysqli_query($koneksi, "
    SELECT COALESCE(SUM(total_harga), 0) AS omzet
    FROM penjualan
    WHERE DATE(tgl_penjualan) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
");

$omzet = mysqli_fetch_assoc($omzet_query)['omzet'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan - KopiKita</title>

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

        .btn-cari {
            background: #4b2e1f;
            color: white;
        }

        .btn-cari:hover {
            background: #6b4430;
            color: white;
        }

        .table th {
            background: #4b2e1f;
            color: white;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .info-card h4 {
            color: #4b2e1f;
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
            <a href="penjualan.php">Data Penjualan</a>
            <a href="laporan.php" class="active">Laporan</a>

            <a href="../logout.php"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>

        </div>

        <div class="col-md-10 content">

            <div class="mb-4">
                <h3>Laporan Penjualan</h3>
                <p class="text-muted">
                    Laporan transaksi penjualan
                </p>
            </div>

            <!-- Filter -->
            <div class="card p-4 mb-4">

                <form method="GET">

                    <div class="row align-items-end">

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Awal
                            </label>

                            <input type="date"
                                   name="tanggal_awal"
                                   class="form-control"
                                   value="<?= $tanggal_awal; ?>"
                                   required>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Akhir
                            </label>

                            <input type="date"
                                   name="tanggal_akhir"
                                   class="form-control"
                                   value="<?= $tanggal_akhir; ?>"
                                   required>

                        </div>

                        <div class="col-md-4">

                            <button type="submit"
                                    class="btn btn-cari">
                                Tampilkan Laporan
                            </button>

                        </div>

                    </div>

                </form>

            </div>

            <!-- Ringkasan -->
            <div class="row mb-4">

                <div class="col-md-6 mb-3">

                    <div class="info-card">

                        <p class="text-muted mb-1">
                            Jumlah Transaksi
                        </p>

                        <h4 class="fw-bold">
                            <?= $jumlah_transaksi; ?>
                        </h4>

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <div class="info-card">

                        <p class="text-muted mb-1">
                            Total Omzet
                        </p>

                        <h4 class="fw-bold">
                            Rp <?= number_format(
                                $omzet,
                                0,
                                ',',
                                '.'
                            ); ?>
                        </h4>

                    </div>

                </div>

            </div>

            <!-- Tabel -->
            <div class="card p-4">

                <h5 class="mb-3">
                    Detail Laporan
                </h5>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
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