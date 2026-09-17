<?php
$adminTitle = 'Downloads';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM downloads WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Download deleted successfully.');
    redirect('downloads.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$item = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = getDB()->prepare('SELECT * FROM downloads WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch();
    if (!$item) { set_flash('error', 'Download not found.'); redirect('downloads.php'); }
}

// --- POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $filePath = trim($_POST['file_path'] ?? '');
    $fileType = trim($_POST['file_type'] ?? 'PDF');
    $fileSize = trim($_POST['file_size'] ?? '0 KB');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '' || $filePath === '') {
        set_flash('error', 'Title and File Path are required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            $sql = "UPDATE downloads SET title=?, description=?, file_path=?, file_type=?, file_size=?, sort_order=?, active=? WHERE id=?";
            getDB()->prepare($sql)->execute([$title, $description, $filePath, $fileType, $fileSize, $sortOrder, $active, $editId]);
            set_flash('success', 'Download updated successfully.');
        } else {
            $sql = "INSERT INTO downloads (title, description, file_path, file_type, file_size, sort_order, active) VALUES (?,?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$title, $description, $filePath, $fileType, $fileSize, $sortOrder, $active]);
            set_flash('success', 'Download created successfully.');
        }
        redirect('downloads.php');
    }
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $item);
    $v = $isEdit ? $item : ['title'=>'','description'=>'','file_path'=>'','file_type'=>'PDF','file_size'=>'0 KB','sort_order'=>0,'active'=>1];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit Download' : 'Add Download'; ?></h2>
    <a href="downloads.php" class="btn btn-secondary">&larr; Back to Downloads</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="downloads.php">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo e($v['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>File Path *</label>
                    <input type="text" name="file_path" value="<?php echo e($v['file_path']); ?>" required>
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?php echo e($v['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>File Type</label>
                    <select name="file_type">
                        <?php foreach (['PDF','DOC','DOCX','XLS','XLSX','ZIP','IMAGE','OTHER'] as $ft): ?>
                        <option value="<?php echo $ft; ?>" <?php echo ($v['file_type']===$ft)?'selected':''; ?>><?php echo $ft; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>File Size</label>
                    <input type="text" name="file_size" value="<?php echo e($v['file_size']); ?>" placeholder="e.g. 1.2 MB">
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
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Download' : 'Create Download'; ?></button>
                <a href="downloads.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $downloads = get_all_downloads(); ?>
<div class="page-header">
    <h2>Downloads</h2>
    <a href="downloads.php?action=add" class="btn btn-primary">+ Add Download</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Title</th><th>Type</th><th>Size</th><th>Sort</th><th>Active</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($downloads)): ?>
                <tr><td colspan="7" class="empty-state">No downloads found.</td></tr>
            <?php else: ?>
                <?php foreach ($downloads as $d): ?>
                <tr>
                    <td><?php echo $d['id']; ?></td>
                    <td><strong><?php echo e($d['title']); ?></strong></td>
                    <td><span class="badge badge-info"><?php echo e($d['file_type']); ?></span></td>
                    <td><?php echo e($d['file_size']); ?></td>
                    <td><?php echo $d['sort_order']; ?></td>
                    <td><?php echo $d['active'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'; ?></td>
                    <td>
                        <a href="downloads.php?action=edit&id=<?php echo $d['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="downloads.php?action=delete&id=<?php echo $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this download?')">Delete</a>
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