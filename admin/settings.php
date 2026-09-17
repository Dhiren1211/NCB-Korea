<?php
$adminTitle = 'Site Settings';
include 'includes/header.php';

// --- POST: UPDATE ALL SETTINGS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?');

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $settingKey = substr($key, 8); // remove 'setting_' prefix
            $stmt->execute([$settingKey, trim($value), trim($value)]);
        }
    }
    set_flash('success', 'Settings updated successfully.');
    redirect('settings.php');
}

$settings = get_all_settings();

/**
 * Helper to render an input field
 */
function setting_field($key, $label, $settings, $type = 'text', $placeholder = '') {
    $val = $settings[$key] ?? '';
    $name = 'setting_' . $key;
    echo '<div class="form-group">';
    echo '<label for="' . $name . '">' . $label . '</label>';
    if ($type === 'textarea') {
        echo '<textarea id="' . $name . '" name="' . $name . '" rows="3" placeholder="' . e($placeholder) . '">' . e($val) . '</textarea>';
    } else {
        echo '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . e($val) . '" placeholder="' . e($placeholder) . '">';
    }
    echo '</div>';
}
?>

<div class="page-header">
    <h2>Site Settings</h2>
</div>

<form method="POST" action="settings.php">

<!-- Site Info -->
<div class="card">
    <div class="card-header">Site Information</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('site_name', 'Site Name', $settings); ?>
            <?php setting_field('site_short_name', 'Short Name', $settings, 'text', 'e.g. NCB'); ?>
            <?php setting_field('established', 'Established Year', $settings, 'text', 'e.g. 2013'); ?>
            <?php setting_field('tagline', 'Tagline', $settings); ?>
            <?php setting_field('description', 'Description', $settings, 'textarea'); ?>
        </div>
    </div>
</div>

<!-- Hero -->
<div class="card">
    <div class="card-header">Hero Section</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('hero_badge', 'Hero Badge', $settings, 'text', 'e.g. WELCOME TO NCB'); ?>
            <?php setting_field('hero_description', 'Hero Description', $settings, 'textarea'); ?>
        </div>
    </div>
</div>

<!-- About -->
<div class="card">
    <div class="card-header">About Section</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('about_title', 'About Title', $settings); ?>
            <?php setting_field('about_description', 'About Description', $settings, 'textarea'); ?>
            <?php setting_field('about_history', 'History', $settings, 'textarea'); ?>
            <?php setting_field('about_mission', 'Mission', $settings, 'textarea'); ?>
            <?php setting_field('about_vision', 'Vision', $settings, 'textarea'); ?>
            <?php setting_field('about_objectives', 'Objectives', $settings, 'textarea'); ?>
        </div>
    </div>
</div>

<!-- Contact -->
<div class="card">
    <div class="card-header">Contact Information</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('contact_address', 'Address', $settings, 'textarea'); ?>
            <?php setting_field('contact_phone', 'Phone', $settings); ?>
            <?php setting_field('contact_email', 'Email', $settings, 'email'); ?>
            <?php setting_field('contact_hours', 'Hours', $settings); ?>
            <?php setting_field('emergency_phone', 'Emergency Phone', $settings); ?>
        </div>
    </div>
</div>

<!-- Social -->
<div class="card">
    <div class="card-header">Social Media</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('social_facebook', 'Facebook URL', $settings, 'url'); ?>
            <?php setting_field('social_instagram', 'Instagram URL', $settings, 'url'); ?>
            <?php setting_field('social_youtube', 'YouTube URL', $settings, 'url'); ?>
            <?php setting_field('social_twitter', 'Twitter / X URL', $settings, 'url'); ?>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="card">
    <div class="card-header">Statistics</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('stat_members', 'Members Count', $settings, 'text', 'e.g. 450'); ?>
            <?php setting_field('stat_events', 'Events Count', $settings, 'text', 'e.g. 120'); ?>
            <?php setting_field('stat_volunteers', 'Volunteers Count', $settings, 'text', 'e.g. 60'); ?>
            <?php setting_field('stat_years', 'Years Active', $settings, 'text', 'e.g. 12'); ?>
        </div>
    </div>
</div>

<!-- Join CTA -->
<div class="card">
    <div class="card-header">Join CTA Section</div>
    <div class="card-body padded">
        <div class="form-grid">
            <?php setting_field('join_badge', 'Join Badge', $settings, 'text', 'e.g. JOIN 450+ MEMBERS'); ?>
            <?php setting_field('join_title', 'Join Title', $settings); ?>
            <?php setting_field('join_description', 'Join Description', $settings, 'textarea'); ?>
        </div>
    </div>
</div>

<div class="form-actions" style="margin-top:24px;">
    <button type="submit" class="btn btn-primary">Save All Settings</button>
</div>

</form>

</div><!-- end admin-main -->
</body>
</html>