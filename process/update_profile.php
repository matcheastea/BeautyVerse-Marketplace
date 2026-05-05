<?php
include '../includes/db.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $address = mysqli_real_escape_string($conn, $_POST['complete_address']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);

    $sql = "UPDATE users SET
        name = '$name',
        phone = '$phone',
        city = '$city',
        complete_address = '$address',
        postal_code = '$postal_code'
        WHERE id = $user_id";

    if(mysqli_query($conn, $sql)){
        header("Location: ../pages/profile.php?status=updated");
    }else{
        echo "Error: " .mysqli_error($conn);
    }
}
?>