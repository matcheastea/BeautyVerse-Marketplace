<?php
include '../includes/db.php';
include '../includes/header.php';

$conn = mysqli_connect($host, $username, $pass, $db);
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'All';

// 2. Mulai susun Query dengan spasi yang benar
$sql = "SELECT * FROM products WHERE 1=1";

if ($category !== 'All') {
    // Berikan spasi sebelum kata AND
    $sql .= " AND category = '$category'";
}

if ($search !== '') {
    // Berikan spasi sebelum kata AND
    $sql .= " AND (name LIKE '%$search%' OR brand LIKE '%$search%')";
}

$sql .= " ORDER BY id DESC";

// 3. Jalankan query
$result = mysqli_query($conn, $sql);

// Jika query gagal, tampilkan pesan error yang jelas
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<main class="shop-container">
    <link rel="stylesheet" href="../assets/css/shop.css">
    <section class="shop-header">
        <h1>All Curations</h1>

        <div class="search-wrapper">
            <form action="shop.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Cari produk kecantikan..." value="<?=  htmlspecialchars($search) ?>">
                <button type ="submit">
                    <img src="../assets/img/search.png">
                </button>
            </form>
        </div>

        <div class="filter-bar">
            <div class="filter-bar">
            <a href="shop.php?category=All" class="filter-btn <?= $category == 'All' ? 'active' : '' ?>">All</a>
            <a href="shop.php?category=Skincare" class="filter-btn <?= $category == 'Skincare' ? 'active' : '' ?>">Skincare</a>
            <a href="shop.php?category=Makeup" class="filter-btn <?= $category == 'Makeup' ? 'active' : '' ?>">Makeup</a>
            <a href="shop.php?category=Haircare" class="filter-btn <?= $category == 'Haircare' ? 'active' : '' ?>">Haircare</a>
        </div>
        </div>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM products ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="product-card">
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="p-link">
                            <div class="p-img">
                                <img src="../assets/img/<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" onerror="this.src='https://via.placeholder.com/400x500?text=BeautyVerse'">
                            </div>
                        </a>

                        <p class="p-brand"><?php echo strtoupper($row['brand']); ?></p>
                        
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="p-name-link">
                            <p class="p-name"><?php echo $row['name']; ?></p>
                        </a>

                        <p class="p-price">$<?php echo number_format($row['price'], 2); ?></p>
                        
                        <form action="../process/add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart">ADD TO CART</button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="no-products">
            <img src="../assets/img/empty-box.png" alt="Kosong" style="width:100px; opacity:0.5;">
            <p>Oops! Belum ada koleksi produk saat ini. Kembali lagi nanti ya!</p>
          </div>';
            }
            ?>
        </div>
    </section>
</main>