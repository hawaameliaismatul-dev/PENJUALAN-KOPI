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

$query = mysqli_query($koneksi, "
    SELECT penjualan.*, pelanggan.nama_pelanggan
    FROM penjualan
    LEFT JOIN pelanggan
    ON penjualan.id_pelanggan = pelanggan.id_pelanggan
    WHERE penjualan.id_penjualan = '$id_penjualan'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: riwayat.php");
    exit;
}

$detail = mysqli_query($koneksi, "
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

    <title>Cetak Struk</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #eeeeee;
        }

        .struk {
            width: 300px;
            background: white;
            margin: 30px auto;
            padding: 20px;
        }

        .tengah {
            text-align: center;
        }

        .nama-toko {
            font-size: 20px;
            font-weight: bold;
        }

        .garis {
            border-top: 1px dashed black;
            margin: 12px 0;
        }

        .info {
            font-size: 13px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        td {
            padding: 4px 0;
            vertical-align: top;
        }

        .kanan {
            text-align: right;
        }

        .total {
            font-weight: bold;
            font-size: 15px;
        }

        .tombol {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            color: white;
            background: #4b2e1f;
            cursor: pointer;
            border-radius: 5px;
        }

        .kembali {
            background: #777;
            margin-left: 5px;
        }

        @media print {

            body {
                background: white;
            }

            .struk {
                margin: 0;
            }

            .tombol {
                display: none;
            }

        }

    </style>

</head>

<body>

<div class="struk">

    <div class="tengah">

        <div class="nama-toko">
            KEDAI KOPI
        </div>

        <div>
            Sistem Penjualan Kopi
        </div>

    </div>

    <div class="garis"></div>

    <div class="info">

        <div>
            No. Transaksi :
            <?= $data['id_penjualan']; ?>
        </div>

        <div>
            Tanggal :
            <?= date('d-m-Y H:i', strtotime($data['tgl_penjualan'])); ?>
        </div>

        <div>
            Pelanggan :
            <?= $data['nama_pelanggan']
                ? htmlspecialchars($data['nama_pelanggan'])
                : 'Umum'; ?>
        </div>

        <div>
            Kasir :
            <?= htmlspecialchars($_SESSION['nama']); ?>
        </div>

    </div>

    <div class="garis"></div>

    <table>

        <?php while ($d = mysqli_fetch_assoc($detail)) { ?>

        <tr>
            <td colspan="2">
                <?= htmlspecialchars($d['nama_produk']); ?>
            </td>
        </tr>

        <tr>

            <td>
                <?= $d['jumlah_produk']; ?> x
                Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
            </td>

            <td class="kanan">
                Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?>
            </td>

        </tr>

        <?php } ?>

    </table>

    <div class="garis"></div>

    <table>

        <tr>

            <td class="total">
                TOTAL
            </td>

            <td class="kanan total">
                Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
            </td>

        </tr>

    </table>

    <div class="garis"></div>

    <div class="tengah info">

        Terima kasih sudah membeli

        <br>

        Selamat menikmati kopi

    </div>

</div>


<div class="tombol">

    <button onclick="window.print()">
        Cetak Struk
    </button>

    <button class="kembali"
            onclick="window.location='riwayat.php'">
        Kembali
    </button>

</div>

</body>

</html>