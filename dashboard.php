<?php
require 'config/db.php';
require 'includes/header.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];
$user_name = $_SESSION['user_name'];

// --- QUERY 1: FETCH STATISTICS (Admin Only) ---
$total_users = 0;
$total_events = 0;
$total_participants = 0;

if ($role == 'admin') {
    $total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $total_participants = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
}

// --- QUERY 2: EVENTS MANAGED BY USER ---
$managed_events = [];
if ($role == 'admin') {
    // Admin sees ALL events (Sorted by ID to prevent crash)
    $stmt = $pdo->query("SELECT events.*, users.name as organizer FROM events JOIN users ON events.organizer_id = users.id ORDER BY events.id DESC");
    $managed_events = $stmt->fetchAll();
    $heading_managed = "Admin Dashboard - All System Events";
} elseif ($role == 'staff' || $role == 'student') {
    // Staff/Student sees events they Created
    $stmt = $pdo->prepare("SELECT * FROM events WHERE organizer_id = ? ORDER BY event_date DESC");
    $stmt->execute([$user_id]);
    $managed_events = $stmt->fetchAll();
    $heading_managed = "My Managed Events";
}

// --- QUERY 3: EVENTS REGISTERED BY USER ---
$stmt = $pdo->prepare("
    SELECT events.*, registrations.status as reg_status, registrations.id as reg_id 
    FROM registrations 
    JOIN events ON registrations.event_id = events.id 
    WHERE registrations.user_id = ? 
    ORDER BY events.event_date DESC
");
$stmt->execute([$user_id]);
$registered_events = $stmt->fetchAll();
?>

<div class="dashboard-header" style="margin-bottom: 20px;">
    <h1>Welcome, <?= htmlspecialchars($user_name) ?> (<?= ucfirst($role) ?>)</h1>
</div>

<?php if ($role == 'admin'): ?>
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; background: #004085; color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <h2 style="font-size: 2.5rem; margin: 0; color: white;"><?= $total_users ?></h2>
            <p style="margin: 0; opacity: 0.8;">Total Users</p>
        </div>
        <div style="flex: 1; background: #800000; color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <h2 style="font-size: 2.5rem; margin: 0; color: white;"><?= $total_events ?></h2>
            <p style="margin: 0; opacity: 0.8;">Total Events</p>
        </div>
        <div style="flex: 1; background: #155724; color: white; padding: 20px; border-radius: 8px; text-align: center;">
             <h2 style="font-size: 2.5rem; margin: 0; color: white;"><?= $total_participants ?></h2>
            <p style="margin: 0; opacity: 0.8;">Total Participants</p>
        </div>
    </div>
<?php endif; ?>

<?php if ($role != 'public'): ?>
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 15px;">
            <h2 style="color: var(--primary-color); margin: 0;"><?= $heading_managed ?></h2>
            <div>
                <?php if ($role == 'admin'): ?>
                    <a href="manage_users.php" class="btn" style="background-color: #333; margin-right: 10px;">Manage Users</a>
                    <a href="admin_reports.php" class="btn" style="background-color: #004085; margin-right: 10px;">Reports</a>
                <?php endif; ?>
                <a href="create_event.php" class="btn">+ Create Event</a>
            </div>
        </div>

        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #eee; text-align: left;">
                    <th style="padding: 10px;">Event Name</th>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($managed_events) > 0): ?>
                    <?php foreach ($managed_events as $event): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px;"><?= htmlspecialchars($event['event_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($event['event_date']) ?></td>
                            <td style="padding: 10px;">
                                <span style="padding: 3px 8px; border-radius: 4px; background: <?= $event['status'] == 'approved' ? '#d4edda' : '#fff3cd' ?>;">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 10px;">
                                <a href="view_participants.php?id=<?= $event['id'] ?>" style="color: var(--primary-color); font-weight: bold; margin-right: 10px;">Participants</a>
                                <a href="view_feedback.php?id=<?= $event['id'] ?>" style="color: #e67e22; font-weight: bold; margin-right: 10px;">Reviews</a>
                                
                                <?php if ($role == 'admin' && $event['status'] == 'pending'): ?>
                                    <a href="admin_action.php?action=approve&id=<?= $event['id'] ?>" style="color: green; font-weight:bold; margin-right:5px;">Approve</a>
                                    <a href="admin_action.php?action=reject&id=<?= $event['id'] ?>" style="color: red; font-weight:bold; margin-right:5px;">Reject</a>
                                <?php endif; ?>
                                
                                <a href="edit_event.php?id=<?= $event['id'] ?>" style="color: blue; font-weight: bold; margin-right: 10px;">Edit</a>
                                <a href="delete_event.php?id=<?= $event['id'] ?>" onclick="return confirm('Delete?')" style="color: red;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="padding: 20px; text-align: center;">You haven't created any events yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2 style="color: var(--primary-color); border-bottom: 2px solid #eee; padding-bottom: 10px;">Events I Have Joined</h2>
    
    <table style="width:100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="background-color: var(--secondary-color); color: white; text-align: left;">
                <th style="padding: 10px;">Event Name</th>
                <th style="padding: 10px;">Date & Time</th>
                <th style="padding: 10px;">Venue</th>
                <th style="padding: 10px;">Attendance</th>
                <th style="padding: 10px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($registered_events) > 0): ?>
                <?php foreach ($registered_events as $re): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">
                            <a href="event_details.php?id=<?= $re['id'] ?>" style="color: var(--primary-color); font-weight: bold;">
                                <?= htmlspecialchars($re['event_name']) ?>
                            </a>
                        </td>
                        <td style="padding: 10px;"><?= htmlspecialchars($re['event_date']) ?> (<?= htmlspecialchars($re['event_time']) ?>)</td>
                        <td style="padding: 10px;"><?= htmlspecialchars($re['venue']) ?></td>
                        <td style="padding: 10px;">
                            <?php if ($re['reg_status'] == 'attended'): ?>
                                <span style="color: green; font-weight: bold;">✔ Present</span>
                            <?php else: ?>
                                <span style="color: orange;">Registered</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px;">
                            <a href="event_details.php?id=<?= $re['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center;">
                        You haven't joined any events yet. <a href="index.php">Browse Events</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>