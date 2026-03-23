<?php
require_once 'api_common.php';
require_once '../includes/payment-functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$method_id = $_POST['payment_method_id'] ?? '';
$amount = (float)($_POST['amount'] ?? 0);

$res = processStripePayment($amount, $method_id);
if ($res['success']) {
    apiResponse(true, $res);
} else {
    apiResponse(false, [], 'Payment failed.');
}
?>
