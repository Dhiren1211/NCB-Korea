<?php
$adminTitle = 'Complaints';
include 'includes/header.php';

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    getDB()->prepare('DELETE FROM complaint_replies WHERE complaint_id = ?')->execute([$_GET['id']]);
    getDB()->prepare('DELETE FROM complaints WHERE id = ?')->execute([$_GET['id']]);
    set_flash('success', 'Complaint deleted successfully.');
    redirect('complaints.php');
}

// --- STATUS UPDATE ---
if (isset($_GET['action'], $_GET['id'], $_GET['status']) && $_GET['action'] === 'status') {
    $allowed = ['open', 'in_progress', 'resolved', 'closed'];
    $newStatus = $_GET['status'];
    if (in_array($newStatus, $allowed)) {
        getDB()->prepare('UPDATE complaints SET status = ? WHERE id = ?')->execute([$newStatus, $_GET['id']]);
        set_flash('success', 'Complaint status updated to ' . ucfirst(str_replace('_', ' ', $newStatus)) . '.');
    }
    if (isset($_GET['view'])) {
        redirect('complaints.php?action=view&id=' . (int)$_GET['id']);
    }
    redirect('complaints.php');
}

// --- ADMIN REPLY ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_reply']) && isset($_GET['id'])) {
    $complaint_id = (int)$_GET['id'];
    $message = trim($_POST['reply_message'] ?? '');
    if ($message !== '') {
        // Use admin user's id (0 or find admin user)
        $adminUser = current_user();
        // Insert reply with a special admin user reference
        create_complaint_reply($complaint_id, 0, $message);
        set_flash('success', 'Reply posted successfully.');
        redirect('complaints.php?action=view&id=' . $complaint_id);
    }
}

// --- DETAIL VIEW ---
$action = $_GET['action'] ?? '';

if ($action === 'view' && isset($_GET['id'])):
    $complaint = get_complaint_by_id($_GET['id']);
    if (!$complaint) { set_flash('error', 'Complaint not found.'); redirect('complaints.php'); }
    $replies = get_complaint_replies($complaint['id']);
    // Get reply count from DB
    $replyCount = count($replies);
    $statusColors = ['open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary'];
?>
<div class="page-header">
    <h2>Complaint #<?php echo $complaint['id']; ?></h2>
    <a href="complaints.php" class="btn btn-secondary">&larr; Back to Complaints</a>
</div>

<div class="card">
    <div class="card-body padded">
        <div style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 8px;"><?php echo e($complaint['title']); ?></h3>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-bottom: 12px;">
                <span class="badge badge-<?php echo $statusColors[$complaint['status']] ?? 'secondary'; ?>"><?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?></span>
                <span class="badge badge-info"><?php echo e($complaint['category']); ?></span>
                <span class="text-muted">By <?php echo e($complaint['full_name']); ?> on <?php echo format_date($complaint['created_at'], 'M d, Y g:i A'); ?></span>
            </div>
            <p style="white-space: pre-wrap; background: #f9fafb; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.6;"><?php echo e($complaint['description']); ?></p>
        </div>

        <div style="margin-bottom: 16px;">
            <strong>Update Status:</strong>
            <span style="margin-left: 12px;">
            <?php foreach (['open', 'in_progress', 'resolved', 'closed'] as $st): ?>
                <?php if ($complaint['status'] !== $st): ?>
                <a href="complaints.php?action=status&id=<?php echo $complaint['id']; ?>&status=<?php echo $st; ?>&view=1" class="btn btn-sm btn-<?php echo ($st === 'resolved') ? 'success' : (($st === 'closed') ? 'secondary' : (($st === 'in_progress') ? 'warning' : 'info')); ?>" style="margin-right: 4px;"><?php echo ucfirst(str_replace('_', ' ', $st)); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            </span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Replies (<?php echo $replyCount; ?>)</div>
    <div class="card-body padded">
        <?php if (empty($replies)): ?>
            <p class="text-muted">No replies yet.</p>
        <?php else: ?>
            <?php foreach ($replies as $r): ?>
            <div style="border-bottom: 1px solid #f0f0f0; padding: 12px 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong><?php echo e($r['full_name'] ?: 'Admin'); ?></strong>
                    <small class="text-muted"><?php echo format_date($r['created_at'], 'M d, Y g:i A'); ?></small>
                </div>
                <p style="white-space: pre-wrap; margin: 0; font-size: 14px; line-height: 1.6;"><?php echo e($r['message']); ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div style="margin-top: 20px; padding-top: 16px; border-top: 2px solid #f0f0f0;">
            <h4 style="margin-bottom: 12px;">Post a Reply (as Admin)</h4>
            <form method="POST" action="complaints.php?action=view&id=<?php echo $complaint['id']; ?>">
                <input type="hidden" name="admin_reply" value="1">
                <div class="form-group">
                    <textarea name="reply_message" rows="3" placeholder="Type your reply..." required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Post Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php else: ?>
<?php
$complaints = get_all_complaints_admin();
$statusColors = ['open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary'];
?>
<div class="page-header">
    <h2>Complaints</h2>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Title</th><th>Category</th><th>Status</th><th>Posted By</th><th>Date</th><th>Replies</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($complaints)): ?>
                <tr><td colspan="8" class="empty-state">No complaints found.</td></tr>
            <?php else: ?>
                <?php foreach ($complaints as $c): 
                    $replyStmt = getDB()->prepare('SELECT COUNT(*) as cnt FROM complaint_replies WHERE complaint_id = ?');
                    $replyStmt->execute([$c['id']]);
                    $rc = $replyStmt->fetch();
                    $replyCount = $rc ? (int)$rc['cnt'] : 0;
                ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><strong><?php echo e($c['title']); ?></strong></td>
                    <td><span class="badge badge-info"><?php echo e($c['category']); ?></span></td>
                    <td>
                        <span class="badge badge-<?php echo $statusColors[$c['status']] ?? 'secondary'; ?>"><?php echo ucfirst(str_replace('_', ' ', $c['status'])); ?></span>
                    </td>
                    <td><?php echo e($c['full_name']); ?></td>
                    <td><?php echo format_date($c['created_at']); ?></td>
                    <td><?php echo $replyCount; ?></td>
                    <td>
                        <a href="complaints.php?action=view&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <a href="complaints.php?action=delete&id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this complaint and all its replies?')">Delete</a>
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
