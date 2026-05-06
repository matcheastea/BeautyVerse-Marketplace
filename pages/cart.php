<?php 
session_start();

include '../includes/db.php'; 
include '../includes/header.php'; 

$conn = mysqli_connect($host, $username, $pass, $db);

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<main class="cart-container">
    <div class="cart-wrapper">
        <h1 class="cart-title">Your Shopping Bag</h1>
        
        <div class="cart-grid">
            <div class="cart-items">
                <table class="luxury-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        $sql = "SELECT cart.id AS cart_id, cart.quantity, products.* FROM cart 
                                JOIN products ON cart.product_id = products.id 
                                WHERE cart.user_id = '$user_id'";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0):
                            while ($item = mysqli_fetch_assoc($result)):
                                $subtotal = $item['price'] * $item['quantity'];
                                $grand_total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="p-detail">
                                    <img src="../assets/img/<?= $item['image_url'] ?>" alt="<?= $item['name'] ?>" onerror="this.src='https://via.placeholder.com/80x100'">
                                    <div class="p-text">
                                        <p class="p-brand"><?= strtoupper($item['brand']) ?></p>
                                        <p class="p-name"><?= $item['name'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <a href="../process/remove_cart.php?id=<?= $item['cart_id'] ?>" class="btn-remove" onclick="return confirm('Hapus produk ini?')">×</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 50px;">
                                Your bag is empty. <a href="shop.php" style="color: var(--primary-color);">Continue shopping</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($grand_total > 0): ?>
            <aside class="cart-summary">
                <div class="summary-card">
                    <h3>Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($grand_total, 2) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="free-text">FREE</span>
                    </div>
                    <hr>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?= number_format($grand_total, 2) ?></span>
                    </div>
                    <a href="checkout.php" class="btn-proceed">PROCEED TO CHECKOUT</a>
                </div>
            </aside>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>