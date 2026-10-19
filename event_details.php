<?php
require 'config/db.php';
require 'includes/header.php';

// Get Event ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$event_id = $_GET['id'];

// 1. Fetch Event Details
$stmt = $pdo->prepare("SELECT events.*, users.name as organizer_name FROM events JOIN users ON events.organizer_id = users.id WHERE events.id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();
if (!$event) die("Event not found.");

// PRIVACY CHECK: If event is University Only AND user is NOT logged in -> BLOCK ACCESS
if ($event['visibility'] == 'university' && !isset($_SESSION['user_id'])) {
    echo "<script>alert('Access Denied: This is a private University event. Please Login to view.'); window.location.href='login.php';</script>";
    exit;
}

if (!$event) die("Event not found.");

// 2. Registration & Feedback Logic
$is_registered = false;
$has_feedback = false;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id) {
    // Check Registration
    $checkStmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $checkStmt->execute([$user_id, $event_id]);
    if ($checkStmt->rowCount() > 0) {
        $is_registered = true;
    }

    // Check if Feedback already exists
    $fbStmt = $pdo->prepare("SELECT * FROM feedback WHERE user_id = ? AND event_id = ?");
    $fbStmt->execute([$user_id, $event_id]);
    if ($fbStmt->rowCount() > 0) {
        $has_feedback = true;
    }

    // Handle Join Event
    if (isset($_POST['join_event'])) {
        if (!$is_registered) {
            $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)")->execute([$user_id, $event_id]);
            
            require_once 'includes/functions.php';
            
            // 1. Notify the Participant
            $msg = "Registration Confirmed: You have successfully joined '" . $event['event_name'] . "'.";
            addNotification($pdo, $user_id, $msg);
            sendEmail($_SESSION['user_email'] ?? 'user@test.com', "Event Registration", $msg);

            // 2. Notify the Organizer
            $orgMsg = "New Participant: " . $_SESSION['user_name'] . " joined your event '" . $event['event_name'] . "'.";
            addNotification($pdo, $event['organizer_id'], $orgMsg);
            // --- NEW NOTIFICATION CODE END ---

            echo "<script>alert('Successfully registered!'); window.location.href='event_details.php?id=$event_id';</script>";
        }
    }
    
    // Handle Cancel Event
    if (isset($_POST['cancel_event'])) {
        $pdo->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?")->execute([$user_id, $event_id]);
        echo "<script>alert('Registration cancelled.'); window.location.href='event_details.php?id=$event_id';</script>";
    }

    // Handle Feedback Submission
    if (isset($_POST['submit_feedback']) && $is_registered && !$has_feedback) {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $pdo->prepare("INSERT INTO feedback (event_id, user_id, rating, comment) VALUES (?, ?, ?, ?)")->execute([$event_id, $user_id, $rating, $comment]);
        echo "<script>alert('Thank you for your feedback!'); window.location.href='event_details.php?id=$event_id';</script>";
    }
}
?>

<div style="display: flex; gap: 30px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px;">
        <?php $poster = !empty($event['poster']) ? 'assets/uploads/' . $event['poster'] : 'https://via.placeholder.com/600x800?text=No+Poster'; ?>
        <img src="<?= $poster ?>" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    </div>

    <div style="flex: 2;">
        <h1 style="color: var(--primary-color); margin-top: 0;"><?= htmlspecialchars($event['event_name']) ?></h1>
        <p style="color: #666;">Organized by: <strong><?= htmlspecialchars($event['organizer_name']) ?></strong></p>
        
        <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?> at <?= htmlspecialchars($event['event_time']) ?></p>
            <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?> (<?= htmlspecialchars($event['mode']) ?>)</p>
            <p><strong>Fee:</strong> RM <?= htmlspecialchars($event['fee']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($event['type']) ?></p>
            <p><strong>Slots:</strong> <?= htmlspecialchars($event['max_participants']) ?> Max Participants</p>
        </div>

        <h3>Description</h3>
        <p style="line-height: 1.6;"><?= nl2br(htmlspecialchars($event['description'])) ?></p>

        <hr style="margin: 20px 0;">

        <?php if ($user_id): ?>
            <?php if ($is_registered): ?>
                <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;">
                    ✅ You are registered for this event.
                </div>
                
                <div style="margin-top: 20px; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
                    <h3>Rate & Review</h3>
                    <?php if ($has_feedback): ?>
                        <p style="color: green;">You have already submitted feedback for this event. Thank you!</p>
                    <?php else: ?>
                        <form method="POST">
                            <label>Rating:</label>
                            <select name="rating" required style="width: 100px; margin-bottom: 10px;">
                                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4">⭐⭐⭐⭐ (4)</option>
                                <option value="3">⭐⭐⭐ (3)</option>
                                <option value="2">⭐⭐ (2)</option>
                                <option value="1">⭐ (1)</option>
                            </select>
                            <br>
                            <label>Comment:</label>
                            <textarea name="comment" rows="3" style="width: 100%; margin-bottom: 10px;" placeholder="How was the event?"></textarea>
                            <button type="submit" name="submit_feedback" class="btn">Submit Feedback</button>
                        </form>
                    <?php endif; ?>
                </div>

                <form method="POST" style="margin-top: 20px;">
                    <button type="submit" name="cancel_event" class="btn" style="background: #dc3545;">Cancel Registration</button>
                </form>

            <?php else: ?>
                <form method="POST">
                    <button type="submit" name="join_event" class="btn" style="width: 100%; padding: 15px; font-size: 1.2rem;">Register for Event</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <div style="padding: 15px; background: #fff3cd; color: #856404; border-radius: 5px;">
                Please <a href="login.php" style="font-weight: bold; color: #856404;">Login</a> to register for this event.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>