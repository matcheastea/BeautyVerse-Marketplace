<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu untuk belanja di BeautyVerse.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
$quantity = (int)$_POST['quantity'];

$check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");

if (mysqli_num_rows($check) > 0) {
    $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
} else {
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
}

if (mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan ke keranjang!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada database.']);
}