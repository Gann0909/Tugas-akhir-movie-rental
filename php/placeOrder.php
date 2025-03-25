<?php
session_start();
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

use Midtrans\Config;
use Midtrans\Snap;

// Konfigurasi Midtrans
Config::$serverKey = 'SB-Mid-server-IEH6f19ygbxEWAxS75Ua6wQ6';
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

// Pastikan request menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method.");
}

// Ambil data dari POST
$total = isset($_POST["total"]) ? (float) $_POST["total"] : 0;
$items = isset($_POST["items"]) ? json_decode($_POST["items"], true) : [];
$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";

// Validasi data
if (empty($items) || $total <= 0 || empty($name) || empty($email)) {
    die("Invalid transaction data.");
}

// Hitung ulang total dari item_details untuk memastikan kesesuaian
$calculatedTotal = 0;
foreach ($items as &$item) {
    if (!isset($item["name"], $item["price"], $item["quantity"])) {
        die("Invalid item details.");
    }
    $item["price"] = (float) $item["price"];
    $item["quantity"] = (int) $item["quantity"];
    $calculatedTotal += $item["price"] * $item["quantity"];
}

// Jika total dari item_details tidak cocok dengan transaction_details.gross_amount
if ($calculatedTotal != $total) {
    die("Total amount mismatch.");
}

$conn = new mysqli("localhost", "root", "", "movierental");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = rand(); // Buat order ID unik
$customer_name = $_POST["name"] ?? "";
$customer_email = $_POST["email"] ?? "";
$customer_phone = $_POST["phone"] ?? "";
$transaction_status = "pending";

$stmt = $conn->prepare("INSERT INTO customers (order_id, customer_name, customer_email, customer_phone, transaction_status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $order_id, $customer_name, $customer_email, $customer_phone, $transaction_status);
$stmt->execute();
$customer_id = $conn->insert_id;
$stmt->close();

// Simpan data transaksi ke database
foreach ($items as $item) {
    $film_id = $item["id"];
    $nama_film = $item["name"];
    $quantity = $item["quantity"];
    $total_price = $item["price"] * $item["quantity"];
    $tanggal = "-";

    
    $stmt = $conn->prepare("INSERT INTO transactions (film_id, id, nama_customer, nama_film, tanggal_sewa, tanggal_kembali, status, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $film_id, $customer_id, $customer_name, $nama_film, $tanggal, $tanggal, $transaction_status, $quantity);
    $stmt->execute();
    $stmt->close();
}


// Buat parameter transaksi
$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $total,
    ],
    'item_details' => $items,
    'customer_details' => [
        'first_name' => $name,
        'email' => $email,
        'phone' => $phone,
    ],
];

// Debug sebelum mengirim ke Midtrans
// echo '<pre>'; print_r($params); echo '</pre>'; exit;

// Ambil Snap Token
$snapToken = Snap::getSnapToken($params);
echo $snapToken;
?>
