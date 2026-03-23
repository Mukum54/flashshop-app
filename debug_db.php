<?php
require_once __DIR__ . '/config/db.php';

try {
    // Check if categories exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $catCount = $stmt->fetchColumn();
    echo "Categories count: " . $catCount . "\n";

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    if ($admin) {
        echo "Admin user found: " . $admin['email'] . "\n";
    } else {
        echo "NO ADMIN USER FOUND!\n";
    }

    // Check if products table is accessible
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $prodCount = $stmt->fetchColumn();
    echo "Products count: " . $prodCount . "\n";

} catch (Exception $e) {
    echo "DATABASE ERROR: " . $e->getMessage() . "\n";
}
?>
