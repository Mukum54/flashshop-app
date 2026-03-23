<?php
require_once 'api_common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') apiResponse(false, [], 'Invalid request method.');

$code = strtoupper(trim($_POST['code'] ?? ''));

// Mock promo code validation
$valid_codes = [
    'WELCOME10' => ['type' => 'percentage', 'value' => 10],
    'SAVE20' => ['type' => 'fixed', 'value' => 20]
];

if (isset($valid_codes[$code])) {
    apiResponse(true, $valid_codes[$code]);
} else {
    apiResponse(false, [], 'Invalid or expired promo code.');
}
?>
