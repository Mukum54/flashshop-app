<?php
require_once 'api_common.php';
require_once '../includes/cart-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) apiResponse(false, [], 'Invalid ID.');

if (removeFromCart($id, $pdo)) {
    apiResponse(true, ['message' => 'Item removed.']);
} else {
    apiResponse(false, [], 'Failed to remove.');
}
?>
