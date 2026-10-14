<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

/* Data pelanggan */
$pelanggan = mysqli_query($koneksi,
    "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC"
);

/* Data produk */
$produk = mysqli_query($koneksi,
    "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC"
);

if (isset($_POST['simpan'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    /* Ambil harga dan stok produk */
    $query_produk = mysqli_query($koneksi,
        "SELECT * FROM produk WHERE id_produk='$id_produk'"
    );

    $data_produk = mysqli_fetch_assoc($query_produk);

    $harga = $data_produk['harga'];
    $stok = $data_produk['stok'];

    /* Cek jumlah */
    if ($jumlah > $stok) {

        echo "<script>
                alert('Stok produk tidak mencukupi!');
                window.location='transaksi.php';
              </script>";
        exit;
    }

    $subtotal = $harga * $jumlah;
    $tanggal = date('Y-m-d H:i:s');

    /* Simpan penjualan */
    mysqli_query($koneksi, "
        INSERT INTO penjualan
        (tgl_penjualan, total_harga, id_pelanggan)
        VALUES
        ('$tanggal', '$subtotal', '$id_pelanggan')
    ");

    /* Ambil ID penjualan */
    $id_penjualan = mysqli_insert_id($koneksi);

    /* Simpan detail penjualan */
    mysqli_query($koneksi, "
        INSERT INTO detailpenjualan
        (id_penjualan, id_produk, jumlah_produk, subtotal)
        VALUES
        ('$id_penjualan', '$id_produk', '$jumlah', '$subtotal')
    ");

    /* Kurangi stok */
    mysqli_query($koneksi, "
        UPDATE produk
        SET stok = stok - $jumlah
        WHERE id_produk='$id_produk'
    ");

    echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.location='riwayat.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaksi - KopiKita</title>

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

        .form-box {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .form-box h3 {
            color: #4b2e1f;
        }

        .btn-simpan {
            background: #4b2e1f;
            color: white;
        }

        .btn-simpan:hover {
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
            <?= htmlspecialchars($_SESSION['nama']); ?> | Kasir
        </span>

    </div>

</nav>

<div class="container-fluid">

    <div class="row">

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

        <div class="col-md-10 content">

            <div class="form-box">

                <h3 class="text-center mb-2">
                    Transaksi Penjualan
                </h3>

                <p class="text-muted text-center mb-4">
                    Masukkan data transaksi
                </p>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Pelanggan
                        </label>

                        <select name="id_pelanggan"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Pelanggan --
                            </option>

                            <?php while ($data = mysqli_fetch_assoc($pelanggan)) { ?>

                                <option value="<?= $data['id_pelanggan']; ?>">
                                    <?= htmlspecialchars($data['nama_pelanggan']); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Produk
                        </label>

                        <select name="id_produk"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Produk --
                            </option>

                            <?php while ($data = mysqli_fetch_assoc($produk)) { ?>

                                <option value="<?= $data['id_produk']; ?>">
                                    <?= htmlspecialchars($data['nama_produk']); ?>
                                    - Rp <?= number_format(
                                        $data['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>
                                    (Stok: <?= $data['stok']; ?>)
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Jumlah
                        </label>

                        <input type="number"
                               name="jumlah"
                               class="form-control"
                               min="1"
                               required>

                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="dashboard.php"
                           class="btn btn-secondary">
                            Kembali
                        </a>

                        <button type="submit"
                                name="simpan"
                                class="btn btn-simpan">
                            Simpan Transaksi
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>