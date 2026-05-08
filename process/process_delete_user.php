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

    if ($user_id == $_SESSION['user_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        exit();
    }

    $query = "DELETE FROM users WHERE id = '$user_id' AND role != 'admin'";
    
    if (mysqli_query($conn, $query)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'User berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan atau Admin tidak bisa dihapus.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dari database.']);
    }
}