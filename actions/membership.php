<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(url('membership'));
}

$errors = [];
$full_name         = trim($_POST['full_name'] ?? '');
$email             = trim($_POST['email'] ?? '');
$phone             = trim($_POST['phone'] ?? '');
$date_of_birth     = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
$address_in_nepal  = trim($_POST['address_in_nepal'] ?? '');
$address_in_korea  = trim($_POST['address_in_korea'] ?? '');
$visa_type         = $_POST['visa_type'] ?? 'Other';
$occupation        = trim($_POST['occupation'] ?? '');
$university_company = trim($_POST['university_company'] ?? '');
$reason            = trim($_POST['reason_for_joining'] ?? '');

if (empty($full_name)) $errors[] = 'Full name is required.';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (empty($phone)) $errors[] = 'Phone number is required.';
if (empty($reason)) $errors[] = 'Please tell us why you want to join NCB.';
if (strlen($reason) < 20) $errors[] = 'Please provide more detail (at least 20 characters).';

$allowed_visa = ['D-2 Student','D-4 Language','E-2 Teaching','E-7 Engineering','E-9 EPS','F-2 Residence','F-5 Permanent','Other'];
if (!in_array($visa_type, $allowed_visa)) $visa_type = 'Other';

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    redirect(url('membership'));
}

try {
    $stmt = getDB()->prepare("
        INSERT INTO membership_applications 
        (full_name, email, phone, date_of_birth, address_in_nepal, address_in_korea, visa_type, occupation, university_company, reason_for_joining)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$full_name, $email, $phone, $date_of_birth, $address_in_nepal, $address_in_korea, $visa_type, $occupation, $university_company, $reason]);
    set_flash('success', 'Your membership application has been submitted! We will review it and notify you via email.');
} catch (Exception $e) {
    set_flash('error', 'Something went wrong. Please try again later.');
}

redirect(url('membership'));
