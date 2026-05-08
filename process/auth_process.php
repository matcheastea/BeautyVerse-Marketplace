<?php
session_start();
include '../includes/db.php';

$conn = mysqli_connect($host, $username, $pass, $db);

header('Content-Type: application/json');

$type = $_POST['type'] ?? '';

if ($type == 'signup') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Email sudah digunakan'
        ]);
        exit();
    }

    $query = mysqli_query($conn,
        "INSERT INTO users (name, email, password, role)
        VALUES ('$name', '$email', '$password', 'user')"
    );

    if ($query) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Registrasi berhasil',
            'redirect' => '/pages/index.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Registrasi gagal'
        ]);
    }

    exit();
}

elseif ($type == 'login') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'"
    );
    if (mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);
        if (password_verify($password, $user['password'])) {


            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                $redirect = '/admin/dashboard.php';
            }else{
                $redirect = '/pages/index.php';
            }
            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'redirect' => $redirect
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password salah'
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email tidak ditemukan'
        ]);
        exit();
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Request tidak valid'
    ]);
    exit();
}
?>