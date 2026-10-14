<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'kasir') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($koneksi, "
    DELETE FROM pelanggan
    WHERE id_pelanggan='$id'
");

header("Location: pelanggan.php");
exit;
?>