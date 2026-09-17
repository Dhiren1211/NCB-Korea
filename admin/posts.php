<?php
$adminTitle = 'Community Posts';
include 'includes/header.php';

// --- APPROVE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'approve') {
    approve_post($_GET['id']);
    set_flash('success', 'Post approved successfully.');
    redirect('posts.php');
}

// --- REJECT ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'reject') {
    reject_post($_GET['id']);
    set_flash('success', 'Post rejected.');
    redirect('posts.php');
}

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM posts WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Post deleted successfully.');
    redirect('posts.php');
}

// --- FILTER ---
$statusFilter = $_GET['status'] ?? '';

// --- LIST VIEW ---
$posts = get_all_posts_admin_with_status();
if ($statusFilter && in_array($statusFilter, ['pending', 'approved', 'rejected'])) {
    $posts = array_filter($posts, function($p) use ($statusFilter) {
        return $p['status'] === $statusFilter;
    });
    $posts = array_values($posts);
}

$pendingCount = get_pending_posts_count();
?>

<div class="page-header">
    <h2>Community Posts</h2>
    <?php if ($pendingCount > 0): ?>
    <span class="badge badge-warning" style="font-size:13px;padding:6px 14px;"><?php echo $pendingCount; ?> Pending Approval</span>
    <?php endif; ?>
</div>

<!-- Status Filter -->
<div class="filter-bar" style="margin-bottom:20px;">
    <a href="posts.php" class="<?php echo !$statusFilter ? 'active' : ''; ?>">All</a>
    <a href="posts.php?status=pending" class="<?php echo $statusFilter === 'pending' ? 'active' : ''; ?>">Pending (<?php echo $pendingCount; ?>)</a>
    <a href="posts.php?status=approved" class="<?php echo $statusFilter === 'approved' ? 'active' : ''; ?>">Approved</a>
    <a href="posts.php?status=rejected" class="<?php echo $statusFilter === 'rejected' ? 'active' : ''; ?>">Rejected</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Likes</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($posts)): ?>
                <tr><td colspan="8" class="empty-state">No posts found.</td></tr>
            <?php else: ?>
                <?php foreach ($posts as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                        <strong><?php echo e($p['full_name']); ?></strong><br>
                        <small class="text-muted">@<?php echo e($p['username']); ?></small>
                    </td>
                    <td><?php echo e(mb_substr($p['content'], 0, 80)) . (mb_strlen($p['content']) > 80 ? '...' : ''); ?></td>
                    <td>
                        <?php if (!empty($p['image'])): ?>
                            <img src="../<?php echo e($p['image']); ?>" class="img-thumb" alt="">
                        <?php else: ?>
                            <span class="text-muted">&mdash;</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo (int)$p['likes_count']; ?></td>
                    <td>
                        <?php
                        $statusMap = [
                            'pending' => ['badge-warning', 'Pending'],
                            'approved' => ['badge-success', 'Approved'],
                            'rejected' => ['badge-danger', 'Rejected'],
                        ];
                        $s = $statusMap[$p['status']] ?? ['badge-secondary', $p['status']];
                        ?>
                        <span class="badge <?php echo $s[0]; ?>"><?php echo $s[1]; ?></span>
                    </td>
                    <td><?php echo format_date($p['created_at'], 'M d, Y g:i A'); ?></td>
                    <td>
                        <?php if ($p['status'] === 'pending'): ?>
                            <a href="posts.php?action=approve&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                            <a href="posts.php?action=reject&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning" onclick="return confirm('Reject this post?')">Reject</a>
                        <?php elseif ($p['status'] === 'rejected'): ?>
                            <a href="posts.php?action=approve&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                        <?php endif; ?>
                        <a href="posts.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post? This cannot be undone.')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div><!-- end admin-main -->
</body>
</html>
