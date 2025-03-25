<?php
header('Content-Type: application/json');

$host = "localhost"; 
$user = "root";   
$pass = "";           
$db   = "movierental";  

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

$sql = "SELECT user_rating FROM ratings ORDER BY id DESC LIMIT 1"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["rating" => (int)$row["user_rating"]]);
} else {
    echo json_encode(["rating" => 0]); // Jika tidak ada data, berikan rating default 0
}

// Tutup koneksi
$conn->close();
?>
