<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

$id = $_GET["id"];

if( hapusFilm($id) > 0) {
        $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Film Berhasil Dihapus'
        ];
        header('Location: data_film.php');
        exit();

        } else {
        $_SESSION['notification'] = [
        'type' => 'danger',
        'message' => 'Data Film Gagal Dihapus: ' . mysqli_error($conn)
        ];
        }
        header('Location: data_film.php');
        exit();
?>