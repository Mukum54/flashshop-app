<?php
require_once 'api_common.php';
require_once '../includes/cart-functions.php';

$items = getCartItems($pdo);
$total = calculateCartTotal($items);

apiResponse(true, [
    'items' => $items, 
    'total' => $total,
    'tax' => $total * 0.1,
    'grand_total' => $total * 1.1
]);
?>
