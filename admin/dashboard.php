<?php
include '../includes/db.php';
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'getProducts') {
    header('Content-Type: application/json');
    $query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
    $data = [];
    while($row = mysqli_fetch_assoc($query)) {

        $data[] = $row;
    }
    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);
    exit();
}
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
            <a href="dashboard.php" class="active">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="orders.php">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="users.php">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="../process/logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>
    <main class="admin-main">
        <header class="admin-header">
            <h1>Product Management</h1>
            <a href="add_product.php" class="btn-add">
                <i class="fas fa-plus"></i>
                ADD NEW PRODUCT
            </a>
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
                    <tbody id="productTableBody">
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px;">
                                Loading products...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<script>
   function loadProducts() {
    fetch('../process/get_products.php') 
        .then(response => response.json())
        .then(result => {
            const tableBody = document.getElementById('productTableBody');
            if (result.status === 'success') {
                let html = '';
                result.data.forEach(p => {
                    html += `
                    <tr id="product-row-${p.id}">
                        <td><img src="../assets/img/${p.image_url}" width="50"></td>
                        <td>${p.name}</td>
                        <td>${p.brand}</td>
                        <td>Rp ${Number(p.price).toLocaleString()}</td>
                        <td>
                            <a href="edit_product.php?id=${p.id}" class="btn-edit"><i class="fas fa-edit"></i></a>
                            <button onclick="deleteProduct(${p.id})" class="btn-delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
                tableBody.innerHTML = html;
            } else {
                tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">${result.message}</td></tr>`;
            }
        })
        .catch(error => console.error('Error:', error));
}

window.onload = loadProducts;

function deleteProduct(id) {
    if(confirm('Hapus produk ini?')) {
        fetch(`../process/delete_product.php?id=${id}`)
            .then(response => response.json())
            .then(result => {
                if(result.status === 'success') {
                    alert('Terhapus!');
                    document.getElementById(`product-row-${id}`).remove();
                } else {
                    alert(result.message);
                }
            });
    }
}
</script>
</body>
</html>