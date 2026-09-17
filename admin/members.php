<?php
$adminTitle = 'Membership Applications';
include 'includes/header.php';

// --- APPROVE / REJECT ---
if (isset($_GET['action'], $_GET['id']) && in_array($_GET['action'], ['approve', 'reject'])) {
    $newStatus = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    getDB()->prepare('UPDATE membership_applications SET status = ? WHERE id = ?')->execute([$newStatus, $_GET['id']]);
    set_flash('success', 'Application ' . $newStatus . '.');
    $redirectParams = '';
    if (isset($_GET['status'])) $redirectParams = '?status=' . urlencode($_GET['status']);
    redirect('members.php' . $redirectParams);
}

// --- FILTER ---
$filterStatus = $_GET['status'] ?? '';
$applications = get_membership_applications($filterStatus ?: null);
?>

<div class="page-header">
    <h2>Membership Applications</h2>
</div>

<div class="filter-bar">
    <strong style="margin-right:4px;">Filter:</strong>
    <a href="members.php" class="<?php echo $filterStatus === '' ? 'active' : ''; ?>">All</a>
    <a href="members.php?status=pending" class="<?php echo $filterStatus === 'pending' ? 'active' : ''; ?>">Pending</a>
    <a href="members.php?status=approved" class="<?php echo $filterStatus === 'approved' ? 'active' : ''; ?>">Approved</a>
    <a href="members.php?status=rejected" class="<?php echo $filterStatus === 'rejected' ? 'active' : ''; ?>">Rejected</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Visa Type</th><th>Date</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($applications)): ?>
                <tr><td colspan="8" class="empty-state">No applications found.</td></tr>
            <?php else: ?>
                <?php foreach ($applications as $a): ?>
                <tr>
                    <td><?php echo $a['id']; ?></td>
                    <td><strong><?php echo e($a['full_name']); ?></strong></td>
                    <td><?php echo e($a['email']); ?></td>
                    <td><?php echo e($a['phone']); ?></td>
                    <td><?php echo e($a['visa_type']); ?></td>
                    <td><?php echo format_date($a['created_at']); ?></td>
                    <td>
                        <?php if ($a['status'] === 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif ($a['status'] === 'approved'): ?>
                            <span class="badge badge-success">Approved</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($a['status'] === 'pending'): ?>
                            <a href="members.php?action=approve&id=<?php echo $a['id']; ?><?php echo $filterStatus ? '&status=' . urlencode($filterStatus) : ''; ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this application?')">Approve</a>
                            <a href="members.php?action=reject&id=<?php echo $a['id']; ?><?php echo $filterStatus ? '&status=' . urlencode($filterStatus) : ''; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this application?')">Reject</a>
                        <?php else: ?>
                            <span class="text-muted">--</span>
                        <?php endif; ?>
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