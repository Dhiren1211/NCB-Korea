<?php
/**
 * NCB Website - Main Entry Point & Router
 */
session_start();
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Allowed pages
$allowed_pages = [
    'home', 'about', 'events', 'event-detail', 'news', 'news-detail',
    'gallery', 'committee', 'membership', 'contact', 'services', 'downloads',
    'volunteers', 'community', 'complaints', 'login', 'register'
];

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Route: event detail by slug
if ($page === 'event-detail') {
    if (isset($_GET['slug'])) {
        $event = get_event_by_slug($_GET['slug']);
    } elseif (isset($_GET['id'])) {
        $event = get_event_by_id($_GET['id']);
    }
    if (!$event) {
        http_response_code(404);
        $page = 'home';
    } else {
        $GLOBALS['event'] = $event;
    }
}

// Route: news detail by slug
if ($page === 'news-detail') {
    if (!isset($_GET['slug'])) {
        http_response_code(404);
        $page = 'home';
    }
}

if (!in_array($page, $allowed_pages)) {
    http_response_code(404);
    $page = 'home';
}

$page_file = __DIR__ . '/pages/' . $page . '.php';
if (!file_exists($page_file)) {
    $page = 'home';
    $page_file = __DIR__ . '/pages/home.php';
}

require_once __DIR__ . '/includes/header.php';
require_once $page_file;
require_once __DIR__ . '/includes/footer.php';
