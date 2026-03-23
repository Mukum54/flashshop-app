<?php
require_once 'includes/header.php';

// Fetch products using modular function
$products = getProducts($pdo, 10);
?>

<main class="container">
    <section class="hero">
        <h1>Discover Premium Quality</h1>
        <p>Shop the latest trends in electronics, fashion, and more.</p>
    </section>

    <section class="products">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>Featured Products</h2>
            <a href="products.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">View All →</a>
        </div>
        
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="product-image">
                            <?php 
                                $images = json_decode($product['images'], true);
                                $img_src = !empty($images) ? $images[0] : 'https://via.placeholder.com/300';
                            ?>
                            <img src="<?php echo $baseUrl . $img_src; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        </div>
                    </a>
                    <div class="product-info">
                        <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                        <h3 class="product-name">
                            <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                            <span class="product-price"><?php echo formatPrice($product['price']); ?></span>
                            <button type="button" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
