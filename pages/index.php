<?php
include '../includes/db.php';
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <p class="tagline">THE SPRING COLLECTION</p>
            <h1>Glow from Within, Naturally.</h1>
            <p class="description">Discover curated skincare rituals backed by science and inspired by the effortless beauty of the editorial world.</p>
            <div class="cta-group">
                <a href="shop.php" class="btn-fill">SHOP NOW</a>
                <a href="about.php" class="btn-outline">OUR STORY</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="../assets/img/hero-product.png" alt="Luxury Skincare" onerror="this.src='https://via.placeholder.com/600x800?text=BeautyVerse+Hero'">
        </div>
    </section>

    <section class="curation">
        <div class="section-title">
            <h2>Curation by Need</h2>
            <a href="shop.php" class="view-all">VIEW ALL →</a>
        </div>
        <div class="grid-categories">
            <div class="card">
                <img src="../assets/img/skincare.jpeg" alt="Skincare" onerror="this.src='https://via.placeholder.com/300x350?text=Skincare'">
                <h3>Skincare</h3>
            </div>
            <div class="card">
                <img src="../assets/img/makeup.png" alt="Makeup" onerror="this.src='https://via.placeholder.com/300x350?text=Makeup'">
                <h3>Makeup</h3>
            </div>
            <div class="card">
                <img src="../assets/img/haircare.png" alt="Haircare" onerror="this.src='https://via.placeholder.com/300x350?text=Haircare'">
                <h3>Haircare</h3>
            </div>
        </div>
    </section>

    <section class="features-split">
        <div class="feature-box ai-box">
            <span class="badge">New Tech</span>
            <h2>Precision AI Skin Analysis</h2>
            <p>Upload a photo to receive a personalized ingredient map and routine tailored to your skin's unique needs.</p>
            <a href="ai-scan.php" class="link-text">Try AI Scan →</a>
        </div>
        <div class="feature-box expert-box">
            <span class="badge">1:1 Concierge</span>
            <h2>Chat with our Beauty Experts</h2>
            <p>Access professional estheticians and makeup artists for a 15-minute video consultation.</p>
            <a href="experts.php" class="link-text">Book Now →</a>
        </div>
    </section>

    <section class="trending">
        <div class="trending-header">
            <p class="label">CURATED FAVORITES</p>
            <h2>Trending This Week</h2>
            <div class="underline"></div>
        </div>
        
        <div class="product-grid">
            <?php
           
            $query = mysqli_query($conn, "SELECT * FROM products LIMIT 4");
            while($row = mysqli_fetch_assoc($query)) :
            ?>
            <div class="product-card">
                <a href="product_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="p-img">
                        <img src="../assets/img/<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" onerror="this.src='https://via.placeholder.com/300x350?text=BeautyVerse'">
                    </div>
                    <p class="p-brand"><?= strtoupper($row['brand']) ?></p>
                    <p class="p-name"><?= $row['name'] ?></p>
                    <p class="p-price">$<?= number_format($row['price'], 2) ?></p>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
include '../includes/footer.php';
?>
