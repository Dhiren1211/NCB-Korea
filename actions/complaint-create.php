<?php
/**
 * NCB Website - Create Complaint Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to submit a complaint.');
    redirect(url('login'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('complaints'));
}

$title       = trim($_POST['title'] ?? '');
$category    = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];

if ($title === '') $errors[] = 'Title is required.';
if ($category === '') $errors[] = 'Category is required.';
if ($description === '') $errors[] = 'Description is required.';

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('complaints'));
}

$user = current_pub_user();

try {
    create_complaint($user['id'], $title, $description, $category);
    set_flash('success', 'Complaint submitted successfully. We will review it soon.');
} catch (Exception $e) {
    set_flash('error', 'Failed to submit complaint. Please try again.');
}

redirect(url('complaints'));
