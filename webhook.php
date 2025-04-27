<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movierental";

$conn = new mysqli($servername, $username, $password, $dbname);

// ngecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ngambil data JSON dari Midtrans
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// ngevalidasiin data yang diterima
if (!isset($data['order_id']) || !isset($data['transaction_status']) || !isset($data['settlement_time'])) {
    error_log("Invalid request: order_id, transaction_status, or settlement_time missing");
    http_response_code(400);
    exit;
}

$order_id = $data['order_id'];
$transaction_status = $data['transaction_status'];
$settlement_time = $data['settlement_time'];

// ngeupdate status transaksi pelanggan
$stmt = $conn->prepare("UPDATE customers SET transaction_status = ? WHERE order_id = ?");
$stmt->bind_param("ss", $transaction_status, $order_id);
if (!$stmt->execute()) {
    error_log("Error updating status: " . $stmt->error);
    http_response_code(500);
    exit;
}
$stmt->close();

// ngambil penyewaan_id terbaru
$result = $conn->query("SELECT penyewaan_id FROM transactions ORDER BY penyewaan_id DESC LIMIT 1");
$penyewaan_id = $result->fetch_assoc()['penyewaan_id'] ?? null;

// mastiin penyewaan_id valid
if (!$penyewaan_id) {
    error_log("Error: penyewaan_id not found");
    http_response_code(400);
    exit;
}

// ngambil jumlah hari penyewaan (quantity)
$sql_quantity = $conn->query("SELECT quantity FROM transactions WHERE penyewaan_id = '{$penyewaan_id}'");
$quantity = $sql_quantity->fetch_assoc()['quantity'] ?? 1;  // Default 1 jika NULL

// ngitung tanggal sewa & kembali
$tanggal_sewa = date('Y-m-d', strtotime($settlement_time));
$tanggal_kembali = date('Y-m-d', strtotime("+{$quantity} days", strtotime($settlement_time)));

// ngeupdate status transaksi berdasarkan status pembayaran
if ($transaction_status == 'settlement') {
    $update_sql = $conn->prepare("UPDATE transactions SET tanggal_sewa = ?, tanggal_kembali = ?, status = 'rented' WHERE penyewaan_id = ?");
    $update_sql->bind_param("ssi", $tanggal_sewa, $tanggal_kembali, $penyewaan_id);
    $update_sql->execute();
    $update_sql->close();
}

if ($transaction_status == 'expired') {
    $update_sql = $conn->prepare("UPDATE transactions SET status = 'cancel' WHERE penyewaan_id = ?");
    $update_sql->bind_param("i", $penyewaan_id);
    $update_sql->execute();
    $update_sql->close();
}

$conn->close();

echo "Status pembayaran berhasil diperbarui.";

?>
