<?php
require 'config/db.php';
require 'includes/header.php';

// 1. Search & Filter Logic
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Start Query: Always show Approved events
$sql = "SELECT * FROM events WHERE status = 'approved'";
$params = [];

// PRIVACY FILTER: If NOT logged in, show ONLY Public events
if (!$is_logged_in) {
    $sql .= " AND visibility = 'public'";
}

if ($search) {
    $sql .= " AND event_name LIKE ?";
    $params[] = "%$search%";
}

if ($category) {
    $sql .= " AND type = ?";
    $params[] = $category;
}

$sql .= " ORDER BY event_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();
?>

<div class="hero" style="position: relative; margin-bottom: 40px; border-radius: 8px; overflow: hidden; color: white; text-align: center;">
    <div style="
        background-image: url('assets/uploads/Background_Event.png'); 
        background-size: cover; 
        background-position: center; 
        position: absolute; 
        top: 0; left: 0; width: 100%; height: 100%; 
        z-index: 0;
    "></div>

    <div style="
        position: absolute; 
        top: 0; left: 0; width: 100%; height: 100%; 
        background-color: rgba(0, 0, 0, 0.4); 
        backdrop-filter: blur(2px); 
        z-index: 1;
    "></div>

    <div style="position: relative; z-index: 2; padding: 40px 20px;">
        <h1 style="font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">Welcome to UniFest</h1>
        <p style="font-size: 1.2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">Where Planning Becomes Momentum</p>
    </div>
</div>
<section class="about-section" style="padding: 60px 20px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 50px;">
    <div class="container" style="max-width: 1100px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: #800000; font-size: 2.5rem; font-weight: 700; letter-spacing: 1px; margin-bottom: 25px; position: relative; display: inline-block;">
                ABOUT US
                <span style="content: ''; position: absolute; left: 50%; bottom: -10px; transform: translateX(-50%); width: 60px; height: 3px; background-color: #800000;"></span>
            </h2>
            <p style="font-size: 1.2rem; line-height: 1.6; color: #555; max-width: 800px; margin: 0 auto;">
                <strong style="color: #800000;">UniFest</strong> is the ultimate event management solution designed for <strong style="color: #000;">university communities everywhere</strong>. We are redefining campus life by creating a connected ecosystem for students, staff, and faculties.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            
            <div style="background: white; padding: 40px 15px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="font-size: 3rem; color: #800000; margin-bottom: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 style="font-size: 1.3rem; color: #333; margin-bottom: 15px; font-weight: 600;">Bridging the Gap</h3>
                <p style="color: #666; line-height: 1.6; font-size: 0.95rem;">
                    We connect event organizers directly with the entire campus population, eliminating communication barriers.
                </p>
            </div>

            <div style="background: white; padding: 40px 15px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="font-size: 3rem; color: #800000; margin-bottom: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <h3 style="font-size: 1.3rem; color: #333; margin-bottom: 15px; font-weight: 600;">Connected Ecosystem</h3>
                <p style="color: #666; line-height: 1.6; font-size: 0.95rem;">
                    A central hub for students, staff, and faculties to discover and engage with campus life seamlessly.
                </p>
            </div>

            <div style="background: white; padding: 40px 15px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="font-size: 3rem; color: #800000; margin-bottom: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>
                </div>
                <h3 style="font-size: 1.3rem; color: #333; margin-bottom: 15px; font-weight: 600;">Never Miss Out</h3>
                <p style="color: #666; line-height: 1.6; font-size: 0.95rem;">
                    Whether it's a campus activity or a public event, UniFest ensures you are always informed.
                </p>
            </div>

        </div>
    </div>
</section>
<form method="GET" action="index.php" style="display: flex; gap: 10px; margin-bottom: 30px; background: #eee; padding: 20px; border-radius: 8px; align-items: center;">
    
    <input type="text" name="search" placeholder="Search event name..." value="<?= htmlspecialchars($search) ?>" style="flex: 2; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px;">
    
    <select name="category" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px;">
        <option value="">All Categories</option>
        <option value="Workshop" <?= $category == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
        <option value="Seminar" <?= $category == 'Seminar' ? 'selected' : '' ?>>Seminar</option>
        <option value="Competition" <?= $category == 'Competition' ? 'selected' : '' ?>>Competition</option>
        <option value="Festival" <?= $category == 'Festival' ? 'selected' : '' ?>>Festival</option>
        <option value="Sport" <?= $category == 'Sport' ? 'selected' : '' ?>>Sport</option>
    </select>

    <button type="submit" class="btn" style="height: 40px; padding: 0 20px; cursor: pointer; background-color: #800000; color: white; border: none; border-radius: 4px;">Search</button>
    
    <a href="index.php" class="btn" style="height: 40px; padding: 0 20px; background: #666; color: white; text-align: center; border-radius: 4px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
        Reset
    </a>
</form>


<div class="events-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
    <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event): ?>
            
            <div class="event-card" style="
                background: white; 
                border-radius: 12px; 
                overflow: hidden; 
                box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
                transition: transform 0.3s ease, box-shadow 0.3s ease; 
                display: flex; 
                flex-direction: column; 
                height: 100%;
            " 
            onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" 
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.05)';">
                
                <?php 
                $poster = !empty($event['poster']) ? 'assets/uploads/' . $event['poster'] : 'https://via.placeholder.com/400x200?text=No+Image'; 
                ?>

                <div style="position: relative; height: 180px; overflow: hidden;">
                    <span style="
                        position: absolute; 
                        top: 15px; 
                        left: 15px; 
                        background: rgba(128, 0, 0, 0.9); /* Maroon with slight transparency */
                        color: white; 
                        padding: 5px 12px; 
                        font-size: 0.75rem; 
                        font-weight: 600; 
                        border-radius: 20px; 
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        z-index: 2;
                    ">
                        <?= htmlspecialchars($event['type']) ?>
                    </span>
                    
                    <img src="<?= $poster ?>" alt="Event Poster" style="
                        width: 100%; 
                        height: 100%; 
                        object-fit: cover; 
                        transition: transform 0.5s ease;
                    " onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1.0)'">
                </div>
                
                <div class="card-body" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                    
                    <h3 style="
                        margin: 0 0 15px 0; 
                        color: #2c3e50; 
                        font-size: 1.15rem; 
                        font-weight: 700; 
                        line-height: 1.4;
                        min-height: 2.8em; /* Ensures alignment even if title is short */
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    ">
                        <?= htmlspecialchars($event['event_name']) ?>
                    </h3>

                    <div style="display: flex; align-items: center; margin-bottom: 10px; color: #666; font-size: 0.9rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span><?= date('d M Y', strtotime($event['event_date'])) ?></span>
                    </div>

                    <div style="display: flex; align-items: start; margin-bottom: 20px; color: #666; font-size: 0.9rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; margin-top: 2px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($event['venue']) ?></span>
                    </div>

                    <a href="event_details.php?id=<?= $event['id'] ?>" style="
                        margin-top: auto; 
                        display: block; 
                        text-align: center; 
                        padding: 12px 0; 
                        background-color: #800000;  /* Maroon Background */
                        color: white;               /* White Text */
                        border: none; 
                        border-radius: 6px; 
                        text-decoration: none; 
                        font-weight: 600; 
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 6px rgba(128,0,0,0.2);
                    "
                    onmouseover="this.style.backgroundColor='#600000'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.backgroundColor='#800000'; this.style.transform='translateY(0)';">
                        View Details
                    </a>
                </div>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="grid-column: 1/-1; text-align: center; font-size: 1.2rem; color: #666; padding: 40px;">No events found matching your criteria.</p>
    <?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>