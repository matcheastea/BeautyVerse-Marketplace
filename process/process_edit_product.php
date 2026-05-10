<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = (float)$_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $img_query = "";

    if (!empty($_FILES['image_url']['name'])) {
        $image_name = $_FILES['image_url']['name'];
        $image_tmp = $_FILES['image_url']['tmp_name'];
        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $unique_name = time() . "_" . $image_name;
            $target = "../assets/img/" . $unique_name;

            if (move_uploaded_file($image_tmp, $target)) {
                $img_query = ", image_url = '$unique_name'";
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Format gambar tidak valid.']);
            exit();
        }
    }

    $query = "UPDATE products SET 
                name = '$name', 
                brand = '$brand', 
                price = '$price', 
                description = '$description' 
                $img_query 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal update database: ' . mysqli_error($conn)]);
    }
}