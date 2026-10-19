<?php
require 'config/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<h2>User Management</h2>
<a href="dashboard.php" class="btn" style="background:#666; margin-bottom:20px;">&larr; Back</a>

<table style="width:100%; border-collapse: collapse; background: white;">
    <thead>
        <tr style="background-color: #333; color: white; text-align: left;">
            <th style="padding: 10px;">Name</th>
            <th style="padding: 10px;">Role</th>
            <th style="padding: 10px;">Status</th>
            <th style="padding: 10px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;">
                    <?= htmlspecialchars($u['name']) ?><br>
                    <small style="color:#666;"><?= htmlspecialchars($u['email']) ?></small>
                </td>
                <td style="padding: 10px;"><?= ucfirst($u['role']) ?></td>
                <td style="padding: 10px;">
                    <?php if ($u['status'] == 'suspended'): ?>
                        <span style="color:red; font-weight:bold;">Suspended</span>
                    <?php else: ?>
                        <span style="color:green;">Active</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 10px;">
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:blue; font-size:0.8rem;">Edit</a>
                        
                        <?php if ($u['status'] == 'active'): ?>
                            <a href="admin_action.php?action=suspend_user&id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:orange; font-size:0.8rem;" onclick="return confirm('Suspend this user?')">Suspend</a>
                        <?php else: ?>
                            <a href="admin_action.php?action=activate_user&id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:green; font-size:0.8rem;">Activate</a>
                        <?php endif; ?>

                        <a href="admin_action.php?action=delete_user&id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:red; font-size:0.8rem;" onclick="return confirm('Delete user?')">Delete</a>
                    <?php else: ?>
                        <span style="color:#999;">(You)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require 'includes/footer.php'; ?>