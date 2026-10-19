<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include the new functions file if DB is loaded
if (isset($pdo)) {
    require_once __DIR__ . '/functions.php';
}

// Fetch Unread Notification Count
$unread_count = 0;
if (isset($_SESSION['user_id']) && isset($pdo)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user_id']]);
    $unread_count = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFest - Campus Event System</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Notification Badge Style */
        .badge {
            background-color: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.8rem;
            vertical-align: top;
            margin-left: -5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar" style="display: flex; align-items: center; justify-content: space-between;">
            
            <a href="index.php" class="logo" style="display: flex; align-items: center; text-decoration: none;">
                
                <img src="assets/uploads/UniFest_Logo.png" alt="UniFest Logo" style="height: 50px; margin-right: 10px;">
                
                <span style="color: white; font-size: 2.5rem; font-weight: bold;">UniFest</span>
            </a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    
                    <a href="notifications.php">
                        🔔 
                        <?php if ($unread_count > 0): ?>
                            <span class="badge"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="profile.php">My Profile</a>
                    <a href="logout.php" style="color: #ffcccc;">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container">