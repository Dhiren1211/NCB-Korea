<?php
/**
 * NCB Website - Toggle Post Like Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to like posts.');
    redirect(url('login'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('community'));
}

$post_id = (int)($_POST['post_id'] ?? 0);
if ($post_id === 0) {
    set_flash('error', 'Invalid post.');
    redirect(url('community'));
}

$user = current_pub_user();
toggle_like($post_id, $user['id']);

$referer = $_SERVER['HTTP_REFERER'] ?? '';
if ($referer && strpos($referer, 'page=community') !== false) {
    redirect($referer);
}
redirect(url('community'));
