<?php 
include '../includes/db.php'; 
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

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
    <title>Edit Product - BeautyVerse Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <main class="admin-main">
            <header class="admin-header">
                <h1>Edit Product: <?= $p['name'] ?></h1>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </header>

            <div class="form-container">
                <form id="formEditProduct" class="admin-form">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="field">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?= $p['name'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Brand</label>
                        <input type="text" name="brand" value="<?= $p['brand'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Price (Rp)</label>
                        <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
                    </div>
                    <div class="field">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?= $p['description'] ?></textarea>
                    </div>
                    <div class="field">
                        <label>Product Image</label>
                        <img src="../assets/img/<?= $p['image_url'] ?>" id="current-img" width="100" style="display:block; margin-bottom:10px; border-radius:8px;">
                        <input type="file" name="image_url" id="image_input">
                        <small>*Kosongkan jika tidak ingin mengganti gambar</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save-prod">UPDATE PRODUCT</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('image_input').onchange = function() {
        const [file] = this.files;
        if (file) {
            document.getElementById('current-img').src = URL.createObjectURL(file);
        }
    };

    document.getElementById('formEditProduct').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('../process/process_edit_product.php', {
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