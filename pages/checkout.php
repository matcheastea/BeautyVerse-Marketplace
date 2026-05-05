<?php
include '../includes/db.php';
include '../includes/header.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<main class="checkout-container">
    <div class="checkout-grid">
        <div class="checkout-form-section">
            <h2>Shipping Details</h2>
            <form action="../process/place_order.php" method="POST" class="luxury-form">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?=  $user['name'] ?>"required>
                </div>
                <div class="field">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?=  $user['phone'] ?>"required>
                </div>
                <div class="field">
                    <label>City</label>
                    <input type="text" name="city" value="<?=  $user['city'] ?>"required>
                </div>
                <div class="field">
                    <label>Shipping Address</label>
                    <textarea name="address" rows="3" required><?=  $user['address'] ?></textarea>
                </div>

                <div class="payment-method">
                    <h3>Payment Method</h3>
                    <div class="payment-options">
                        <label class="pay-card">
                            <input type="radio" name="payment" value="card" checked>
                            <span class="custom-radio"></span>
                            Bank Transfer (Manual Verification)
                        </label>
                        <label class="pay-card">
                            <input type="radio" name="payment" value="card" checked>
                            <span class="custom-radio"></span>
                            E-Wallet (Gopay / OVO/ Dana)
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-checkout">COMPLETE ORDER</button>
            </form>
        </div>

        <aside class="order-summary">
            <h3>Your Order</h3>
            <div class="summary-list">
                <div class="summary-item">
                    <div class="s-img">
                        <img src="../assets/img/product.1.jpg" alt="Produc">
                    </div>
                    <div class="s-info">
                        <div class="s-name">Res</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>