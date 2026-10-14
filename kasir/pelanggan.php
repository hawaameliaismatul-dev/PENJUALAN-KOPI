<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan</title>

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

            <a href="pelanggan.php" class="active">
                Data Pelanggan
            </a>

            <a href="riwayat.php">
                Riwayat Penjualan
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </div>

        <!-- CONTENT -->
        <div class="col-md-10 content">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3>Data Pelanggan</h3>

                <a href="pelanggan_tambah.php" class="btn btn-brown">
                    + Tambah Pelanggan
                </a>

            </div>

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>No. Telepon</th>
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
                                        <?= htmlspecialchars($data['nama_pelanggan']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['alamat']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['no_telpon']); ?>
                                    </td>

                                    <td>

                                        <a href="pelanggan_edit.php?id=<?= $data['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <a href="pelanggan_hapus.php?id=<?= $data['id_pelanggan']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">
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

</div>

<script src="../js/bootstrap.bundle.min.js"></script>

</body>
</html>