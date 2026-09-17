<?php
$adminTitle = 'Messages';
include 'includes/header.php';

// --- MARK AS REPLIED / CLOSED ---
if (isset($_GET['action'], $_GET['id']) && in_array($_GET['action'], ['replied', 'closed'])) {
    getDB()->prepare('UPDATE contact_submissions SET status = ? WHERE id = ?')->execute([$_GET['action'], $_GET['id']]);
    set_flash('success', 'Message marked as ' . $_GET['action'] . '.');
    redirect('messages.php');
}

// --- POST REPLY ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_reply'])) {
    $replyId = (int)($_POST['reply_id'] ?? 0);
    $replyText = trim($_POST['admin_reply'] ?? '');
    if ($replyId && $replyText !== '') {
        getDB()->prepare('UPDATE contact_submissions SET admin_reply = ?, status = ? WHERE id = ?')
            ->execute([$replyText, 'replied', $replyId]);
        set_flash('success', 'Reply saved and message marked as replied.');
    }
    redirect('messages.php');
}

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM contact_submissions WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Message deleted.');
    redirect('messages.php');
}

// --- VIEW DETAIL ---
$viewId = $_GET['view'] ?? null;
$viewMsg = null;
if ($viewId) {
    $stmt = getDB()->prepare('SELECT * FROM contact_submissions WHERE id = ?');
    $stmt->execute([$viewId]);
    $viewMsg = $stmt->fetch();
    if (!$viewMsg) { set_flash('error', 'Message not found.'); redirect('messages.php'); }
    // Mark as read
    getDB()->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = ?')->execute([$viewId]);
}

// --- DETAIL VIEW ---
if ($viewMsg): ?>
<div class="page-header">
    <h2>Message #<?php echo $viewMsg['id']; ?></h2>
    <a href="messages.php" class="btn btn-secondary">&larr; Back to Messages</a>
</div>

<div class="card">
    <div class="card-header">Message Details</div>
    <div class="card-body padded">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
            <div><strong>Name:</strong> <?php echo e($viewMsg['full_name']); ?></div>
            <div><strong>Email:</strong> <?php echo e($viewMsg['email']); ?></div>
            <div><strong>Phone:</strong> <?php echo e($viewMsg['phone'] ?? 'N/A'); ?></div>
            <div><strong>Date:</strong> <?php echo format_date($viewMsg['created_at'], 'M d, Y h:i A'); ?></div>
            <div><strong>Subject:</strong> <?php echo e($viewMsg['subject']); ?></div>
            <div><strong>Status:</strong>
                <span class="badge badge-<?php echo $viewMsg['status']==='new'?'danger':($viewMsg['status']==='replied'?'info':'secondary'); ?>">
                    <?php echo ucfirst($viewMsg['status']); ?>
                </span>
            </div>
        </div>

        <div style="background:#f9fafb; padding:16px; border-radius:8px; margin-bottom:20px;">
            <strong style="display:block; margin-bottom:8px;">Message:</strong>
            <?php echo nl2br(e($viewMsg['message'])); ?>
        </div>

        <?php if ($viewMsg['admin_reply']): ?>
        <div style="background:#eff6ff; padding:16px; border-radius:8px; margin-bottom:20px; border-left:4px solid #2563eb;">
            <strong style="display:block; margin-bottom:8px;">Admin Reply:</strong>
            <?php echo nl2br(e($viewMsg['admin_reply'])); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="messages.php">
            <input type="hidden" name="reply_id" value="<?php echo $viewMsg['id']; ?>">
            <div class="form-group">
                <label>Admin Reply</label>
                <textarea name="admin_reply" rows="4" placeholder="Type your reply..."><?php echo e($viewMsg['admin_reply'] ?? ''); ?></textarea>
            </div>
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <button type="submit" name="save_reply" class="btn btn-primary">Save Reply</button>
                <?php if ($viewMsg['status'] !== 'replied'): ?>
                <a href="messages.php?action=replied&id=<?php echo $viewMsg['id']; ?>" class="btn btn-info">Mark as Replied</a>
                <?php endif; ?>
                <?php if ($viewMsg['status'] !== 'closed'): ?>
                <a href="messages.php?action=closed&id=<?php echo $viewMsg['id']; ?>" class="btn btn-warning">Mark as Closed</a>
                <?php endif; ?>
                <a href="messages.php?action=delete&id=<?php echo $viewMsg['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
// --- LIST VIEW ---
$filterStatus = $_GET['status'] ?? '';
$messages = get_contact_submissions($filterStatus ?: null);
?>
<div class="page-header">
    <h2>Messages</h2>
</div>

<div class="filter-bar">
    <strong style="margin-right:4px;">Filter:</strong>
    <a href="messages.php" class="<?php echo $filterStatus === '' ? 'active' : ''; ?>">All</a>
    <a href="messages.php?status=new" class="<?php echo $filterStatus === 'new' ? 'active' : ''; ?>">New</a>
    <a href="messages.php?status=replied" class="<?php echo $filterStatus === 'replied' ? 'active' : ''; ?>">Replied</a>
    <a href="messages.php?status=closed" class="<?php echo $filterStatus === 'closed' ? 'active' : ''; ?>">Closed</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($messages)): ?>
                <tr><td colspan="7" class="empty-state">No messages found.</td></tr>
            <?php else: ?>
                <?php foreach ($messages as $m): ?>
                <tr<?php echo $m['is_read'] ? '' : ' style="font-weight:600;"'; ?>>
                    <td><?php echo $m['id']; ?></td>
                    <td><?php echo e($m['full_name']); ?></td>
                    <td><?php echo e($m['email']); ?></td>
                    <td><?php echo e(mb_strimwidth($m['subject'], 0, 35, '...')); ?></td>
                    <td><?php echo format_date($m['created_at']); ?></td>
                    <td>
                        <?php if ($m['status'] === 'new'): ?>
                            <span class="badge badge-danger">New</span>
                        <?php elseif ($m['status'] === 'replied'): ?>
                            <span class="badge badge-info">Replied</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Closed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="messages.php?view=<?php echo $m['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <a href="messages.php?action=delete&id=<?php echo $m['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
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