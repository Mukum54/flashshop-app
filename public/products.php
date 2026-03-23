<?php
require_once 'includes/header.php';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Fetch products and categories using modular functions
$products = getProducts($pdo, 20, $category_id, $search);
$categories = getCategories($pdo);
?>

<main class="container">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 2rem; margin-top: 2rem;">
        <!-- Sidebar Filters -->
        <aside>
            <div style="background: var(--card-bg); padding: 1.5rem; border-radius: var(--border-radius); box-shadow: var(--shadow);">
                <h3 style="margin-bottom: 1.5rem;">Categories</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.75rem;">
                        <a href="products.php" style="text-decoration: none; color: <?php echo !$category_id ? 'var(--primary-color)' : 'var(--text-main)'; ?>; font-weight: <?php echo !$category_id ? '600' : '400'; ?>;">
                            All Categories
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li style="margin-bottom: 0.75rem;">
                            <a href="products.php?category=<?php echo $cat['id']; ?>" style="text-decoration: none; color: <?php echo $category_id == $cat['id'] ? 'var(--primary-color)' : 'var(--text-main)'; ?>; font-weight: <?php echo $category_id == $cat['id'] ? '600' : '400'; ?>;">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--border-color);">

                <h3 style="margin-bottom: 1.5rem;">Search</h3>
                <form action="products.php" method="GET">
                    <?php if ($category_id): ?>
                        <input type="hidden" name="category" value="<?php echo $category_id; ?>">
                    <?php endif; ?>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product name..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 1rem;">
                    <button type="submit" style="width: 100%;">Filter</button>
                </form>
            </div>
        </aside>

        <!-- Product Listing -->
        <section>
            <h1 style="margin-bottom: 2rem;">
                <?php 
                    if ($search) echo 'Search results for "' . htmlspecialchars($search) . '"';
                    elseif ($category_id) {
                        foreach ($categories as $cat) {
                            if ($cat['id'] == $category_id) {
                                echo htmlspecialchars($cat['name']);
                                break;
                            }
                        }
                    } else echo 'All Products';
                ?>
            </h1>

            <?php if (empty($products)): ?>
                <div style="text-align: center; padding: 4rem; background: var(--card-bg); border-radius: var(--border-radius);">
                    <p style="color: var(--text-muted); font-size: 1.2rem;">No products found matching your criteria.</p>
                    <a href="products.php" style="color: var(--primary-color); text-decoration: none; display: inline-block; margin-top: 1rem; font-weight: 600;">Clear all filters</a>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
        </section>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
