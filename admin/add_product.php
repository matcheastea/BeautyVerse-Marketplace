<?php 
include '../includes/db.php'; 
session_start();

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
            <div class="admin-logo"><h2>BV <span>Admin</span></h2></div>
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
                <form id="formAddProduct" class="admin-form">
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
                        <textarea name="description" rows="4"></textarea>
                    </div>
                    <div class="field">
                        <label>Product Image</label>
                        <input type="file" name="image_url" id="image_input" required>
                        <div id="preview-box" style="margin-top: 10px;"></div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save-prod">UPLOAD PRODUCT</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('image_input').onchange = function() {
        const [file] = this.files;
        if (file) {
            const previewBox = document.getElementById('preview-box');
            previewBox.innerHTML = `<img src="${URL.createObjectURL(file)}" width="150" style="border-radius:8px; border:1px solid #ddd;">`;
        }
    };

    document.getElementById('formAddProduct').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../process/process_add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert(result.message);
                window.location.href = 'dashboard.php';
            } else {
                alert('Gagal: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada sistem.');
        });
    });
    </script>
</body>
</html>