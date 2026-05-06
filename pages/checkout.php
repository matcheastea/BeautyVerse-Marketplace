<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
if(mysqli_num_rows($check_cart) == 0){
    header("Location: shop.php?error=cart-empty");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<link rel="stylesheet" href="../assets/css/checkout.css">

<main class="checkout-container">
    <div class="checkout-grid">
        <div class="checkout-form-section">
            <h2>Shipping Details</h2>
            <form action="../process/checkout_process.php" method="POST" class="luxury-form">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?=  htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="field">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?=  isset($user['phone']) ? $user['phone'] : '' ?>" required>
                </div>
                <div class="field">
                    <label>City</label>
                    <input type="text" name="city" value="<?=  isset($user['city']) ? $user['city'] : ''?>" required>
                </div>
                <div class="field">
                    <label>Shipping Address</label>
                    <textarea name="address" rows="3" required><?=  isset($user['address']) ? $user['address'] : '' ?></textarea>
                </div>

                <div class="payment-method">
                    <h3>Payment Method</h3>
                    <div class="payment-options">
                        <label class="pay-card">
                            <input type="radio" name="payment" value="bank_transfer" checked>
                            <span class="custom-radio"></span>
                            Bank Transfer (Manual)
                        </label>
                        <label class="pay-card">
                            <input type="radio" name="payment" value="ewallet">
                            <span class="custom-radio"></span>
                            E-Wallet (GoPay / OVO)
                        </label>   
                    </div>
                </div>
                <button type="submit" class="btn-checkout">COMPLETE ORDER</button>
            </form>
        </div>

        <aside class="order-summary">
            <h3>Your Order</h3>
            <div class="summary-list">
                <?php
                $total_all = 0;
                $cart_query = mysqli_query($conn, "SELECT c.*, p.name, p.price, p.image_url 
                                           FROM cart c 
                                           JOIN products p ON c.product_id = p.id 
                                           WHERE c.user_id = '$user_id'");
                while($item = mysqli_fetch_assoc($cart_query)):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_all += $subtotal;
                ?>
                <div class="summary-item">
                    <div class="s-img">
                        <img src="../assets/img/<?= $item['image_url'] ?>" alt="<?= $item['name'] ?>">
                    </div>
                    <div class="s-info">
                        <p class="s-name"><?= $item ['name'] ?></p>
                        <p class="s-qty"><?= $item ['quantity'] ?></p>
                        <p class="s-price">Rp<?number_format($item['price'], 2) ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="summary-total">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>Rp<?= number_format($total_all, 2) ?></span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>
                <hr>
                <div class="total-row final">
                    <span>Total</span>
                    <span>Rp<?= number_format($total_all, 2) ?></span>
                </div>
            </div>
        </aside>
    </div>
</main>
<?php include '../includes/footer.php'; ?>