<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/auth.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image_name = $_FILES['image_url']['name'];
    $image_tmp = $_FILES['image_url']['tmp_name'];
    $image_size = $_FILES['image_url']['size'];

    $upload_dir = "../assets/img/";

    $unique_name = time() . "_". $image_name;
    $target_file = $upload_dir . $unique_name;  

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "jpeg", "png", "webp");

    if (!in_array($file_type, $allowed_types)) {
        header("Location: ../admin/add_product.php?error=format_salah");
        exit();
    }

    if ($image_size > 2000000) {
        header("Location: ../admin/add_product.php?error=file_terlalu_besar");
        exit();
    }

    if (move_uploaded_file($image_tmp, $target_file)) {
        $query = "INSERT INTO products (name, brand, price, description, image_url) 
                  VALUES ('$name', '$brand', '$price', '$description', '$unique_name')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: ../admin/dashboard.php?status=success");
        } else {
            header("Location: ../admin/add_product.php?error=db_error");
        }
    } else {
        header("Location: ../admin/add_product.php?error=upload_gagal");
    }
}
?>