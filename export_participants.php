<?php
require 'config/db.php';
session_start();

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    die("Access Denied");
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

// Verify Ownership
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if ($role != 'admin' && $event['organizer_id'] != $user_id) {
    die("Access Denied");
}

// Fetch Data
$sql = "SELECT u.name, u.email, u.phone, u.organization, r.status, r.registration_date 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.event_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set Headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="participants_event_'.$event_id.'.csv"');

// Create File Pointer
$output = fopen('php://output', 'w');

// Add Column Headings
fputcsv($output, ['Name', 'Email', 'Phone', 'Organization', 'Status', 'Registration Date']);

// Add Rows
foreach ($participants as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>