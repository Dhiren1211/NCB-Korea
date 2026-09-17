<?php
/**
 * NCB Admin - Header & Layout
 * Include this at the top of every admin page (except login.php)
 */
ob_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$user = current_user();
$currentPage = basename($_SERVER['PHP_SELF']);
$pageParam = $_GET['page'] ?? '';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($adminTitle ?? 'NCB Admin'); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            font-size: 14px;
            line-height: 1.5;
        }
        a { color: #0f3460; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Top Bar */
        .admin-topbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 56px;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .admin-topbar .logo {
            display: flex; align-items: center; gap: 12px;
            color: #fff; font-weight: 700; font-size: 18px;
        }
        .admin-topbar .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #e94560, #c23152);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
        }
        .admin-topbar .logo span { font-size: 12px; font-weight: 400; opacity: 0.7; margin-left: 4px; }
        .admin-topbar .user-info {
            display: flex; align-items: center; gap: 16px; color: #fff;
        }
        .admin-topbar .user-info .name { font-size: 13px; }
        .admin-topbar .user-info .role {
            font-size: 11px; background: rgba(233,69,96,0.3);
            padding: 2px 10px; border-radius: 12px; color: #e94560;
        }
        .admin-topbar .user-info a {
            color: rgba(255,255,255,0.7); font-size: 13px;
        }
        .admin-topbar .user-info a:hover { color: #fff; text-decoration: none; }

        /* Sidebar */
        .admin-sidebar {
            position: fixed; top: 56px; left: 0; bottom: 0;
            width: 250px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            z-index: 999;
            padding: 16px 0;
        }
        .admin-sidebar a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 24px;
            color: #444; font-size: 14px;
            transition: all 0.15s;
            text-decoration: none;
            border-left: 3px solid transparent;
        }
        .admin-sidebar a:hover {
            background: #f5f5f5;
            color: #1a1a2e;
            text-decoration: none;
        }
        .admin-sidebar a.active {
            background: #f0f4ff;
            color: #0f3460;
            border-left-color: #e94560;
            font-weight: 600;
        }
        .admin-sidebar a .icon {
            width: 20px; text-align: center; font-size: 15px;
        }
        .admin-sidebar .divider {
            height: 1px; background: #eee; margin: 12px 24px;
        }

        /* Main Content */
        .admin-main {
            margin-left: 250px;
            margin-top: 56px;
            padding: 28px;
            min-height: calc(100vh - 56px);
        }

        /* Flash Messages */
        .flash { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
        .flash-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .flash-error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

        /* Page Header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h2 { font-size: 22px; font-weight: 700; color: #1a1a2e; }

        /* Buttons */
        .btn {
            display: inline-block; padding: 9px 18px;
            border-radius: 6px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            transition: opacity 0.2s; text-align: center;
        }
        .btn:hover { opacity: 0.85; text-decoration: none; }
        .btn-primary { background: #0f3460; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-info { background: #2563eb; color: #fff; }
        .btn-secondary { background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* Stat Cards */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 16px; margin-bottom: 28px;
        }
        .stat-card {
            background: #fff; border-radius: 10px; padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex; align-items: center; gap: 16px;
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-card .stat-info h3 { font-size: 24px; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-info p { font-size: 12px; color: #888; margin-top: 2px; }
        .bg-blue { background: #eff6ff; color: #2563eb; }
        .bg-green { background: #f0fdf4; color: #16a34a; }
        .bg-amber { background: #fffbeb; color: #d97706; }
        .bg-red { background: #fef2f2; color: #dc2626; }
        .bg-purple { background: #faf5ff; color: #9333ea; }
        .bg-indigo { background: #eef2ff; color: #4f46e5; }
        .bg-pink { background: #fdf2f8; color: #db2777; }
        .bg-teal { background: #f0fdfa; color: #0d9488; }

        /* Tables */
        .card {
            background: #fff; border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            margin-bottom: 24px; overflow: hidden;
        }
        .card-header {
            padding: 16px 20px; border-bottom: 1px solid #f0f0f0;
            font-weight: 600; font-size: 15px; color: #1a1a2e;
        }
        .card-body { padding: 0; }
        .card-body.padded { padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td {
            padding: 11px 16px; text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        table th {
            background: #fafafa; font-weight: 600;
            font-size: 12px; text-transform: uppercase;
            color: #666; letter-spacing: 0.5px;
        }
        table tr:hover { background: #f9fafb; }
        table td { font-size: 13px; }

        /* Forms */
        .form-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #444; margin-bottom: 6px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 10px 12px;
            border: 2px solid #e0e0e0; border-radius: 6px;
            font-size: 14px; font-family: inherit;
            outline: none; transition: border-color 0.2s;
            background: #fff;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0f3460;
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group input[type="checkbox"] {
            width: auto; margin-right: 6px; }
        .form-group .checkbox-label {
            display: flex; align-items: center; font-weight: 400; cursor: pointer;
        }
        .form-actions { margin-top: 20px; display: flex; gap: 10px; }

        /* Badges */
        .badge {
            display: inline-block; padding: 3px 10px;
            border-radius: 12px; font-size: 11px; font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f3f4f6; color: #4b5563; }

        /* Misc */
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .mt-16 { margin-top: 16px; }
        .mb-16 { margin-bottom: 16px; }
        .empty-state { padding: 40px 20px; text-align: center; color: #999; }
        .filter-bar { display: flex; gap: 8px; margin-bottom: 16px; align-items: center; flex-wrap: wrap; }
        .filter-bar a { padding: 6px 14px; border-radius: 6px; font-size: 13px; background: #fff; border: 1px solid #e0e0e0; }
        .filter-bar a.active { background: #0f3460; color: #fff; border-color: #0f3460; }
        .filter-bar a:hover { text-decoration: none; }
        .img-thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; background: #f0f0f0; }

        /* Image Upload Zone */
        .upload-zone {
            position: relative;
            border: 2px dashed #d0d5dd;
            border-radius: 10px;
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafbfc;
            overflow: hidden;
        }
        .upload-zone:hover, .upload-zone.drag-over {
            border-color: #0f3460;
            background: #f0f4ff;
        }
        .upload-zone .upload-placeholder {
            text-align: center;
            padding: 24px;
            color: #666;
        }
        .upload-zone .upload-icon {
            font-size: 36px;
            margin-bottom: 8px;
            opacity: 0.5;
        }
        .upload-zone .upload-placeholder p {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .upload-zone .upload-placeholder small {
            font-size: 12px;
            color: #999;
        }
        .upload-preview {
            max-width: 100%;
            max-height: 240px;
            object-fit: contain;
            border-radius: 6px;
        }
        .upload-preview-round {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
        }
        .upload-remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            font-size: 11px;
            padding: 4px 10px;
        }
        .upload-loading {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.85);
            z-index: 3;
        }
        .upload-loading::after {
            content: '';
            width: 32px; height: 32px;
            border: 3px solid #e0e0e0;
            border-top-color: #0f3460;
            border-radius: 50%;
            animation: uploadSpin 0.6s linear infinite;
        }
        @keyframes uploadSpin {
            to { transform: rotate(360deg); }
        }
        .upload-error {
            position: absolute;
            bottom: 8px;
            left: 8px;
            right: 8px;
            background: #fee2e2;
            color: #dc2626;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 2;
        }
    
        /* === ADMIN RESPONSIVE === */
        @media (max-width: 1024px) {
            .admin-sidebar { width: 220px; }
            .admin-main { margin-left: 220px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .form-group.full { grid-column: 1; }
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                left: -260px;
                transition: left 0.3s ease;
                z-index: 1001;
            }
            .admin-sidebar.admin-sidebar--open {
                left: 0;
                box-shadow: 4px 0 20px rgba(0,0,0,0.2);
            }
            .admin-main {
                margin-left: 0;
                padding: 20px 16px;
            }
            .admin-topbar { padding: 0 16px; }
            .admin-topbar .logo span { display: none; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            table { font-size: 12px; }
            table th, table td { padding: 8px 10px; }
            .img-thumb { width: 45px; height: 30px; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .page-header h2 { font-size: 18px; }
            .admin-mobile-toggle {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 36px; height: 36px;
                background: rgba(255,255,255,0.1);
                border: none;
                border-radius: 8px;
                color: #fff;
                font-size: 20px;
                cursor: pointer;
                margin-right: 12px;
            }
            .admin-sidebar-overlay {
                display: none;
                position: fixed; inset: 0; background: rgba(0,0,0,0.4);
                z-index: 1000;
            }
            .admin-sidebar-overlay.admin-sidebar-overlay--visible { display: block; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .card-header, .card-body.padded { padding: 12px; }
            .btn { padding: 8px 14px; font-size: 12px; }
        }
        .admin-mobile-toggle { display: none; }

    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var folderMap = { event:'events', news:'news', gallery:'gallery', committee:'committee', volunteer:'committee' };
        document.querySelectorAll('.upload-zone').forEach(function(zone) {
            var id = zone.id.replace('ImageZone','');
            var fileInput = document.getElementById(id+'ImageFile');
            var hiddenInput = document.getElementById(id+'ImageInput');
            var preview = document.getElementById(id+'ImagePreview');
            var placeholder = document.getElementById(id+'ImagePlaceholder');
            var removeBtn = document.getElementById(id+'ImageRemove');
            var folder = folderMap[id] || 'general';
            if (!fileInput || !hiddenInput) return;
            var existingPath = hiddenInput.value;
            if (existingPath && existingPath !== 'assets/images/demo.jpg') {
                preview.src = '../' + existingPath;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
                if (removeBtn) removeBtn.style.display = 'inline-block';
            }
            zone.addEventListener('click', function(e) {
                if (e.target.closest('.upload-remove-btn') || e.target.closest('.upload-loading')) return;
                fileInput.click();
            });
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) doUpload(id, this.files[0], folder);
            });
            zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('drag-over'); });
            zone.addEventListener('dragleave', function(e) { e.preventDefault(); zone.classList.remove('drag-over'); });
            zone.addEventListener('drop', function(e) {
                e.preventDefault(); zone.classList.remove('drag-over');
                if (e.dataTransfer.files && e.dataTransfer.files[0]) doUpload(id, e.dataTransfer.files[0], folder);
            });
        });
        function doUpload(id, file, folder) {
            var zone = document.getElementById(id+'ImageZone');
            var hiddenInput = document.getElementById(id+'ImageInput');
            var preview = document.getElementById(id+'ImagePreview');
            var placeholder = document.getElementById(id+'ImagePlaceholder');
            var removeBtn = document.getElementById(id+'ImageRemove');
            var allowed = ['image/jpeg','image/png','image/gif','image/webp','image/svg+xml'];
            if (allowed.indexOf(file.type) === -1) { showErr(zone,'Invalid file type. Use JPG, PNG, GIF, or WebP.'); return; }
            if (file.size > 5*1024*1024) { showErr(zone,'File too large. Max 5MB.'); return; }
            clearErr(zone);
            var loader = document.createElement('div'); loader.className='upload-loading'; loader.id=id+'Uploading'; zone.appendChild(loader);
            var fd = new FormData(); fd.append('image', file); fd.append('folder', folder);
            fetch('../actions/upload.php', { method:'POST', body:fd })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                var ld = document.getElementById(id+'Uploading'); if(ld) ld.remove();
                if (data.success) {
                    hiddenInput.value = data.path;
                    preview.src = '../' + data.path;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                    if (removeBtn) removeBtn.style.display = 'inline-block';
                } else { showErr(zone, data.error || 'Upload failed.'); }
            })
            .catch(function() {
                var ld = document.getElementById(id+'Uploading'); if(ld) ld.remove();
                showErr(zone, 'Network error. Please try again.');
            });
        }
        window.removeImage = function(id) {
            var hi = document.getElementById(id+'ImageInput');
            var pv = document.getElementById(id+'ImagePreview');
            var ph = document.getElementById(id+'ImagePlaceholder');
            var rb = document.getElementById(id+'ImageRemove');
            hi.value = 'assets/images/demo.jpg';
            pv.src = ''; pv.style.display = 'none';
            if (ph) ph.style.display = 'block';
            if (rb) rb.style.display = 'none';
            clearErr(document.getElementById(id+'ImageZone'));
        };
        function showErr(zone, msg) { clearErr(zone); var d=document.createElement('div'); d.className='upload-error'; d.textContent=msg; zone.appendChild(d); }
        function clearErr(zone) { zone.querySelectorAll('.upload-error').forEach(function(e){e.remove();}); }
    });
    </script>
</head>
<body>

<!-- Top Bar -->
<div class="admin-topbar">
    <button class="admin-mobile-toggle" onclick="document.querySelector('.admin-sidebar').classList.toggle('admin-sidebar--open');document.querySelector('.admin-sidebar-overlay').classList.toggle('admin-sidebar-overlay--visible');">&#9776;</button>
    <div class="admin-sidebar-overlay" onclick="document.querySelector('.admin-sidebar').classList.remove('admin-sidebar--open');this.classList.remove('admin-sidebar-overlay--visible');"></div>
    <div class="logo">
        <div class="logo-icon">NCB</div>
        Admin Panel <span>| NCB Website</span>
    </div>
    <div class="user-info">
        <span class="role"><?php echo e(strtoupper($user['role'] ?? '')); ?></span>
        <span class="name"><?php echo e($user['name']); ?></span>
        <a href="?action=logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </div>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout_user();
}
?>

<!-- Sidebar -->
<div class="admin-sidebar">
    <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
        <span class="icon">&#9776;</span> Dashboard
    </a>
    <a href="events.php" class="<?php echo $currentPage === 'events.php' ? 'active' : ''; ?>">
        <span class="icon">&#128197;</span> Events
    </a>
    <a href="news.php" class="<?php echo $currentPage === 'news.php' ? 'active' : ''; ?>">
        <span class="icon">&#128240;</span> News
    </a>
    <a href="gallery.php" class="<?php echo $currentPage === 'gallery.php' ? 'active' : ''; ?>">
        <span class="icon">&#128247;</span> Gallery
    </a>
    <a href="committee.php" class="<?php echo $currentPage === 'committee.php' ? 'active' : ''; ?>">
        <span class="icon">&#128101;</span> Committee
    </a>
    <div class="divider"></div>
    <a href="volunteers.php" class="<?php echo $currentPage === 'volunteers.php' ? 'active' : ''; ?>">
        <span class="icon">&#127891;</span> Volunteers
    </a>
    <a href="posts.php" class="<?php echo $currentPage === 'posts.php' ? 'active' : ''; ?>">
        <span class="icon">&#128172;</span> Community Posts
        <?php try { $pp = get_pending_posts_count(); if ($pp > 0): ?>
        <span style="background:#dc2626;color:#fff;font-size:10px;padding:1px 7px;border-radius:10px;margin-left:auto;"><?php echo $pp; ?></span>
        <?php endif; } catch(Exception $e) {} ?>
    </a>
    <a href="complaints.php" class="<?php echo $currentPage === 'complaints.php' ? 'active' : ''; ?>">
        <span class="icon">&#128221;</span> Complaints
    </a>
    <div class="divider"></div>
    <a href="members.php" class="<?php echo $currentPage === 'members.php' ? 'active' : ''; ?>">
        <span class="icon">&#128100;</span> Members
    </a>
    <a href="messages.php" class="<?php echo $currentPage === 'messages.php' ? 'active' : ''; ?>">
        <span class="icon">&#128172;</span> Messages
    </a>
    <div class="divider"></div>
    <a href="downloads.php" class="<?php echo $currentPage === 'downloads.php' ? 'active' : ''; ?>">
        <span class="icon">&#128229;</span> Downloads
    </a>
    <a href="settings.php" class="<?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
        <span class="icon">&#9881;</span> Settings
    </a>
    <?php if (($user['role'] ?? '') === 'super_admin'): ?>
    <div class="divider"></div>
    <a href="users.php" class="<?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
        <span class="icon">&#128274;</span> Admin Users
    </a>
    <?php endif; ?>
    <div class="divider"></div>
    <a href="../index.php" target="_blank">
        <span class="icon">&#8592;</span> Back to Website
    </a>
</div>

<script>document.querySelectorAll('.admin-sidebar a').forEach(function(a){a.addEventListener('click',function(){if(window.innerWidth<=768){document.querySelector('.admin-sidebar').classList.remove('admin-sidebar--open');document.querySelector('.admin-sidebar-overlay').classList.remove('admin-sidebar-overlay--visible');}});});</script>

<!-- Main Content -->
<div class="admin-main">

<?php if ($flash): ?>
    <div class="flash flash-<?php echo e($flash['type']); ?>">
        <?php echo e($flash['message']); ?>
    </div>
<?php endif; ?>
