<?php
require_once 'api_common.php';
require_once '../includes/cart-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);

if ($product_id <= 0) apiResponse(false, [], 'Invalid product ID.');

if (addToCart($product_id, $quantity, $pdo)) {
    apiResponse(true, ['message' => 'Product added to cart.']);
} else {
    apiResponse(false, [], 'Failed to add item.');
}
?>
