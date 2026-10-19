<?php
require 'config/db.php';
require 'includes/header.php';

// Security: ID required and User must be logged in
if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// Verify Ownership
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) die("Event not found.");
if ($role != 'admin' && $event['organizer_id'] != $user_id) {
    die("Access Denied.");
}

// Fetch Feedback
$sql = "SELECT f.*, u.name FROM feedback f JOIN users u ON f.user_id = u.id WHERE f.event_id = ? ORDER BY f.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$feedbacks = $stmt->fetchAll();

// Calculate Average Rating
$avg_rating = 0;
if (count($feedbacks) > 0) {
    $sum = 0;
    foreach ($feedbacks as $f) $sum += $f['rating'];
    $avg_rating = round($sum / count($feedbacks), 1);
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
    <h2>Feedback: <?= htmlspecialchars($event['event_name']) ?></h2>
    <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back to Dashboard</a>
</div>

<div style="background: var(--primary-color); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
    <h1><?= $avg_rating ?> / 5.0</h1>
    <p>Average Rating from <?= count($feedbacks) ?> participants</p>
</div>

<div class="feedback-list">
    <?php if (count($feedbacks) > 0): ?>
        <?php foreach ($feedbacks as $fb): ?>
            <div style="background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                <div style="display:flex; justify-content:space-between;">
                    <strong><?= htmlspecialchars($fb['name']) ?></strong>
                    <span style="color: #f39c12;">
                        <?= str_repeat("⭐", $fb['rating']) ?>
                    </span>
                </div>
                <p style="margin-top: 10px; color: #555;">"<?= htmlspecialchars($fb['comment']) ?>"</p>
                <small style="color: #999;"><?= $fb['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center;">No feedback has been submitted yet.</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>