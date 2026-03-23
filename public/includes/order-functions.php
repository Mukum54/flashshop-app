<?php
/**
 * Order Processing Functions
 */

function createOrder($user_id, $total, $pdo) {
    $order_number = generateOrderNumber();
    $stmt = $pdo->prepare("INSERT INTO orders (order_number, user_id, total, status, payment_status) VALUES (?, ?, ?, 'pending', 'unpaid')");
    $stmt->execute([$order_number, $user_id, $total]);
    return $pdo->lastInsertId();
}

function getOrderById($id, $pdo) {
    $stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserOrders($user_id, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function generateOrderNumber() {
    return 'ORD-' . strtoupper(uniqid());
}
?>
