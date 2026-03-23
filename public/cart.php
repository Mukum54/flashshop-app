<?php
require_once 'includes/header.php';

$items = getCartItems($pdo);
$subtotal = calculateCartTotal($items);
$total = $subtotal; // Simplify for now
?>

<main class="container">
    <h1 style="margin: 2rem 0;">Your Shopping Cart</h1>

    <?php if (empty($items)): ?>
        <div style="text-align: center; padding: 4rem; background: var(--card-bg); border-radius: var(--border-radius); box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
            <h2 style="margin-bottom: 1rem;">Your cart is empty</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Looks like you haven't added anything to your cart yet.</p>
            <a href="products.php" class="logo" style="background: var(--primary-color); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-size: 1.1rem;">Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
            <!-- Cart Items -->
            <div style="background: var(--card-bg); border-radius: var(--border-radius); box-shadow: var(--shadow); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                        <tr>
                            <th style="padding: 1rem; text-align: left;">Product</th>
                            <th style="padding: 1rem; text-align: left;">Price</th>
                            <th style="padding: 1rem; text-align: left;">Quantity</th>
                            <th style="padding: 1rem; text-align: left;">Subtotal</th>
                            <th style="padding: 1rem; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1.5rem;">
                                    <div style="display: flex; gap: 1rem; align-items: center;">
                                        <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; text-align: center;">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </div>
                                        <a href="product.php?id=<?php echo $item['product_id']; ?>" style="text-decoration: none; color: var(--text-main); font-weight: 600;">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </div>
                                </td>
                                <td style="padding: 1.5rem;"><?php echo formatPrice($item['price']); ?></td>
                                <td style="padding: 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)" style="padding: 0.2rem 0.5rem; background: #e2e8f0; color: black; border-radius: 4px;">-</button>
                                        <input type="number" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px; padding: 0.4rem; border: 1px solid var(--border-color); border-radius: 4px; text-align: center;" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)" style="padding: 0.2rem 0.5rem; background: #e2e8f0; color: black; border-radius: 4px;">+</button>
                                    </div>
                                </td>
                                <td style="padding: 1.5rem; font-weight: 600;"><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                <td style="padding: 1.5rem; text-align: right;">
                                    <button onclick="removeFromCart(<?php echo $item['id']; ?>)" style="background: none; border: none; color: #ef4444; font-weight: 600; cursor: pointer;">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <aside>
                <div style="background: var(--card-bg); padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow); position: sticky; top: 2rem;">
                    <h2 style="margin-bottom: 1.5rem;">Order Summary</h2>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: var(--text-muted);">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <hr style="margin-bottom: 1.5rem; border: none; border-top: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.25rem; font-weight: 700;">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <a href="checkout.php" style="display: block; background: var(--primary-color); color: white; text-align: center; padding: 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem;">Proceed to Checkout</a>
                    <div style="margin-top: 1.5rem; text-align: center;">
                        <a href="products.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Continue Shopping</a>
                    </div>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</main>

<script>
/**
 * AJAX functions linked to shop.js
 */
async function removeFromCart(cartId) {
    if (!confirm('Are you sure?')) return;
    const formData = new FormData();
    formData.append('id', cartId);
    formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const response = await fetch('api/cart-remove.php', { method: 'POST', body: formData });
    const result = await response.json();
    if (result.success) location.reload();
}
</script>

<?php require_once 'includes/footer.php'; ?>
