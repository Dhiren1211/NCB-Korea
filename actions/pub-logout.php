<?php
/**
 * NCB Website - Public User Logout Handler
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

pub_logout();
set_flash('success', 'You have been logged out.');
redirect(url('home'));
