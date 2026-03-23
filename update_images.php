<?php
require_once __DIR__ . '/config/db.php';

try {
    // Update Smartphone
    $stmt = $pdo->prepare("UPDATE products SET images = ? WHERE id = 1");
    $stmt->execute([json_encode(['uploads/products/smartphone.png'])]);

    // Update T-Shirt
    $stmt = $pdo->prepare("UPDATE products SET images = ? WHERE id = 2");
    $stmt->execute([json_encode(['uploads/products/tshirt.png'])]);

    // Update Garden Hose
    $stmt = $pdo->prepare("UPDATE products SET images = ? WHERE id = 3");
    $stmt->execute([json_encode(['uploads/products/hose.png'])]);

    echo "Database updated with image paths.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
