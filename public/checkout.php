<?php
require_once 'includes/header.php';

$items = getCartItems($pdo);
$total = calculateCartTotal($items);

if (empty($items)) redirect('cart.php');

$error = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'stripe';
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // 1. Process Payment
        $payment = ($payment_method === 'stripe') 
            ? processStripePayment($total, 'tok_visa') 
            : processPayPalPayment('ORD-MOCK');

        if (!$payment['success']) throw new Exception("Payment failed.");

        // 2. Create Order
        $order_id = createOrder($user_id, $total, $pdo);

        // 3. Clear Cart
        clearCart($pdo);

        $pdo->commit();
        
        // 4. Notifications
        sendOrderConfirmation($order_id, $pdo);

        $success_message = "Order placed successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Checkout Error: " . $e->getMessage();
    }
}
?>

<main class="container">
    <section style="max-width: 800px; margin: 4rem auto;">
        <?php if ($success_message): ?>
            <div style="background: #ecfdf5; color: #065f46; padding: 2rem; border-radius: 12px; text-align: center; box-shadow: var(--shadow);">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
                <h1 style="margin-bottom: 1rem;"><?php echo $success_message; ?></h1>
                <p style="margin-bottom: 2rem;">Thank you for your order. A confirmation email has been sent.</p>
                <a href="dashboard.php" style="background: var(--primary-color); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">View Your Orders</a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
                <!-- Checkout Form -->
                <div>
                    <h1 style="margin-bottom: 2rem;">Checkout</h1>
                    
                    <?php if ($error): ?><div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;"><?php echo $error; ?></div><?php endif; ?>

                    <div style="background: var(--card-bg); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 2rem;">
                        <h3 style="margin-bottom: 1.5rem;">Shipping Information</h3>
                        <p style="color: var(--text-muted);"><?php echo $_SESSION['user_name']; ?> (Default Address)</p>
                    </div>

                    <div id="payment-section">
                        <h3 style="margin-bottom: 1rem;">Choose Payment Method</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <button onclick="submitOrder('stripe')" style="background: #635bff; color: white; padding: 1rem; font-weight: 700;">Stripe (Card)</button>
                            <button onclick="submitOrder('paypal')" style="background: #ffc439; color: #111; padding: 1rem; font-weight: 700;">PayPal</button>
                        </div>
                        <form action="checkout.php" method="POST" id="checkout-form" style="display: none;">
                            <input type="hidden" name="payment_method" id="payment_method">
                        </form>
                    </div>
                </div>

                <!-- Summary -->
                <aside>
                    <div style="background: var(--card-bg); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow);">
                        <h3 style="margin-bottom: 1.5rem;">Order Review</h3>
                        <?php foreach ($items as $item): ?>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem;">
                                <span><?php echo $item['quantity']; ?>x <?php echo htmlspecialchars($item['name']); ?></span>
                                <span><?php echo formatPrice($item['price'] * $item['quantity']); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--border-color);">
                        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700;">
                            <span>Total</span>
                            <span><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
function submitOrder(method) {
    document.getElementById('payment_method').value = method;
    document.getElementById('checkout-form').submit();
}
</script>

<?php require_once 'includes/footer.php'; ?>
