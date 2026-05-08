<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi berakhir, silakan login ulang.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$name = mysqli_real_escape_string($conn, $_POST['name']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$postal = mysqli_real_escape_string($conn, $_POST['postal_code']);

$image_sql = "";

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['profile_pic']['name'];
    $file_tmp = $_FILES['profile_pic']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($file_ext, $allowed)) {
        
        $unique_image = "user_" . $user_id . "_" . time() . "." . $file_ext;
        $upload_path = "../assets/img/profiles/" . $unique_image;

        if (!is_dir('../assets/img/profiles/')) {
            mkdir('../assets/img/profiles/', 0777, true);
        }

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $image_sql = ", profile_pic = '$unique_image'";
        }
    }
}

$query = "UPDATE users SET 
            name = '$name', 
            phone = '$phone', 
            city = '$city', 
            address = '$address', 
            postal_code = '$postal' 
            $image_sql 
          WHERE id = '$user_id'";

if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $name; 
    echo json_encode(['status' => 'success', 'message' => 'Profil BeautyVerse kamu berhasil diperbarui!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data di database.']);
}