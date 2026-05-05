<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/auth.php?msg=login_first");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_SESSION['user_id'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = mysqli_real_escape_string($conn, $_POST['qty']);

    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");

    if(mysqli_num_rows($check_cart) > 0){
        $sql = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
    }

    if(mysqli_query($conn, $sql)){
        header("Location: ../pages/cart.php");
    }else{
        echo "Error: ".mysqli_error($conn);
    }
}
?>