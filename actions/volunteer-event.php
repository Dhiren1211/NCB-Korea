<?php
/**
 * NCB Website - Volunteer Event Registration Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!is_pub_logged_in()) {
    set_flash('error', 'Please login to register for events as a volunteer.');
    redirect(url('login'));
}

$event_id = (int)($_GET['event_id'] ?? 0);
if ($event_id === 0) {
    set_flash('error', 'Invalid event.');
    redirect(url('events'));
}

$event = get_event_by_id($event_id);
if (!$event) {
    set_flash('error', 'Event not found.');
    redirect(url('events'));
}

$user = current_pub_user();
$userData = get_public_user_by_id($user['id']);

// Find or create volunteer record
$stmt = getDB()->prepare('SELECT id FROM volunteers WHERE user_id = ?');
$stmt->execute([$user['id']]);
$volunteer = $stmt->fetch();

if (!$volunteer) {
    // Try by email
    $stmt = getDB()->prepare('SELECT id FROM volunteers WHERE name = ? AND email = ?');
    $stmt->execute([$userData['full_name'], $userData['email']]);
    $volunteer = $stmt->fetch();
}

if (!$volunteer) {
    // Create volunteer record
    $stmt = getDB()->prepare('INSERT INTO volunteers (name, email, photo, bio, message, work_count, total_hours, joined_date, status, user_id) VALUES (?, ?, "assets/images/demo.jpg", "", "", 0, 0, CURDATE(), "active", ?)');
    $stmt->execute([$userData['full_name'], $userData['email'], $user['id']]);
    $volunteer_id = getDB()->lastInsertId();
} else {
    $volunteer_id = $volunteer['id'];
}

if (register_volunteer_for_event($volunteer_id, $event_id)) {
    set_flash('success', 'You have registered as a volunteer for ' . e($event['title']) . '!');
} else {
    set_flash('info', 'You are already registered as a volunteer for this event.');
}

$redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('events');
if (strpos($redirectUrl, 'event-detail') !== false) {
    redirect($redirectUrl);
}
redirect(url('event-detail', ['slug' => $event['slug']]));
