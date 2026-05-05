<?php
include '../includes/db.php';

session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = $_POST['price'];
    $description = $_POST['description'];

    if ($_FILES['image_url']['name'] != "") {

        $image_name = time() . "_" . $_FILES['image_url']['name'];
        move_uploaded_file($_FILES['image_url']['tmp_name'], "../assets/img/" . $image_name);
       
        $sql = "UPDATE products SET name='$name', brand='$brand', price='$price', image_url='$image_name' WHERE id='$id'";
    } else {
        
        $sql = "UPDATE products SET name='$name', brand='$brand', price='$price' WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../admin/dashboard.php?status=updated");
        exit();
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>