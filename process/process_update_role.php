<?php
header('Content-Type: application/json');
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak!']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_role = mysqli_real_escape_string($conn, $_POST['new_role']);
    
    if ($user_id == $_SESSION['user_id'] && $new_role !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Anda tidak bisa menurunkan jabatan Anda sendiri!']);
        exit();
    }

    $query = "UPDATE users SET role = '$new_role' WHERE id = '$user_id'";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Role berhasil diperbarui menjadi ' . strtoupper($new_role)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database.']);
    }
}