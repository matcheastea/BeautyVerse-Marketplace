<?php
include '../includes/db.php';
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);

session_start();

if(!isset($_SESSION['user_id']) || empty($_SESSION['cart'])){
    header("Location: shop.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHILE id = '$user_id'" );
$user = mysqli_fetch_assoc($query);

$total_all = 0;
?>

<link rel="stylesheet" href="../assets/css/checkout.css">

<mian class="checkout-container">
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
                foreach($_SESSION['cart'] as $id => $item) : 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_all += $subtotal;
                ?>
                <div class="summary-item">
                    <div class="s-img">
                        <img src="../assests/img/<?=  $item['image'] ?>" alt="<?= $item['name'] ?>" onerror="this.src='https://via.placeholder.com/100'">
                    </div>
                    <div class="s-info">
                        <p class="s-name"><?= $item['name'] ?></p>
                        <p class="s-qty"><?= $item['quantity'] ?></p>
                        <p class="s-price">Rp<?= number_format($item['price'], 2) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
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
<?php include '../includes/footer.php'; ?>