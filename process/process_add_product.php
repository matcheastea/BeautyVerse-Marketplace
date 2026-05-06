<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Akses ditolak. Anda bukan admin.'
    ]);
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = (int)$_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if(!isset($_FILES['image_url']) || $_FILES['image_url']['error'] !== UPLOAD_ERR_OK){
        echo json_encode(['status' => 'error', 'message' => 'Gambar wajib diunggah']);
        exit();
    }

    $image_name = $_FILES['image_url']['name'];
    $image_tmp = $_FILES['image_url']['tmp_name'];
    $image_size = $_FILES['image_url']['size'];
    $upload_dir = '../assets/img/';

    $unique_name = time() . "_" . $image_name;
    $target_file = $upload_dir . $unique_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = array("jpg", "jpeg", "png", "webp");
    if(!in_array($file_type, $allowed_types)){
        echo json_encode(['status' => 'error', 'message' => 'Format file harus JPG, JPEG, PNG, atau WEBP']);
        exit();
    }

    if($image_size > 2000000){
        echo json_encode(['status' => 'error', 'message' => 'Ukuran file terlalu besar (Maks 2MB)']);
        exit();
    }

    if(move_uploaded_file($image_tmp, $target_file)){
        $query = "INSERT INTO products (name, brand, price, description, image_url) VALUES ('$name', '$brand', '$price', '$description', '$unique_name')";

        if(mysqli_query($conn, $query)){
            echo json_encode([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke katalog BeautyVerse!'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
        } 
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file ke server']);
        }
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Metode pengiriman tidak valid.']);
    }
    exit();
?>