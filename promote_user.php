<?php
require 'config/db.php';
session_start();

// 1. Security Check: Ensure only an Admin can access this page
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin') && (isset($_SESSION['role']) && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit;
}

// 2. Check if an ID was provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 3. Update the user's role to 'admin' in the database
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        // Success: Redirect back with a success message
        header("Location: manage_users.php?msg=UserPromoted");
    } else {
        // Error: Redirect back with error
        header("Location: manage_users.php?msg=Error");
    }
} else {
    // No ID provided, go back
    header("Location: manage_users.php");
}
?>