<?php
// Function to create an in-system notification
function addNotification($pdo, $user_id, $message) {
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $message]);
}

// Function to SIMULATE sending an email (Writes to a log file for proof)
function sendEmail($to, $subject, $body) {
    $logFile = __DIR__ . '/../email_log.txt'; // Saves in root folder
    $entry = "----------- EMAIL SENT [" . date('Y-m-d H:i:s') . "] -----------\n";
    $entry .= "To: $to\n";
    $entry .= "Subject: $subject\n";
    $entry .= "Body: $body\n";
    $entry .= "------------------------------------------------------\n\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
}
?>