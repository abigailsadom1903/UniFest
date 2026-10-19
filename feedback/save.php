<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $event_id = $_POST['event_id'];
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $rating = $_POST['rating'];
    $enjoyed = isset($_POST['enjoyed']) ? trim($_POST['enjoyed']) : '';
    $improve = isset($_POST['improve']) ? trim($_POST['improve']) : '';
    $comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        die("Invalid rating. Please go back and select 1-5 stars.");
    }
    
    // Combine all feedback into one text
    $combined_feedback = "";
    if (!empty($enjoyed)) {
        $combined_feedback .= "❤️ WHAT I ENJOYED MOST:\n" . $enjoyed . "\n\n";
    }
    if (!empty($improve)) {
        $combined_feedback .= "💡 SUGGESTIONS FOR IMPROVEMENT:\n" . $improve . "\n\n";
    }
    if (!empty($comments)) {
        $combined_feedback .= "📝 ADDITIONAL COMMENTS:\n" . $comments;
    }
    
    // Clean up extra newlines
    $combined_feedback = trim($combined_feedback);
    
    // Check if user already submitted feedback
    $check_sql = "SELECT id FROM feedback WHERE event_id = ? AND user_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $event_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $error_message = "You have already submitted feedback for this event.";
        mysqli_stmt_close($check_stmt);
        showErrorPage($error_message);
        exit();
    }
    mysqli_stmt_close($check_stmt);
    
    // Insert into database
    $sql = "INSERT INTO feedback (event_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiis", $event_id, $user_id, $rating, $combined_feedback);
        
        if (mysqli_stmt_execute($stmt)) {
            showSuccessPage($event_id, $rating, $combined_feedback, $user_name);
        } else {
            $error_message = "Error saving feedback: " . mysqli_error($conn);
            showErrorPage($error_message);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Error preparing statement: " . mysqli_error($conn);
        showErrorPage($error_message);
    }
} else {
    header("Location: submit.php");
    exit();
}

