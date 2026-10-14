<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($koneksi, "
    DELETE FROM penjualan
    WHERE id_penjualan='$id'
");

header("Location: penjualan.php");
exit;
?>