<?php
/**
 * Global Utility Functions
 */

/**
 * Redirect to a specific URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Flash Message Handler
 */
function flashMessage($name, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$name] = $message;
    } elseif (isset($_SESSION['flash'][$name])) {
        $msg = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $msg;
    }
    return '';
}

/**
 * Price Formatter
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Slug Generator
 */
function generateSlug($text) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
}

/**
 * Input Sanitization
 */
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
