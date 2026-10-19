<?php
session_start();       // 1. Resume the current session
session_unset();       // 2. Clear all session variables
session_destroy();     // 3. Destroy the session completely

// 4. Redirect user back to the Login page
header("Location: login.php");
exit;
?>