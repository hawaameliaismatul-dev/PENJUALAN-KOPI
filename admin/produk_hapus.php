<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id'");

header("Location: produk.php");
exit;
?>