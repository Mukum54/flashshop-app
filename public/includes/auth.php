<?php
/**
 * Authentication and Session Management
 */

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAuth($adminOnly = false) {
    global $baseUrl;
    if (!isLoggedIn()) {
        redirect($baseUrl . 'login.php');
    }
    if ($adminOnly && !isAdmin()) {
        redirect($baseUrl . 'index.php');
    }
}

function loginUser($email, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        return $user;
    }
    return false;
}

function logoutUser() {
    global $baseUrl;
    session_destroy();
    redirect($baseUrl . 'index.php');
}

function registerUser($name, $email, $phone, $password, $pdo) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, 'customer')");
        $stmt->execute([$name, $email, $phone, $password_hash]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}
?>
