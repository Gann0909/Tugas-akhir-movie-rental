<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

$id = $_GET["id"];

if( hapus($id) > 0) {
        $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Admin Berhasil Dihapus'
        ];

        } else {
        $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Data Admin Gagal Dihapus: ' . mysqli_error($conn)
        ];
        }
        header('Location: data_admin.php');
        exit();
?>