<?php
/**
 * NCB Website - Volunteer Registration Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('volunteers'));
}

$full_name = trim($_POST['full_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$bio       = trim($_POST['bio'] ?? '');
$message   = trim($_POST['message'] ?? '');

$errors = [];

if ($full_name === '') $errors[] = 'Full name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('volunteers'));
}

$user_id = null;
if (is_pub_logged_in()) {
    $user = current_pub_user();
    $user_id = $user['id'];
}

try {
    $stmt = getDB()->prepare('INSERT INTO volunteers (name, photo, bio, message, work_count, total_hours, joined_date, status, user_id) VALUES (?, ?, ?, ?, 0, 0, CURDATE(), "active", ?)');
    $stmt->execute([$full_name, 'assets/images/demo.jpg', $bio, $message, $user_id]);
    set_flash('success', 'Thank you for registering as a volunteer!');
} catch (Exception $e) {
    set_flash('error', 'Something went wrong. Please try again.');
}

redirect(url('volunteers'));
