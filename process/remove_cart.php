<?php
header('Content-Type: application/json');

session_start();
include '../includes/db.php';

$conn = mysqli_connect($host, $username, $pass, $db);

/*
|--------------------------------------------------------------------------
| CEK LOGIN
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'status' => 'error',
        'message' => 'User belum login'
    ]);

    exit();
}

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| CEK CART ID
|--------------------------------------------------------------------------
*/
if (!isset($_POST['cart_id'])) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Cart ID tidak ditemukan'
    ]);

    exit();
}

$cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);


$sql = "DELETE FROM cart
        WHERE id='$cart_id'
        AND user_id='$user_id'";

if (mysqli_query($conn, $sql)) {

    echo json_encode([
        'status' => 'success',
        'message' => 'Produk berhasil dihapus dari keranjang'
    ]);

} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghapus produk'
    ]);
}

exit();
?>