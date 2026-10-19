<?php
require 'config/db.php';
require 'includes/header.php';

// 1. Security & Validation
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// 2. Verify Ownership (Is this YOUR event?)
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) die("Event not found.");
// Only Admin or the Organizer can view this
if ($role != 'admin' && $event['organizer_id'] != $user_id) {
    die("Access Denied: You do not own this event.");
}

// 3. Handle Attendance Action (Mark Present)
if (isset($_GET['mark_present'])) {
    $reg_id = $_GET['mark_present'];
    $updateStmt = $pdo->prepare("UPDATE registrations SET status = 'attended' WHERE id = ?");
    $updateStmt->execute([$reg_id]);
    echo "<script>window.location.href='view_participants.php?id=$event_id';</script>";
}

// 4. Fetch Participants (Updated to include u.id for notifications)
$sql = "SELECT u.id as user_id, r.id as reg_id, r.status as attendance_status, u.name, u.email, u.phone, u.organization 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.event_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$participants = $stmt->fetchAll();

// 5. Handle Broadcast Message Logic
if (isset($_POST['send_broadcast'])) {
    // Only process if functions.php is available (it is included in header.php)
    if (function_exists('addNotification')) {
        $message = "Update for '" . $event['event_name'] . "': " . $_POST['broadcast_msg'];
        $count = 0;

        foreach ($participants as $p) {
            // 1. In-System Notification
            addNotification($pdo, $p['user_id'], $message);
            
            // 2. Mock Email
            sendEmail($p['email'], "Event Update: " . $event['event_name'], $message);
            $count++;
        }
        echo "<script>alert('Message sent to $count participants!');</script>";
    } else {
        echo "<script>alert('Error: Notification system not loaded.');</script>";
    }
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
    <div>
        <h2>Participants: <?= htmlspecialchars($event['event_name']) ?></h2>
        <a href="dashboard.php" class="btn" style="background: #666;">&larr; Back to Dashboard</a>
    </div>
    <a href="export_participants.php?id=<?= $event_id ?>" class="btn" style="background-color: green;">
        Download Excel/CSV
    </a>
</div>

<table style="width:100%; border-collapse: collapse; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background-color: var(--primary-color); color: white; text-align: left;">
            <th style="padding: 12px;">No.</th>
            <th style="padding: 12px;">Name</th>
            <th style="padding: 12px;">Email</th>
            <th style="padding: 12px;">Phone</th>
            <th style="padding: 12px;">Faculty/Org</th>
            <th style="padding: 12px;">Status</th>
            <th style="padding: 12px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($participants) > 0): ?>
            <?php $i = 1; foreach ($participants as $p): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px;"><?= $i++ ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($p['name']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($p['email']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($p['phone']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($p['organization']) ?></td>
                    <td style="padding: 12px;">
                        <?php if ($p['attendance_status'] == 'attended'): ?>
                            <span style="color: green; font-weight: bold;">✔ Present</span>
                        <?php else: ?>
                            <span style="color: orange;">Registered</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;">
                        <?php if ($p['attendance_status'] != 'attended'): ?>
                            <a href="view_participants.php?id=<?= $event_id ?>&mark_present=<?= $p['reg_id'] ?>" 
                               class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Mark Present</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" style="padding: 20px; text-align: center;">No participants have registered yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top: 40px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
    <h3 style="color: var(--primary-color); margin-top: 0;">📢 Communicate with Participants</h3>
    <p style="color: #666;">Send an update or reminder to everyone registered for this event.</p>
    
    <form method="POST">
        <label style="font-weight: bold;">Message:</label>
        <textarea name="broadcast_msg" rows="3" style="width: 100%; margin-top: 5px; padding: 10px; border-radius: 4px; border: 1px solid #ccc;" required placeholder="e.g., Don't forget the event starts tomorrow at 8 AM!"></textarea>
        <br><br>
        <button type="submit" name="send_broadcast" class="btn" style="background-color: #004085;" onclick="return confirm('Send this message to all participants?')">Send Notification & Email</button>
    </form>
</div>

<?php require 'includes/footer.php'; ?>