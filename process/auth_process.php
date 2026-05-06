<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if(!$data){
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid.']);
    exit();
}

$type = $data['type'];
$email = mysqli_real_escape_string($conn, $data['email']);
$password = $data['password'];

if($type == 'signup'){
    $name = mysqli_real_escape_string($conn, $data['name']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nanme, email, password, role) VALUES ('$name', '$email', '$hashed_password', 'user')";

    if(mysqli_query($conn, $sql)){
        echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil! Silahkan login']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
    }
} else if ($type === 'login') {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($result);
    
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $redirect = ($user['role'] == 'admin') ? '../admin/dashboard.php' : '../pages/index.php';
        echo json_encode(['status' => 'success', 'redirect' => $redirect]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email atau password salah']);
    }
}
?>