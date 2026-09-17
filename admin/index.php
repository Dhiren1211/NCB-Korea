<?php
$adminTitle = 'Dashboard';
include 'includes/header.php';

$counts = get_dashboard_counts();
$messages = get_contact_submissions();
$members = get_membership_applications();
?>

<div class="page-header">
    <h2>Welcome, <?php echo e($user['name']); ?>!</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-blue">&#128197;</div>
        <div class="stat-info">
            <h3><?php echo $counts['events']; ?></h3>
            <p>Upcoming Events</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green">&#128240;</div>
        <div class="stat-info">
            <h3><?php echo $counts['news']; ?></h3>
            <p>Published News</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-teal">&#9989;</div>
        <div class="stat-info">
            <h3><?php echo $counts['members']; ?></h3>
            <p>Approved Members</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber">&#9203;</div>
        <div class="stat-info">
            <h3><?php echo $counts['pending']; ?></h3>
            <p>Pending Applications</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple">&#128101;</div>
        <div class="stat-info">
            <h3><?php echo $counts['registrations']; ?></h3>
            <p>Event Registrations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-red">&#128172;</div>
        <div class="stat-info">
            <h3><?php echo $counts['messages']; ?></h3>
            <p>New Messages</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-pink">&#128247;</div>
        <div class="stat-info">
            <h3><?php echo $counts['gallery']; ?></h3>
            <p>Gallery Items</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-indigo">&#128101;</div>
        <div class="stat-info">
            <h3><?php echo $counts['committee']; ?></h3>
            <p>Committee Members</p>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
    <!-- Latest Messages -->
    <div class="card">
        <div class="card-header">Latest Messages</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr><th>Name</th><th>Subject</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php $latestMsgs = array_slice($messages, 0, 5); ?>
                <?php if (empty($latestMsgs)): ?>
                    <tr><td colspan="4" class="empty-state">No messages yet</td></tr>
                <?php else: ?>
                    <?php foreach ($latestMsgs as $m): ?>
                    <tr>
                        <td><?php echo e($m['full_name']); ?></td>
                        <td><?php echo e(mb_strimwidth($m['subject'], 0, 30, '...')); ?></td>
                        <td class="text-muted"><?php echo format_date($m['created_at'], 'M d'); ?></td>
                        <td>
                            <?php if ($m['status'] === 'new'): ?>
                                <span class="badge badge-danger">New</span>
                            <?php elseif ($m['status'] === 'replied'): ?>
                                <span class="badge badge-info">Replied</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Closed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Latest Applications -->
    <div class="card">
        <div class="card-header">Latest Membership Applications</div>
        <div class="card-body">
            <table>
                <thead>
                    <tr><th>Name</th><th>Visa</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php $latestMem = array_slice($members, 0, 5); ?>
                <?php if (empty($latestMem)): ?>
                    <tr><td colspan="4" class="empty-state">No applications yet</td></tr>
                <?php else: ?>
                    <?php foreach ($latestMem as $m): ?>
                    <tr>
                        <td><?php echo e($m['full_name']); ?></td>
                        <td class="text-muted"><?php echo e($m['visa_type']); ?></td>
                        <td class="text-muted"><?php echo format_date($m['created_at'], 'M d'); ?></td>
                        <td>
                            <?php if ($m['status'] === 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php elseif ($m['status'] === 'approved'): ?>
                                <span class="badge badge-success">Approved</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div><!-- end admin-main -->
</body>
</html>