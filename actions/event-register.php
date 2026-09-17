<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('events'));
}

$event_id     = (int)($_POST['event_id'] ?? 0);
$full_name    = trim($_POST['full_name'] ?? '');
$email        = trim($_POST['email'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$member       = isset($_POST['is_ncb_member']) ? 1 : 0;
$notes        = trim($_POST['additional_notes'] ?? '');

$errors = [];

if ($event_id === 0) $errors[] = 'Invalid event.';
if (empty($full_name)) $errors[] = 'Full name is required.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (empty($phone)) $errors[] = 'Phone number is required.';

$event = $event_id ? get_event_by_id($event_id) : null;
if (!$event) $errors[] = 'Event not found.';

if (is_user_registered($event_id, $email)) {
    set_flash('info', 'You have already registered for this event.');
    redirect(url('event-detail', ['slug' => $event['slug']]));
}

if ($event && $event['capacity'] > 0) {
    $count = get_event_registration_count($event_id);
    if ($count >= $event['capacity']) {
        set_flash('error', 'Sorry, registration for this event is full.');
        redirect(url('event-detail', ['slug' => $event['slug']]));
    }
}

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('event-detail', ['slug' => $event['slug']]));
}

try {
    $stmt = getDB()->prepare("
        INSERT INTO event_registrations (event_id, full_name, email, phone, is_ncb_member, additional_notes)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$event_id, $full_name, $email, $phone, $member, $notes]);
    $_SESSION['registered_email'] = $email;
    set_flash('success', 'You have been registered for ' . $event['title'] . '! Check your email for confirmation.');
} catch (Exception $e) {
    set_flash('error', 'Something went wrong. Please try again.');
}

redirect(url('event-detail', ['slug' => $event['slug']]));
