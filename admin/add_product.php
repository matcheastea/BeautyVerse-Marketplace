<?php 
include '../includes/db.php'; 
session_start();

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/auth.php");
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
                <h1>Add New Product</h1>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </header>

            <div class="form-container">
                <!-- ID ditambahkan agar mudah diakses JavaScript -->
                <form id="formAddProduct" enctype="multipart/form-data" class="admin-form">
                    <div class="field">
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="field">
                        <label>Brand</label>
                        <input type="text" name="brand" required>
                    </div>
                    <div class="field">
                        <label>Price (Rp)</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="description"></textarea>
                    </div>
                    <div class="field">
                        <label>Product Image</label>
                        <input type="file" name="image_url" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save-prod">UPLOAD PRODUCT</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('formAddProduct').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah pindah ke halaman hitam

        const formData = new FormData(this); // Mengambil semua input termasuk file

        fetch('../process/process_add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Memproses respon JSON dari PHP
        .then(result => {
            if (result.status === 'success') {
                alert(result.message);
                // Redirect langsung ke dashboard admin setelah sukses[cite: 1]
                window.location.href = 'dashboard.php'; 
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat menghubungi server.');
        });
    });
    </script>
</body>
</html>