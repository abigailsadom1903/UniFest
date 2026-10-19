<?php
require 'config/db.php';
require 'includes/header.php';

if ($_SESSION['user_role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// 1. Build Query Dynamically
$sql = "SELECT events.*, users.name as organizer 
        FROM events 
        JOIN users ON events.organizer_id = users.id 
        WHERE 1=1"; // Start with a true condition

$params = [];
$title_parts = [];

// Filter by Month
if (!empty($_GET['filter_month'])) {
    $month = $_GET['filter_month'];
    $sql .= " AND MONTH(event_date) = ?";
    $params[] = $month;
    $title_parts[] = "Month: " . date("F", mktime(0, 0, 0, $month, 10));
}

// Filter by Category
if (!empty($_GET['filter_category'])) {
    $cat = $_GET['filter_category'];
    $sql .= " AND type = ?";
    $params[] = $cat;
    $title_parts[] = "Category: " . htmlspecialchars($cat);
}

$sql .= " ORDER BY event_date DESC";

// Execute Query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();

// Construct Title
$report_title = empty($title_parts) ? "All Events" : implode(" | ", $title_parts);
?>

<h2>Generate Reports</h2>

<div style="background:#f4f4f4; padding:20px; border-radius:8px; margin-bottom:20px;">
    <form method="GET" style="display:flex; gap:10px; align-items:flex-end; flex-wrap: wrap;">
        <div>
            <label>By Month:</label>
            <select name="filter_month" style="padding: 8px;">
                <option value="">All Months</option>
                <?php for($m=1; $m<=12; $m++): ?>
                    <option value="<?= $m ?>" <?= (isset($_GET['filter_month']) && $_GET['filter_month'] == $m) ? 'selected' : '' ?>>
                        <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label>By Category:</label>
            <select name="filter_category" style="padding: 8px;">
                <option value="">All Categories</option>
                <option value="Workshop" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Workshop') ? 'selected' : '' ?>>Workshop</option>
                <option value="Seminar" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Seminar') ? 'selected' : '' ?>>Seminar</option>
                <option value="Competition" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Competition') ? 'selected' : '' ?>>Competition</option>
                <option value="Festival" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Festival') ? 'selected' : '' ?>>Festival</option>
                <option value="Sport" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Sport') ? 'selected' : '' ?>>Sport</option>
                <option value="Course" <?= (isset($_GET['filter_category']) && $_GET['filter_category'] == 'Course') ? 'selected' : '' ?>>Course</option>
            </select>
        </div>
        <button type="submit" class="btn">Generate Report</button>
        <a href="admin_reports.php" class="btn" style="background:#666;">Reset</a>
    </form>
</div>

<h3>Report Result: <?= $report_title ?> (<?= count($events) ?> records found)</h3>

<?php if (count($events) > 0): ?>
    <button onclick="window.print()" class="btn" style="background:blue; margin-bottom:10px;">Print / Save PDF</button>

    <table style="width:100%; border-collapse: collapse; background: white;" border="1">
        <thead>
            <tr style="background-color: #333; color: white;">
                <th style="padding:8px;">Event Name</th>
                <th style="padding:8px;">Date</th>
                <th style="padding:8px;">Category</th>
                <th style="padding:8px;">Organizer</th>
                <th style="padding:8px;">Max Participants</th>
                <th style="padding:8px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $e): ?>
                <tr>
                    <td style="padding:8px;"><?= htmlspecialchars($e['event_name']) ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($e['event_date']) ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($e['type']) ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($e['organizer']) ?></td>
                    <td style="padding:8px;"><?= htmlspecialchars($e['max_participants']) ?></td>
                    <td style="padding:8px;"><?= ucfirst($e['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="padding: 20px; background: white; text-align: center; border: 1px solid #ddd;">No events found matching these filters.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>