<?php
require_once 'config.php';

$event_id = $_GET['event_id'] ?? 1;

// Get event details
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - UniFest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --maroon: #800000;
            --maroon-light: #a52a2a;
            --maroon-soft: #f8f0f0;
            --maroon-border: #e0c8c8;
            --gold: #d4af37;
            --gold-light: #f4e4b5;
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
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: white;
            border-radius: var(--radius);
            box-shadow: 0 5px 20px var(--shadow);
            border-top: 4px solid var(--maroon);
        }

        .header h1 {
            color: var(--maroon);
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .header p {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        @media (min-width: 992px) {
            .content-wrapper {
                grid-template-columns: 320px 1fr;
            }
        }

        .sidebar {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: 0 5px 15px var(--shadow);
            border: 1px solid var(--border);
        }

        .event-card {
            text-align: center;
        }

        .event-icon {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--maroon);
            font-size: 2rem;
            border: 3px solid var(--maroon);
        }

        .event-card h3 {
            color: var(--maroon);
            margin-bottom: 15px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .event-details {
            text-align: left;
            margin-top: 25px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
            padding: 15px;
            background: var(--maroon-soft);
            border-radius: 8px;
            border-left: 3px solid var(--maroon);
        }

        .detail-item i {
            color: var(--maroon);
            width: 20px;
            margin-top: 3px;
            font-size: 1.1rem;
        }

        .main-form {
            background: white;
            border-radius: var(--radius);
            padding: 35px;
            box-shadow: 0 5px 15px var(--shadow);
            border: 1px solid var(--border);
        }

        .form-section {
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .section-icon {
            width: 45px;
            height: 45px;
            background: var(--maroon-soft);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--maroon);
            font-size: 1.3rem;
            border: 2px solid var(--maroon-border);
        }

        .section-header h3 {
            color: var(--dark);
            font-size: 1.4rem;
            font-weight: 600;
        }

        .section-header p {
            color: var(--gray);
            margin-top: 5px;
            font-size: 0.95rem;
        }

        .rating-section {
            text-align: center;
        }

        .stars-container {
            margin: 25px 0;
        }

        .stars {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .star {
            cursor: pointer;
            color: #ddd;
            transition: all 0.2s ease;
            margin: 0 8px;
        }

        .star.active {
            color: var(--gold);
            transform: scale(1.1);
        }

        .rating-labels {
            display: flex;
            justify-content: space-between;
            max-width: 500px;
            margin: 0 auto;
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .rating-text {
            font-size: 1.2rem;
            color: var(--maroon);
            margin: 15px 0;
            font-weight: 600;
            min-height: 24px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.05rem;
        }

        .form-group small {
            display: block;
            margin-top: 8px;
            color: var(--gray);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        textarea {
            width: 100%;
            padding: 18px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            min-height: 130px;
            transition: all 0.3s;
            line-height: 1.6;
        }

        textarea:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .form-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .btn-submit {
            background: var(--maroon);
            color: white;
            border: none;
            padding: 18px 45px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
        }

        .btn-submit:hover {
            background: var(--maroon-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(128, 0, 0, 0.25);
        }

        .user-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: var(--maroon-soft);
            padding: 12px 24px;
            border-radius: 8px;
            margin-top: 25px;
            border: 1px solid var(--maroon-border);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: var(--maroon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .character-count {
            text-align: right;
            color: var(--gray);
            font-size: 0.85rem;
            margin-top: 8px;
            font-weight: 500;
        }

        .required {
            color: var(--danger);
        }

        .info-note {
            background: var(--maroon-soft);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--maroon);
            color: var(--dark);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content-wrapper {
                grid-template-columns: 1fr;
            }
            
            .stars {
                font-size: 2.5rem;
            }
            
            .rating-labels {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }
            
            .main-form {
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 15px;
            }
            
            .stars {
                font-size: 2rem;
                margin: 0 2px;
            }
            
            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-comment-medical"></i> Submit Feedback</h1>
            <p>Share your valuable experience to help us create better events in the future</p>
            
            <!-- User Info -->
            <div class="user-info-badge">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div>
                    <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong><br>
                    <small style="color: var(--gray);"><?php echo ucfirst($_SESSION['user_role']); ?> • Ready to provide feedback</small>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Sidebar - Event Info -->
            <aside class="sidebar">
                <div class="event-card">
                    <div class="event-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                    
                    <div class="event-details">
                        <div class="detail-item">
                            <i class="fas fa-user-tie"></i>
                            <div>
                                <strong>Organizer</strong><br>
                                <span style="color: var(--gray);"><?php echo htmlspecialchars($event['organizer_name']); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-calendar-day"></i>
                            <div>
                                <strong>Event Date</strong><br>
                                <span style="color: var(--gray);"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Location</strong><br>
                                <span style="color: var(--gray);"><?php echo htmlspecialchars($event['location'] ?? 'University Main Hall'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-note">
                        <i class="fas fa-info-circle" style="color: var(--maroon); margin-right: 8px;"></i>
                        Your feedback is confidential and will be used solely for improving future events.
                    </div>
                </div>
            </aside>

            <!-- Main Form -->
            <main class="main-form">
                <form action="save.php" method="POST" id="feedbackForm">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="user_name" value="<?php echo $_SESSION['user_name']; ?>">
                    
                    <!-- Rating Section -->
                    <section class="form-section rating-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h3>Overall Rating</h3>
                                <p>How would you rate your overall experience?</p>
                            </div>
                        </div>
                        
                        <div class="stars-container">
                            <div class="stars" id="starRating">
                                <span class="star" data-value="1">☆</span>
                                <span class="star" data-value="2">☆</span>
                                <span class="star" data-value="3">☆</span>
                                <span class="star" data-value="4">☆</span>
                                <span class="star" data-value="5">☆</span>
                            </div>
                            <div id="ratingText" class="rating-text"></div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                            
                            <div class="rating-labels">
                                <span>Poor</span>
                                <span>Fair</span>
                                <span>Good</span>
                                <span>Very Good</span>
                                <span>Excellent</span>
                            </div>
                        </div>
                    </section>
                    
                    <!-- Enjoyed Section -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3>What You Enjoyed</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="enjoyed">What aspects of the event did you enjoy most? <span class="required">*</span></label>
                            <small>Share what stood out to you, what you found valuable, or memorable moments</small>
                            <textarea 
                                id="enjoyed" 
                                name="enjoyed" 
                                placeholder="e.g., The keynote speaker was inspiring, the workshops were practical, the networking session was valuable..."
                                required
                            ></textarea>
                            <div class="character-count"><span id="enjoyedCount">0</span>/500 characters</div>
                        </div>
                    </section>
                    
                    <!-- Improvement Section -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3>Suggestions for Improvement</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="improve">How can we enhance this event for future participants?</label>
                            <small>Your constructive suggestions help us improve</small>
                            <textarea 
                                id="improve" 
                                name="improve" 
                                placeholder="e.g., More hands-on activities, better time management, additional topics..."
                            ></textarea>
                            <div class="character-count"><span id="improveCount">0</span>/500 characters</div>
                        </div>
                    </section>
                    
                    <!-- Additional Comments -->
                    <section class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-comment"></i>
                            </div>
                            <h3>Additional Comments</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="comments">Any other feedback you'd like to share?</label>
                            <small>General comments, observations, or suggestions</small>
                            <textarea 
                                id="comments" 
                                name="comments" 
                                placeholder="e.g., Overall organization, venue facilities, registration process..."
                            ></textarea>
                            <div class="character-count"><span id="commentsCount">0</span>/500 characters</div>
                        </div>
                    </section>
                    
                    <!-- Form Footer -->
                    <div class="form-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Submit Feedback
                        </button>
                        <div class="info-note" style="margin-top: 20px;">
                            <i class="fas fa-shield-alt" style="color: var(--maroon); margin-right: 8px;"></i>
                            All feedback is anonymous and will be reviewed by the event organizers only.
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>
        // Star Rating System
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        const ratingText = document.getElementById('ratingText');
        
        const ratingDescriptions = {
            1: "Poor - Significant improvements needed",
            2: "Fair - Room for improvement", 
            3: "Good - Met expectations",
            4: "Very Good - Exceeded expectations",
            5: "Excellent - Outstanding experience"
        };
        
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.dataset.value);
                ratingInput.value = value;
                ratingText.textContent = ratingDescriptions[value];
                
                stars.forEach(s => {
                    const sValue = parseInt(s.dataset.value);
                    s.textContent = sValue <= value ? '★' : '☆';
                    s.classList.toggle('active', sValue <= value);
                });
            });
            
            star.addEventListener('mouseover', () => {
                const value = parseInt(star.dataset.value);
                
                stars.forEach(s => {
                    const sValue = parseInt(s.dataset.value);
                    s.textContent = sValue <= value ? '★' : '☆';
                });
            });
            
            star.addEventListener('mouseout', () => {
                if (!ratingInput.value) {
                    stars.forEach(s => {
                        s.textContent = '☆';
                        s.classList.remove('active');
                    });
                } else {
                    const value = parseInt(ratingInput.value);
                    stars.forEach(s => {
                        const sValue = parseInt(s.dataset.value);
                        s.textContent = sValue <= value ? '★' : '☆';
                    });
                }
            });
        });
        
        // Character Counters
        const enjoyedTextarea = document.getElementById('enjoyed');
        const improveTextarea = document.getElementById('improve');
        const commentsTextarea = document.getElementById('comments');
        const enjoyedCount = document.getElementById('enjoyedCount');
        const improveCount = document.getElementById('improveCount');
        const commentsCount = document.getElementById('commentsCount');
        
        function updateCharacterCount(textarea, counter) {
            const count = textarea.value.length;
            counter.textContent = count;
            
            if (count > 500) {
                counter.style.color = 'var(--danger)';
            } else if (count > 400) {
                counter.style.color = 'var(--warning)';
            } else {
                counter.style.color = 'var(--gray)';
            }
        }
        
        enjoyedTextarea.addEventListener('input', () => updateCharacterCount(enjoyedTextarea, enjoyedCount));
        improveTextarea.addEventListener('input', () => updateCharacterCount(improveTextarea, improveCount));
        commentsTextarea.addEventListener('input', () => updateCharacterCount(commentsTextarea, commentsCount));
        
        // Form Validation
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            if (!ratingInput.value) {
                e.preventDefault();
                alert('Please select a rating before submitting your feedback.');
                return false;
            }
            
            if (enjoyedTextarea.value.trim() === '') {
                e.preventDefault();
                alert('Please share what you enjoyed about the event.');
                enjoyedTextarea.focus();
                return false;
            }
            
            if (enjoyedTextarea.value.length > 500) {
                e.preventDefault();
                alert('"What You Enjoyed" exceeds 500 characters. Please shorten your response.');
                enjoyedTextarea.focus();
                return false;
            }
            
            if (improveTextarea.value.length > 500) {
                e.preventDefault();
                alert('"Suggestions for Improvement" exceeds 500 characters. Please shorten your response.');
                improveTextarea.focus();
                return false;
            }
            
            if (commentsTextarea.value.length > 500) {
                e.preventDefault();
                alert('"Additional Comments" exceeds 500 characters. Please shorten your response.');
                commentsTextarea.focus();
                return false;
            }
        });
    </script>
</body>
</html>