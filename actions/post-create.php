<?php
/**
 * NCB Website - Create Post Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to create a post.');
    redirect(url('login'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('community'));
}

$content = trim($_POST['content'] ?? '');

if ($content === '') {
    set_flash('error', 'Post content cannot be empty.');
    redirect(url('community'));
}

if (mb_strlen($content) > 500) {
    set_flash('error', 'Post content must be 500 characters or less.');
    redirect(url('community'));
}

// Handle optional image upload
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($mime, $allowedTypes) && in_array($ext, $allowedExts)) {
        if ($file['size'] <= 5 * 1024 * 1024) {
            $uploadDir = __DIR__ . '/../uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $basename = bin2hex(random_bytes(8)) . '_' . time();
            $filename = $basename . '.' . $ext;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $imagePath = 'uploads/posts/' . $filename;
            }
        }
    }
}

$user = current_pub_user();

try {
    create_post($user['id'], $content, $imagePath);
    set_flash('success', 'Post submitted for approval. It will appear once an admin approves it.');
} catch (Exception $e) {
    set_flash('error', 'Failed to create post. Please try again.');
}

redirect(url('community'));
