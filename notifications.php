<?php
require 'config/db.php';
require 'includes/header.php'; // This now includes functions.php

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mark all as read when opening page
$pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);

// Fetch Notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifs = $stmt->fetchAll();
?>

<h2>My Notifications</h2>
<a href="dashboard.php">&larr; Back to Dashboard</a>

<div style="margin-top: 20px;">
    <?php if (count($notifs) > 0): ?>
        <?php foreach ($notifs as $n): ?>
            <div style="padding: 15px; border-bottom: 1px solid #ddd; background: <?= $n['is_read'] ? '#fff' : '#f0f8ff' ?>;">
                <p style="margin: 0; font-size: 1.1rem;"><?= htmlspecialchars($n['message']) ?></p>
                <small style="color: #888;"><?= $n['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>