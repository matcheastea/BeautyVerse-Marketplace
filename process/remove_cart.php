<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (isset($_GET['id'])) {
    $cart_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE id = '$cart_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../pages/cart.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>