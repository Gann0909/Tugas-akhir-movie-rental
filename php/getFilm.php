<?php
// Koneksi kedatabase
header('Content-Type: application/json');
$host = "localhost";
$user = "root";
$password = "";
$dbname = "movierental";

$conn = new mysqli($host, $user, $password, $dbname);
// pengecekkkan bila koneksi gagal
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
// query untuk mengambil data dari database
$sql = "SELECT film_id, judul_film, gambar, rating, harga_sewa, deskripsi FROM film";
$result = $conn->query($sql);
// Memasukkan data ke format object
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
// Membuat data menjadi json
echo json_encode($products);
$conn->close();
?>
