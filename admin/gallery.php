<?php
$adminTitle = 'Gallery';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $stmt = getDB()->prepare('DELETE FROM gallery WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    set_flash('success', 'Gallery item deleted successfully.');
    redirect('gallery.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$item = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = getDB()->prepare('SELECT * FROM gallery WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch();
    if (!$item) { set_flash('error', 'Item not found.'); redirect('gallery.php'); }
}

// --- POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bulk_upload'])) {
        $title = trim($_POST['title'] ?? '');
        $category = $_POST['category'] ?? 'EVENT';
        $size = $_POST['size'] ?? 'medium';
        $eventDate = $_POST['event_date'] ?? '';
        $images = array_values(array_filter(array_map('trim', $_POST['images'] ?? [])));

        if ($title === '' || empty($images)) {
            set_flash('error', 'Event title and at least one image are required.');
        } else {
            $stmt = getDB()->prepare('INSERT INTO gallery (title, category, image, size, event_date, sort_order) VALUES (?, ?, ?, ?, ?, ?)');
            foreach ($images as $image) {
                $stmt->execute([$title, $category, $image, $size, $eventDate ?: null, 0]);
            }
            set_flash('success', count($images) . ' gallery images created successfully.');
        }
        redirect('gallery.php');
    }

    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? 'EVENT';
    $image = trim($_POST['image'] ?? '');
    $size = $_POST['size'] ?? 'medium';
    $eventDate = $_POST['event_date'] ?? '';
    $sortOrder = (int)($_POST['sort_order'] ?? 0);

    if ($title === '' || $image === '') {
        set_flash('error', 'Title and Image are required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            $sql = "UPDATE gallery SET title=?, category=?, image=?, size=?, event_date=?, sort_order=? WHERE id=?";
            getDB()->prepare($sql)->execute([$title, $category, $image, $size, $eventDate ?: null, $sortOrder, $editId]);
            set_flash('success', 'Gallery item updated successfully.');
        } else {
            $sql = "INSERT INTO gallery (title, category, image, size, event_date, sort_order) VALUES (?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$title, $category, $image, $size, $eventDate ?: null, $sortOrder]);
            set_flash('success', 'Gallery item created successfully.');
        }
        redirect('gallery.php');
    }
}

// --- SHOW FORM ---
if ($action === 'bulk'):
?>
<div class="page-header">
    <h2>Bulk Upload Gallery Images</h2>
    <a href="gallery.php" class="btn btn-secondary">&larr; Back to Gallery</a>
</div>
<div class="card">
    <div class="card-body padded">
        <form method="POST" action="gallery.php" id="bulkGalleryForm">
            <input type="hidden" name="bulk_upload" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach (['FESTIVAL','SPORTS','VOLUNTEER','WORKSHOP','MEETING','EVENT','SOCIAL'] as $cat): ?>
                        <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Size</label>
                    <select name="size">
                        <?php foreach (['large','medium','small'] as $s): ?>
                        <option value="<?php echo $s; ?>"><?php echo ucfirst($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date">
                </div>
                <div class="form-group full">
                    <label>Images *</label>
                    <input type="file" id="bulkGalleryFiles" accept="image/jpeg,image/png,image/gif,image/webp" multiple required>
                    <div id="bulkGalleryStatus" style="margin-top:10px;color:#666;"></div>
                    <div id="bulkGalleryInputs"></div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="bulkGallerySubmit" disabled>Create Gallery Items</button>
                <a href="gallery.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('bulkGalleryFiles').addEventListener('change', async function () {
    var files = Array.from(this.files);
    var status = document.getElementById('bulkGalleryStatus');
    var inputs = document.getElementById('bulkGalleryInputs');
    var submit = document.getElementById('bulkGallerySubmit');
    inputs.innerHTML = '';
    submit.disabled = true;
    var uploaded = 0;
    for (var file of files) {
        if (file.size > 5 * 1024 * 1024) { status.textContent = file.name + ' exceeds 5MB.'; return; }
        status.textContent = 'Uploading ' + (uploaded + 1) + ' of ' + files.length + '...';
        var data = new FormData();
        data.append('image', file);
        data.append('folder', 'gallery');
        try {
            var response = await fetch('../actions/upload.php', { method: 'POST', body: data });
            var result = await response.json();
            if (!result.success) { status.textContent = result.error || 'Upload failed.'; return; }
            var input = document.createElement('input');
            input.type = 'hidden'; input.name = 'images[]'; input.value = result.path;
            inputs.appendChild(input);
            uploaded++;
        } catch (error) { status.textContent = 'Upload failed for ' + file.name + '.'; return; }
    }
    status.textContent = uploaded + ' image(s) ready.';
    submit.disabled = uploaded === 0;
});
</script>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    $isEdit = ($action === 'edit' && $item);
    $v = $isEdit ? $item : ['title'=>'','category'=>'EVENT','image'=>'','size'=>'medium','event_date'=>'','sort_order'=>0];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit Gallery Item' : 'Add Gallery Item'; ?></h2>
    <a href="gallery.php" class="btn btn-secondary">&larr; Back to Gallery</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="gallery.php" id="galleryForm">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" value="<?php echo e($v['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <?php foreach (['FESTIVAL','SPORTS','VOLUNTEER','WORKSHOP','MEETING','EVENT','SOCIAL'] as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo ($v['category']===$cat)?'selected':''; ?>><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Gallery Image *</label>
                    <div class="upload-zone" id="galleryImageZone">
                        <div class="upload-placeholder" id="galleryImagePlaceholder">
                            <div class="upload-icon">&#128247;</div>
                            <p>Click or drag image here to upload</p>
                            <small>JPG, PNG, GIF, WebP &mdash; Max 5MB</small>
                        </div>
                        <img id="galleryImagePreview" class="upload-preview" src="" alt="" style="display:none;">
                        <input type="hidden" name="image" id="galleryImageInput" value="<?php echo e($v['image']); ?>">
                        <input type="file" name="file" id="galleryImageFile" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-danger upload-remove-btn" id="galleryImageRemove" style="display:none;" onclick="removeImage('gallery')">Remove</button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Size</label>
                    <select name="size">
                        <?php foreach (['large','medium','small'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo ($v['size']===$s)?'selected':''; ?>><?php echo ucfirst($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" value="<?php echo e($v['event_date']); ?>">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="<?php echo e($v['sort_order']); ?>" min="0">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Item' : 'Create Item'; ?></button>
                <a href="gallery.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $items = get_all_gallery(); ?>
<div class="page-header">
    <h2>Gallery</h2>
    <a href="gallery.php?action=add" class="btn btn-primary">+ Add Item</a>
    <a href="gallery.php?action=bulk" class="btn btn-info">Bulk Upload</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Image</th><th>ID</th><th>Title</th><th>Category</th><th>Size</th><th>Sort</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr><td colspan="7" class="empty-state">No gallery items found.</td></tr>
            <?php else: ?>
                <?php foreach ($items as $g): ?>
                <tr>
                    <td><img src="../<?php echo e($g['image']); ?>" class="img-thumb" alt=""></td>
                    <td><?php echo $g['id']; ?></td>
                    <td><strong><?php echo e($g['title']); ?></strong></td>
                    <td><?php echo e($g['category']); ?></td>
                    <td><?php echo e($g['size']); ?></td>
                    <td><?php echo $g['sort_order']; ?></td>
                    <td>
                        <a href="gallery.php?action=edit&id=<?php echo $g['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="gallery.php?action=delete&id=<?php echo $g['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this gallery item?')">Delete</a>
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
