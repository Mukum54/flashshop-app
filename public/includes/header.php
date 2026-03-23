<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) session_start();

// Database Connection
require_once __DIR__ . '/../../config/db.php';

// New Modular Includes
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/cart-functions.php';
require_once __DIR__ . '/product-functions.php';
require_once __DIR__ . '/category-functions.php';
require_once __DIR__ . '/order-functions.php';
require_once __DIR__ . '/payment-functions.php';
require_once __DIR__ . '/email-functions.php';
require_once __DIR__ . '/security.php';

// Auth State
$isLoggedIn = isLoggedIn();

// Determine Base Path for assets/links
$scriptName = $_SERVER['SCRIPT_NAME'];
$publicPos = strpos($scriptName, '/public/');
$baseUrl = ($publicPos !== false) ? substr($scriptName, 0, $publicPos + 8) : '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Shop | E-commerce Platform</title>
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container nav-wrapper">
            <a href="<?php echo $baseUrl; ?>index.php" class="logo" style="text-decoration: none; font-size: 1.5rem; font-weight: 800; color: var(--primary-color); letter-spacing: -0.02em;">
                ANTISTIC SHOP
            </a>
            <nav class="nav-links" style="display: flex; align-items: center;">
                <!-- Search Bar -->
                <div style="position: relative; margin-right: 2rem;">
                    <input type="text" id="search-input" placeholder="Search..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 20px; outline: none; width: 200px;">
                    <div id="search-suggestions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid var(--border-color); border-radius: 8px; margin-top: 5px; box-shadow: var(--shadow); z-index: 1000; display: none;"></div>
                </div>

                <a href="<?php echo $baseUrl; ?>index.php">Home</a>
                <a href="<?php echo $baseUrl; ?>products.php">Shop</a>
                <a href="<?php echo $baseUrl; ?>cart.php" style="position: relative;">
                    Cart
                    <span id="cart-counter" style="position: absolute; top: -10px; right: -15px; background: #ef4444; color: white; font-size: 0.7rem; padding: 0.2rem 0.5rem; border-radius: 10px; display: none;">0</span>
                </a>
                <?php if ($isLoggedIn): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?php echo $baseUrl; ?>admin/product-add.php" style="color: #6366f1; font-weight: 700;">Admin</a>
                    <?php endif; ?>
                    <a href="<?php echo $baseUrl; ?>dashboard.php">Dashboard</a>
                    <a href="<?php echo $baseUrl; ?>logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $baseUrl; ?>login.php">Login</a>
                    <a href="<?php echo $baseUrl; ?>register.php" style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 6px;">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script src="<?php echo $baseUrl; ?>js/shop.js"></script>
