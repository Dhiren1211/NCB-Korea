<?php
/**
 * NCB Website - Helper Functions
 */
require_once __DIR__ . '/../config/database.php';

/**
 * Get a site setting by key
 */
function get_setting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        try {
            $stmt = getDB()->query("SELECT setting_key, setting_value FROM settings");
            $rows = $stmt->fetchAll();
            $settings = [];
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            $settings = [];
        }
    }
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Get all settings as associative array
 */
function get_all_settings() {
    static $all = null;
    if ($all === null) {
        try {
            $stmt = getDB()->query("SELECT setting_key, setting_value FROM settings");
            $rows = $stmt->fetchAll();
            $all = [];
            foreach ($rows as $row) {
                $all[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            $all = [];
        }
    }
    return $all;
}

/**
 * Get upcoming events
 */
function get_events($limit = null, $featured_only = false) {
    $db = getDB();
    $sql = "SELECT * FROM events WHERE status = 'upcoming' AND date >= CURDATE()";
    if ($featured_only) $sql .= " AND featured = 1";
    $sql .= " ORDER BY date ASC";
    if ($limit) $sql .= " LIMIT " . (int)$limit;
    return $db->query($sql)->fetchAll();
}

/**
 * Get all events (admin)
 */
function get_all_events() {
    return getDB()->query("SELECT * FROM events ORDER BY date DESC")->fetchAll();
}

/**
 * Get single event by ID
 */
function get_event_by_id($id) {
    $stmt = getDB()->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get event by slug
 */
function get_event_by_slug($slug) {
    $stmt = getDB()->prepare("SELECT * FROM events WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

/**
 * Get event registration count
 */
function get_event_registration_count($event_id) {
    $stmt = getDB()->prepare("SELECT COUNT(*) as total FROM event_registrations WHERE event_id = ? AND status = 'registered'");
    $stmt->execute([$event_id]);
    $row = $stmt->fetch();
    return $row ? (int)$row['total'] : 0;
}

/**
 * Check if user already registered
 */
function is_user_registered($event_id, $email) {
    $stmt = getDB()->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND email = ? AND status = 'registered'");
    $stmt->execute([$event_id, $email]);
    return $stmt->fetch() ? true : false;
}

/**
 * Get news articles
 */
function get_news($limit = null, $featured_only = false) {
    $sql = "SELECT * FROM news WHERE status = 'published'";
    if ($featured_only) $sql .= " AND featured = 1";
    $sql .= " ORDER BY published_at DESC";
    if ($limit) $sql .= " LIMIT " . (int)$limit;
    return getDB()->query($sql)->fetchAll();
}

/**
 * Get all news (admin)
 */
function get_all_news() {
    return getDB()->query("SELECT * FROM news ORDER BY published_at DESC")->fetchAll();
}

/**
 * Get news by slug
 */
function get_news_by_slug($slug) {
    $stmt = getDB()->prepare("SELECT * FROM news WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

/**
 * Get news by ID
 */
function get_news_by_id($id) {
    $stmt = getDB()->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Get gallery items
 */
function get_gallery($category = null) {
    $sql = "SELECT * FROM gallery";
    $params = [];
    if ($category && $category !== 'ALL') {
        $sql .= " WHERE category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY sort_order ASC, event_date DESC";
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Get gallery categories
 */
function get_gallery_categories() {
    $rows = getDB()->query("SELECT DISTINCT category FROM gallery ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    return array_merge(['ALL'], $rows);
}

/**
 * Get all gallery (admin)
 */
function get_all_gallery() {
    return getDB()->query("SELECT * FROM gallery ORDER BY sort_order ASC")->fetchAll();
}

/**
 * Get committee members
 */
function get_committee($active_only = true) {
    $sql = "SELECT * FROM committee";
    if ($active_only) $sql .= " WHERE active = 1";
    $sql .= " ORDER BY sort_order ASC";
    return getDB()->query($sql)->fetchAll();
}

/**
 * Get all committee (admin)
 */
function get_all_committee() {
    return getDB()->query("SELECT * FROM committee ORDER BY sort_order ASC")->fetchAll();
}

/**
 * Get downloads
 */
function get_downloads() {
    return getDB()->query("SELECT * FROM downloads WHERE active = 1 ORDER BY sort_order ASC")->fetchAll();
}

/**
 * Get all downloads (admin)
 */
function get_all_downloads() {
    return getDB()->query("SELECT * FROM downloads ORDER BY sort_order ASC")->fetchAll();
}

/**
 * Get membership applications (admin)
 */
function get_membership_applications($status = null) {
    $sql = "SELECT * FROM membership_applications";
    $params = [];
    if ($status) {
        $sql .= " WHERE status = ?";
        $params[] = $status;
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Get contact submissions (admin)
 */
function get_contact_submissions($status = null) {
    $sql = "SELECT * FROM contact_submissions";
    $params = [];
    if ($status) {
        $sql .= " WHERE status = ?";
        $params[] = $status;
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Get event registrations for an event (admin)
 */
function get_event_registrations($event_id = null) {
    $sql = "SELECT er.*, e.title as event_title FROM event_registrations er LEFT JOIN events e ON er.event_id = e.id";
    $params = [];
    if ($event_id) {
        $sql .= " WHERE er.event_id = ?";
        $params[] = $event_id;
    }
    $sql .= " ORDER BY er.created_at DESC";
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Sanitize output
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a browser URL for an uploaded or bundled image.
 */
function image_url($path) {
    $path = trim((string)$path);
    if ($path === '') {
        return '';
    }
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return ($basePath === '' || $basePath === '.') ? '/' . $path : $basePath . '/' . $path;
}

/**
 * Generate page URL
 */
function url($page, $params = []) {
    $base = 'index.php?page=' . urlencode($page);
    foreach ($params as $k => $v) {
        $base .= '&' . urlencode($k) . '=' . urlencode($v);
    }
    return $base;
}

/**
 * Get current page
 */
function current_page() {
    return isset($_GET['page']) ? $_GET['page'] : 'home';
}

/**
 * Format date
 */
function format_date($date_str, $format = 'M d, Y') {
    if (!$date_str) return '';
    return date($format, strtotime($date_str));
}

/**
 * JSON decode highlights/tags
 */
function get_tags($json_str) {
    $arr = json_decode($json_str, true);
    return is_array($arr) ? $arr : [];
}

/**
 * Set flash message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect
 */
function redirect($url) {
    // Ensure URL is absolute
    if (!preg_match('/^https?:\/\//i', $url) && !preg_match('/^\//', $url)) {
        // Check if we're in admin folder
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if (basename($scriptDir) === 'admin') {
            $url = rtrim($scriptDir, '/') . '/' . $url;
        }
    }
    header("Location: $url");
    exit;
}

/**
 * Get dashboard counts
 */
function get_dashboard_counts() {
    $db = getDB();
    return [
        'events'     => (int)$db->query("SELECT COUNT(*) FROM events WHERE status = 'upcoming'")->fetchColumn(),
        'news'       => (int)$db->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn(),
        'members'    => (int)$db->query("SELECT COUNT(*) FROM membership_applications WHERE status = 'approved'")->fetchColumn(),
        'pending'    => (int)$db->query("SELECT COUNT(*) FROM membership_applications WHERE status = 'pending'")->fetchColumn(),
        'registrations' => (int)$db->query("SELECT COUNT(*) FROM event_registrations WHERE status = 'registered'")->fetchColumn(),
        'messages'   => (int)$db->query("SELECT COUNT(*) FROM contact_submissions WHERE status = 'new'")->fetchColumn(),
        'gallery'    => (int)$db->query("SELECT COUNT(*) FROM gallery")->fetchColumn(),
        'committee'  => (int)$db->query("SELECT COUNT(*) FROM committee WHERE active = 1")->fetchColumn(),
        'pending_posts' => 0, // Fallback in case column doesn't exist yet
    ];
}

// === PUBLIC USER FUNCTIONS ===
function get_public_user_by_id($id) {
    $stmt = getDB()->prepare('SELECT id, username, full_name, email, phone, avatar, bio, role, status, created_at FROM public_users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_public_user_by_username($username) {
    $stmt = getDB()->prepare('SELECT * FROM public_users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function get_public_user_by_email($email) {
    $stmt = getDB()->prepare('SELECT * FROM public_users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function register_public_user($data) {
    $stmt = getDB()->prepare('INSERT INTO public_users (username, full_name, email, phone, password, role) VALUES (?,?,?,?,?,?)');
    return $stmt->execute([$data['username'], $data['full_name'], $data['email'], $data['phone'], $data['password'], $data['role'] ?? 'member']);
}

function login_public_user($email, $password) {
    $stmt = getDB()->prepare('SELECT * FROM public_users WHERE email = ? AND status = "active"');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['pub_user_id'] = $user['id'];
        $_SESSION['pub_user_name'] = $user['full_name'];
        $_SESSION['pub_user_role'] = $user['role'];
        return true;
    }
    return false;
}

function is_pub_logged_in() {
    return isset($_SESSION['pub_user_id']);
}

function pub_logout() {
    unset($_SESSION['pub_user_id'], $_SESSION['pub_user_name'], $_SESSION['pub_user_role']);
}

function current_pub_user() {
    if (!is_pub_logged_in()) return null;
    return [
        'id' => $_SESSION['pub_user_id'],
        'name' => $_SESSION['pub_user_name'],
        'role' => $_SESSION['pub_user_role'],
    ];
}

// === POSTS FUNCTIONS ===
function get_posts($limit = 20, $offset = 0) {
    $stmt = getDB()->prepare('SELECT p.*, u.full_name, u.avatar, u.username FROM posts p JOIN public_users u ON p.user_id = u.id WHERE p.status = \'approved\' ORDER BY p.is_pinned DESC, p.created_at DESC LIMIT ? OFFSET ?');
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function get_post_count() {
    return (int)getDB()->query("SELECT COUNT(*) FROM posts WHERE status = 'approved'")->fetchColumn();
}

function create_post($user_id, $content, $image = null) {
    $stmt = getDB()->prepare("INSERT INTO posts (user_id, content, image, status) VALUES (?,?,?,'pending')");
    return $stmt->execute([$user_id, $content, $image]);
}

function delete_post($post_id, $user_id) {
    $stmt = getDB()->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    return $stmt->execute([$post_id, $user_id]);
}

function toggle_like($post_id, $user_id) {
    $check = getDB()->prepare('SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?');
    $check->execute([$post_id, $user_id]);
    if ($check->fetch()) {
        getDB()->prepare('DELETE FROM post_likes WHERE post_id = ? AND user_id = ?')->execute([$post_id, $user_id]);
        getDB()->prepare('UPDATE posts SET likes_count = likes_count - 1 WHERE id = ?')->execute([$post_id]);
        return false; // unliked
    } else {
        getDB()->prepare('INSERT INTO post_likes (post_id, user_id) VALUES (?,?)')->execute([$post_id, $user_id]);
        getDB()->prepare('UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?')->execute([$post_id]);
        return true; // liked
    }
}

function user_liked_post($post_id, $user_id) {
    $stmt = getDB()->prepare('SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?');
    $stmt->execute([$post_id, $user_id]);
    return $stmt->fetch() ? true : false;
}

function get_all_posts_admin() {
    return getDB()->query('SELECT p.*, u.full_name, u.username, u.email FROM posts p JOIN public_users u ON p.user_id = u.id ORDER BY p.created_at DESC')->fetchAll();
}

// === VOLUNTEER FUNCTIONS ===
function get_volunteers($active_only = true) {
    $sql = 'SELECT * FROM volunteers';
    if ($active_only) $sql .= ' WHERE status = "active"';
    $sql .= ' ORDER BY work_count DESC';
    return getDB()->query($sql)->fetchAll();
}

function get_volunteer_by_id($id) {
    $stmt = getDB()->prepare('SELECT * FROM volunteers WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_all_volunteers_admin() {
    return getDB()->query('SELECT * FROM volunteers ORDER BY id DESC')->fetchAll();
}

function register_volunteer_for_event($volunteer_id, $event_id, $message = '') {
    // Check if already registered
    $check = getDB()->prepare('SELECT id FROM volunteer_registrations WHERE volunteer_id = ? AND event_id = ? AND status IN ("pending","approved")');
    $check->execute([$volunteer_id, $event_id]);
    if ($check->fetch()) return false;
    $stmt = getDB()->prepare('INSERT INTO volunteer_registrations (volunteer_id, event_id, message) VALUES (?,?,?)');
    return $stmt->execute([$volunteer_id, $event_id, $message]);
}

function get_volunteer_registrations_admin($status = null) {
    $sql = 'SELECT vr.*, v.name as volunteer_name, e.title as event_title FROM volunteer_registrations vr JOIN volunteers v ON vr.volunteer_id = v.id LEFT JOIN events e ON vr.event_id = e.id';
    $params = [];
    if ($status) { $sql .= ' WHERE vr.status = ?'; $params[] = $status; }
    $sql .= ' ORDER BY vr.created_at DESC';
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// === COMPLAINT FUNCTIONS ===
function get_complaints($status = null) {
    $sql = 'SELECT c.*, u.full_name, u.avatar FROM complaints c JOIN public_users u ON c.user_id = u.id';
    $params = [];
    if ($status) { $sql .= ' WHERE c.status = ?'; $params[] = $status; }
    $sql .= ' ORDER BY c.created_at DESC';
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_complaint_by_id($id) {
    $stmt = getDB()->prepare('SELECT c.*, u.full_name, u.avatar, u.username FROM complaints c JOIN public_users u ON c.user_id = u.id WHERE c.id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_complaint_replies($complaint_id) {
    $stmt = getDB()->prepare('SELECT r.*, u.full_name, u.avatar, u.username FROM complaint_replies r JOIN public_users u ON r.user_id = u.id WHERE r.complaint_id = ? ORDER BY r.created_at ASC');
    $stmt->execute([$complaint_id]);
    return $stmt->fetchAll();
}

function create_complaint($user_id, $title, $description, $category) {
    $stmt = getDB()->prepare('INSERT INTO complaints (user_id, title, description, category) VALUES (?,?,?,?)');
    return $stmt->execute([$user_id, $title, $description, $category]);
}

function create_complaint_reply($complaint_id, $user_id, $message) {
    $stmt = getDB()->prepare('INSERT INTO complaint_replies (complaint_id, user_id, message) VALUES (?,?,?)');
    return $stmt->execute([$complaint_id, $user_id, $message]);
}

function get_all_complaints_admin() {
    return getDB()->query('SELECT c.*, u.full_name, u.username, u.email FROM complaints c JOIN public_users u ON c.user_id = u.id ORDER BY c.created_at DESC')->fetchAll();
}


// === ADMIN POST APPROVAL FUNCTIONS ===
function get_all_posts_admin_with_status() {
    return getDB()->query('SELECT p.*, u.full_name, u.username, u.email FROM posts p JOIN public_users u ON p.user_id = u.id ORDER BY p.created_at DESC')->fetchAll();
}

function approve_post($post_id) {
    getDB()->prepare("UPDATE posts SET status = 'approved' WHERE id = ?")->execute([$post_id]);
}

function reject_post($post_id) {
    getDB()->prepare("UPDATE posts SET status = 'rejected' WHERE id = ?")->execute([$post_id]);
}

function get_pending_posts_count() {
    return (int)getDB()->query("SELECT COUNT(*) FROM posts WHERE status = 'pending'")->fetchColumn();
}

function get_all_public_users_admin() {
    return getDB()->query('SELECT id, username, full_name, email, phone, role, status, created_at FROM public_users ORDER BY created_at DESC')->fetchAll();
}