// ============================================
// SUCCESS PAGE FUNCTION (Updated with Maroon Theme)
// ============================================
function showSuccessPage($event_id, $rating, $feedback, $user_name) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Feedback Submitted - UniFest</title>
        <style>
            :root {
                --maroon-dark: #800000;
                --maroon-medium: #a52a2a;
                --maroon-light: #c04040;
                --maroon-soft: #e8c8c8;
                --gold: #d4af37;
                --gold-light: #f4e4b5;
                --light: #f9f3f3;
                --dark: #5a0a0a;
                --gray: #8a6d6d;
                --border: #d9b8b8;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #fdf5f5 0%, #f9ebeb 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            
            .success-container {
                background: white;
                border-radius: 20px;
                padding: 50px;
                max-width: 600px;
                width: 100%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(128, 0, 0, 0.15);
                border: 2px solid var(--maroon-soft);
                animation: fadeIn 0.5s ease-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .success-icon {
                width: 100px;
                height: 100px;
                background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-medium) 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 30px;
                font-size: 50px;
                color: var(--gold-light);
                animation: scaleIn 0.5s ease-out 0.3s both;
            }
            
            @keyframes scaleIn {
                from { transform: scale(0); }
                to { transform: scale(1); }
            }
            
            h1 {
                color: var(--maroon-dark);
                margin-bottom: 10px;
                font-size: 2.5rem;
            }
            
            .subtitle {
                color: var(--gray);
                font-size: 1.1rem;
                margin-bottom: 30px;
                line-height: 1.6;
            }
            
            .rating-display {
                background: var(--maroon-soft);
                padding: 25px;
                border-radius: 15px;
                margin: 25px 0;
                border: 2px solid var(--gold);
            }
            
            .stars-large {
                font-size: 40px;
                color: var(--gold);
                margin-bottom: 10px;
            }
            
            .rating-text {
                font-size: 1.5rem;
                color: var(--maroon-dark);
                font-weight: bold;
            }
            
            .feedback-preview {
                background: var(--maroon-soft);
                padding: 25px;
                border-radius: 15px;
                text-align: left;
                margin: 25px 0;
                border-left: 5px solid var(--maroon-medium);
            }
            
            .feedback-preview h3 {
                color: var(--maroon-dark);
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .feedback-content {
                white-space: pre-line;
                line-height: 1.8;
                color: var(--dark);
                font-size: 1.05rem;
            }
            
            .user-info {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 30px;
                color: var(--gray);
            }
            
            .user-avatar {
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-medium) 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.2rem;
                border: 2px solid var(--gold);
            }
            
            .action-buttons {
                display: flex;
                gap: 15px;
                justify-content: center;
                margin-top: 30px;
                flex-wrap: wrap;
            }
            
            .btn {
                padding: 15px 30px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: bold;
                font-size: 1rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                transition: all 0.3s ease;
                min-width: 180px;
                cursor: pointer;
                border: none;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-medium) 100%);
                color: white;
            }
            
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(128, 0, 0, 0.4);
            }
            
            .btn-secondary {
                background: white;
                color: var(--maroon-dark);
                border: 2px solid var(--maroon-dark);
            }
            
            .btn-secondary:hover {
                background: var(--maroon-dark);
                color: white;
                transform: translateY(-3px);
            }
            
            .confirmation-message {
                background: #e8f5e9;
                padding: 15px;
                border-radius: 10px;
                margin-top: 25px;
                border-left: 4px solid #4CAF50;
                color: #2e7d32;
                font-size: 0.95rem;
            }
            
            .thank-you {
                font-size: 1.2rem;
                color: var(--maroon-medium);
                font-weight: bold;
                margin: 20px 0;
            }
            
            @media (max-width: 600px) {
                .success-container {
                    padding: 30px 20px;
                }
                
                .action-buttons {
                    flex-direction: column;
                }
                
                .btn {
                    width: 100%;
                }
                
                h1 {
                    font-size: 2rem;
                }
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="success-container">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- Title -->
            <h1>Feedback Submitted!</h1>
            <p class="subtitle">Thank you for sharing your thoughts. Your feedback helps us improve future events.</p>
            
            <!-- User Info -->
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                </div>
                <div>
                    <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                    <small>Your feedback has been recorded</small>
                </div>
            </div>
            
            <!-- Rating Display -->
            <div class="rating-display">
                <div class="stars-large">
                    <?php echo str_repeat('★', $rating); ?>
                </div>
                <div class="rating-text">
                    <?php 
                    $rating_texts = [
                        1 => "Poor",
                        2 => "Fair", 
                        3 => "Good",
                        4 => "Very Good",
                        5 => "Excellent"
                    ];
                    echo $rating_texts[$rating] . " ($rating/5)";
                    ?>
                </div>
            </div>
            
            <!-- Feedback Preview -->
            <div class="feedback-preview">
                <h3><i class="fas fa-comment-alt" style="color: var(--maroon-dark);"></i> Your Feedback</h3>
                <div class="feedback-content">
                    <?php echo nl2br(htmlspecialchars($feedback)); ?>
                </div>
            </div>
            
            <!-- Thank You Message -->
            <div class="thank-you">
                <i class="fas fa-heart" style="color:var(--maroon-light);"></i>
                We appreciate your honest feedback!
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="submit.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Submit More Feedback
                </a>
                <a href="view.php?event_id=<?php echo $event_id; ?>" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    View All Feedback
                </a>
            </div>
            
            <!-- Confirmation Message -->
            <div class="confirmation-message">
                <i class="fas fa-info-circle"></i>
                Your feedback will be reviewed by the event organizer.
            </div>
            
            <!-- Auto-redirect notice -->
            <div style="margin-top: 25px; color: var(--gray); font-size: 0.9rem;">
                <i class="fas fa-clock"></i>
                You will be redirected to the event page in <span id="countdown">10</span> seconds
            </div>
        </div>
        
        <script>
            // Auto-redirect countdown
            let seconds = 10;
            const countdownElement = document.getElementById('countdown');
            
            const countdown = setInterval(function() {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdown);
                    window.location.href = 'view.php?event_id=<?php echo $event_id; ?>';
                }
            }, 1000);
        </script>
    </body>
    </html>
    <?php
}

// ============================================
// ERROR PAGE FUNCTION (Updated with Maroon Theme)
// ============================================
function showErrorPage($message) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - UniFest</title>
        <style>
            :root {
                --maroon-dark: #800000;
                --maroon-medium: #a52a2a;
                --maroon-light: #c04040;
                --maroon-soft: #e8c8c8;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #fdf5f5 0%, #f9ebeb 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            
            .error-container {
                background: white;
                border-radius: 20px;
                padding: 50px;
                max-width: 500px;
                width: 100%;
                text-align: center;
                box-shadow: 0 20px 60px rgba(128, 0, 0, 0.15);
                border: 2px solid var(--maroon-soft);
            }
            
            .error-icon {
                width: 100px;
                height: 100px;
                background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-medium) 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 30px;
                font-size: 50px;
                color: white;
            }
            
            h1 {
                color: var(--maroon-dark);
                margin-bottom: 15px;
            }
            
            .error-message {
                background: #ffebee;
                padding: 20px;
                border-radius: 10px;
                color: #c62828;
                margin: 25px 0;
                border-left: 4px solid #f44336;
            }
            
            .btn {
                display: inline-block;
                padding: 15px 30px;
                background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-medium) 100%);
                color: white;
                text-decoration: none;
                border-radius: 50px;
                font-weight: bold;
                margin-top: 20px;
                transition: transform 0.3s;
                border: none;
                cursor: pointer;
            }
            
            .btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(128, 0, 0, 0.3);
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h1>Oops! Something went wrong</h1>
            
            <div class="error-message">
                <i class="fas fa-info-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            
            <p>Please try again or contact support if the problem persists.</p>
            
            <a href="submit.php" class="btn">
                <i class="fas fa-arrow-left"></i>
                Back to Feedback Form
            </a>
        </div>
    </body>
    </html>
    <?php
}
?>