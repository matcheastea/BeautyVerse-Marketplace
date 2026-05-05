<?php 
include '../includes/db.php'; 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/auth.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($query);

if(!$p){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - BeautyVerse Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>BV <span>Admin</span></h2>
            </div>
            <nav>
                <a href="dashboard.php" class="active"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../process/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Edit Product</h1>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </header>

            <div class="form-container">
                <form action="../process/process_edit_product.php" method="POST" enctype="multipart/form-data" class="admin-form">
                    <input type="hidden" name="id" value="<?=  $p['id'] ?>">

                    <div class="field">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?=  $p['name']?>" required>
                    </div>
                    <div class="field">
                        <label>Brand</label>
                        <input type="text" name="brand" value="<?=  $p['brand'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Price ($)</label>
                        <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" value="<?= $p['description'] ?>"></textarea>
                    </div>
                    <div class="field">
                        <label>Current Image</label><br>
                        <img src="../assets/img/<?= $p['image_url'] ?>" width="100" style="margin-bottom: 10px; border-radius: 4px; display: block;">
                        <input type="file" name="image_url">
                        <small style="color: #888;">*Kosongkan jika tidak ingin mengganti gambar</small>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save-prod">UPDATE PRODUCT</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>