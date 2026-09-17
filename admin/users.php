<?php
/**
 * NCB Admin - User Management (Super Admin Only)
 * Create, edit, delete admin users
 */
$adminTitle = 'Admin Users';
include 'includes/header.php';

// Only super_admin can manage users
if (($user['role'] ?? '') !== 'super_admin') {
    echo '<div class="card"><div class="card-body padded"><h2>Access Denied</h2><p class="mt-16">Only Super Admin can manage admin users.</p><a href="index.php" class="btn btn-secondary">Back to Dashboard</a></div></div></div><!-- admin-main --></body></html>';
    exit;
}

// --- DELETE ---
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
    $deleteId = (int)$_GET['id'];
    // Prevent deleting yourself
    if ($deleteId === (int)($_SESSION['user_id'] ?? 0)) {
        set_flash('error', 'You cannot delete your own account.');
        redirect('users.php');
    }
    // Check if last super_admin
    $target = getDB()->prepare('SELECT role FROM users WHERE id = ?');
    $target->execute([$deleteId]);
    $targetUser = $target->fetch();

    if ($targetUser && $targetUser['role'] === 'super_admin') {
        $superCount = (int)getDB()->query("SELECT COUNT(*) FROM users WHERE role = 'super_admin'")->fetchColumn();
        if ($superCount <= 1) {
            set_flash('error', 'Cannot delete the last Super Admin account.');
            redirect('users.php');
        }
    }

    getDB()->prepare('DELETE FROM users WHERE id = ?')->execute([$deleteId]);
    set_flash('success', 'User deleted successfully.');
    redirect('users.php');
}

// --- EDIT / ADD FORM ---
$action = $_GET['action'] ?? '';
$editUser = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = getDB()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $editUser = $stmt->fetch();
    if (!$editUser) { set_flash('error', 'User not found.'); redirect('users.php'); }
}

// --- POST (Insert / Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $fullName  = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $role      = $_POST['role'] ?? 'admin';
    $password  = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');
    $editId    = $_POST['edit_id'] ?? '';

    // Validation
    $errors = [];
    if ($username === '') $errors[] = 'Username is required.';
    if ($fullName === '') $errors[] = 'Full name is required.';
    if ($email === '') $errors[] = 'Email is required.';
    if (!in_array($role, ['super_admin', 'admin', 'editor'])) $errors[] = 'Invalid role.';

    // Check unique username
    if ($username !== '') {
        $checkSql = 'SELECT id FROM users WHERE username = ?';
        $checkParams = [$username];
        if ($editId !== '') {
            $checkSql .= ' AND id != ?';
            $checkParams[] = $editId;
        }
        $stmt = getDB()->prepare($checkSql);
        $stmt->execute($checkParams);
        if ($stmt->fetch()) $errors[] = 'Username already exists.';
    }

    // Password validation
    if ($editId === '') {
        // Creating new user - password required
        if ($password === '') $errors[] = 'Password is required.';
        elseif (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        elseif ($password !== $password2) $errors[] = 'Passwords do not match.';
    } else {
        // Editing - password optional but must match if provided
        if ($password !== '' && $password !== $password2) {
            $errors[] = 'Passwords do not match.';
        }
        if ($password !== '' && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode(' ', $errors));
    } else {
        if ($editId !== '') {
            // Update
            if ($password !== '') {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET username=?, full_name=?, email=?, role=?, password=? WHERE id=?";
                getDB()->prepare($sql)->execute([$username, $fullName, $email, $role, $hashed, $editId]);
            } else {
                $sql = "UPDATE users SET username=?, full_name=?, email=?, role=? WHERE id=?";
                getDB()->prepare($sql)->execute([$username, $fullName, $email, $role, $editId]);
            }
            set_flash('success', 'User updated successfully.');
        } else {
            // Insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, full_name, email, role) VALUES (?,?,?,?,?)";
            getDB()->prepare($sql)->execute([$username, $hashed, $fullName, $email, $role]);
            set_flash('success', 'User created successfully.');
        }
        redirect('users.php');
    }
}

// --- SHOW FORM ---
if ($action === 'add' || $action === 'edit'):
    $isEdit = ($action === 'edit' && $editUser);
    $v = $isEdit ? $editUser : ['username'=>'','full_name'=>'','email'=>'','role'=>'admin'];
