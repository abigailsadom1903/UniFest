<?php
require 'config/db.php';
require 'includes/header.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// 2. Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // --- CASE A: UPDATE PERSONAL DETAILS ---
    if (isset($_POST['update_details'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $org = $_POST['organization'];
        $interests = isset($_POST['interests']) ? implode(',', $_POST['interests']) : '';

        // Note: matrix_id is excluded from UPDATE because it cannot be changed
        $sql = "UPDATE users SET name = ?, phone = ?, organization = ?, interested_categories = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$name, $phone, $org, $interests, $user_id]);
            $_SESSION['user_name'] = $name; 
            $success = "Profile details updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }

    // --- CASE B: CHANGE PASSWORD (Same as before) ---
    if (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch();

        if (password_verify($current_pass, $user_data['password'])) {
            if ($new_pass === $confirm_pass) {
                if (strlen($new_pass) >= 6) {
                    $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$new_hash, $user_id]);
                    $success = "Password changed successfully!";
                } else { $error = "New password must be at least 6 characters long."; }
            } else { $error = "New passwords do not match."; }
        } else { $error = "Incorrect current password."; }
    }
}

// 3. Fetch User Data to Display
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$user_interests = explode(',', $user['interested_categories']);
?>

<div class="container" style="max-width: 800px;">
    <h2>My Profile</h2>
    
    <?php if ($success): ?>
        <p style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px;"><?= $success ?></p>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <p style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;"><?= $error ?></p>
    <?php endif; ?>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <h3 style="color: var(--primary-color); border-bottom: 2px solid #ddd; padding-bottom: 10px;">Personal Details</h3>
            <form method="POST">
                
                <?php if ($user['role'] !== 'public'): ?>
                <div class="form-group">
                    <label><?= ($user['role'] === 'student') ? 'Student ID' : 'Staff ID' ?></label>
                    <input type="text" value="<?= htmlspecialchars($user['matrix_id']) ?>" disabled style="background: #eee; cursor: not-allowed;">
                    <small style="color: #888;">ID cannot be changed after registration.</small>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email (Cannot be changed)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: #eee;">
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Organization / Faculty</label>
                    <input type="text" name="organization" value="<?= htmlspecialchars($user['organization']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Interests</label>
                    <select name="interests[]" multiple style="height: 120px;">
                        <?php 
                        $cats = ['Workshop', 'Seminar', 'Competition', 'Festival', 'Sport', 'Course'];
                        foreach ($cats as $cat) {
                            $selected = in_array($cat, $user_interests) ? 'selected' : '';
                            echo "<option value='$cat' $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="update_details" class="btn">Save Changes</button>
            </form>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h3 style="color: var(--primary-color); border-bottom: 2px solid #ddd; padding-bottom: 10px;">Change Password</h3>
            <form method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn" style="background: #333;">Update Password</button>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>