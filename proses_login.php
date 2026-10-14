<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = md5($_POST['password']);

$query = mysqli_query($koneksi, "SELECT * FROM user
                                  WHERE username='$username'
                                  AND password='$password'");

$data = mysqli_fetch_assoc($query);

if ($data) {

    $_SESSION['id'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    if ($data['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($data['role'] == 'kasir') {
        header("Location: kasir/dashboard.php");
    }

    exit;

} else {
    header("Location: login.php?pesan=gagal");
    exit;
}
?>