<?php

session_start();
include '../includes/db.php';
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak'
    ]);
    exit();
}
$user_id = $_POST['user_id'];
$role = $_POST['role'];

if ($role != 'admin' && $role != 'user') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Role tidak valid'
    ]);
    exit();
}
$query = mysqli_query(
    $conn,
    "UPDATE users SET role='$role' WHERE id='$user_id'"
);
if ($query) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Role berhasil diperbarui'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal update role'
    ]);
}
?>