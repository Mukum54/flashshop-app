<?php
/**
 * Mock Email Helper
 */
function sendOrderConfirmation($order_id, $pdo) {
    // In a real app, use PHPMailer or mail()
    // For this simulation, we'll "log" the email to a temporary file
    
    $stmt = $pdo->prepare("SELECT o.*, u.email, u.name FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    
    $subject = "Order Confirmation - " . $order['order_number'];
    $message = "Hi " . $order['name'] . ",\n\nThank you for your order!\nOrder Total: $" . number_format($order['total'], 2) . "\n\nYou can view your receipt here: http://localhost/phppro/public/receipt.php?id=" . $order_id;
    
    $log_entry = "To: " . $order['email'] . "\nSubject: " . $subject . "\nMessage:\n" . $message . "\n---\n";
    
    file_put_contents(__DIR__ . '/../../logs/emails.txt', $log_entry, FILE_APPEND);
    
    return true;
}
?>
