<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

$id = $_GET["id"];

if( hapusCustomer($id) > 0) {
        $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Customer Berhasil Dihapus'
        ];
        header('Location: data_customer.php');
        exit();

        } else {
        $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Data Customer Gagal Dihapus: ' . mysqli_error($conn)
        ];
        }
        header('Location: data_customer.php');
        exit();
?>