?>
<div class="page-header">
    <h2><?php echo $isEdit ? 'Edit User' : 'Add New Admin User'; ?></h2>
    <a href="users.php" class="btn btn-secondary">&larr; Back to Users</a>
</div>

<div class="card">
    <div class="card-header">User Information</div>
    <div class="card-body padded">
        <form method="POST" action="users.php">
            <?php if ($isEdit): ?><input type="hidden" name="edit_id" value="<?php echo $v['id']; ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Username *</label>
                    <input type="text" name="username" value="<?php echo e($v['username']); ?>" required <?php echo $isEdit ? 'readonly' : ''; ?>>
                    <?php if ($isEdit): ?><small class="text-muted">Username cannot be changed.</small><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" value="<?php echo e($v['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?php echo e($v['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role">
                        <option value="super_admin" <?php echo ($v['role']==='super_admin')?'selected':''; ?>>Super Admin</option>
                        <option value="admin" <?php echo ($v['role']==='admin')?'selected':''; ?>>Admin</option>
                        <option value="editor" <?php echo ($v['role']==='editor')?'selected':''; ?>>Editor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo $isEdit ? 'New Password (leave blank to keep current)' : 'Password *'; ?></label>
                    <input type="password" name="password" <?php echo $isEdit ? '' : 'required'; ?> minlength="6" placeholder="<?php echo $isEdit ? 'Leave blank to keep current password' : 'Minimum 6 characters'; ?>">
                </div>
                <div class="form-group">
                    <label>Confirm Password <?php echo $isEdit ? '' : '*'; ?></label>
                    <input type="password" name="password2" <?php echo $isEdit ? '' : 'required'; ?> placeholder="Re-enter password">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update User' : 'Create User'; ?></button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<?php $allUsers = getDB()->query('SELECT * FROM users ORDER BY id ASC')->fetchAll(); ?>
<div class="page-header">
    <h2>Admin Users</h2>
    <a href="users.php?action=add" class="btn btn-primary">+ Add User</a>
</div>

<div class="card">
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($allUsers)): ?>
                <tr><td colspan="7" class="empty-state">No users found.</td></tr>
            <?php else: ?>
                <?php foreach ($allUsers as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><strong><?php echo e($u['username']); ?></strong>
                        <?php if ((int)$u['id'] === (int)($_SESSION['user_id'] ?? 0)): ?>
                            <span class="badge badge-info">You</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($u['full_name']); ?></td>
                    <td class="text-muted"><?php echo e($u['email']); ?></td>
                    <td>
                        <?php
                        $roleBadge = ['super_admin'=>'danger','admin'=>'info','editor'=>'success'];
                        $roleLabel = ['super_admin'=>'Super Admin','admin'=>'Admin','editor'=>'Editor'];
                        ?>
                        <span class="badge badge-<?php echo $roleBadge[$u['role']] ?? 'secondary'; ?>">
                            <?php echo $roleLabel[$u['role']] ?? $u['role']; ?>
                        </span>
                    </td>
                    <td class="text-muted"><?php echo format_date($u['created_at'], 'M d, Y'); ?></td>
                    <td>
                        <a href="users.php?action=edit&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <?php if ((int)$u['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
                            <a href="users.php?action=delete&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user &quot;<?php echo e($u['username']); ?>&quot;? This cannot be undone.')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">Role Permissions</div>
    <div class="card-body padded">
        <table>
            <thead>
                <tr><th>Permission</th><th>Super Admin</th><th>Admin</th><th>Editor</th></tr>
            </thead>
            <tbody>
                <tr><td>Manage Admin Users</td><td>&#9989;</td><td>&#10060;</td><td>&#10060;</td></tr>
                <tr><td>Manage All Content (Events, News, etc.)</td><td>&#9989;</td><td>&#9989;</td><td>&#9989;</td></tr>
                <tr><td>Manage Settings</td><td>&#9989;</td><td>&#9989;</td><td>&#10060;</td></tr>
                <tr><td>View Members & Messages</td><td>&#9989;</td><td>&#9989;</td><td>&#9989;</td></tr>
                <tr><td>Delete Content</td><td>&#9989;</td><td>&#9989;</td><td>&#10060;</td></tr>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

</div><!-- end admin-main -->
</body>
</html>
