<?php
$adminTitle = 'Committee';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM committee WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Committee member deleted successfully.');
    redirect('committee.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$member = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = getDB()->prepare('SELECT * FROM committee WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $member = $stmt->fetch();
    if (!$member) { set_flash('error', 'Member not found.'); redirect('committee.php'); }
}

// --- POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $image = trim($_POST['image'] ?? 'assets/images/demo.jpg');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $sinceYear = trim($_POST['since_year'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($name === '' || $role === '') {
        set_flash('error', 'Name and Role are required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            $sql = "UPDATE committee SET name=?, role=?, bio=?, image=?, email=?, phone=?, since_year=?, sort_order=?, active=? WHERE id=?";
            getDB()->prepare($sql)->execute([$name, $role, $bio, $image, $email, $phone, $sinceYear, $sortOrder, $active, $editId]);
            set_flash('success', 'Committee member updated successfully.');
        } else {
            $sql = "INSERT INTO committee (name, role, bio, image, email, phone, since_year, sort_order, active) VALUES (?,?,?,?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$name, $role, $bio, $image, $email, $phone, $sinceYear, $sortOrder, $active]);
            set_flash('success', 'Committee member created successfully.');
        }
        redirect('committee.php');
    }
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $member);
    $v = $isEdit ? $member : ['name'=>'','role'=>'','bio'=>'','image'=>'assets/images/demo.jpg','email'=>'','phone'=>'','since_year'=>'','sort_order'=>0,'active'=>1];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit Committee Member' : 'Add Committee Member'; ?></h2>
    <a href="committee.php" class="btn btn-secondary">&larr; Back to Committee</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="committee.php" id="committeeForm">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="<?php echo e($v['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Role *</label>
                    <input type="text" name="role" value="<?php echo e($v['role']); ?>" required>
                </div>
                <div class="form-group full">
                    <label>Bio</label>
                    <textarea name="bio" rows="4"><?php echo e($v['bio']); ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Photo</label>
                    <div class="upload-zone" id="committeeImageZone">
                        <div class="upload-placeholder" id="committeeImagePlaceholder">
                            <div class="upload-icon">&#128100;</div>
                            <p>Click or drag photo here to upload</p>
                            <small>JPG, PNG, GIF, WebP &mdash; Max 5MB</small>
                        </div>
                        <img id="committeeImagePreview" class="upload-preview upload-preview-round" src="" alt="" style="display:none;">
                        <input type="hidden" name="image" id="committeeImageInput" value="<?php echo e($v['image']); ?>">
                        <input type="file" name="file" id="committeeImageFile" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-danger upload-remove-btn" id="committeeImageRemove" style="display:none;" onclick="removeImage('committee')">Remove</button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo e($v['email']); ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo e($v['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Since Year</label>
                    <input type="text" name="since_year" value="<?php echo e($v['since_year']); ?>" placeholder="e.g. 2023">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="<?php echo e($v['sort_order']); ?>" min="0">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <label class="checkbox-label"><input type="checkbox" name="active" value="1" <?php echo $v['active']?'checked':''; ?>> Active</label>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Member' : 'Create Member'; ?></button>
                <a href="committee.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $committee = get_all_committee(); ?>
<div class="page-header">
    <h2>Committee</h2>
    <a href="committee.php?action=add" class="btn btn-primary">+ Add Member</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Photo</th><th>ID</th><th>Name</th><th>Role</th><th>Email</th><th>Since</th><th>Active</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($committee)): ?>
                <tr><td colspan="8" class="empty-state">No committee members found.</td></tr>
            <?php else: ?>
                <?php foreach ($committee as $c): ?>
                <tr>
                    <td><img src="../<?php echo e($c['image']); ?>" class="img-thumb" alt=""></td>
                    <td><?php echo $c['id']; ?></td>
                    <td><strong><?php echo e($c['name']); ?></strong></td>
                    <td><?php echo e($c['role']); ?></td>
                    <td><?php echo e($c['email']); ?></td>
                    <td><?php echo e($c['since_year']); ?></td>
                    <td><?php echo $c['active'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'; ?></td>
                    <td>
                        <a href="committee.php?action=edit&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="committee.php?action=delete&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this member?')">Delete</a>
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