<?php
require_once 'api_common.php';
require_once '../includes/product-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') apiResponse(false, [], 'Invalid request method.');

$query = trim($_GET['q'] ?? '');
if (strlen($query) < 2) apiResponse(true, []);

$products = getProducts($pdo, 5, null, $query);
apiResponse(true, $products);
?>
