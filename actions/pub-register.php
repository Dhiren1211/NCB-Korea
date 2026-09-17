<?php
/**
 * NCB Website - Public User Registration Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('register'));
}

$full_name = trim($_POST['full_name'] ?? '');
$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$phone     = trim($_POST['phone'] ?? '');
$terms     = isset($_POST['terms']);

$errors = [];

if ($full_name === '') $errors[] = 'Full name is required.';
if ($username === '') {
    $errors[] = 'Username is required.';
} elseif (strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = 'Username may only contain letters, numbers, and underscores.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}
if ($password !== $password2) {
    $errors[] = 'Passwords do not match.';
}
if (!$terms) {
    $errors[] = 'You must agree to the terms and conditions.';
}

// Check uniqueness
if (empty($errors)) {
    if (get_public_user_by_username($username)) {
        $errors[] = 'Username is already taken.';
    }
    if (get_public_user_by_email($email)) {
        $errors[] = 'An account with this email already exists.';
    }
}

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('register'));
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    register_public_user([
        'username'  => $username,
        'full_name' => $full_name,
        'email'     => $email,
        'phone'     => $phone,
        'password'  => $hashedPassword,
        'role'      => 'member',
    ]);

    // Auto-login
    login_public_user($email, $password);

    set_flash('success', 'Registration successful! Welcome to NCB Community.');
    redirect(url('community'));
} catch (Exception $e) {
    $errors[] = 'Something went wrong. Please try again.';
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('register'));
}
