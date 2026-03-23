<?php
require_once 'api_common.php';
require_once '../includes/payment-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$order_id = $_POST['order_id'] ?? '';

$res = processPayPalPayment($order_id);
if ($res['success']) {
    apiResponse(true, $res);
} else {
    apiResponse(false, [], 'Verification failed.');
}
?>
