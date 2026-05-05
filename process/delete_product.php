<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../pages/auth.php");
    exit();
}

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT image_url FROM products WHERE id = '$id'");
    $product = mysqli_fetch_assoc($result);

    if($product){
        $file_path = "../assets/img/".$product['image_url'];

        if(!empty($product['image_url']) && file_exists($file_path)){
            unlink($file_path);
        }

        $query = "DELETE FROM products WHERE id = '$id'";

        if(mysqli_query($conn, $query)){
            header("Location: ../admin/dashboard.php?status=deleted");
            exit();
        } else {
            echo "Gagal menghapus data: " . mysqli_error($conn);
        }
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    header("Location: ../admin/dashboard.php");
    exit();
}
?>