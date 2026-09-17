<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('contact'));
}

// Validate
$errors = [];
$full_name = trim($_POST['full_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$subject    = trim($_POST['subject'] ?? '');
$message    = trim($_POST['message'] ?? '');

if (empty($full_name)) $errors[] = 'Full name is required.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (empty($subject)) $errors[] = 'Subject is required.';
if (empty($message)) $errors[] = 'Message is required.';
if (strlen($message) < 10) $errors[] = 'Message must be at least 10 characters.';

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('contact'));
}

try {
    $stmt = getDB()->prepare("
        INSERT INTO contact_submissions (full_name, email, phone, subject, message)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$full_name, $email, $phone, $subject, $message]);
    set_flash('success', 'Thank you! Your message has been sent successfully. We will get back to you soon.');
} catch (Exception $e) {
    set_flash('error', 'Something went wrong. Please try again later.');
}

redirect(url('contact'));
