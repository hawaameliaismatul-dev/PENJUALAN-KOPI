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

    <title>Data Pelanggan - KopiKita</title>

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

            <a href="pelanggan.php" class="active">
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

                <div>
                    <h3 class="fw-bold mb-1" style="color: #4b2e1f;">
                        Data Pelanggan
                    </h3>

                    <p class="text-muted mb-0">
                        Kelola data pelanggan KopiKita.
                    </p>
                </div>

                <a href="pelanggan_tambah.php" class="btn btn-brown">
                    + Tambah Pelanggan
                </a>

            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

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

                            if (mysqli_num_rows($query) > 0) {
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

                            <?php
                                }
                            } else {
                            ?>

                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data pelanggan.
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
