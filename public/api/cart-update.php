<?php
require_once 'api_common.php';
require_once '../includes/cart-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$id = (int)($_POST['id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);

if ($id <= 0 || $quantity <= 0) apiResponse(false, [], 'Invalid data.');

if (updateCartQty($id, $quantity, $pdo)) {
    apiResponse(true, ['message' => 'Cart updated.']);
} else {
    apiResponse(false, [], 'Failed to update.');
}
?>
