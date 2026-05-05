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
    <title>Manage Orders - BeautyVerse</title>
    <link rel="stylesheet" href="../assets/css/admin.css">\
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo"><h2>BV <span>Admin</span></h2></div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../process/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Incoming Orders</h1>
            </header>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_orders = mysqli_query($conn, "SELECT orders.*, users.name as customer_name 
                                                       FROM orders JOIN users ON orders.user_id = users.id 
                                                       ORDER BY created_at DESC");
                        while($ord = mysqli_fetch_assoc($q_orders)):
                        ?>
                        <tr>
                            <td>#<?= $ord['order_id'] ?></td>
                            <td><?= $ord['customer_name'] ?></td>
                            <td>$<?= number_format($ord['total_price'], 2) ?></td>
                            <td><span class="badge <?= $ord['status'] ?>"><?= strtoupper($ord['status']) ?></span></td>
                            <td><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                            <td>
                                <a href="order_detail.php?id=<?= $ord['order_id'] ?>" class="btn-edit">Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>