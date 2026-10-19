<?php
require_once 'config.php';

$event_id = $_GET['event_id'] ?? 1;

// Get event details
$event_sql = "SELECT * FROM events WHERE id = $event_id";
$event_result = mysqli_query($conn, $event_sql);
$event = mysqli_fetch_assoc($event_result);

// Get all feedback with user names
$sql = "SELECT f.*, u.full_name 
        FROM feedback f 
        JOIN users u ON f.user_id = u.id 
        WHERE f.event_id = $event_id 
        ORDER BY f.submitted_at DESC";
$result = mysqli_query($conn, $sql);

// Check for errors
if (!$result) {
    echo "<h3>Database Error: " . mysqli_error($conn) . "</h3>";
    exit();
}

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total,
    AVG(rating) as average,
    MIN(rating) as min_rating,
    MAX(rating) as max_rating,
    SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive_count
    FROM feedback WHERE event_id = $event_id";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

$positive_percentage = $stats['total'] > 0 ? round(($stats['positive_count'] / $stats['total']) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report - <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon: #800000;
            --maroon-light: #a52a2a;
            --maroon-soft: #f8f0f0;
            --maroon-border: #e0c8c8;
            --gold: #d4af37;
            --dark: #333333;
            --gray: #666666;
            --gray-light: #f5f5f5;
            --border: #e0e0e0;
            --shadow: rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f9f9f9 0%, #f5f5f5 100%);
            color: var(--dark);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 35px;
            background: white;
            border-radius: var(--radius);
            box-shadow: 0 5px 20px var(--shadow);
            border-top: 4px solid var(--maroon);
        }

        .header h1 {
            color: var(--maroon);
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .event-highlight {
            background: var(--maroon-soft);
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid var(--maroon-border);
        }

        .event-highlight h2 {
            color: var(--maroon);
            margin-bottom: 20px;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .event-detail {
            padding: 18px;
            background: white;
            border-radius: 8px;
            border-left: 3px solid var(--maroon);
        }

        .event-detail h4 {
            color: var(--gray);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .event-detail p {
            color: var(--dark);
            font-size: 1.05rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: 0 5px 15px var(--shadow);
            text-align: center;
            transition: transform 0.3s ease;
            border: 1px solid var(--border);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--maroon-border);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.5rem;
        }

        .stat-icon.total { background: #f0f7ff; color: #0066cc; }
        .stat-icon.average { background: #fff0f0; color: var(--maroon); }
        .stat-icon.positive { background: #f0fff4; color: var(--success); }
        .stat-icon.range { background: #fff8f0; color: var(--warning); }

        .stat-value {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--dark);
            margin: 10px 0;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .progress-container {
            margin-top: 15px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .progress-bar {
            height: 6px;
            background: var(--gray-light);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--maroon), var(--maroon-light));
            border-radius: 3px;
            width: <?php echo $positive_percentage; ?>%;
        }

        .feedback-section {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px var(--shadow);
            border: 1px solid var(--border);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--maroon-soft);
        }

        .section-header h2 {
            color: var(--dark);
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-header h2 i {
            color: var(--maroon);
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid var(--border);
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            border-color: var(--maroon);
            color: var(--maroon);
        }

        .filter-btn.active {
            background: var(--maroon);
            color: white;
            border-color: var(--maroon);
        }

        .feedback-count {
            background: var(--maroon-soft);
            color: var(--maroon);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .feedback-list {
            display: grid;
            gap: 25px;
        }

        .feedback-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .feedback-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--maroon-border);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: var(--maroon-soft);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--maroon);
            font-weight: bold;
            font-size: 1.1rem;
            flex-shrink: 0;
            border: 2px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .user-details h4 {
            color: var(--dark);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .user-role {
            display: inline-block;
            padding: 3px 10px;
            background: var(--gray-light);
            color: var(--gray);
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .rating-display {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .stars {
            font-size: 1.4rem;
            color: var(--gold);
        }

        .rating-badge {
            background: var(--maroon-soft);
            color: var(--maroon);
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1rem;
            border: 1px solid var(--maroon-border);
        }

        .feedback-content {
            margin: 20px 0;
            padding: 20px;
            background: var(--gray-light);
            border-radius: 8px;
            border-left: 3px solid var(--maroon);
        }

        .feedback-section-title {
            color: var(--maroon);
            margin-bottom: 12px;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .feedback-text {
            white-space: pre-line;
            line-height: 1.7;
            color: var(--dark);
            font-size: 1rem;
        }

        .feedback-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--border);
            color: var(--gray);
            font-size: 0.85rem;
        }

        .feedback-date {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feedback-id {
            font-family: monospace;
            background: var(--gray-light);
            padding: 3px 8px;
            border-radius: 4px;
            color: var(--gray);
            font-size: 0.8rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: var(--maroon-soft);
            border-radius: var(--radius);
            border: 2px dashed var(--maroon-border);
            margin: 30px 0;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: var(--maroon-border);
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            min-width: 160px;
            justify-content: center;
        }

        .btn-primary {
            background: var(--maroon);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--maroon-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(128, 0, 0, 0.2);
        }

        .btn-secondary {
            background: white;
            color: var(--maroon);
            border: 1px solid var(--maroon);
        }

        .btn-secondary:hover {
            background: var(--maroon-soft);
            transform: translateY(-3px);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding: 25px;
            color: var(--gray);
            font-size: 0.9rem;
            background: white;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .header {
                padding: 25px 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .dashboard {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .filter-controls {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .feedback-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .rating-display {
                align-self: flex-start;
                flex-direction: row;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .event-grid {
                grid-template-columns: 1fr;
            }
            
            .btn {
                width: 100%;
            }
            
            body {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-chart-bar"></i> Feedback Report</h1>
            <p>Analyzed feedback for <strong><?php echo htmlspecialchars($event['event_name']); ?></strong></p>
        </div>

        <!-- Event Information -->
        <div class="event-highlight">
            <h2><i class="fas fa-info-circle"></i> Event Details</h2>
            <div class="event-grid">
                <div class="event-detail">
                    <h4>Event Name</h4>
                    <p><?php echo htmlspecialchars($event['event_name']); ?></p>
                </div>
                
                <div class="event-detail">
                    <h4>Organizer</h4>
                    <p><?php echo htmlspecialchars($event['organizer_name']); ?></p>
                </div>
                
                <div class="event-detail">
                    <h4>Date</h4>
                    <p><?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                </div>
                
                <div class="event-detail">
                    <h4>Description</h4>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="dashboard">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                <div class="stat-label">Total Responses</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon average">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['average'] ?? 0, 1); ?>/5</div>
                <div class="stat-label">Average Rating</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon positive">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stat-value"><?php echo $positive_percentage; ?>%</div>
                <div class="stat-label">Satisfaction Rate</div>
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon range">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value"><?php echo $stats['min_rating'] ?? 0; ?>-<?php echo $stats['max_rating'] ?? 0; ?></div>
                <div class="stat-label">Rating Range</div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="feedback-section">
            <div class="section-header">
                <h2><i class="fas fa-comments"></i> Participant Feedback</h2>
                <div class="filter-controls">
                    <span class="feedback-count"><?php echo $stats['total'] ?? 0; ?> responses</span>
                    <button class="filter-btn active" onclick="filterFeedback('all')">All</button>
                    <button class="filter-btn" onclick="filterFeedback('positive')">Positive (4-5★)</button>
                    <button class="filter-btn" onclick="filterFeedback('neutral')">Neutral (3★)</button>
                    <button class="filter-btn" onclick="filterFeedback('negative')">Constructive (1-2★)</button>
                    <button class="filter-btn" onclick="sortFeedback('highest')">
                        <i class="fas fa-sort-amount-down"></i> Highest
                    </button>
                    <button class="filter-btn" onclick="sortFeedback('lowest')">
                        <i class="fas fa-sort-amount-up"></i> Lowest
                    </button>
                </div>
            </div>

            <div class="feedback-list" id="feedbackList">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): 
                        $initials = '';
                        $name_parts = explode(' ', $row['full_name']);
                        foreach ($name_parts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                            if (strlen($initials) >= 2) break;
                        }
                        
                        $feedback_class = '';
                        if ($row['rating'] >= 4) {
                            $feedback_class = 'positive';
                        } elseif ($row['rating'] == 3) {
                            $feedback_class = 'neutral';
                        } else {
                            $feedback_class = 'negative';
                        }
                    ?>
                    <div class="feedback-card" data-rating="<?php echo $feedback_class; ?>">
                        <div class="feedback-header">
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?php echo $initials; ?>
                                </div>
                                <div class="user-details">
                                    <h4><?php echo htmlspecialchars($row['full_name']); ?></h4>
                                    <span class="user-role">Participant</span>
                                </div>
                            </div>
                            
                            <div class="rating-display">
                                <div class="stars">
                                    <?php echo str_repeat('★', $row['rating']); ?>
                                </div>
                                <div class="rating-badge">
                                    <?php echo $row['rating']; ?>/5
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        // Parse combined feedback
                        $comment = $row['comment'];
                        $sections = [];
                        
                        if (!empty($comment)) {
                            $lines = explode("\n", $comment);
                            $current_section = '';
                            $current_title = '';
                            
                            foreach ($lines as $line) {
                                if (strpos($line, '❤️ WHAT I ENJOYED MOST:') !== false) {
                                    if ($current_section) {
                                        $sections[] = ['title' => $current_title, 'content' => trim($current_section)];
                                    }
                                    $current_title = 'What I Enjoyed Most';
                                    $current_section = '';
                                } elseif (strpos($line, '💡 SUGGESTIONS FOR IMPROVEMENT:') !== false) {
                                    if ($current_section) {
                                        $sections[] = ['title' => $current_title, 'content' => trim($current_section)];
                                    }
                                    $current_title = 'Suggestions for Improvement';
                                    $current_section = '';
                                } elseif (strpos($line, '📝 ADDITIONAL COMMENTS:') !== false) {
                                    if ($current_section) {
                                        $sections[] = ['title' => $current_title, 'content' => trim($current_section)];
                                    }
                                    $current_title = 'Additional Comments';
                                    $current_section = '';
                                } else {
                                    $current_section .= $line . "\n";
                                }
                            }
                            
                            if ($current_section) {
                                $sections[] = ['title' => $current_title, 'content' => trim($current_section)];
                            }
                        }
                        ?>
                        
                        <div class="feedback-content">
                            <?php if (!empty($sections)): ?>
                                <?php foreach ($sections as $section): ?>
                                    <?php if (!empty(trim($section['content']))): ?>
                                    <div class="feedback-section-title">
                                        <?php 
                                        if ($section['title'] == 'What I Enjoyed Most') {
                                            echo '<i class="fas fa-heart"></i> ';
                                        } elseif ($section['title'] == 'Suggestions for Improvement') {
                                            echo '<i class="fas fa-lightbulb"></i> ';
                                        } elseif ($section['title'] == 'Additional Comments') {
                                            echo '<i class="fas fa-comment"></i> ';
                                        }
                                        echo $section['title']; 
                                        ?>
                                    </div>
                                    <div class="feedback-text">
                                        <?php echo nl2br(htmlspecialchars($section['content'])); ?>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="feedback-text" style="color: var(--gray); font-style: italic;">
                                    <i class="fas fa-info-circle"></i> No detailed feedback provided
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="feedback-meta">
                            <div class="feedback-date">
                                <i class="far fa-clock"></i>
                                <?php echo date('M j, Y \a\t g:i A', strtotime($row['submitted_at'])); ?>
                            </div>
                            <div class="feedback-id">
                                ID: <?php echo str_pad($row['id'], 6, '0', STR_PAD_LEFT); ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 style="color: var(--gray); margin-bottom: 15px;">No Feedback Yet</h3>
                        <p style="color: var(--gray); margin-bottom: 20px;">
                            This event hasn't received any feedback yet. Be the first to share your thoughts!
                        </p>
                        <a href="submit.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            Add First Feedback
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="submit.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Add New Feedback
            </a>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Events
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><i class="fas fa-calendar-alt" style="color: var(--maroon);"></i> UniFest Event Management System</p>
            <p style="font-size: 0.8rem; margin-top: 10px; color: var(--gray);">
                Report generated on <?php echo date('F j, Y \a\t g:i A'); ?> • 
                <i class="fas fa-chart-pie" style="margin-left: 10px;"></i> 
                <?php echo $stats['total'] ?? 0; ?> total responses analyzed
            </p>
        </div>
    </div>

    <script>
        // Feedback Filtering
        function filterFeedback(type) {
            const feedbackCards = document.querySelectorAll('.feedback-card');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(type)) {
                    btn.classList.add('active');
                }
            });
            
            // Show/hide cards
            feedbackCards.forEach(card => {
                if (type === 'all') {
                    card.style.display = 'block';
                } else {
                    const cardType = card.getAttribute('data-rating');
                    card.style.display = cardType === type ? 'block' : 'none';
                }
            });
            
            // Update count
            const visibleCards = Array.from(feedbackCards).filter(card => 
                card.style.display !== 'none'
            ).length;
            
            const countBadge = document.querySelector('.feedback-count');
            if (countBadge) {
                countBadge.textContent = `${visibleCards} ${visibleCards === 1 ? 'response' : 'responses'}`;
            }
        }
        
        // Sort by rating
        function sortFeedback(criteria) {
            const container = document.getElementById('feedbackList');
            const cards = Array.from(container.querySelectorAll('.feedback-card'));
            
            cards.sort((a, b) => {
                const ratingA = parseInt(a.querySelector('.rating-badge').textContent.split('/')[0]);
                const ratingB = parseInt(b.querySelector('.rating-badge').textContent.split('/')[0]);
                
                if (criteria === 'highest') {
                    return ratingB - ratingA;
                } else {
                    return ratingA - ratingB;
                }
            });
            
            // Re-append cards in sorted order
            cards.forEach(card => container.appendChild(card));
            
            // Add animation effect
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`;
                card.style.animation = 'fadeInUp 0.3s ease-out forwards';
            });
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to cards
            const cards = document.querySelectorAll('.feedback-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeInUp 0.5s ease-out forwards';
            });
            
            // Add animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(15px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .feedback-card {
                    opacity: 0;
                    animation-fill-mode: forwards;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>