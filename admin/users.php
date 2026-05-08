<?php 
include '../includes/db.php'; 
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(isset($_GET['action']) && $_GET['action'] == 'getUsers'){
    header('Content-Type: application/json');

    $query = mysqli_query($conn, "SELECT id, name, email, role FROM users ORDER BY id DESC");

    $data =[];

    while($u = mysqli_fetch_assoc($query)){
        $data[] = $u;
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
    <title>Manage Users - BeautyVerse Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo"><h2>BV <span>Admin</span></h2></div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php" class="active"><i class="fas fa-users"></i> Users</a>
                <a href="../process/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Registered Users</h1>
            </header>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                        while($u = mysqli_fetch_assoc($query)):
                        ?>
                        <tr id="row-user-<?= $u['id'] ?>">
                            <td>#<?= $u['id'] ?></td>
                            <td><strong><?= $u['name'] ?></strong></td>
                            <td><?= $u['email'] ?></td>
                            <td><span class="badge <?= $u['role'] ?>"><?= strtoupper($u['role']) ?></span></td>
                            <td>
                                <?php if($u['role'] !== 'admin'): ?>
                                    <button class="btn-delete" onclick="confirmDelete(<?= $u['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php else: ?>
                                    <small style="color: #999;">Protected</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
    function confirmDelete(userId) {
        if (confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak bisa dibatalkan.')) {
            const formData = new FormData();
            formData.append('user_id', userId);

            fetch('../process/process_delete_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    alert(result.message);
                    // Hapus baris tabel tanpa refresh
                    document.getElementById('row-user-' + userId).style.opacity = '0';
                    setTimeout(() => {
                        document.getElementById('row-user-' + userId).remove();
                    }, 500);
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghubungi server.');
            });
        }
    }
    </script>
</body>
</html>