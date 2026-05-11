<?php
header('Content-Type: application/json');

include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!$conn) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Koneksi database gagal: ' . mysqli_connect_error()
    ]);
    exit();
}

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Data produk tidak ditemukan'
        ]);
    }
}else{
    $query = "SELECT * FROM products ORDER BY id desc";
    $result = mysqli_query($conn, $query);

    if($result){
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengupdate data produk: '.mysqli_error($conn)
        ]);
    }
}
mysqli_close($conn);
?>