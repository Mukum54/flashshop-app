<?php
require_once 'includes/header.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ? AND status = 'active'");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.status = 'active'");
$stmt->execute([$category['id']]);
$products = $stmt->fetchAll();
?>

<main class="container">
    <section style="margin-top: 3rem;">
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 3rem; border-radius: var(--border-radius); margin-bottom: 3rem; text-align: center;">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($category['name']); ?></h1>
            <p style="color: var(--text-muted);">Explore our selection of products in the <?php echo htmlspecialchars($category['name']); ?> category.</p>
        </div>

        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
                    <p style="color: var(--text-muted);">No products found in this category.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $product['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="product-image">
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
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
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
