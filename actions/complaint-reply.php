<?php
/**
 * NCB Website - Complaint Reply Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to reply.');
    redirect(url('login'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('complaints'));
}

$complaint_id = (int)($_POST['complaint_id'] ?? 0);
$message      = trim($_POST['message'] ?? '');

if ($complaint_id === 0) {
    set_flash('error', 'Invalid complaint.');
    redirect(url('complaints'));
}

if ($message === '') {
    set_flash('error', 'Reply message cannot be empty.');
    redirect(url('complaints'));
}

$user = current_pub_user();

try {
    create_complaint_reply($complaint_id, $user['id'], $message);
    set_flash('success', 'Reply posted successfully.');
} catch (Exception $e) {
    set_flash('error', 'Failed to post reply. Please try again.');
}

// Redirect back to complaint detail page
redirect(url('complaints') . '&id=' . $complaint_id);
