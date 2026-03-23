<?php
require_once 'api_common.php';
require_once '../includes/cart-functions.php';

$items = getCartItems($pdo);
$count = 0;
foreach ($items as $item) $count += $item['quantity'];

apiResponse(true, ['count' => $count]);
?>
