<?php
session_start();
include '../includes/db.php';

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['user_id']) || empty($_SESSION['cart'])){
    header("Location: ../pages/shop.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

foreach($_SESSION['cart'] as $item){
    $total_price += $item['price'] * $item['quantity'];
}

$sql_order = "INSERT INTO orders (user_id, total_price) VALUES ('$user_id','$total_price')";
if(mysqli_query($conn, $sql_order)){
    $order_id = mysqli_insert_id($conn);

    foreach($_SESSION['cart'] as $product_id => $item){
        $price = $item['price'];
        $qty = $item['quantity'];
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES ('$order_id','$product_id','$qty','$price')";
        mysqli_query($conn, $sql_item);
    }

    unset($_SESSION['cart']);
        header("Location: ../pages/order_success.php?id" . $order_id);  
    }else{
        echo "Gagal memproses pesanan.";
    }
?>