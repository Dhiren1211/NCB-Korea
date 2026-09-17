<?php
/**
 * NCB Website - AJAX Image Upload Handler
 * Returns JSON: { success: true, path: "uploads/...", url: "uploads/..." }
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require login for uploads
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $errMessages = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize.',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds MAX_FILE_SIZE.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk.',
    ];
    $code = $_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE;
    echo json_encode(['success' => false, 'error' => $errMessages[$code] ?? 'Upload failed.']);
    exit;
}

$file = $_FILES['image'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
$allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($mime, $allowedTypes) || !in_array($ext, $allowedExts)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP, SVG.']);
    exit;
}

// Max 5MB
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'File too large. Maximum size is 5MB.']);
    exit;
}

// Determine subfolder
$subfolder = trim($_POST['folder'] ?? 'general', '/\\');
$allowedFolders = ['events', 'news', 'gallery', 'committee', 'general'];
if (!in_array($subfolder, $allowedFolders)) {
    $subfolder = 'general';
}

// Generate unique filename
$basename = bin2hex(random_bytes(8)) . '_' . time();
$filename = $basename . '.' . $ext;
$uploadDir = __DIR__ . '/../uploads/' . $subfolder . '/';

// Ensure directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['success' => false, 'error' => 'Failed to save file. Check folder permissions.']);
    exit;
}

$relativePath = 'uploads/' . $subfolder . '/' . $filename;

echo json_encode([
    'success' => true,
    'path'   => $relativePath,
    'url'    => $relativePath,
    'name'   => $file['name'],
    'size'   => $file['size'],
]);
