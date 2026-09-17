<?php
$adminTitle = 'Events';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $stmt = getDB()->prepare('DELETE FROM events WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    set_flash('success', 'Event deleted successfully.');
    redirect('events.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$event = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $event = get_event_by_id($_GET['id']);
    if (!$event) { set_flash('error', 'Event not found.'); redirect('events.php'); }
}

// --- POST (Insert / Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = $_POST['description'] ?? '';
    $highlights = trim($_POST['highlights'] ?? '');
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $day = $_POST['day'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $category = $_POST['category'] ?? 'CULTURAL';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $organizer = trim($_POST['organizer'] ?? '');
    $capacity = (int)($_POST['capacity'] ?? 0);
    $fee = trim($_POST['fee'] ?? 'Free');
    $regDeadline = $_POST['registration_deadline'] ?? '';
    $contactPerson = trim($_POST['contact_person'] ?? '');
    $contactPhone = trim($_POST['contact_phone'] ?? '');
    $status = $_POST['status'] ?? 'upcoming';
    $image = trim($_POST['image'] ?? 'assets/images/demo.jpg');

    // Auto-generate slug if empty
    if ($slug === '' && $title !== '') {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($title)));
    }
    $highlightsJson = json_encode(array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $highlights)))), JSON_UNESCAPED_UNICODE);

    if ($title === '' || $date === '') {
        set_flash('error', 'Title and Date are required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            // Update
            $sql = "UPDATE events SET title=?, slug=?, description=?, highlights=?, date=?, time=?, day=?, location=?, category=?, featured=?, organizer=?, capacity=?, fee=?, registration_deadline=?, contact_person=?, contact_phone=?, status=?, image=? WHERE id=?";
            getDB()->prepare($sql)->execute([$title, $slug, $description, $highlightsJson, $date, $time, $day, $location, $category, $featured, $organizer, $capacity, $fee, $regDeadline ?: null, $contactPerson, $contactPhone, $status, $image, $editId]);
            set_flash('success', 'Event updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO events (title, slug, description, highlights, date, time, day, location, category, featured, organizer, capacity, fee, registration_deadline, contact_person, contact_phone, status, image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$title, $slug, $description, $highlightsJson, $date, $time, $day, $location, $category, $featured, $organizer, $capacity, $fee, $regDeadline ?: null, $contactPerson, $contactPhone, $status, $image]);
            set_flash('success', 'Event created successfully.');
        }
        redirect('events.php');
    }
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $event);
    $highlightValues = $isEdit ? json_decode($event['highlights'] ?? '[]', true) : [];
    $highlightText = is_array($highlightValues) ? implode("\n", $highlightValues) : '';
    $v = $isEdit ? $event : ['title'=>'','slug'=>'','description'=>'','highlights'=>'','date'=>'','time'=>'','day'=>'','location'=>'','category'=>'CULTURAL','featured'=>0,'organizer'=>'','capacity'=>0,'fee'=>'Free','registration_deadline'=>'','contact_person'=>'','contact_phone'=>'','status'=>'upcoming','image'=>'assets/images/demo.jpg'];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit Event' : 'Add New Event'; ?></h2>
    <a href="events.php" class="btn btn-secondary">&larr; Back to Events</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="events.php" id="eventForm">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo e($v['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="<?php echo e($v['slug']); ?>" placeholder="auto-generated if empty">
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo e($v['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" value="<?php echo e($v['date']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <input type="text" name="time" value="<?php echo e($v['time']); ?>" placeholder="e.g. 6:00 PM KST">
                </div>
                <div class="form-group">
                    <label>Day</label>
                    <input type="text" name="day" value="<?php echo e($v['day']); ?>" placeholder="e.g. Saturday">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo e($v['location']); ?>">
                </div>
                <div class="form-group full">
                    <label>Highlights</label>
                    <textarea name="highlights" rows="4" placeholder="One highlight per line"><?php echo e($highlightText); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach (['CULTURAL','WORKSHOP','VOLUNTEER','SPORTS','SOCIAL','MEETING'] as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo ($v['category'] === $cat) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <?php foreach (['upcoming','ongoing','completed','cancelled'] as $st): ?>
                        <option value="<?php echo $st; ?>" <?php echo ($v['status'] === $st) ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Organizer</label>
                    <input type="text" name="organizer" value="<?php echo e($v['organizer']); ?>">
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" value="<?php echo e($v['capacity']); ?>" min="0">
                </div>
                <div class="form-group">
                    <label>Fee</label>
                    <input type="text" name="fee" value="<?php echo e($v['fee']); ?>">
                </div>
                <div class="form-group">
                    <label>Registration Deadline</label>
                    <input type="date" name="registration_deadline" value="<?php echo e($v['registration_deadline']); ?>">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" value="<?php echo e($v['contact_person']); ?>">
                </div>
                <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="contact_phone" value="<?php echo e($v['contact_phone']); ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <label class="checkbox-label"><input type="checkbox" name="featured" value="1" <?php echo $v['featured'] ? 'checked' : ''; ?>> Featured Event</label>
                </div>
                <div class="form-group full">
                    <label>Event Image</label>
                    <div class="upload-zone" id="eventImageZone">
                        <div class="upload-placeholder" id="eventImagePlaceholder">
                            <div class="upload-icon">&#128247;</div>
                            <p>Click or drag image here to upload</p>
                            <small>JPG, PNG, GIF, WebP &mdash; Max 5MB</small>
                        </div>
                        <img id="eventImagePreview" class="upload-preview" src="" alt="" style="display:none;">
                        <input type="hidden" name="image" id="eventImageInput" value="<?php echo e($v['image']); ?>">
                        <input type="file" name="file" id="eventImageFile" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-danger upload-remove-btn" id="eventImageRemove" style="display:none;" onclick="removeImage('event')">Remove</button>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Event' : 'Create Event'; ?></button>
                <a href="events.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php
// --- LIST VIEW ---
$events = get_all_events();
?>
<div class="page-header">
    <h2>Events</h2>
    <a href="events.php?action=add" class="btn btn-primary">+ Add Event</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Image</th><th>Title</th><th>Date</th><th>Category</th><th>Featured</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($events)): ?>
                <tr><td colspan="7" class="empty-state">No events found.</td></tr>
            <?php else: ?>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td><img src="../<?php echo e($ev['image']); ?>" class="img-thumb" alt=""></td>
                    <td><strong><?php echo e($ev['title']); ?></strong></td>
                    <td><?php echo format_date($ev['date']); ?></td>
                    <td><?php echo e($ev['category']); ?></td>
                    <td><?php echo $ev['featured'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'; ?></td>
                    <td>
                        <?php
                        $statusColors = ['upcoming'=>'info','ongoing'=>'warning','completed'=>'success','cancelled'=>'danger'];
                        $c = $statusColors[$ev['status']] ?? 'secondary';
                        ?>
                        <span class="badge badge-<?php echo $c; ?>"><?php echo ucfirst($ev['status']); ?></span>
                    </td>
                    <td>
                        <a href="events.php?action=edit&id=<?php echo $ev['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="events.php?action=delete&id=<?php echo $ev['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

</div><!-- end admin-main -->
</body>
</html>
