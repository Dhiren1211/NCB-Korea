<?php
$adminTitle = 'Volunteers';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM volunteers WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Volunteer deleted successfully.');
    redirect('volunteers.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$volunteer = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = getDB()->prepare('SELECT * FROM volunteers WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $volunteer = $stmt->fetch();
    if (!$volunteer) { set_flash('error', 'Volunteer not found.'); redirect('volunteers.php'); }
}

// --- POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $bio         = trim($_POST['bio'] ?? '');
    $message     = trim($_POST['message'] ?? '');
    $photo       = trim($_POST['photo'] ?? 'assets/images/demo.jpg');
    $work_count  = (int)($_POST['work_count'] ?? 0);
    $total_hours = (float)($_POST['total_hours'] ?? 0);
    $joined_date = $_POST['joined_date'] ?? date('Y-m-d');
    $status      = $_POST['status'] ?? 'active';

    if ($name === '') {
        set_flash('error', 'Name is required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            $sql = "UPDATE volunteers SET name=?, bio=?, message=?, photo=?, work_count=?, total_hours=?, joined_date=?, status=? WHERE id=?";
            getDB()->prepare($sql)->execute([$name, $bio, $message, $photo, $work_count, $total_hours, $joined_date, $status, $editId]);
            set_flash('success', 'Volunteer updated successfully.');
        } else {
            $sql = "INSERT INTO volunteers (name, bio, message, photo, work_count, total_hours, joined_date, status) VALUES (?,?,?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$name, $bio, $message, $photo, $work_count, $total_hours, $joined_date, $status]);
            set_flash('success', 'Volunteer created successfully.');
        }
        redirect('volunteers.php');
    }
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $volunteer);
    $v = $isEdit ? $volunteer : ['name'=>'','bio'=>'','message'=>'','photo'=>'assets/images/demo.jpg','work_count'=>0,'total_hours'=>0,'joined_date'=>date('Y-m-d'),'status'=>'active'];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit Volunteer' : 'Add Volunteer'; ?></h2>
    <a href="volunteers.php" class="btn btn-secondary">&larr; Back to Volunteers</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="volunteers.php" id="volunteerForm">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="<?php echo e($v['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active" <?php echo ($v['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($v['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Work Count</label>
                    <input type="number" name="work_count" value="<?php echo e($v['work_count']); ?>" min="0">
                </div>
                <div class="form-group">
                    <label>Total Hours</label>
                    <input type="number" name="total_hours" value="<?php echo e($v['total_hours']); ?>" min="0" step="0.5">
                </div>
                <div class="form-group">
                    <label>Joined Date</label>
                    <input type="date" name="joined_date" value="<?php echo e($v['joined_date']); ?>">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                </div>
                <div class="form-group full">
                    <label>Bio</label>
                    <textarea name="bio" rows="3"><?php echo e($v['bio']); ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Message / Motivation</label>
                    <textarea name="message" rows="3"><?php echo e($v['message']); ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Photo</label>
                    <div class="upload-zone" id="volunteerImageZone">
                        <div class="upload-placeholder" id="volunteerImagePlaceholder">
                            <div class="upload-icon">&#128100;</div>
                            <p>Click or drag photo here to upload</p>
                            <small>JPG, PNG, GIF, WebP &mdash; Max 5MB</small>
                        </div>
                        <img id="volunteerImagePreview" class="upload-preview upload-preview-round" src="" alt="" style="display:none;">
                        <input type="hidden" name="photo" id="volunteerImageInput" value="<?php echo e($v['photo']); ?>">
                        <input type="file" name="file" id="volunteerImageFile" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-danger upload-remove-btn" id="volunteerImageRemove" style="display:none;" onclick="removeImage('volunteer')">Remove</button>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Volunteer' : 'Create Volunteer'; ?></button>
                <a href="volunteers.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $volunteers = get_all_volunteers_admin(); ?>
<div class="page-header">
    <h2>Volunteers</h2>
    <a href="volunteers.php?action=add" class="btn btn-primary">+ Add Volunteer</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Photo</th><th>Name</th><th>Work Count</th><th>Hours</th><th>Joined</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($volunteers)): ?>
                <tr><td colspan="8" class="empty-state">No volunteers found.</td></tr>
            <?php else: ?>
                <?php foreach ($volunteers as $vol): ?>
                <tr>
                    <td><?php echo $vol['id']; ?></td>
                    <td><img src="../<?php echo e($vol['photo']); ?>" class="img-thumb" alt=""></td>
                    <td><strong><?php echo e($vol['name']); ?></strong></td>
                    <td><?php echo (int)$vol['work_count']; ?></td>
                    <td><?php echo (float)$vol['total_hours']; ?></td>
                    <td><?php echo format_date($vol['joined_date']); ?></td>
                    <td><?php echo $vol['status'] === 'active' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?></td>
                    <td>
                        <a href="volunteers.php?action=edit&id=<?php echo $vol['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="volunteers.php?action=delete&id=<?php echo $vol['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this volunteer?')">Delete</a>
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
