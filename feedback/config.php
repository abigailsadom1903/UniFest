<?php
// Database Configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'unifest_feedback';

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Start session (for storing user data temporarily)
session_start();

// Auto-set test user for demo (remove when integrating with main system)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 3;           // Ali student
    $_SESSION['user_name'] = 'Ali bin Ahmad';
    $_SESSION['user_role'] = 'student';
}
?>