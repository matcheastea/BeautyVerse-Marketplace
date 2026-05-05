<?php 
include '../includes/db.php'; 
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BeautyVerse</title>
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
                <a href="dashboard.php" class="active"><i class="fas fa-box"></i>Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../process/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Product Management</h1>
                <a href="add_product.php" class="btn-add"><i class="fas fa-plus"></i> ADD NEW PRODUCT</a>
            </header>

            <section class="content-body">
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

                            if (mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td>
                                    <img src="../assets/img/<?= $row['image_url'] ?>" width="50" height="65" style="object-fit: cover; border-radius: 4px;">
                                </td>
                                <td><strong><?= $row['name'] ?></strong></td>
                                <td><?= $row['brand'] ?></td>
                                <td>$<?= number_format($row['price'], 2) ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                    <a href="../process/delete_product.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center; padding: 50px;'>Belum ada produk. Klik Add New Product.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>