<?php 
include '../includes/db.php'; 
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);

$id = mysqli_real_escape_string($conn, $_GET['id']);

$product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($product_query);


$review_query = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id = '$id' ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="../assets/css/product-detail.css">

<main class="product-container">
    <div class="product-main-info">
        <div class="product-image-box">
            <img src="../assets/img/<?= $p['image_url'] ?>" alt="<?= $p['name'] ?>">
        </div>

        <div class="product-text-box">
            <h1 class="product-title"><?= $p['name'] ?></h1>
            <div class="rating-summary">
                <span class="stars">★★★★☆</span>
                <span class="review-count">(4.5 dari 120 ulasan)</span>
            </div>
            <p class="product-price">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
            
            <p class="product-description">
                <?= nl2br($p['description']) ?>
            </p>

            <form action="../process/add_to_cart.php" method="POST" class="purchase-form">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <div class="qty-control">
                    <button type="button" class="btn-min"onclick="changeQty(-1)">-</button>
                    <input type="number" name="qty" id="qty" value="1" min="1">
                    <button type="button" class="btn-add" onclick="changeQty(1)">+</button>
                    <button type="submit" class="btn-cart">ADD TO CART</button>
                </div>
            </form>
        </div>
    </div>

    <section class="customer-reviews">
        <h2 class="section-heading">Ulasan Pelanggan</h2>
        
        <?php if(mysqli_num_rows($review_query) > 0): ?>
            <?php while($rev = mysqli_fetch_assoc($review_query)): ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= $rev['user_name'] ?></strong>
                        <span class="review-date">| <?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                    </div>
                    <div class="review-stars">
                        <?= str_repeat('★', $rev['rating']) ?><?= str_repeat('☆', 5 - $rev['rating']) ?>
                    </div>
                    <p class="review-text"><?= $rev['comment'] ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada ulasan untuk produk ini.</p>
        <?php endif; ?>
    </section>
</main>

<script>
function changeQty(amt) {
    const qtyInput = document.getElementById('qty');
    let newVal = parseInt(qtyInput.value) + amt;
    if (newVal < 1) newVal = 1;
    qtyInput.value = newVal;
}
</script>

<?php include '../includes/footer.php'; ?>