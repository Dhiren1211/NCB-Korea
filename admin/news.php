<?php
$adminTitle = 'News';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $stmt = getDB()->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    set_flash('success', 'News article deleted successfully.');
    redirect('news.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$article = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $article = get_news_by_id($_GET['id']);
    if (!$article) { set_flash('error', 'Article not found.'); redirect('news.php'); }
}

// --- POST (Insert / Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $excerpt = trim($_POST['excerpt'] ?? '');
    $author = trim($_POST['author'] ?? 'NCB Media Team');
    $tags = trim($_POST['tags'] ?? '');
    $image = trim($_POST['image'] ?? 'assets/images/demo.jpg');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = $_POST['status'] ?? 'published';

    // Auto-generate slug
    if ($slug === '' && $title !== '') {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($title)));
    }

    // Convert tags to JSON array
    $tagsJson = null;
    if ($tags !== '') {
        $tagsJson = json_encode(array_map('trim', explode(',', $tags)));
    }

    if ($title === '') {
        set_flash('error', 'Title is required.');
    } else {
        $editId = $_POST['edit_id'] ?? '';

        if ($editId !== '') {
            $sql = "UPDATE news SET title=?, slug=?, content=?, excerpt=?, author=?, tags=?, image=?, featured=?, status=? WHERE id=?";
            getDB()->prepare($sql)->execute([$title, $slug, $content, $excerpt, $author, $tagsJson, $image, $featured, $status, $editId]);
            set_flash('success', 'News article updated successfully.');
        } else {
            $sql = "INSERT INTO news (title, slug, content, excerpt, author, tags, image, featured, status) VALUES (?,?,?,?,?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$title, $slug, $content, $excerpt, $author, $tagsJson, $image, $featured, $status]);
            set_flash('success', 'News article created successfully.');
        }
        redirect('news.php');
    }
}

// Parse tags for form
$tagsString = '';
if ($article && $article['tags']) {
    $arr = json_decode($article['tags'], true);
    $tagsString = is_array($arr) ? implode(', ', $arr) : '';
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $article);
    $v = $isEdit ? $article : ['title'=>'','slug'=>'','content'=>'','excerpt'=>'','author'=>'NCB Media Team','tags'=>'','image'=>'assets/images/demo.jpg','featured'=>0,'status'=>'published'];
    if ($isEdit) { $tagsString = ''; $arr = json_decode($v['tags'], true); $tagsString = is_array($arr) ? implode(', ', $arr) : ''; }
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit News' : 'Add New Article'; ?></h2>
    <a href="news.php" class="btn btn-secondary">&larr; Back to News</a>
</div>

<div class="card">
    <div class="card-body padded">
        <form method="POST" action="news.php" id="newsForm">
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
                    <label>Content</label>
                    <textarea name="content" rows="8"><?php echo e($v['content']); ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Excerpt</label>
                    <textarea name="excerpt" rows="2"><?php echo e($v['excerpt']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" value="<?php echo e($v['author']); ?>">
                </div>
                <div class="form-group">
                    <label>Tags (comma-separated)</label>
                    <input type="text" name="tags" value="<?php echo e($isEdit ? $tagsString : ''); ?>" placeholder="e.g. FEATURED, PARTNERSHIP">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="published" <?php echo ($v['status']==='published')?'selected':''; ?>>Published</option>
                        <option value="draft" <?php echo ($v['status']==='draft')?'selected':''; ?>>Draft</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <label class="checkbox-label"><input type="checkbox" name="featured" value="1" <?php echo $v['featured']?'checked':''; ?>> Featured Article</label>
                </div>
                <div class="form-group full">
                    <label>Article Image</label>
                    <div class="upload-zone" id="newsImageZone">
                        <div class="upload-placeholder" id="newsImagePlaceholder">
                            <div class="upload-icon">&#128247;</div>
                            <p>Click or drag image here to upload</p>
                            <small>JPG, PNG, GIF, WebP &mdash; Max 5MB</small>
                        </div>
                        <img id="newsImagePreview" class="upload-preview" src="" alt="" style="display:none;">
                        <input type="hidden" name="image" id="newsImageInput" value="<?php echo e($v['image']); ?>">
                        <input type="file" name="file" id="newsImageFile" accept="image/*" style="display:none;">
                        <button type="button" class="btn btn-sm btn-danger upload-remove-btn" id="newsImageRemove" style="display:none;" onclick="removeImage('news')">Remove</button>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Article' : 'Create Article'; ?></button>
                <a href="news.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $articles = get_all_news(); ?>
<div class="page-header">
    <h2>News</h2>
    <a href="news.php?action=add" class="btn btn-primary">+ Add Article</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Image</th><th>Title</th><th>Date</th><th>Tags</th><th>Featured</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($articles)): ?>
                <tr><td colspan="7" class="empty-state">No news articles found.</td></tr>
            <?php else: ?>
                <?php foreach ($articles as $a): ?>
                <tr>
                    <td><img src="../<?php echo e($a['image']); ?>" class="img-thumb" alt=""></td>
                    <td><strong><?php echo e($a['title']); ?></strong></td>
                    <td><?php echo format_date($a['published_at']); ?></td>
                    <td class="text-muted"><?php
                        $t = json_decode($a['tags'], true);
                        echo is_array($t) ? e(implode(', ', $t)) : '-';
                    ?></td>
                    <td><?php echo $a['featured'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>'; ?></td>
                    <td>
                        <span class="badge badge-<?php echo $a['status']==='published' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($a['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="news.php?action=edit&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="news.php?action=delete&id=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this article?')">Delete</a>
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