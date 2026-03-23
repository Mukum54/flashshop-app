<?php
require_once __DIR__ . '/config/db.php';

try {
    $stmt = $pdo->query("SELECT id, name, images FROM products");
    while ($row = $stmt->fetch()) {
        echo "Product ID " . $row['id'] . " (" . $row['name'] . "): " . $row['images'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
