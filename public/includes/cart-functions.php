<?php
/**
 * Shopping Cart Functions
 */

function getCartItems($pdo) {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.slug FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ? OR c.user_id = ?");
    $stmt->execute([$session_id, $user_id]);
    return $stmt->fetchAll();
}

function addToCart($product_id, $quantity, $pdo) {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;
    
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE (session_id = ? OR user_id = ?) AND product_id = ?");
    $stmt->execute([$session_id, $user_id, $product_id]);
    $item = $stmt->fetch();

    if ($item) {
        $new_qty = $item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_qty, $item['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (session_id, user_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$session_id, $user_id, $product_id, $quantity]);
    }
    return true;
}

function updateCartQty($cart_id, $quantity, $pdo) {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND (session_id = ? OR user_id = ?)");
    return $stmt->execute([$quantity, $cart_id, $session_id, $user_id]);
}

function removeFromCart($cart_id, $pdo) {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND (session_id = ? OR user_id = ?)");
    return $stmt->execute([$cart_id, $session_id, $user_id]);
}

function clearCart($pdo) {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ? OR user_id = ?");
    return $stmt->execute([$session_id, $user_id]);
}

function calculateCartTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function migrateSessionToUser($user_id, $pdo) {
    $session_id = session_id();
    $stmt = $pdo->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
    return $stmt->execute([$user_id, $session_id]);
}
?>
