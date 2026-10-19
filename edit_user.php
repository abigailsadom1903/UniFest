<?php
require 'config/db.php';
require 'includes/header.php';

if ($_SESSION['user_role'] != 'admin' || !isset($_GET['id'])) {
    die("Access Denied");
}

$id = $_GET['id'];

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, role=?, phone=? WHERE id=?");
    $stmt->execute([$name, $email, $role, $phone, $id]);
    echo "<script>alert('User Updated'); window.location.href='manage_users.php';</script>";
}

// Fetch User
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<h2>Edit User: <?= htmlspecialchars($user['name']) ?></h2>
<form method="POST" style="max-width:500px;">
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
    </div>
    <div class="form-group">
        <label>Role</label>
        <select name="role">
            <option value="student" <?= $user['role']=='student'?'selected':'' ?>>Student</option>
            <option value="staff" <?= $user['role']=='staff'?'selected':'' ?>>Staff</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
            <option value="public" <?= $user['role']=='public'?'selected':'' ?>>Public</option>
        </select>
    </div>
    <button type="submit" class="btn">Update User</button>
    <a href="manage_users.php" style="margin-left:10px;">Cancel</a>
</form>
<?php require 'includes/footer.php'; ?>