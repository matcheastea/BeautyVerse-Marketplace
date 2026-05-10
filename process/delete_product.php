<?php
header('Content-Type: application/json');
include '../includes/db.php';

$conn = mysqli_connect($host, $username, $pass, $db);

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
}else{
    $query = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if($data){
    echo json_encode(['status'=>'success','data' => $data]);
    exit();
}else{
    echo json_encode(['status' =>'error', 'message'=>'Data tidak ditemukan']);
    exit();
}
?>