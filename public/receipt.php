<?php
require_once 'includes/header.php';

if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$order_id = (int)($_GET['id'] ?? 0);

// Fetch Order
$stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ? AND o.user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

// Fetch Items
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

// This page is designed to be printed as PDF
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - <?php echo $order['order_number']; ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1e293b; padding: 2rem; max-width: 800px; margin: 0 auto; }
        .receipt-header { display: flex; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 2rem; }
        .logo { font-size: 1.5rem; font-weight: 700; color: #6366f1; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        .table th { text-align: left; background: #f8fafc; padding: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 0.75rem; border-bottom: 1px solid #f1f5f9; }
        .total-row { font-weight: 700; font-size: 1.1rem; }
        .footer { text-align: center; color: #64748b; font-size: 0.8rem; margin-top: 4rem; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 2rem;">
        <button onclick="window.print()" style="background: #6366f1; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer;">Download / Print PDF</button>
        <a href="dashboard.php" style="margin-left: 1rem; color: #64748b; text-decoration: none;">← Back to Dashboard</a>
    </div>

    <div class="receipt-header">
        <div>
            <div class="logo">ANTISTIC SHOP</div>
            <p>123 Tech Avenue, Silicon Valley<br>support@antistic.shop</p>
        </div>
        <div style="text-align: right;">
            <h1>RECEIPT</h1>
            <p>Order #: <?php echo htmlspecialchars($order['order_number']); ?><br>Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <strong>Billed To:</strong><br>
        <?php echo htmlspecialchars($order['user_name']); ?><br>
        <?php echo htmlspecialchars($order['email']); ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo formatPrice($item['price']); ?></td>
                    <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; padding: 0.75rem;">Subtotal:</td>
                <td style="padding: 0.75rem;"><?php echo formatPrice($order['total']); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right; padding: 0.75rem;">Total:</td>
                <td style="padding: 0.75rem;"><?php echo formatPrice($order['total']); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for shopping with Antigravity! All sales are final.</p>
    </div>
</body>
</html>
<?php exit; // End of receipt page ?>
