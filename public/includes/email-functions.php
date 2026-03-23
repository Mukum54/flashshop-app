<?php
/**
 * Email and Notification Functions
 */

function sendOrderConfirmation($order_id, $pdo) {
    $stmt = $pdo->prepare("SELECT o.*, u.email, u.name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    
    $subject = "Order Confirmation - " . $order['order_number'];
    $message = "Hi " . $order['name'] . ", confirmation for order " . $order['order_number'];
    
    // In a real app, use a mail library. Simulation:
    file_put_contents(__DIR__ . '/../../logs/emails.txt', "To: " . $order['email'] . "\nSubject: $subject\n\n", FILE_APPEND);
    return true;
}
?>
