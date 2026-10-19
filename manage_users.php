<?php
require 'config/db.php';
require 'includes/header.php';

// Security Check: Admin Only
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin') && (isset($_SESSION['role']) && $_SESSION['role'] != 'admin')) {
    header("Location: dashboard.php");
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<div class="container" style="padding: 20px;">
    <h2>User Management</h2>
    <a href="index.php" class="btn" style="background:#666; color:white; text-decoration:none; padding: 10px 15px; border-radius:4px; margin-bottom:20px; display:inline-block;">&larr; Back to Dashboard</a>

    <table style="width:100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background-color: #800000; color: white; text-align: left;">
                <th style="padding: 15px;">Name</th>
                <th style="padding: 15px;">Category</th>
                <th style="padding: 15px;">Role</th>
                <th style="padding: 15px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    
                    <td style="padding: 15px;">
                        <strong><?= htmlspecialchars($u['name']) ?></strong><br>
                        <small style="color:#666;"><?= htmlspecialchars($u['email']) ?></small>
                    </td>

                    <td style="padding: 15px;">
                        <?= htmlspecialchars($u['category']) ?>
                    </td>

                    <td style="padding: 15px;">
                        <span style="
                            padding: 4px 8px; 
                            border-radius: 4px; 
                            background-color: <?= $u['role'] == 'admin' ? '#d4edda' : '#f8f9fa' ?>; 
                            color: <?= $u['role'] == 'admin' ? '#155724' : '#333' ?>;
                            font-weight: bold;
                        ">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </td>

                    <td style="padding: 15px;">
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            
                            <?php if ($u['role'] !== 'admin' && $u['category'] == 'Staff'): ?>
                                <a href="promote_user.php?id=<?= $u['id'] ?>" 
                                   onclick="return confirm('Are you sure you want to promote this Staff member to Admin?');"
                                   class="btn" 
                                   style="padding:5px 10px; background:#28a745; color:white; font-size:0.8rem; margin-right:5px; text-decoration:none; border-radius:3px;">
                                   Make Admin
                                </a>
                            <?php endif; ?>

                            <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:blue; color:white; font-size:0.8rem; margin-right:5px; text-decoration:none; border-radius:3px;">Edit</a>
                            
                            <a href="admin_action.php?action=delete_user&id=<?= $u['id'] ?>" class="btn" style="padding:5px 10px; background:red; color:white; font-size:0.8rem; text-decoration:none; border-radius:3px;" onclick="return confirm('Delete user?')">Delete</a>
                        
                        <?php else: ?>
                            <span style="color:#999;">(You)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>