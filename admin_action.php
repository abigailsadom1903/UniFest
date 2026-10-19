<?php
require 'config/db.php';
session_start();

// Security Check: strictly Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Access Denied.");
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'approve') {
        // Fetch event and organizer ID first
        $stmt = $pdo->prepare("SELECT event_name, organizer_id FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();

        // Approve
        $stmt = $pdo->prepare("UPDATE events SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);

        // Notify Organizer
        require_once 'includes/functions.php';
        $msg = "Good news! Your event '" . $event['event_name'] . "' has been APPROVED by Admin.";
        addNotification($pdo, $event['organizer_id'], $msg);

        header("Location: dashboard.php?msg=approved");
    } 

    elseif ($action == 'reject') {
        // Fetch event details
        $stmt = $pdo->prepare("SELECT event_name, organizer_id FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();

        // Reject
        $stmt = $pdo->prepare("UPDATE events SET status = 'archived' WHERE id = ?");
        $stmt->execute([$id]);

        // Notify Organizer
        require_once 'includes/functions.php';
        $msg = "Alert: Your event '" . $event['event_name'] . "' was REJECTED/ARCHIVED by Admin.";
        addNotification($pdo, $event['organizer_id'], $msg);

        header("Location: dashboard.php?msg=archived");
    }

    elseif ($action == 'delete_user') {
        // Delete a User
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_users.php?msg=user_deleted");
    }

    elseif ($action == 'suspend_user') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_users.php?msg=suspended");
    }

    elseif ($action == 'activate_user') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: manage_users.php?msg=activated");
    }
}
?>