<?php 
include '../includes/db.php'; 
include '../includes/header.php';
session_start();

$conn = mysqli_connect($host, $username, $pass, $db);

$id = mysqli_real_escape_string($conn, $_GET['id']);

$product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($product_query);

if (!$p) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='shop.php';</script>";
    exit();
}

$review_query = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id = '$id' ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="../assets/css/product-detail.css">

<main class="product-container">
    <div class="product-main-info">
        <div class="product-image-box">
            <img src="../assets/img/<?= $p['image_url'] ?>" alt="<?= $p['name'] ?>" id="main-product-img">
        </div>

        <div class="product-text-box">
            <p class="product-brand"><?= strtoupper($p['brand']) ?></p>
            <h1 class="product-title"><?= $p['name'] ?></h1>
            
            <div class="rating-summary">
                <span class="stars">★★★★☆</span>
                <span class="review-count">(<?= mysqli_num_rows($review_query) ?> ulasan)</span>
            </div>

            <p class="product-price">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
            
            <div class="product-description">
                <h3>Description</h3>
                <p><?= nl2br($p['description']) ?></p>
            </div>

            <form id="formAddToCart" class="cart-action-form">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                
                <div class="quantity-wrapper">
                    <label>Quantity</label>
                    <div class="qty-control">
                        <button type="button" class="btn-qty" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" readonly>
                        <button type="button" class="btn-qty" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <button type="submit" class="btn-cart">
                    <i class="fas fa-shopping-bag"></i> ADD TO CART
                </button>
            </form>
        </div>
    </div>

    <section class="customer-reviews">
        <h2 class="section-heading">Customer Reviews</h2>
        <div class="review-list">
            <?php if(mysqli_num_rows($review_query) > 0): ?>
                <?php while($rev = mysqli_fetch_assoc($review_query)): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <strong><?= htmlspecialchars($rev['user_name']) ?></strong>
                            <span class="review-date"><?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                        </div>
                        <div class="review-stars">
                            <?= str_repeat('★', $rev['rating']) ?><?= str_repeat('☆', 5 - $rev['rating']) ?>
                        </div>
                        <p class="review-text"><?= htmlspecialchars($rev['comment']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty-msg">Belum ada ulasan untuk produk ini.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>

function changeQty(amt) {
    const qtyInput = document.getElementById('qty');
    let newVal = parseInt(qtyInput.value) + amt;
    if (newVal < 1) newVal = 1;
    qtyInput.value = newVal;
}
document.getElementById('formAddToCart').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('../process/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            alert(result.message);
            // Kamu bisa menambahkan kode di sini untuk update badge keranjang di header
        } else {
            alert(result.message);
            if(result.message.includes('login')) {
                window.location.href = 'auth.php';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menghubungi server.');
    });
});
</script>

<?php include '../includes/footer.php'; ?>