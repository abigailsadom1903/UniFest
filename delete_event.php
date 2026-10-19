<?php
require 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// 1. Check if the user OWNS the event or is ADMIN
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if ($event) {
    if ($role == 'admin' || $event['organizer_id'] == $user_id) {
        // 2. Perform Delete
        $deleteStmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $deleteStmt->execute([$event_id]);
        
        // Optional: Delete the poster image file from folder here if you want
        
        header("Location: dashboard.php?msg=deleted");
    } else {
        die("Access Denied: You do not have permission to delete this event.");
    }
} else {
    die("Event not found.");
}
?>