<?php
/**
 * NCB Website - Authentication
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login_user($username, $password) {
    $stmt = getDB()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    return false;
}

function logout_user() {
    session_destroy();
    header('Location: login.php');
    exit;
}

function current_user() {
    return [
        'id'   => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
    ];
}
