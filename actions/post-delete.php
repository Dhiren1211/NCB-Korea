<?php
/**
 * NCB Website - Delete Post Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to delete posts.');
    redirect(url('login'));
}

$post_id = (int)($_GET['post_id'] ?? 0);
if ($post_id === 0) {
    set_flash('error', 'Invalid post.');
    redirect(url('community'));
}

$user = current_pub_user();
delete_post($post_id, $user['id']);

set_flash('success', 'Post deleted.');
redirect(url('community'));
