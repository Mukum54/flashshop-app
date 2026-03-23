<?php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($id, $pdo);

if (!$product) {
    redirect('index.php');
}
?>

<main class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 4rem; align-items: start;">
        <!-- Product Images -->
        <div class="product-detail-image">
            <?php 
                $images = json_decode($product['images'], true);
                if (!empty($images)): ?>
                <div style="aspect_ratio: 1/1; border-radius: 12px; overflow: hidden; background: #f8fafc; box-shadow: var(--shadow);">
                    <img src="<?php echo $baseUrl . $images[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <!-- Mini thumbnails if more than 1 -->
                <?php if (count($images) > 1): ?>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <?php foreach($images as $img): ?>
                            <img src="<?php echo $baseUrl . $img; ?>" style="width: 60px; height: 60px; border-radius: 6px; cursor: pointer; border: 1px solid var(--border-color);">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="aspect-ratio: 1/1; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: #cbd5e1;">
                    No Image
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="product-detail-info">
            <div style="color: var(--primary-color); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.9rem; margin-bottom: 1rem;">
                <?php echo htmlspecialchars($product['category_name']); ?>
            </div>
            <h1 style="font-size: 3rem; line-height: 1.1; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div style="font-size: 2rem; font-weight: 700; margin-bottom: 2rem; color: var(--text-main);">
                <?php echo formatPrice($product['price']); ?>
            </div>
            
            <div style="background: #f8fafc; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Product Description</h3>
                <p style="color: var(--text-muted); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>

            <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 2rem;">
                <div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">SKU</div>
                    <div style="font-weight: 600;"><?php echo htmlspecialchars($product['sku']); ?></div>
                </div>
                <div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">Availability</div>
                    <div style="font-weight: 600; color: <?php echo $product['stock'] > 0 ? '#10b981' : '#ef4444'; ?>">
                        <?php echo $product['stock'] > 0 ? $product['stock'] . ' in stock' : 'Out of stock'; ?>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <input type="number" id="qty-input" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 80px; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px;">
                <button type="button" onclick="addToCart(<?php echo $product['id']; ?>, document.getElementById('qty-input').value)" style="flex-grow: 1; font-size: 1.1rem;" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
