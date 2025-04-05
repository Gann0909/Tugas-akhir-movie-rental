<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

$id = $_GET["id"];

if( hapusTransaksi($id) > 0) {
        $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Penyewaan Berhasil Dihapus'
        ];
        header('Location: dashboard.php');
        exit();

        } else {
        $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Data Penyewaan Gagal Dihapus: ' . mysqli_error($conn)
        ];
        }
        header('Location: dashboard.php');
        exit();
?>