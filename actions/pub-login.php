<?php
/**
 * NCB Website - Public User Login Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('login'));
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if ($email === '') $errors[] = 'Email is required.';
if ($password === '') $errors[] = 'Password is required.';

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('login'));
}

if (login_public_user($email, $password)) {
    set_flash('success', 'Welcome back!');
    redirect(url('community'));
} else {
    set_flash('error', 'Invalid email or password.');
    redirect(url('login'));
}
