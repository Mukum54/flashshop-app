<?php
require_once 'includes/header.php';
requireAuth();

$orders = getUserOrders($_SESSION['user_id'], $pdo);
?>

<main class="container">
    <div style="margin: 3rem 0; display: flex; justify-content: space-between; align-items: center;">
        <h1>Your Dashboard</h1>
        <div style="display: flex; gap: 1rem;">
            <?php if (isAdmin()): ?>
                <a href="<?php echo $baseUrl; ?>admin/product-add.php" style="background: #1e293b; color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Admin Panel</a>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 3rem;">
        <!-- Sidebar -->
        <aside>
            <div style="background: var(--card-bg); padding: 2rem; border-radius: var(--border-radius); box-shadow: var(--shadow);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; margin: 0 auto 1rem;">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </div>
                    <h3 style="margin: 0;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Customer Member</p>
                </div>
                <nav style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="<?php echo $baseUrl; ?>dashboard.php" style="background: #f1f5f9; color: var(--primary-color); padding: 0.75rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600;">Order History</a>
                    <a href="#" style="padding: 0.75rem 1rem; border-radius: 6px; text-decoration: none; color: var(--text-main);">Profile Settings</a>
                    <a href="<?php echo $baseUrl; ?>logout.php" style="padding: 0.75rem 1rem; border-radius: 6px; text-decoration: none; color: #ef4444;">Logout</a>
                </nav>
            </div>
        </aside>

        <!-- Orders -->
        <section>
            <div style="background: var(--card-bg); border-radius: var(--border-radius); box-shadow: var(--shadow); overflow: hidden;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 1.25rem;">Recent Orders</h2>
                </div>
                
                <?php if (empty($orders)): ?>
                    <div style="text-align: center; padding: 4rem;">
                        <p style="color: var(--text-muted);">You haven't placed any orders yet.</p>
                        <a href="products.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600; display: inline-block; margin-top: 1rem;">Start Shopping</a>
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8fafc; text-align: left;">
                            <tr>
                                <th style="padding: 1rem;">Order #</th>
                                <th style="padding: 1rem;">Date</th>
                                <th style="padding: 1rem;">Status</th>
                                <th style="padding: 1rem;">Total</th>
                                <th style="padding: 1rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 1.5rem; font-weight: 600;"><?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td style="padding: 1.5rem; color: var(--text-muted);"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                    <td style="padding: 1.5rem;">
                                        <span style="padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; 
                                            <?php echo $order['status'] === 'completed' ? 'background: #dcfce7; color: #166534;' : 'background: #fef9c3; color: #854d0e;'; ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1.5rem; font-weight: 600;"><?php echo formatPrice($order['total']); ?></td>
                                    <td style="padding: 1.5rem;">
                                        <a href="<?php echo $baseUrl; ?>receipt.php?id=<?php echo $order['id']; ?>" target="_blank" style="color: var(--primary-color); text-decoration: none; font-size: 0.85rem; font-weight: 600;">View Receipt</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
