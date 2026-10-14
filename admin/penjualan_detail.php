<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT penjualan.*, pelanggan.nama_pelanggan
    FROM penjualan
    LEFT JOIN pelanggan
        ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    WHERE penjualan.id_penjualan='$id'
");

$penjualan = mysqli_fetch_assoc($query);

if (!$penjualan) {
    header("Location: penjualan.php");
    exit;
}

$detail = mysqli_query($koneksi, "
    SELECT detailpenjualan.*, produk.nama_produk
    FROM detailpenjualan
    JOIN produk
        ON detailpenjualan.id_produk = produk.id_produk
    WHERE detailpenjualan.id_penjualan='$id'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Penjualan - KopiKita</title>

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

        .btn-kembali {
            background: #4b2e1f;
            color: white;
        }

        .btn-kembali:hover {
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

<div class="container content">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3>Detail Penjualan</h3>
            <p class="text-muted">
                Informasi transaksi
            </p>
        </div>

        <a href="penjualan.php" class="btn btn-kembali">
            Kembali
        </a>

    </div>

    <div class="card p-4 mb-4">

        <div class="row">

            <div class="col-md-4">
                <strong>ID Penjualan</strong>
                <p>
                    <?= $penjualan['id_penjualan']; ?>
                </p>
            </div>

            <div class="col-md-4">
                <strong>Tanggal</strong>
                <p>
                    <?= date(
                        'd-m-Y',
                        strtotime($penjualan['tgl_penjualan'])
                    ); ?>
                </p>
            </div>

            <div class="col-md-4">
                <strong>Pelanggan</strong>
                <p>
                    <?= htmlspecialchars(
                        $penjualan['nama_pelanggan'] ?? 'Umum'
                    ); ?>
                </p>
            </div>

        </div>

    </div>

    <div class="card p-4">

        <h5 class="mb-3">
            Produk yang Dibeli
        </h5>

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                while ($data = mysqli_fetch_assoc($detail)) {
                ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($data['nama_produk']); ?>
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

                <tfoot>

                    <tr>
                        <th colspan="3" class="text-end">
                            Total
                        </th>

                        <th>
                            Rp <?= number_format(
                                $penjualan['total_harga'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </th>
                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>

</body>
</html>