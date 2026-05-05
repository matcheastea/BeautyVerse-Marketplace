<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $type = $_POST['type'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if($type === 'signup'){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name,  email, password, role) VALUES ('$name', '$email', '$hashed_password', 'user')";
        
        if(mysqli_query($conn, $sql)){
            header("Location: ../pages/auth.php?status=success_reg");
        }else{
            echo "Error: Email sudah terdaftar";
        }
    }else if($type === 'login'){
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($result);
        if($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'admin'){
                header("Location: ../admin/dashboard.php");
            }else{
                header("Location: ../pages/index.php");
            }
        }else{
            header("Location: ../pages/auth.php?error=invalid_credentials");
        }
    }
}
?>