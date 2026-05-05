<?php
session_start();
include '../includes/db.php';

$conn= mysqli_connect($host, $username, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/auth.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");

    if (mysqli_num_rows($check_cart) > 0) {
        $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../pages/shop.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>